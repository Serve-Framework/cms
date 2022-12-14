<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

namespace cms\query\helpers;

/**
 * CMS Query template methods.
 *
 * @author Joe J. Howard
 */
class Templates extends Helper
{
    /**
     * Display the contents of header.php.
     *
     * @return string
     */
    public function the_header(): string
    {
        return $this->container->Response->view()->display($this->query->theme_directory() . DIRECTORY_SEPARATOR . 'header.php');
    }

    /**
     * Display the contents of footer.php.
     *
     * @return string
     */
    public function the_footer(): string
    {
        return $this->container->Response->view()->display($this->query->theme_directory() . DIRECTORY_SEPARATOR . 'footer.php');
    }

    /**
     * Display the contents of sidebar.php.
     *
     * @return string
     */
    public function the_sidebar(): string
    {
        return $this->container->Response->view()->display($this->query->theme_directory() . DIRECTORY_SEPARATOR . 'sidebar.php');
    }

    /**
     * Display the contents of any template file relative to the theme's base directory.
     *
     * @param  string $template_name Template file name/path without .php extension
     * @param  array  $data          Array of variables to make available within file scope (optional) (default [])
     * @return string
     */
    public function include_template(string $template_name, array $data = []): string
    {
        $template = $this->query->theme_directory() . DIRECTORY_SEPARATOR . $template_name . '.php';

        if (file_exists($template))
        {
            return $this->container->Response->view()->display($template, $data);
        }

        return '';
    }
}
