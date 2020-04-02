<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function ajax_for_maplist()
    {
    global $post;
 
// Query Arguments
    $args = array (
                'post_type' => HADES_CPT,
                'nopaging' => true,
                'order' => 'DESC',
                'category_name' => implode( ",", $_POST['category_name'] ),
    );
    echo hades_maplist_get_json_offres($args);
    die();
    }
