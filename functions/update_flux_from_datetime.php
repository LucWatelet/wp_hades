<?php
namespace Atlb\Hades;

function update_flux_from_datetime($new_date)
{
    $settings = get_option('hades_settings');
    $settings['flux_from_datetime'] = $new_date;
    update_option($option = 'hades_settings', $settings);
}
