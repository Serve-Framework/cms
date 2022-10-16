<?php

// Blog category
if (is_blog_location())
{
    $breadcrumbs =
    [
        $serve->Request->environment()->HTTP_HOST => 'Home',
        $serve->Request->environment()->HTTP_HOST . '/blog/' => 'Blog',
    ];
}
// Single Blog Post
elseif (is_single())
{
    // Base crumbs
    $breadcrumbs =
    [
        $serve->Request->environment()->HTTP_HOST => 'Home',
        $serve->Request->environment()->HTTP_HOST . '/blog/' => 'Blog',
    ];

    $category = the_category(the_post_id());

    $breadcrumbs[the_category_url($category->id)] = $category->name;
}
elseif (is_category())
{
    // Base crumbs
    $breadcrumbs =
    [
        $serve->Request->environment()->HTTP_HOST       => 'Home',
        $serve->Request->environment()->HTTP_HOST . '/blog/' => 'Blog',
    ];

    // Split URL into parts
    $urlParts = array_filter(explode('/', $serve->Request->environment()->REQUEST_PATH));

    // Remove 'blog/category'
    array_shift($urlParts);
    array_shift($urlParts);

    // Remove '/page/number/'
    if (in_array('page', $urlParts))
    {
        array_pop($urlParts);
        array_pop($urlParts);
    }

    // Nested categories
    foreach ($urlParts as $_slug)
    {
        $category = $serve->CategoryManager->bySlug($_slug);
        $breadcrumbs[the_category_url($category->id)] = $category->name;
    }
}
// Blog tag or author
elseif(is_tag() || is_author())
{
    $breadcrumbs =
    [
        $serve->Request->environment()->HTTP_HOST => 'Home',
        $serve->Request->environment()->HTTP_HOST . '/blog/' => 'Blog',
    ];
}       

?>

<?php if (isset($breadcrumbs)) : ?>
<div class="breadcrumbs">
    <ul>
        <?php $i = 1; foreach($breadcrumbs as $url => $name) :?>
            <li class="crumb"><a href="<?php echo $url;?>"><?php echo $name;?></a></li>
            <?php if ($i < count($breadcrumbs)) : ?><li class="spacer">»</li><?php endif;?>
        <?php $i++; endforeach; ?>
    </ul>
</div>

<?php endif; ?>

