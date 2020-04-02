<?php
namespace Atlb\Hades;

function setup_feed_loading()
{
    add_submenu_page(
        $parent_slug = 'edit.php?post_type=hades_offre',
        $page_title = 'Flux de données et gestion du cache XML',
        $menu_title = 'Flux et cache XML',
        $capability = 'manage_options',
        $menu_slug = 'page_flux',
        $function = 'page_flux'
    );
}
