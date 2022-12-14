<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\wrappers;

use serve\utility\Str;
use serve\utility\UUID;
use serve\database\wrappers\Wrapper;

/**
 * CRM Funnel Section Abstract.
 *
 * @author Joe J. Howard
 */
class Visitor extends Wrapper
{
    /**
     * Current visit.
     *
     * @var \cms\wrappers\Visit
     */
    protected $visit;

    /**
     * Regenerates a unique visitor id and returns it.
     *
     * @return string
     */
    public function regenerateId(): string
    {
        $this->data['visitor_id'] = UUID::v4();

        return $this->data['visitor_id'];
    }

    /**
     * Gets the current visit.
     *
     * @return \cms\wrappers\Visit
     */
    public function visit(): Visit
    {
        return $this->visit;
    }

    /**
     * Adds the current visit. Ends the last one.
     *
     * @param array $row Visit row to save to the database
     */
    public function addVisit(array $row): void
    {
        $previousVisit = $this->previousVisit();

        if ($previousVisit && (!$previousVisit->end || $previousVisit->end === 0))
        {
            $previousVisit->end = time();

            $previousVisit->save();
        }

        $this->visit = new Visit($this->SQL, $row);

        $this->visit->save();

        $this->data['last_active'] = time();

        $this->save();
    }

    /**
     * Is this visitor a lead?
     *
     * @return bool
     */
    public function isLead(): bool
    {
        return !empty($this->data['email']);
    }

    /**
     * Checks if this is the first visit.
     *
     * @return bool
     */
    public function isFirstVisit(): bool
    {
        return $this->previousVisit() === false;
    }

    /**
     * Get all visits (newest first).
     *
     * @return array
     */
    public function visits(): array
    {
        $result = [];

        $visits = $this->SQL->SELECT('*')->FROM('crm_visits')->WHERE('visitor_id', '=', $this->data['visitor_id'])->ORDER_BY('date', 'DESC')->FIND_ALL();

        foreach ($visits as $visit)
        {
           $result[] = new Visit($this->SQL, $visit);
        }

        return $result;
    }

    /**
     * Count number of visits.
     *
     * @return int
     */
    public function countVisits(): int
    {
        return count($this->SQL->SELECT('visitor_id')->FROM('crm_visits')->WHERE('visitor_id', '=', $this->data['visitor_id'])->FIND_ALL());
    }

    /**
     * Gets a visitor's most recent visit (excluding the current one).
     *
     * @return \cms\wrappers\Visit|false
     */
    public function previousVisit()
    {
        if (isset($this->data['id']))
        {
            $visit = $this->SQL->SELECT('*')->FROM('crm_visits')->WHERE('visitor_id', '=', $this->data['visitor_id'])->ORDER_BY('date', 'DESC')->LIMIT(1, 1)->ROW();

            if ($visit)
            {
                return new Visit($this->SQL, $visit);
            }
        }

        return false;
    }

    /**
     * Calculates the time since their previous visit.
     *
     * @return int
     */
    public function timeSincePrevVisit(): int
    {
        $previousVisit = $this->previousVisit();

        if ($previousVisit)
        {
            return time() - $previousVisit->date;
        }

        return 0;
    }

    /**
     * Gets a their first visit.
     *
     * @return \cms\wrappers\Visit
     */
    public function firstVisit(): Visit
    {
        return new Visit($this->SQL, $this->SQL->SELECT('*')->FROM('crm_visits')->WHERE('visitor_id', '=', $this->data['visitor_id'])->ORDER_BY('date', 'ASC')->ROW());
    }

    /**
     * Mark visitor as still active on page.
     * Sets a visitor's last active to now.
     */
    public function heartBeat(): void
    {
        $previousVisit = $this->previousVisit();

        if ($previousVisit)
        {
            $previousVisit->end = time();

            $previousVisit->save();
        }

        $this->data['last_active'] = time();

        $this->save();
    }

    /**
     * Makes visitor a lead.
     *
     * @param  string $email Email address to subscribe
     * @param  string $name  Persons name (optional) (default '')
     * @return bool
     */
    public function makeLead(string $email, string $name = ''): bool
    {
        $this->data['email'] = $email;

        if ($name)
        {
            $this->data['name'] = $name;
        }

        $this->save();

        return true;
    }

