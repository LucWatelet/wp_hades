<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function ajax_for_map()
    {
    global $post;
 
// Query Arguments
    $args = array (
                'post_type' => HADES_CPT,
                'nopaging' => true,
                'order' => 'DESC',
                'category_name' => implode( ",", $_POST['category_name'] ),
    );

    $query_hades = new WP_Query( $args );

    if( $query_hades->have_posts() )
        {
        $postes=array();
        while( $query_hades->have_posts() )
            {
            $query_hades->the_post();
            $p["id"]=$post->ID;
            $p["title"]=$post->post_title;
            $p["permalink"]=get_post_permalink( $post->ID );
            $p["thumbnail"]=get_post_thumbnail_id($post->ID);
            $p["contact"]=get_post_meta( $post->ID, 'contact', true );
            $p["x"]=get_post_meta( $post->ID, 'gps_x', true );
            $p["y"]=get_post_meta( $post->ID, 'gps_y', true );
            $p["cat"]=get_the_category()[0]->slug;
            array_push($postes,$p ) ;
            }
         echo json_encode($postes);   
        }
    else
        {
        // no posts found
        }
    wp_reset_postdata();
    die();
    }
