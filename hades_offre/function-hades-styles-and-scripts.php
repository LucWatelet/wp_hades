<?php

function hades_styles_and_scripts()
    {


    $settings = get_option( $option = 'hades_settings' );

 
  



    /* 	if ( get_post_type() == 'hadesoff' or $screen->base = 'hades_page_hades-debug' ) {
      wp_enqueue_style( $handle = 'hades-style-leaflet' );
      wp_enqueue_script( $handle = 'hades-js-leaflet' );
      switch ( $settings['map_provider'] ) {
      case 'MapQuest':
      wp_enqueue_script( $handle = 'hades-js-mapquest-leaflet-key' );
      break;

      default:
      break;
      }
      } */
    /* 	if ( $screen->base = 'hades_pages_hades-debug' ) {
      wp_enqueue_script( $handle = 'hades-js-markers' );
      if ( $settings['is_cluster_enabled'] ) {
      wp_enqueue_script( $handle = 'hades-js-leafletmarkercluster' );
      wp_enqueue_style( $handle = 'hades-style-leafletmarkercluster' );
      wp_enqueue_style( $handle = 'hades-style-leafletmarkerclusterdefault' );
      //wp_enqueue_style( $handle = 'hades-style-leafletmarkerclusterscreen' );
      }
      } */
    }
