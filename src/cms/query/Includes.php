<?php

/**
 * @copyright Joe J. Howard
 * @license   https://github.com/serve-framework/cms/blob/master/LICENSE
 */

use serve\application\Application;

/**
 * View includes.
 *
 * @author Joe J. Howard
 */
function tag_exists($tag_name)
{
    return Application::instance()->Query->tag_exists($tag_name);
}

function author_exists($author_name)
{
    return Application::instance()->Query->author_exists($author_name);
}

function category_exists($category_name)
{
    return Application::instance()->Query->category_exists($category_name);
}

function the_post($post_id = null)
{
    return Application::instance()->Query->the_post($post_id);
}

function the_posts()
{
    return Application::instance()->Query->the_posts();
}

function the_title($post_id = null)
{
    return Application::instance()->Query->the_title($post_id);
}

function the_permalink($post_id = null)
{
    return Application::instance()->Query->the_permalink($post_id);
}

function the_slug($post_id = null)
{
    return Application::instance()->Query->the_slug($post_id);
}

function the_excerpt($post_id = null)
{
    return Application::instance()->Query->the_excerpt($post_id);
}

function the_category($post_id = null)
{
    return Application::instance()->Query->the_category($post_id);
}

function the_categories($post_id = null)
{
    return Application::instance()->Query->the_categories($post_id);
}

function the_category_name($post_id = null)
{
    return Application::instance()->Query->the_category_name($post_id);
}

function the_category_url($category_id = null)
{
    return Application::instance()->Query->the_category_url($category_id);
}

function the_category_slug($category_id = null)
{
    return Application::instance()->Query->the_category_slug($category_id);
}

function the_category_id($category_name = null)
{
    return Application::instance()->Query->the_category_id($category_name);
}

function the_categories_list($post_id = null, $glue = ', ')
{
    return Application::instance()->Query->the_categories_list($post_id, $glue);
}

function the_tags($post_id = null)
{
    return Application::instance()->Query->the_tags($post_id);
}

function the_tags_list($post_id = null, $glue = ', ')
{
    return Application::instance()->Query->the_tags_list($post_id, $glue);
}

function the_tag_slug($tag_id = null)
{
    return Application::instance()->Query->the_tag_slug($tag_id);
}

function the_tag_url($tag_id = null)
{
    return Application::instance()->Query->the_tag_url($tag_id);
}

function the_taxonomy()
{
    return Application::instance()->Query->the_taxonomy();
}

function the_attachment()
{
    return Application::instance()->Query->the_attachment();
}

function all_the_attachments()
{
    return Application::instance()->Query->all_the_attachments();
}

function the_attachment_url($id = null)
{
    return Application::instance()->Query->the_attachment_url($id);
}

function the_attachment_size()
{
    return Application::instance()->Query->the_attachment_size();
}

function the_attachments_url()
{
    return Application::instance()->Query->the_attachments_url();
}

function the_content($post_id = null, $raw = false)
{
    return Application::instance()->Query->the_content($post_id, $raw);
}

function the_post_thumbnail($post_id = null, $size = 'original')
{
    return Application::instance()->Query->the_post_thumbnail($post_id, $size);
}

function the_post_thumbnail_src($post_id = null, $size = 'original')
{
    return Application::instance()->Query->the_post_thumbnail_src($post_id, $size);
}

function display_thumbnail($thumbnail, $size = 'original', $width = '', $height = '', $classes = '', $id = '')
{
    return Application::instance()->Query->display_thumbnail($thumbnail, $size, $width, $height, $classes, $id);
}

function the_author($post_id = null)
{
    return Application::instance()->Query->the_author($post_id);
}

function the_author_name($post_id = null)
{
    return Application::instance()->Query->the_author_name($post_id);
}

function the_author_url($author_id = null)
{
    return Application::instance()->Query->the_author_url($author_id);
}

function the_author_thumbnail($author_id = null)
{
    return Application::instance()->Query->the_author_thumbnail($author_id);
}

function the_author_thumbnail_src($author_id = null, $size = 'small')
{
    return Application::instance()->Query->the_author_thumbnail($author_id, $size);
}

