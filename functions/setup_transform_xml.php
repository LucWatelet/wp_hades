<?php
namespace Atlb\Hades;

function setup_transform_xml()
{
    add_submenu_page(
        $parent_slug = 'edit.php?post_type=hades_offre',
        $page_title = 'Transformation du cache XML en post WordPress',
        $menu_title = 'Transformation XML',
        $capability = 'manage_options',
        $menu_slug = 'page_mise_a_jour_offre',
        $function = 'Atlb\Hades\xml_transform_page'
    );
}
