<?php
namespace Atlb\Hades;

function debug_section()
{
    $template = file_get_contents(HADES_DIR . 'views/debug_section.mustache');
    $mustache = new \Mustache_Engine;
    echo $mustache->render($template);
}
