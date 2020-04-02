<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function ajax_for_search_loc()
    {
// Query Arguments
    $terms = get_terms(
            array (
                        'taxonomy' => HADES_TAXO_LOC,
                        'hide_empty' => false,
                        'name__like' => $_POST['searchloc']
            )
    );

    if( !empty( $terms ) && !is_wp_error( $terms ) )
        {
        $o_terms=array();
        foreach( $terms as $key => $term )
            {
            if( stripos( $term->name, $_POST['searchloc'] ) === 0 || stripos( $term->slug, $_POST['searchloc'] ) === 0 )
                {
                $o_terms[]=$term->slug;
                }
             else
                         {
                unset($terms[$key]);
                }        
            }
        array_multisort($o_terms,$terms);
        echo json_encode( $terms );
        }
    else
        {
// no posts found
        }
    wp_reset_postdata();
    die();
    }
