<?php
namespace Atlb\Hades;

function xml_transform($what = '')
{
    $warning_message = false;
    $xml_transform_status = json_decode(get_option($option = 'hades_xml_transform_status'), true);
    $xml_transform_status['status'] = 'running';
    $xml_transform_status['last_started'] = date('c');
    update_option('hades_xml_transform_status', json_encode($xml_transform_status));
    global $hades_syn_text_log;
    if ($hades_syn_text_log) {
        echo "\r\n<h1>Conversion XML -> WP</h1>";
    } else {
        echo "<h1>Conversion XML -> WP</h1>";
    }

    $converter = \Hades_Converter::get_instance();
    global $wpdb;

    switch (true) {
        case $what == "express":
            //ou uniquement ceux dont la date de modification a changé :
            echo "\n <p>mise à jour express</p>";
            $results = $wpdb->get_results("
                SELECT DISTINCT fk_off_id FROM " . HADESDBPFX . "offre_xml 
                    LEFT JOIN " . $wpdb->prefix . "postmeta ON meta_key='hades_id' AND meta_value= fk_off_id
                    LEFT JOIN " . $wpdb->prefix . "posts ON ID= post_id 
                    WHERE modif_date!=post_date OR meta_id IS NULL;");
            $warning_message = 'no offer were updated';
            break;

        case preg_match("/^[0-9,]+$/", $what) == 1:
            //ou des offres en particulier
            echo "\n<p>mise à jour de (" . $what . ")</p>\n";
            $results = $wpdb->get_results("SELECT fk_off_id FROM `" . HADESDBPFX . "offre_xml` WHERE fk_off_id IN(" . $what . ");");
            $warning_message = 'bad IDs';
            break;

        case $what == "last":
            echo "\n *** mise à jour des dernières offres *** \n";
            $results = $wpdb->get_results("SELECT fk_off_id FROM `" . HADESDBPFX . "offre_xml` WHERE fk_off_id>".$_GET["id"]." ;");
            $warning_message = 'no feed';
            break;


        default:
            // tous
            echo "<p>mise à jour de toutes les offres</p>\n";
            $results = $wpdb->get_results("SELECT fk_off_id FROM `" . HADESDBPFX . "offre_xml`; ");
            $warning_message = 'no feed';
            break;
    }
    if (count($results) === 0) {
        echo $warning_message;
        echo 'nothing to do';
    } else {
        foreach ($results as $result) {
            //if($result->fk_off_id==49095)
            set_time_limit(ini_get('max_execution_time'));
            $converter->convert_xml_to_wp($result->fk_off_id);
            ob_flush();
            flush();
        }
    
        /* SUPRESSION DES OFFRES PERIMEES */

        $results = $wpdb->get_results("SELECT off_id  FROM " . HADESDBPFX . "offre_suppr WHERE suppr_date > DATE_SUB( NOW(), INTERVAL 7 DAY ) ;");

        foreach ($results as $result) {
            //if($result->fk_off_id==49095)
            $converter->delete_xml_to_wp($result->off_id);
            ob_flush();
            flush();
        }
        /* GEOLOCALISATION DES LOCALITES */
        foreach ($converter->geo_loc as $key => $gl) {
            $obj_term=get_term_by('name', $key, HADES_TAXO_LOC);
            update_term_meta($obj_term->term_id, 'x', $gl['x']);
            update_term_meta($obj_term->term_id, 'y', $gl['y']);
        }

        /* DONNEES SPATIALES DES OFFRES */
        $results = $wpdb->get_results("TRUNCATE " . HADESDBPFX . "post_spatial;");
        $results = $wpdb->get_results("
            INSERT IGNORE INTO " . HADESDBPFX . "post_spatial
                SELECT
                    x.post_id as ID,
                    PointFromText(CONCAT('POINT(', (x.meta_value-5)*111.319, ' ', (y.meta_value-49.50)*172.190, ')')) AS coord
                FROM ".$wpdb->prefix."postmeta x,
                    ".$wpdb->prefix."postmeta y
                WHERE x.post_id = y.post_id
                AND x.meta_key = 'gps_x'
                AND y.meta_key = 'gps_y';");
    
        echo "<hr/><p>Synchronisation terminée.</p><hr/>";
    }
    $xml_transform_status['status'] = 'idle';
    $xml_transform_status['last_finished'] = date('c');
    update_option('hades_xml_transform_status', json_encode($xml_transform_status));
}
