<?php
namespace Atlb\Hades;

function reset_plugin_data()
{
    global $wpdb;
    $tables = array('flux_index', 'localites', 'offre_categories', 'offre_dateheure', 'offre_liee', 'offre_localites', 'offre_node', 'offre_suppr', 'offre_xml', 'post_spatial', 'selections');
    foreach ($tables as $table) {
        echo $wpdb->query('TRUNCATE ' . $wpdb->prefix . 'hades_' . $table);
        echo "\r\n";
        ob_flush();
        flush();
    }
    $offers = get_posts(
        $args = array(
            'post_type' => HADES_CPT,
            'numberposts' => -1,
        )
    );
    foreach ($offers as $offer) {
        delete_wp_post($offer);
    }
    $settings = get_option('hades_settings');
    $settings['flux_from_datetime'] = '2000-01-01 00:00:00';
    update_option($option = 'hades_settings', $settings);
    echo 'réinitialisation de la date de suppression des flux à 2000-01-01 00:00:00';
    echo "\r\n";
    $settings['maj_en_cours'] = null;
    update_option($option = 'hades_settings', $settings);
    echo 'réinitialisation de la date de mise à jour en cours';
    echo "\r\n";
    update_option($option = 'hades_xml_transform_status', null);
    update_option($option = 'hades_feed_loading_status', null);
    echo 'réinitialisation des statuts';
    echo "\r\n";
    ob_flush();
    flush();
}
