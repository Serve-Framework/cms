<?php

/**
 * Helper functions for theme.
 */
use cms\wrappers\Post;
use serve\utility\Str;
use serve\application\Application;

/**
 * Assets cache busting.
 *
 * @return string
 */
function assetsVersion(): string
{
	if (Application::instance()->environment() === 'sandbox')
	{
		return strval(time());
	}

	return strval(Application::VERSION);
}

/**
 * Enqueue scripts.
 */
function enqueueScripts(): void
{
	// theme css
	enqueue_style(theme_url() . '/assets/css/theme.css', assetsVersion());

	// Custom inline script example
	enqueue_inline_script('var foo = "bar";console.log(foo);');

	// theme.js
	enqueue_script(theme_url() . '/assets/js/theme.js', assetsVersion(), true);
}

/**
 * Custom excerpt length.
 *
 * @param  string $excerpt Text to shorten
 * @param  int    $length  Length of return string
 * @param  string $suffix  Suffix to add when string is shortened (optional) (default '')
 * @param  bool   $toChar  Trim to char (true) or words (false) (optional) (default true)
 * @return string
 */
function trimExcerpt($excerpt, $length, $suffix = '', $toChar = true): string
{
    if ($toChar) return (strlen($excerpt) > $length) ? substr($excerpt, 0, $length) . $suffix : $excerpt;

    $words = explode(' ', $excerpt);

    if(count($words) > $length) return implode(' ', array_slice($words, 0, $length)) . $suffix;

    return $excerpt;
}

/**
 * Get related posts.
 *
 * @param  int|null $postid Post id defaults to current post (optional) (default null)
 * @param  int      $max    Maxiumum posts to return
 * @param  bool     $random Randomize the results
 * @return array
 */
function relatedPosts(int $postid = null, $max = 10, $random = true): array
{
	$query      = Application::instance()->Query;
	$postid     = !$postid ? the_post_id() : $postid;
	$maxPool    = $maxPool = !$random ? $max : $max + $max;
	$post       = the_post($postid);
	$categories = $post->categories;
	$posts      = [];

	foreach ($categories as $category)
	{
		$_posts = $query->create([
			'post_status' =>
            [
                'condition' => '=',
                'match'     => 'published'
            ],
            'post_type' =>
            [
                'condition' => '=',
                'match'     => 'post'
            ],
            'category_id' =>
            [
                'condition' => '=',
                'match'     => $category->id,
            ],
            'sort' =>
            [
                'by'        => 'post_created',
                'direction' => 'DESC'
            ],
            'per_page' => 10,
            'page'     => 1,

		])->the_posts();

		foreach ($_posts as $_post)
		{
			if ($_post->id === $postid)
			{
				continue;
			}
			elseif (count($posts) === $maxPool)
			{
				break;
			}

			$posts[$_post->id] = $_post;
		}
	}

	if (count($posts) === $maxPool)
	{
		$posts = array_values($posts);

		if ($random)
		{
			shuffle($posts);
		}

		return array_slice($posts, 0, $max);
	}

	if (count($post->tags) > 0)
	{
		$tags = [];

		foreach ($post->tags as $tag)
	    {
	    	$tags[] = $tag->id;
	    }

		$_posts = $query->create([
			'post_status' =>
            [
                'condition' => '=',
                'match'     => 'published'
            ],
            'post_type' =>
            [
                'condition' => '=',
                'match'     => 'post'
            ],
            'tag_id' =>
            [
                'condition' => 'IN',
                'match'     => $tags,
            ],
            'sort' =>
            [
                'by'        => 'post_created',
                'direction' => 'DESC'
            ],
            'per_page' => 10,
            'page'     => 1,

		])->the_posts();

		foreach ($_posts as $_post)
		{
			if ($_post->id === $postid)
			{
				continue;
			}
			elseif (count($posts) === $maxPool)
			{
				break;
			}

			$posts[$_post->id] = $_post;
		}
	}

	if (count($posts) < $max)
	{
		$_posts = $query->create([
			'post_status' =>
            [
                'condition' => '=',
                'match'     => 'published'
            ],
            'post_type' =>
            [
                'condition' => '=',
                'match'     => 'post'
            ],
            'per_page' => 10,
            'page'     => 1,

		])->the_posts();

		foreach ($_posts as $_post)
		{
			if ($_post->id === $postid)
			{
				continue;
			}
			elseif (count($posts) === $maxPool)
			{
				break;
			}

			$posts[$_post->id] = $_post;
		}
	}

	$posts = array_values($posts);

	if ($random)
	{
		shuffle($posts);
	}

	return array_slice($posts, 0, $max);
}

