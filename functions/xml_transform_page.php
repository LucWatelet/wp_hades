<?php
namespace Atlb\Hades;

function xml_transform_page()
{
    $template = file_get_contents(HADES_DIR . 'views/xml_transform_form.mustache');
    $values = array(
        'custom_ids' => '',
        'custom_radio_state' => '',
        'express_radio_state' => '',
        'all_radio_state' => '',
    );
    $hades_last_sync_mode = get_option('hades_last_sync_mode');
    if ($hades_last_sync_mode) {
        $values[$hades_last_sync_mode . '_radio_state'] = 'checked';
    } else {
        $values['all_radio_state'] = 'checked';
    }
    $custom_ids = get_option('hades_sync_ids');
    if ($custom_ids) {
        $values['custom_ids'] = $custom_ids;
    }
    $values['custom_ids_state'] = ($values['custom_radio_state'] === 'checked') ? '' : 'disabled';
    $mustache = new \Mustache_Engine;
    echo $mustache->render($template, $values);
}
