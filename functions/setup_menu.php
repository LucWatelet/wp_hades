<?php
namespace Atlb\Hades;

function setup_menu()
{
    add_submenu_page(
        $parent_slug = 'edit.php?post_type=hades_offre',
        $page_title = 'Association des catégories Hadès aux catégories WordPress',
        $menu_title = 'Association des catégories',
        $capability = 'manage_options',
        $menu_slug = 'page_assoc_cat',
        $function = 'page_assoc_cat'
    );
    add_submenu_page(
        $parent_slug = 'hades',
        $page_title = 'Sélections',
        $menu_title = 'Sélections',
        $capability = 'manage_options',
        $menu_slug = 'page_selections',
        $function = 'page_selections'
    );
    add_submenu_page(
        $parent_slug = 'edit.php?post_type=hades_offre',
        $page_title = "Paramètres de l'extension",
        $menu_title = "Paramètres de l'extension",
        $capability = 'manage_options',
        $menu_slug = 'hades_settings',
        $function = 'Atlb\Hades\settings_page'
    );
}
