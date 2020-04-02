<?php
namespace Atlb\Hades;

function settings_page()
{
    $template_open = file_get_contents(HADES_DIR . 'views/settings_open.mustache');
    $template_close = file_get_contents(HADES_DIR . 'views/settings_close.mustache');
    $mustache = new \Mustache_Engine;
    echo $mustache->render($template_open);
    settings_fields('hades_settings');
    do_settings_sections('hades-settings');
    submit_button();
    echo $mustache->render($template_close);
}
