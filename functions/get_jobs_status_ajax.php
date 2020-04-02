<?php
namespace Atlb\Hades;

function get_jobs_status_ajax()
{
    wp_send_json(get_jobs_status());
}
