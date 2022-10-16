<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\schema;

use cms\schema\json\Address;
use cms\schema\json\Article;
use cms\schema\json\Author;
use cms\schema\json\Brand;
use cms\schema\json\Breadcrumb;
use cms\schema\json\Image;
use cms\schema\json\ItemList;
use cms\schema\json\JsonInterface;
use cms\schema\json\Logo;
use cms\schema\json\Organization;
use cms\schema\json\Profile;
use cms\schema\json\Search;
use cms\schema\json\Webpage;
use cms\schema\json\Website;
use serve\mvc\model\Model;

/**
 * SEO Schema.
 *
 * @author Joe J. Howard
 */
class Schema extends Model
{
    /**
     * JSON Generators.
     *
     * @var array
     */
    private $generators =
    [
        Organization::class,
        Brand::class,
        Website::class,
        Webpage::class,
        Profile::class,
        Article::class,
        Author::class,
        Address::class,
        Logo::class,
        Search::class,
        Image::class,
        ItemList::class,
        Breadcrumb::class,
    ];

    /**
     * The schema graph.
     *
     * @var bool
     */
    private $haveBreadcrumb = false;

    /**
     * The schema graph.
     *
     * @var array
     */
    private $graph = [];

    /**
     * Create and return the schema.org graph.
     *
     * @return array
     */
    public function graph(): array
    {
        $schema = [];

        foreach ($this->generators as $class)
        {
            $this->graph[strval($class)] = $this->invokeSchemaClass($class)->generate();
        }

        $schema =
        [
            '@context' => 'https://schema.org',
            '@graph'   => $this->sanitizeGraph(),
        ];

        return $schema;
    }

    /**
     * Invoke and return schema component by classname.
     *
     * @param  string                               $className The classname to invoke
     * @return \cms\schema\json\JsonInterface
     */
    private function invokeSchemaClass(string $className): JsonInterface
    {
        return new $className($this->Request, $this->Response, $this->Config, $this->PostManager, $this->CategoryManager, $this->TagManager, $this->UserManager, $this->MediaManager, $this->CommentManager);
    }

    /**
     * Sanitize the final output.
     *
     * @return array
     */
    private function sanitizeGraph(): array
    {
        $this->haveBreadcrumb = !empty($this->graph[strval(Breadcrumb::class)]);

        foreach ($this->graph as $class => $data)
        {
            foreach ($data as $key => $val)
            {
                if ($key === 'breadcrumb' && !$this->haveBreadcrumb)
                {
                    unset($this->graph[$class][$key]);
                }
            }
        }

        return array_values(array_filter($this->graph));
    }

}
