<?php
namespace Atlb\Hades;

function register_rest_routes()
{
    register_rest_route(
        'hades/v1',
        'update_offers',
        array(
            'methods' => 'GET',
            'callback' => 'Atlb\Hades\setup_run_update',
        )
    );
    register_rest_route(
        'hades/v1',
        'jobs_status',
        array(
            'methods' => 'GET',
            'callback'=> 'Atlb\Hades\get_jobs_status'
        )
    );
    register_rest_route(
        'hades/v1',
        'reset_plugin_data',
        array(
            'methods' => 'GET',
            'callback'=> 'Atlb\Hades\reset_plugin_data'
        )
    );
    register_rest_route(
        'hades/v1',
        'xml_transform',
        array(
            'methods' => 'GET',
            'callback'=> 'Atlb\Hades\xml_transform'
        )
    );
    register_rest_route(
        'hades/v1',
        'export_categories_tree',
        array(
            'methods' => 'GET',
            'callback'=> 'Atlb\Hades\export_categories_tree'
        )
    );
    register_rest_route(
        'hades/v1',
        'import_categories_tree',
        array(
            'methods' => 'GET',
            'callback'=> 'Atlb\Hades\import_category_mapping'
        )
    );
}
