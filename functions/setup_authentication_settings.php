<?php
namespace Atlb\Hades;

function setup_authentication_settings()
{
    add_settings_section(
        $id = 'hades-settings-authentication',
        $title = 'Authentification',
        $callback = 'Atlb\Hades\authentication_section',
        $page = 'hades-settings'
    );
    add_settings_field(
        $id = 'hades-settings-flux-user',
        $title = 'Identifiant',
        $callback = 'Atlb\Hades\authentication_settings_user',
        $page = 'hades-settings',
        $section = 'hades-settings-authentication',
        $args = array ('label_for' => $id)
    );
    add_settings_field(
        $id = 'hades-settings-flux-password',
        $title = 'Mot de Passe',
        $callback = 'Atlb\Hades\authentication_settings_password',
        $page = 'hades-settings',
        $section = 'hades-settings-authentication',
        $args = array ('label_for' => $id)
    );
}
