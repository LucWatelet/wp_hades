<?php

function hades_communes_add_taxonomy_filters()
    {
    global $typenow;
    global $wp_query;
    $post_type = $typenow;
    if( $typenow == HADES_CPT )
        {
        $taxonomy = HADES_TAXO_COM;
        $commune_taxonomy = get_taxonomy( $taxonomy );
        wp_dropdown_categories(
                array (
                            'show_option_all' => __( "Toutes communes" ),
                            'show_option_none' => '',
                            'option_none_value' => '-1',
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'show_count' => 1,
                            'hide_empty' => 1,
                            'selected' => $wp_query->query['commune'],
                            'hierarchical' => 0,
                            'name' => 'commune',
                            'class' => 'postform',
                            'depth' => 0,
                            'taxonomy' => $taxonomy,
                            'hide_if_empty' => false,
                            'value_field' => 'name',//'value_field' => 'term_id',
                )
        );
        }
    }

add_action( 'restrict_manage_posts', 'hades_communes_add_taxonomy_filters',1 );


function hades_localites_add_taxonomy_filters()
    {
    global $typenow;
    global $wp_query;
    $post_type = $typenow;
    if( $typenow == HADES_CPT )
        {
        $taxonomy = HADES_TAXO_LOC;
        $localite_taxonomy = get_taxonomy( $taxonomy );
        wp_dropdown_categories(
                array (
                            'show_option_all' => __( "Toutes localités" ),
                            'show_option_none' => '',
                            'option_none_value' => '-1',
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'show_count' => 1,
                            'hide_empty' => 1,
                            'selected' => $wp_query->query['localite'],
                            'hierarchical' => 0,
                            'name' => 'localite',
                            'class' => 'postform',
                            'depth' => 0,
                            'taxonomy' => $taxonomy,
                            'hide_if_empty' => false,
                            'value_field' => 'name',//'value_field' => 'term_id',
                )
        );
        }
    }

add_action( 'restrict_manage_posts', 'hades_localites_add_taxonomy_filters',1 );


function hades_selections_add_taxonomy_filters()
    {
    global $typenow;
    global $wp_query;
    $post_type = $typenow;
    if( $typenow == HADES_CPT )
        {
        $taxonomy = HADES_TAXO_SEL;
        $selection_taxonomy = get_taxonomy( $taxonomy );
        wp_dropdown_categories(
                array (
                            'show_option_all' => __( "Tous Thèmes" ),
                            'show_option_none' => '',
                            'option_none_value' => '-1',
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'show_count' => 1,
                            'hide_empty' => 1,
                            'selected' => $wp_query->query['selection'],
                            'hierarchical' => 0,
                            'name' => 'selection',
                            'class' => 'postform',
                            'depth' => 0,
                            'taxonomy' => $taxonomy,
                            'hide_if_empty' => false,
                            'value_field' => 'name',//'value_field' => 'term_id',
                )
        );
        }
    }

add_action( 'restrict_manage_posts', 'hades_selections_add_taxonomy_filters',1 );