/**
 * Returns a fallback img 
 *
 * @param  string $url URL to full size image
 * @return string
 */
function fallbackImg(?string $url = null): string
{
	if (!$url)
	{

		return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2aWV3Qm94PSIwIDAgNTAwIDUwMCI+PHBhdGggZmlsbD0iI0NGQ0ZDRiIgZD0iTTI1MCw0OTkuOWMtODEuNCwwLTE2Mi44LTAuMS0yNDQuMiwwLjFDMSw1MDAsMCw0OTksMCw0OTQuMkMwLjIsMzMxLjQsMC4yLDE2OC42LDAsNS44QzAsMSwxLDAsNS44LDBjMTYyLjgsMC4yLDMyNS41LDAuMiw0ODguMywwYzQuOSwwLDUuOSwxLDUuOCw1LjhjLTAuMiwxNjIuOC0wLjIsMzI1LjUsMCw0ODguM2MwLDQuOS0xLDUuOS01LjgsNS45QzQxMi44LDQ5OS44LDMzMS40LDQ5OS45LDI1MCw0OTkuOXoiLz48cGF0aCBmaWxsPSIjQTlBN0E3IiBkPSJNMjQ5LjgsMzE0LjJjLTE3LjYsMC0zNS4yLDAuMS01Mi44LDBjLTE0LjUtMC4xLTIyLjYtOC4xLTIyLjctMjIuNWMtMC4xLTIxLjYsMC00My4zLDAtNjQuOWMwLTE0LjYsNy4yLTIyLjQsMjEuOC0yMy43YzIuMS0wLjIsNC4zLTAuMyw2LjQsMGM2LjcsMC45LDExLTAuOCwxMy4yLTguMmMyLjQtOC4xLDguNy0xMiwxNy40LTExLjljMTEuNCwwLjEsMjIuOC0wLjEsMzQuMiwwLjFjOSwwLjEsMTQuOCw0LjEsMTcuNSwxMi44YzEuNyw1LjUsNC40LDcuNywxMC4yLDcuM2M1LjYtMC40LDExLjMtMC42LDE2LjksMS42YzguOCwzLjQsMTMuNSw5LjgsMTMuNiwxOWMwLjIsMjMuMywwLjMsNDYuNiwwLDY5LjljLTAuMSwxMi41LTguNywyMC42LTIxLjYsMjAuN0MyODYsMzE0LjQsMjY3LjksMzE0LjIsMjQ5LjgsMzE0LjJ6Ii8+PHBhdGggZmlsbD0iI0NGQ0ZDRiIgZD0iTTI0OS44LDI5NC4yYy0xOC43LTAuMS0zNi0xNi42LTM1LjItMzVjMC45LTIxLjcsMTUuNC0zNS40LDM1LjgtMzYuMWMxOC40LTAuNiwzNS4yLDE3LDM1LjIsMzUuM0MyODUuNywyNzguMywyNjkuNywyOTQuMywyNDkuOCwyOTQuMnoiLz48cGF0aCBmaWxsPSIjQTlBN0E3IiBkPSJNMjQ5LjYsMjgxLjRjLTEyLjEtMC4yLTIyLjYtMTAuOS0yMi40LTIzLjFjMC4yLTExLjcsMTEuOC0yMi45LDIzLjItMjIuNGMxMS43LDAuNSwyMi4zLDExLjIsMjIuMywyMi42QzI3Mi44LDI3MS4xLDI2Mi4zLDI4MS42LDI0OS42LDI4MS40eiIvPjwvc3ZnPg==';
	}

	return $url;
}


