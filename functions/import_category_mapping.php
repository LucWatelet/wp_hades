<?php
namespace Atlb\Hades;

function import_category_mapping($tree_json, $write_mode = 'append')
{
    //$tree_json = file_get_contents("http://localhost/wp-hades/wp-content/uploads/tree.json");
    
    $tree = json_decode($tree_json, true);
    if ('overwrite' === $write_mode) {
        // delete previous mappings
    }
    return append_category_mapping($tree['root']);
}
