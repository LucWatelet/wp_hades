<?php
namespace Atlb\Hades;

function authentication_settings_password()
{
    $settings = get_option($option = 'hades_settings');
    $template = file_get_contents(HADES_DIR . 'views/authentication_settings_password.mustache');
    $values = array('password' => $settings['flux-password']);
    $mustache = new \Mustache_Engine;
    echo $mustache->render($template, $values);
}
