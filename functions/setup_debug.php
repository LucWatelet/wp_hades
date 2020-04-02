<?php
namespace Atlb\Hades;

function setup_debug()
{
    add_submenu_page(
        $parent_slug = 'edit.php?post_type=hades_offre',
        $page_title = 'Débogage',
        $menu_title = 'Débogage',
        $capability = 'manage_options',
        $menu_slug = 'page_debug',
        $function = 'Atlb\Hades\debug_page'
    );
}
