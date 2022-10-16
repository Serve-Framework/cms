<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\router\controllers;

use cms\sitemap\SiteMap as XMLMap;
use serve\mvc\controller\Controller;

/**
 * CMS Query Dispatcher.
 *
 * @author Joe J. Howard
 */
class Sitemap extends Controller
{
    /**
     * Loads the sitemap route.
     */
    public function load(): void
    {
    	$this->model->filter();

    	$template = $this->Config->get('cms.themes_path') . '/' . $this->Config->get('cms.theme_name') . '/sitemap.php';

	    if ($this->Filesystem->exists($template))
		{
			$this->fileResponse($template);
		}
		else
		{
			$sitemap = new XMLMap(
				$this->Request,
				$this->Response,
				$this->Config->get('cms.route_tags'),
				$this->Config->get('cms.route_categories'),
				$this->Config->get('cms.route_authors'),
				$this->Config->get('cms.route_attachments'),
				$this->Config->get('cms.custom_posts')
			);

			$sitemap->display();
		}
	}
}
