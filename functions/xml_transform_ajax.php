<?php
namespace Atlb\Hades;

function xml_transform_ajax()
{
        global $wpdb;

        /* -----Pierre Ernould -----
        Mise à la corbeille des offres périmées */
        $t = date("Y-m-d");
        $db = $wpdb->prefix;
        $r = $wpdb->query(
            $wpdb->prepare(
                "update ".$wpdb->posts."
                inner join ".$wpdb->postmeta."
                on ".$wpdb->posts.".ID = ".$wpdb->postmeta.".post_id
                set ".$wpdb->posts.".post_status = %s
                where ".$wpdb->postmeta.".meta_key = %s and
                ".$wpdb->postmeta.".meta_value < %s",
                'trash', 'date_fin', $t
            )
        );
//        var_dump($r);

    if ('express' === $_POST['type_synchro']) {
        update_option('hades_last_sync_mode', 'express');
        xml_transform('express');
    } elseif ('specifique' === $_POST['type_synchro'] && strlen($_POST['hades_id_synchro']) > 1) {
        update_option('hades_last_sync_mode', 'custom');
        update_option('hades_sync_ids', $_POST['hades_id_synchro']);
        $id_list = preg_replace('/[^0-9,]+/', '', $_POST['hades_id_synchro']);
        if ('' != $id_list) {
            xml_transform($id_list);
        }
    } elseif ('tout' === $_POST['type_synchro']) {
        update_option('hades_last_sync_mode', 'all');
        xml_transform('');
    }


}
