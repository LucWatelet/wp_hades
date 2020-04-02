<?php

namespace Atlb\Hades;

function run_update($what)
{
    //if (date('H')<23 && date('H')>3 ) {return TRUE;}

    global $hades_syn_text_log;
    $hades_syn_text_log = true;
    $flux = \Hades_Flux::get_instance();
    $feed_loading_status = json_decode(get_option($option = 'hades_feed_loading_status'), true);
    $feed_loading_status['status'] = 'running';
    $feed_loading_status['last_started'] = date('c');
    update_option('hades_feed_loading_status', json_encode($feed_loading_status));
    $flux->loadallflux();
    $feed_loading_status['status'] = 'idle';
    $feed_loading_status['last_finished'] = date('c');
    update_option('hades_feed_loading_status', json_encode($feed_loading_status));
    xml_transform($what);
    $to = 'l.watelet@ftlb.be';
    $subject = 'Synchronisation Hadès '.$what.' de ' .get_bloginfo('name');
    $message = 'cloturé';
    $headers = 'From: hadesext@noreply.be' . "\r\n" .
            'Reply-To: hadesext@noreply.be';
    mail($to, $subject, $message, $headers);
    //die( '\n\nSynchronisation::hades::END' );
    return true;
}
