<?php
namespace Atlb\Hades;

function reset_xml_transform_status()
{
    update_option($option = 'hades_xml_transform_status', null);
    echo 'réinitialisation du statut xml_transform';
    echo "\r\n";
    ob_flush();
    flush();
}
