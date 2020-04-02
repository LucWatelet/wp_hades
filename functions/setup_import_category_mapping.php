<?php
namespace Atlb\Hades;

function setup_import_category_mapping()
{
    add_submenu_page(
        $parent_slug = 'edit.php?post_type=hades_offre',
        $page_title = 'import et export des catégories',
        $menu_title = 'Import/export des catégories',
        $capability = 'manage_options',
        $menu_slug = 'import_category_mapping_page',
        $function = 'Atlb\Hades\import_category_mapping_page'
    );
}