    /**
     * Did this visitor bounce?
     *
     * @return bool
     */
    public function bounced(): bool
    {
        return $this->countVisits() <= 1;
    }

    /**
     * What is the visitor's initial channel entry.
     *
     * @return string
     */
    public function channel(): string
    {
        $visit = $this->firstVisit();

        if ($visit && !empty($visit->page))
        {
            $queryStr = Str::getAfterLastChar($visit->page, '?');

            if ($queryStr !== $visit->page)
            {
                $querySets = explode('&', trim($queryStr, '/'));

                if (!empty($querySets))
                {
                    foreach ($querySets as $querySet)
                    {
                        if (Str::contains($querySet, '='))
                        {
                            $querySet = explode('=', $querySet);
                            $key      = urldecode($querySet[0]);
                            $value    = urldecode($querySet[1]);

                            if ($key === 'ch')
                            {
                                return $value;
                            }
                        }
                    }
                }
            }
        }

        return 'direct';
    }

    /**
     * What is the visitor's initial medium entry.
     *
     * @return string
     */
    public function medium(): string
    {
        $visit = $this->firstVisit();

        if ($visit && !empty($visit->page))
        {
            $queryStr = Str::getAfterLastChar($visit->page, '?');

            if ($queryStr !== $visit->page)
            {
                $querySets = explode('&', trim($queryStr, '/'));

                if (!empty($querySets))
                {
                    foreach ($querySets as $querySet)
                    {
                        if (Str::contains($querySet, '='))
                        {
                            $querySet = explode('=', $querySet);
                            $key      = urldecode($querySet[0]);
                            $value    = urldecode($querySet[1]);

                            if ($key === 'md')
                            {
                                return $value;
                            }
                        }
                    }
                }
            }
        }

        return 'none';
    }

    /**
     * Grades the current visitor or a visitor.
     *
     * 1. Visitor
     * 2. Lead
     * 3. SQL
     * 4. Customer
     *
     * @param  \cms\wrappers\Visitor|null $visitor      Visitor to grade (optional) (default null)
     * @param  bool                             $returnString Return score as string (optional) (default false)
     * @return string|int
     */
    public function grade(Visitor $visitor = null, bool $returnString = false)
    {
        $visitor = !$visitor ? $this : $visitor;

        $visitCount = $visitor->countVisits();

        // Visitor is a customer
        if ($visitor->made_purchase)
        {
            return $returnString ? 'customer' : 4;
        }

        // Visitor is not a lead
        if (!$visitor->email)
        {
            return $returnString ? 'visitor' : 1;
        }
        // Visitor is a lead
        else
        {
            // SQL
            if ($visitCount >= 8)
            {
                return $returnString ? 'sql' : 3;
            }

            // Lead
            return $returnString ? 'lead' : 2;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function save(): bool
    {
        if (isset($this->data['id']))
        {
            $data = $this->data;

            unset($data['id']);

            unset($data['visit']);

            $update = $this->SQL->UPDATE('crm_visitors')->SET($data)->WHERE('id', '=', $this->data['id'])->EXEC();

            if ($update)
            {
                return true;
            }

            return false;
        }
        else
        {
            $data = $this->data;

            unset($data['visit']);

            // Insert into database
            $this->SQL->INSERT_INTO('crm_visitors')->VALUES($data)->EXEC();

            $this->data['id'] = intval($this->SQL->connectionHandler()->lastInsertId());

            return true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(): bool
    {
        if (isset($this->data['id']))
        {
            $this->SQL->DELETE_FROM('crm_visitors')->WHERE('id', '=', $this->data['id'])->EXEC();

            $this->SQL->DELETE_FROM('crm_visitors')->WHERE('visitor_id', '=', $this->data['visitor_id'])->EXEC();

            $this->SQL->DELETE_FROM('crm_visits')->WHERE('visitor_id', '=', $this->data['visitor_id'])->EXEC();

            $this->SQL->DELETE_FROM('crm_visit_actions')->WHERE('visitor_id', '=', $this->data['visitor_id'])->EXEC();

            return true;
        }

        return false;
    }
}