function the_author_bio($author_id = null)
{
    return Application::instance()->Query->the_author_bio($author_id);
}

function the_author_twitter($author_id = null)
{
    return Application::instance()->Query->the_author_twitter($author_id);
}

function the_author_google($author_id = null)
{
    return Application::instance()->Query->the_author_google($author_id);
}

function the_author_facebook($author_id = null)
{
    return Application::instance()->Query->the_author_facebook($author_id);
}

function the_author_instagram($author_id = null)
{
    return Application::instance()->Query->the_author_instagram($author_id);
}

function the_post_id()
{
    return Application::instance()->Query->the_post_id();
}

function the_post_status($post_id = null)
{
    return Application::instance()->Query->the_post_status($post_id);
}

function the_post_type($post_id = null)
{
    return Application::instance()->Query->the_post_type($post_id);
}

function the_post_meta($post_id = null)
{
    return Application::instance()->Query->the_post_meta($post_id);
}

function the_time($format = 'U', $post_id = null)
{
    return Application::instance()->Query->the_time($format, $post_id);
}

function the_modified_time($format = 'U', $post_id = null)
{
    return Application::instance()->Query->the_modified_time($format, $post_id);
}

function the_author_posts($author_id, $publihsed = true)
{
    return Application::instance()->Query->the_author_posts($author_id, $publihsed);
}

function the_category_posts($category_id, $publihsed = true)
{
    return Application::instance()->Query->the_category_posts($category_id, $publihsed);
}

function the_tag_posts($tag_id, $publihsed = true)
{
    return Application::instance()->Query->the_tag_posts($tag_id, $publihsed);
}

function the_page_type()
{
    return Application::instance()->Query->the_page_type();
}

function is_single()
{
    return Application::instance()->Query->is_single();
}

function is_custom_post()
{
    return Application::instance()->Query->is_custom_post();
}

function is_home()
{
    return Application::instance()->Query->is_home();
}

function is_front_page()
{
    return Application::instance()->Query->is_front_page();
}

function is_blog_location()
{
    return Application::instance()->Query->is_blog_location();
}

function is_page($slug = null)
{
    return Application::instance()->Query->is_page($slug);
}

function is_search()
{
    return Application::instance()->Query->is_search();
}

function is_tag()
{
    return Application::instance()->Query->is_tag();
}

function is_category()
{
    return Application::instance()->Query->is_category();
}

function is_author()
{
    return Application::instance()->Query->is_author();
}

function is_admin()
{
    return Application::instance()->Query->is_admin();
}

function is_attachment()
{
    return Application::instance()->Query->is_attachment();
}

function is_not_found()
{
    return Application::instance()->Query->is_not_found();
}

function has_post_thumbnail($post_id = null)
{
    return Application::instance()->Query->has_post_thumbnail($post_id);
}

function has_author_thumbnail($author_id = null)
{
    return Application::instance()->Query->has_author_thumbnail($author_id);
}

function has_tags($post_id = null)
{
    return Application::instance()->Query->has_tags($post_id);
}

function has_category($post_id = null)
{
    return Application::instance()->Query->has_category($post_id);
}

function the_next_page()
{
    return Application::instance()->Query->the_next_page();
}

function the_previous_page()
{
    return Application::instance()->Query->the_previous_page();
}

function the_next_page_title()
{
    return Application::instance()->Query->the_next_page_title();
}

function the_previous_page_title()
{
    return Application::instance()->Query->the_previous_page_title();
}

function the_next_page_url()
{
    return Application::instance()->Query->the_next_page_url();
}

function the_previous_page_url()
{
    return Application::instance()->Query->the_previous_page_url();
}

function search_query()
{
    return Application::instance()->Query->search_query();
}

function have_posts($post_id = null)
{
    return Application::instance()->Query->have_posts($post_id);
}

function the_posts_count()
{
    return Application::instance()->Query->the_posts_count();
}

function posts_per_page()
{
    return Application::instance()->Query->posts_per_page();
}

function blog_location()
{
    return Application::instance()->Query->blog_location();
}

function _next()
{
    return Application::instance()->Query->_next();
}

function _previous()
{
    return Application::instance()->Query->_previous();
}

