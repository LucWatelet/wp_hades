<?php
namespace Atlb\Hades;

function debug_page()
{
    wp_enqueue_script($handle = 'axios');
    wp_enqueue_script($handle = 'qs');
    $mustache = new \Mustache_Engine;
    $hades_settings = get_option('hades_settings');

    echo '<div class="wrap">';
    echo '<strong># reset_things</strong>';
    echo '<div class="postbox">';
    echo $mustache->render(file_get_contents(HADES_DIR . 'views/debug_reset_update_status.mustache'), $hades_settings);
    echo $mustache->render(file_get_contents(HADES_DIR . 'views/debug_reset_jobs_status.mustache'));
    echo $mustache->render(file_get_contents(HADES_DIR . 'views/debug_reset_plugin_data.mustache'));
    echo '</div>';

    $status = ini_get('safe_mode') ? 'disabled' : '';
    $tweak_time_limit = array(
        'status' => $status,
        'state' => $hades_settings['time-extend']);
    echo $mustache->render(file_get_contents(HADES_DIR . 'views/debug_switch_time_extend.mustache'), $tweak_time_limit);

    echo '<strong># hades_settings</strong>';
    echo '<div class="postbox"><pre>';
    print_r(get_option('hades_settings'));
    echo '</pre></div>';

    $options = [['name' => 'hades_xml_transform_status'], ['name' => 'hades_feed_loading_status']];
    foreach ($options as $option) {
        echo $mustache->render(file_get_contents(HADES_DIR . 'views/debug_display_value_top.mustache'), $option);
        var_dump(json_decode(get_option($option['name']), true));
        echo $mustache->render(file_get_contents(HADES_DIR . 'views/debug_display_value_bottom.mustache'));
    }


    echo '<div class="postbox"><pre>';
    echo '<strong># orderby id</strong>';
    wp_list_categories([
        'orderby' => 'id',
        'order'   => 'ASC',
        'taxonomy' => 'localite'
    ]);
    echo '<strong># orderby name</strong>';
    wp_list_categories([
        'orderby' => 'name',
        'order'   => 'ASC',
        'taxonomy' => 'localite'
    ]);
    echo '<strong># orderby slug</strong>';
    wp_list_categories([
        'orderby' => 'slug',
        'order'   => 'ASC',
        'taxonomy' => 'localite'
    ]);
    echo '</pre></div>';
    echo '</div>';
}
