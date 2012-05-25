<?php
/*
Template Name: Redirect To First Child
*/
if (have_posts()) {
    while (have_posts()) {
        the_post();
        $children = get_pages(array(
            'child_of'      => get_the_ID(),
            'sort_column'   => 'menu_order',
            'sort_order'    => 'ASC',
        ));
        $firstchild = $children[0];
        wp_redirect(get_permalink($firstchild->ID));exit;
    }
}