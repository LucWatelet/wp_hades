<?php
namespace Atlb\Hades;

function get_jobs_status()
{
    $settings = get_option($option = 'hades_settings');
    $update_status['update']['status'] = ('' != $settings['maj_en_cours']) ? 'running' : 'idle';
    $update_status['update']['last_finished'] = $settings['flux_from_datetime'];
    if ('running' === $update_status['update']['status']) {
        $update_status['update']['last_started'] = $settings['maj_en_cours'];
    }
    $update_status['xml_transform'] = json_decode(get_option($option = 'hades_xml_transform_status'));
    $update_status['feed_loading'] = json_decode(get_option($option = 'hades_feed_loading_status'));
    return $update_status;
}
