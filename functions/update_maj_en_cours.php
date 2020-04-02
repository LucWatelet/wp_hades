<?php
namespace Atlb\Hades;

function update_maj_en_cours($new_date)
{
    $settings = get_option('hades_settings');
    $settings['maj_en_cours'] = $new_date;
    update_option($option = 'hades_settings', $settings);
}
