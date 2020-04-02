<?php
namespace Atlb\Hades;

function setup_debug_settings()
{
    add_settings_section(
        $id = 'hades-settings-debug',
        $title = 'Options',
        $callback = 'Atlb\Hades\debug_section',
        $page = 'hades-settings'
    );
    add_settings_field(
        $id = 'hades_settings_debug',
        $title = 'Activer les options de débogage',
        $callback = 'Atlb\Hades\debug_settings_state',
        $page = 'hades-settings',
        $section = 'hades-settings-debug',
        $args = array('label_for' => $id)
    );
    /*
    add_settings_field(
        $id = 'hades_settings_flux_from_datetime',
        $title = 'Dernier chargement des flux',
        $callback = 'Atlb\Hades\debug_settings_last_started',
        $page = 'hades-settings',
        $section = 'hades-settings-debug',
        $args = array('label_for' => $id)
    );
    add_settings_field(
        $id = 'hades-settings-time-extend',
        $title = 'Utiliser Set_Time_Limit',
        $callback = array($this, 'time_extend'),
        $page = 'hades-settings',
        $section = 'hades-settings-debug',
        $args = array('label_for' => $id)
    );
    add_settings_field(
        $id = 'hades_maj_en_cours',
        $title = 'Mise à jour en cours',
        $callback = 'Atlb\Hades\debug_settings_last_finished',
        $page = 'hades-settings',
        $section = 'hades-settings-debug',
        $args = array('label_for' => $id)
    );
    */
}
