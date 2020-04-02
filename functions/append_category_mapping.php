<?php
namespace Atlb\Hades;

function append_category_mapping(&$tree, $parent_term = 0)
{
    global $wpdb;

    if ($tree['slug']) {
        $new_term = wp_insert_term(
            $tree['name'],
            'category',
            array(
                'description' => $tree['description'],
                'slug' => $tree['slug'],
                'parent_id' => $parent_term
            )
        );
        
        if (!is_array($new_term)) {
            return $new_term;
        }

        if (key_exists('is_hades_event_category_parent', $tree)) {
            $settings = get_option('hades_settings');
            $settings['is_hades_event_category_parent'] = $new_term['term_id'];
            update_option('hades_settings', $settings);
        }

        if (key_exists('is_hades_category_parent', $tree)) {
            $settings = get_option('hades_settings');
            $settings['is_hades_category_parent'] = $new_term['term_id'];
            update_option('hades_settings', $settings);
        }

        if (is_array($tree['hades_categories']) && count($tree['hades_categories']) > 0) {
            $query_head = " INSERT INTO `" . $wpdb->prefix . "hades_taxo_cat` (`fk_cat_id`, `xpath`, `fk_tid`) VALUES ";
            $query_val_array = array();
            foreach ($tree['hades_categories'] as $cat) {
                $query_val_array[] = "(\"" . addslashes($cat["slug"]) . "\",\"" . addslashes($cat["xpath"]) . "\"," . $new_term['term_id'] . ")";
            }
            $wpdb->get_results($query_head . join(",", $query_val_array));
        }

        if ($tree['children']) {
            foreach ($tree['children'] as $subtree) {
                write_category_mapping($subtree, $new_term['term_id']);
            }
        }
    }
    return true;
}
