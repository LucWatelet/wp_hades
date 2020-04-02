<?php
namespace Atlb\Hades;

function setup_run_update()
{
    /* log in du user Hades */

    require_once(ABSPATH . 'wp-includes/pluggable.php');

    $hades_user = get_user_by("slug", $settings['hades_user']);
    if ($hades_user) {
        wp_set_current_user($hades_user->ID, $hades_user->user_login);
        wp_set_auth_cookie($hades_user->ID);
        do_action('wp_login', $hades_user->user_login);
    }

    //echo "<br/>===== Init Done ======<br/>";
    $settings = get_option($option = 'hades_settings');

    if (!$settings['maj_en_cours']) {
        $settings['maj_en_cours'] = date('d/m/Y H:i:s');
        update_option($option = 'hades_settings', $settings);
        
        run_update($_GET['what']);
        
        $settings['maj_en_cours'] = null;
        if (!$_GET['what']) {
            $settings['flux_from_datetime'] = date('Y-m-d H:i:s');
        }
        update_option($option = 'hades_settings', $settings);
    } else {
        die('Une mise à jour est déjà en cours depuis :' . $settings['maj_en_cours']);
    }

    die('\n==== cron end ====');
}