function all_the_tags()
{
    return Application::instance()->Query->all_the_tags();
}

function all_the_categories()
{
    return Application::instance()->Query->all_the_categories();
}

function has_categories($postid = null)
{
    return Application::instance()->Query->has_categories($postid);
}

function all_the_authors()
{
    return Application::instance()->Query->all_the_authors();
}

function all_static_pages($publihsed = true)
{
    return Application::instance()->Query->all_static_pages($publihsed);
}

function all_custom_posts($type, $publihsed = true)
{
    return Application::instance()->Query->all_custom_posts($type, $publihsed);
}

function the_header(): void
{
    echo Application::instance()->Query->the_header();
}

function the_footer(): void
{
    echo Application::instance()->Query->the_footer();
}

function the_sidebar(): void
{
    echo Application::instance()->Query->the_sidebar();
}

function include_template($template_name, $data = []): void
{
    echo Application::instance()->Query->include_template($template_name, $data);
}

function enqueue_script(string $src = '', string $ver = null, bool $inFooter = false)
{
    return Application::instance()->Query->enqueue_script($src, $ver, $inFooter);
}

function enqueue_style(string $src = '', string $ver = null, string $media = 'all')
{
    return Application::instance()->Query->enqueue_style($src, $ver, $media);
}

function enqueue_inline_style(string $css = '')
{
    return Application::instance()->Query->enqueue_inline_style($css);
}

function enqueue_inline_script(string $js = '', bool $inFooter = false)
{
    return Application::instance()->Query->enqueue_inline_script($js, $inFooter);
}

function serve_head(): void
{
    echo Application::instance()->Query->serve_head();
}

function serve_footer(): void
{
    echo Application::instance()->Query->serve_footer();
}

function themes_directory()
{
    return Application::instance()->Query->themes_directory();
}

function theme_directory()
{
    return Application::instance()->Query->theme_directory();
}

function theme_name()
{
    return Application::instance()->Query->theme_name();
}

function theme_url()
{
    return Application::instance()->Query->theme_url();
}

function base_url()
{
    return Application::instance()->Query->base_url();
}

function home_url()
{
    return Application::instance()->Query->home_url();
}

function blog_url()
{
    return Application::instance()->Query->blog_url();
}

function domain_name()
{
    return Application::instance()->Query->domain_name();
}

function website_title()
{
    return Application::instance()->Query->website_title();
}

function website_description()
{
    return Application::instance()->Query->website_description();
}

function the_meta_title()
{
    return Application::instance()->Query->the_meta_title();
}

function the_canonical_url()
{
    return Application::instance()->Query->the_canonical_url();
}

function the_meta_description()
{
    return Application::instance()->Query->the_meta_description();
}

function current_userinfo()
{
    return Application::instance()->Query->get_current_userinfo();
}

function user()
{
    return Application::instance()->Query->user();
}

function is_loggedin()
{
    return Application::instance()->Query->is_loggedin();
}

function user_is_admin()
{
    return Application::instance()->Query->user_is_admin();
}

function get_gravatar($email_or_md5 = null, $size = 160, $srcOnly = false)
{
    return Application::instance()->Query->get_gravatar($email_or_md5, $size, $srcOnly);
}

function comments_open($postId = null)
{
    return Application::instance()->Query->comments_open($postId);
}

function has_comments($postId = null)
{
    return Application::instance()->Query->has_comments($postId);
}

function comments_number($postId = null)
{
    return Application::instance()->Query->comments_number($postId);
}

function comment($commentid)
{
    return Application::instance()->Query->get_comment($commentid);
}

function comments($postId = null)
{
    return Application::instance()->Query->get_comments($postId);
}

function display_comments($args = null, $postId = null): void
{
    echo Application::instance()->Query->display_comments($args, $postId);
}

function comment_form($args = null, $postId = null)
{
    return Application::instance()->Query->comment_form($args, $postId);
}

function pagination_links($args = null)
{
    return Application::instance()->Query->pagination_links($args);
}

function search_form()
{
    return Application::instance()->Query->get_search_form();
}

function rewind_posts()
{
    return Application::instance()->Query->rewind_posts();
}
