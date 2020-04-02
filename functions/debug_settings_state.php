<?php
namespace Atlb\Hades;

function debug_settings_state()
{
    $settings = get_option($option = 'hades_settings');
    $template = file_get_contents(HADES_DIR . 'views/debug_settings_state.mustache');
    $values = array('debug_mode_state' => $settings['debug-mode']);
    $mustache = new \Mustache_Engine;
    echo $mustache->render($template, $values);
}
