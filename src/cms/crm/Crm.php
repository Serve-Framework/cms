<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\crm;

use Exception;
use serve\auth\Gatekeeper;
use cms\wrappers\providers\LeadProvider;
use cms\wrappers\Visitor;
use serve\database\builder\Builder;
use serve\http\request\Request;
use serve\http\response\Response;

/**
 * CRM Utility Class.
 *
 * @author Joe J. Howard
 */
class Crm
{
    /**
     * Request instance.
     *
     * @var \serve\http\request\Request
     */
    private $request;

    /**
     * Response instance.
     *
     * @var \serve\http\response\Response
     */
    private $response;

    /**
     * Gatekeeper instance.
     *
     * @var \serve\auth\Gatekeeper
     */
    private $gatekeeper;

    /**
     * Gatekeeper instance.
     *
     * @var \cms\wrappers\providers\LeadProvider
     */
    private $leadProvider;

    /**
     * Sql builder instance.
     *
     * @var \serve\database\builder\Builder
     */
    private $sql;

    /**
     * The current visitor making the request.
     *
     * @var \cms\wrappers\Visitor
     */
    private $visitor;

    /**
     * Is this request via the CLI?
     *
     * @var bool
     */
    private $isCommandLine;

    /**
     * Is this a request to the admin panel?
     *
     * @var bool
     */
    private $isAdmin;

    /**
     * The current visitor a bot.
     *
     * @var bool
     */
    private $isCrawler;

    /**
     * The cookie key to be used to identify visitors.
     *
     * @var string
     */
    private $cookieKey = 'crm_visitor_id';

    /**
     * Constructor.
     *
     * @param \serve\http\request\Request      $request       Request instance
     * @param \serve\http\response\Response    $response      Response instance
     * @param \serve\auth\Gatekeeper                 $gatekeeper    Gatekeeper instance
     * @param \cms\wrappers\providers\LeadProvider $leadProvider  LeadProvider instance
     * @param \serve\database\builder\Builder    $sql           SQL builder instance
     * @param bool                                       $isCommandLine Is the CMS running via command line? (optional) (default false)
     * @param bool                                       $isCrawler     Is this a request from a bot? (optional) (default false)
     * @param bool                                       $isAdmin       Is this a request for the admin panel? (optional) (default false)
     */
    public function __construct(Request $request, Response $response, Gatekeeper $gatekeeper, LeadProvider $leadProvider, Builder $sql, bool $isCommandLine = false, bool $isCrawler = false, bool $isAdmin = false)
    {
        $this->request       = $request;
        $this->response      = $response;
        $this->gatekeeper    = $gatekeeper;
        $this->sql           = $sql;
        $this->leadProvider  = $leadProvider;
        $this->isCommandLine = $isCommandLine;
        $this->isAdmin       = $isAdmin;
        $this->isCrawler     = $isCrawler;

        // Only load if not in CLI
        if (!$this->isCommandLine)
        {
            $this->findVisitor();

            // Real humans
            if (!$this->isCrawler && $this->request->isGet() && !$this->isAdmin)
            {
                $this->visitor->addVisit($this->newVisitRow());
            }
        }
    }

    /**
     * Finds/and/or returns the current visitor.
     *
     * @return \cms\wrappers\Visitor
     */
    public function visitor(): Visitor
    {
        return $this->visitor;
    }

    /**
     * Get the lead provider.
     *
     * @return \cms\wrappers\providers\LeadProvider
     */
    public function leadProvider(): LeadProvider
    {
        return $this->leadProvider;
    }

    /**
     * Links the logged in user with the current visitor.
     */
    public function login(): void
    {
        if (!$this->gatekeeper->isLoggedIn())
        {
            throw new Exception('Error logging in CRM visitor. The user is not logged in via the Gatekeeper.');
        }

        // Update the user with the visitor
        $user = $this->gatekeeper->getUser();

        $user->visitor_id = $this->visitor->visitor_id;

        $user->save();

        // Update the visitor with the user
        $this->visitor->email = $user->email;

        $this->visitor->name = $user->name;

        $this->visitor->save();

        $this->response->cookie()->put($this->cookieKey, $this->visitor->visitor_id);
    }

