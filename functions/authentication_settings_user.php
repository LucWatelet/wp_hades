<?php
namespace Atlb\Hades;

function authentication_settings_user()
{
    $settings = get_option($option = 'hades_settings');
    $template = file_get_contents(HADES_DIR . 'views/authentication_settings_user.mustache');
    $values = array('user' => $settings['flux-user']);
    $mustache = new \Mustache_Engine;
    echo $mustache->render($template, $values);
}
