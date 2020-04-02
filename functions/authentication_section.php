<?php
namespace Atlb\Hades;

function authentication_section()
{
    $template = file_get_contents(HADES_DIR . 'views/authentication_section.mustache');
    $mustache = new \Mustache_Engine;
    echo $mustache->render($template);
}