    /**
     * After a visitor logs out, their cookie and sessions get wiped
     * This function retains their original visitor id.
     */
    public function logout(): void
    {
        // Add the crm visitor cookie again
        $this->response->cookie()->put($this->cookieKey, $this->visitor->visitor_id);
    }

    /**
     * Merges the current visitor with another one.
     *
     * @param  string $newVisitorId New visitor id
     * @return bool
     */
    public function mergeVisitor(string $newVisitorId): bool
    {
        if ($newVisitorId !== $this->visitor->visitor_id)
        {
            $newVisitor = $this->sql->SELECT('*')->FROM('crm_visitors')->WHERE('visitor_id', '=', $newVisitorId)->ROW();

            if ($newVisitor)
            {
                if (isset($this->visitor->id))
                {
                    $this->sql->DELETE_FROM('crm_visitors')->WHERE('id', '=', $this->visitor->id)->EXEC();

                    $this->sql->UPDATE('crm_visits')->SET(['visitor_id' => $newVisitorId])->WHERE('visitor_id', '=', $this->visitor->visitor_id)->EXEC();
                }

                foreach ($newVisitor as $key => $value)
                {
                    $this->visitor->$key = $value;
                }

                $this->response->cookie()->put($this->cookieKey, $newVisitorId);

                $this->visitor->save();

                return true;
            }
        }

        return false;
    }

    /**
     * Find the current visitor.
     *
     * @return \cms\wrappers\Visitor
     */
    private function findVisitor(): Visitor
    {
        // Logged in users
        if ($this->gatekeeper->isLoggedIn())
        {
            $this->visitor = $this->leadProvider()->byKey('visitor_id', $this->gatekeeper->getUser()->visitor_id);

            if (!$this->visitor)
            {
                $this->visitor = $this->leadProvider()->create($this->newVisitorRow());

                $this->login();
            }
        }
        // Returning visitors
        elseif ($this->response->cookie()->has($this->cookieKey))
        {
            $this->visitor = $this->leadProvider()->byKey('visitor_id', $this->response->cookie()->get($this->cookieKey));
        }
        else
        {
            // New visitors
            $this->visitor = $this->leadProvider()->create($this->newVisitorRow());
        }

        // Fallback
        if (!$this->visitor)
        {
            $this->visitor = $this->leadProvider()->create($this->newVisitorRow());
        }

        $this->response->cookie()->put($this->cookieKey, $this->visitor->visitor_id);

        return $this->visitor;
    }

    /**
     * Returns the base array for a new visitor.
     *
     * @return array
     */
    private function newVisitorRow(): array
    {
        return
        [
            'ip_address'  => $this->request->environment()->REMOTE_ADDR,
            'name'        => '',
            'email'       => '',
            'last_active' => time(),
            'user_agent'  => $this->request->environment()->HTTP_USER_AGENT,
            'is_bot'      => $this->isCrawler,
        ];
    }

    /**
     * Returns the base array for the current visit.
     *
     * channel => 'social'      - Social media website
     *            'referral'    - Referral. (e.g someone else's website)
     *            'cpc'         - Paid search.
     *            'organic'     - Organic search.
     *            'email'       - Email
     *            'display'     - Display advertising
     *            'direct'      - Direct visits.
     * medium =>  The medium
     *             'facebook', 'instagram', 'google', 'outlook' etc..
     *
     * @return array
     */
    private function newVisitRow(): array
    {
        $queries = $this->request->queries();

        return
        [
            'visitor_id'   => $this->visitor->visitor_id,
            'ip_address'   => $this->request->environment()->REMOTE_ADDR,
            'page'         => substr($this->request->environment()->REQUEST_URL, 0, 255),
            'date'         => time(),
            'medium'       => $queries['md'] ?? null,
            'channel'      => $queries['ch'] ?? 'direct',
            'campaign'     => $queries['cp'] ?? null,
            'keyword'      => $queries['kw'] ?? null,
            'creative'     => $queries['cr'] ?? null,
            'browser'      => $this->request->environment()->HTTP_USER_AGENT,
        ];
    }
}
