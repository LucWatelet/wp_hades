<?php
namespace Atlb\Hades;

function hide_unused_submenu_pages()
{
    remove_submenu_page(
        $menu_slug = 'edit.php?post_type=hades_offre',
        $submenu_slug = 'edit-tags.php?taxonomy=localite&amp;post_type=hades_offre'
    );
    remove_submenu_page(
        $menu_slug = 'edit.php?post_type=hades_offre',
        $submenu_slug = 'edit-tags.php?taxonomy=selection&amp;post_type=hades_offre'
    );
    remove_submenu_page(
        $menu_slug = 'edit.php?post_type=hades_offre',
        $submenu_slug = 'edit-tags.php?taxonomy=commune&amp;post_type=hades_offre'
    );
    remove_submenu_page(
        $menu_slug = 'edit.php?post_type=hades_offre',
        $submenu_slug = 'post-new.php?post_type=hades_offre'
    );
}
