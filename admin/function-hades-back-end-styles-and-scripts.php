<?php

function hades_back_end_styles_and_scripts()
    {

    $screen = get_current_screen();
    $settings = get_option( $option = 'hades_settings' );
    wp_enqueue_style( 'load-fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    wp_register_style(
            $handle = 'hades_admin_css', $src = plugins_url(
            $path = 'css/hades_admin.css', $plugin = dirname( __FILE__ ) ), $deps = array (), $ver = null );

    wp_register_script(
            $handle = 'hades_admin_js', $src = plugins_url(
            $path = 'js/hades.js', $plugin = dirname( __FILE__ ) ), $deps = array (), $ver = null );

    wp_enqueue_style( $handle = 'hades_admin_css' );
    
    wp_enqueue_script( $handle = 'hades_admin_js' );


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
