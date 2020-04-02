<?php
namespace Atlb\Hades;

function xml_transform_ajax()
{
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
