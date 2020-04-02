<?php
namespace Atlb\Hades;

function import_category_mapping_page()
{
    $template = file_get_contents(HADES_DIR . 'views/category_mapping_tools.mustache');
    $values = array(
        'overwrite_radio_state' => '',
        'append_radio_state' => '',
    );
    $hades_last_category_mapping_mode = get_option('hades_last_category_mapping_mode');
    if ($hades_last_category_mapping_mode) {
        $values[$hades_last_category_mapping_mode . '_radio_state'] = 'checked';
    } else {
        $values['overwrite_radio_state'] = 'checked';
    }
    $mustache = new \Mustache_Engine;
    echo $mustache->render($template, $values);
}
