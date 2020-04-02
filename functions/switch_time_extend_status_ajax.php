<?php
namespace Atlb\Hades;

function switch_time_extend_status_ajax()
{
    $settings = get_option('hades_settings');
    if (array_key_exists('time-extend', $settings) && $settings) {
        $settings['time-extend'] = ($settings['time-extend'] == 'checked') ? 'unchecked' : 'checked';
    } else {
        $settings['time-extend'] = 'checked';
    }
    update_option('hades_settings', $settings);
    $settings = get_option('hades_settings');
    wp_send_json($settings['time-extend']);
}
