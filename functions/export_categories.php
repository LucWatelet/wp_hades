<?php

namespace Atlb\Hades;

function export_categories_tree() {
    $settings = get_option('hades_settings');
    $racine_id = $settings['hades_category_parent'];
    $racine = get_term_by('id', $racine_id,'category');
    $tree = array('root' => array() );
    set_branch_export_categories_tree($tree['root'], $racine);
    $term_children = get_term_children($racine_id, 'category');
    foreach ($term_children as $child) {
        $term = get_term_by('id', $child, 'category');
        $parent = $term->parent;
        explore_children_export_categories_tree($tree['root'], $term);
    }
    return $tree;
}

function set_branch_export_categories_tree(&$branch, $term) {
    global $wpdb;
    $settings = get_option('hades_settings');
    $branch = array(
        'slug' => $term->slug,
        'name' => $term->name,
        'origin_id' => $term->term_id,
        'description' => $term->description,
        'parent_id' => $term->parent,
        'hades_categories' => array()
    );
    if ($settings['is_hades_category_parent'] == $term->term_id)
        $branch['is_hades_category_parent'] = 1;
    if ($settings['is_hades_event_category_parent'] == $term->term_id)
        $branch['is_hades_event_category_parent'] = 1;
    $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'hades_taxo_cat WHERE fk_tid=' . $term->term_id);
    foreach ($rows as $row) {
        $cat = array();
        $cat['slug'] = $row->fk_cat_id;
        $cat['xpath'] = $row->xpath;
        array_push($branch['hades_categories'], $cat);
    }
}

function explore_children_export_categories_tree(&$branch, $term) {

    if ($branch['origin_id'] == $term->parent) {
        if (!key_exists('children', $branch)) {
            $branch['children'] = array();
        }
        $branch['children'][$term->term_id] = array();
        set_branch_export_categories_tree($branch['children'][$term->term_id], $term);
    } elseif (key_exists('children', $branch)) {
        foreach ($branch['children'] as &$sub_branch) {
            explore_children_export_categories_tree($sub_branch, $term);
        }
    }
}
