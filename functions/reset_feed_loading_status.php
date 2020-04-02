<?php
namespace Atlb\Hades;

function reset_feed_loading_status()
{
    update_option($option = 'hades_feed_loading_status', null);
    echo 'réinitialisation du statut feed_download';
    echo "\r\n";
    ob_flush();
    flush();
}
