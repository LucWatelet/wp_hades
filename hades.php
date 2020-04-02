<?php

/*
  Plugin Name: Hades
  Plugin URI: http://w3.ftlb.be/
  Description: Plugin de récupération des offres touristiques d'Hadès.
  Version: 0.15
  Author: Luc Watelet, Jean-Christophe vanhalle
  Author URI: http://w3.ftlb.be/
  License: GPL
 */
//namespace Atlb\Hades;

ini_set( "display_errors", 1 );

global $wpdb;

define( 'HADES_DIR', plugin_dir_path( __FILE__ ) );
define('HADES_TMP', get_temp_dir() . preg_replace('/[^\w]/', '_', preg_replace('/https?:\/\//', '', get_site_url())));

define('HADESDBPFX', $wpdb->prefix . 'hades_');
define('HADES_FEED_USERNAME', 'wp-cron');
define('HADES_FEED_PASSWORD', 'wp-cron');
define('HADES_FEED_URL', 'http://w3.ftlb.be/webservice/h2o.php');

define( 'HADES_TAX', 'hades_offres' );
define( 'HADES_CPT', 'hades_offre' );

define( 'HADES_TAXO_LOC', 'localite' );
define( 'HADES_TAXO_COM', 'commune' );
define( 'HADES_TAXO_SEL', 'selection' );
define( 'HADES_PG_AGENDA', 'hades_agenda.php' );



//Le vocabulaire de taxonomie permettant de re-classer les offres
define( 'HADESVOC', 'hades_voc' );

date_default_timezone_set( "Europe/Brussels" );

require_once HADES_DIR . '/helper.php';

require_once HADES_DIR . '/admin/function-hades-back-end-styles-and-scripts.php';
require_once HADES_DIR . '/admin/hades_add_taxonomy_filters.php';

require_once HADES_DIR . '/admin/page_assoc_cat.php';
require_once HADES_DIR . '/flux/class_hades_flux.php';
require_once HADES_DIR . '/flux/class_hades_converter.php';

require_once HADES_DIR . '/admin/page_flux.php';

require_once HADES_DIR . '/admin/class_hades_settings.php';

require_once HADES_DIR . '/admin/class_hades_categories.php';
require_once HADES_DIR . '/admin/page_assoc_cat.php';

require_once HADES_DIR . '/admin/class_hades_selections.php';
require_once HADES_DIR . '/admin/class_table_selections.php';
require_once HADES_DIR . '/admin/page_selections.php';

require_once HADES_DIR . '/hades_offre/class_hades_offre.php';
require_once HADES_DIR . '/hades_offre/function-hades-styles-and-scripts.php';

require_once HADES_DIR . '/hades_offre/ajax_for_map.php';
require_once HADES_DIR . '/hades_offre/ajax_for_search_loc.php';
require_once HADES_DIR . '/hades_offre/ajax_for_excell.php';
require_once HADES_DIR . '/hades_offre/ajax_for_maplist.php';

require_once HADES_DIR . '/widget/class_hades_widget_event_calendar.php';
require_once HADES_DIR . '/widget/class_hades_widget_search.php';
require_once HADES_DIR . '/widget/class_hades_widget_recent_offres.php';

require_once HADES_DIR . '/shortcodes/function_hades_map_search.php';
require_once HADES_DIR . '/shortcodes/function_hades_maplist.php';

/* * *****************************************************************************
 *  INSTALLATION
 * **************************************************************************** */
require_once( HADES_DIR . '/admin/install.php' );
$debug = register_activation_hook( __FILE__, 'hades_install' );


/* * *****************************************************************************
 *  Chargement des settings
 * **************************************************************************** */
add_action( 'plugins_loaded', function()
    {
    Hades_Settings::get_instance();
    } );


/* * *****************************************************************************
 *  FLUX
 * **************************************************************************** */
add_action( 'plugins_loaded', function()
    {
    Hades_Flux::get_instance();
    } );


/* * *****************************************************************************
 *  CUSTOM POST TYPE HADES
 * **************************************************************************** */

add_action( 'plugins_loaded', function()
    {
    Hades_Offre::get_instance();
    } );

/* * *****************************************************************************
 *  CHARGEMENT DES WIDGET HADES
 * **************************************************************************** */

add_action(
        $tag = 'widgets_init', $function_to_add = function()
    {
    register_widget( $widget_class = 'Hades_Widget_Event_Calendar' );
    register_widget( $widget_class = 'Hades_Widget_Recent_Offres' );
    //register_widget( $widget_class = 'Hades_Widget_Annexes_Offers' );
    } );

add_action(
        $tag = 'widgets_init', $function_to_add = function()
    {
    register_widget( $widget_class = 'Hades_Widget_Search' );
    //register_widget( $widget_class = 'Hades_Widget_Annexes_Offers' );
    } );

/* * *****************************************************************************
 *  MAP AJAX
 * **************************************************************************** */

add_action( 'wp_ajax_get_map_hades', 'ajax_for_map' );
add_action( 'wp_ajax_nopriv_get_map_hades', 'ajax_for_map' );

/* * *****************************************************************************
 *  SEARCH LOC AJAX
 * **************************************************************************** */

add_action( 'wp_ajax_search_loc_hades', 'ajax_for_search_loc' );
add_action( 'wp_ajax_nopriv_search_loc_hades', 'ajax_for_search_loc' );


/* * *****************************************************************************
 *  EXCELL AJAX
 * **************************************************************************** */

add_action( 'wp_ajax_get_excell_hades', 'ajax_for_excell' );
add_action( 'wp_ajax_nopriv_get_excell_hades', 'ajax_for_excell' );

/* * *****************************************************************************
 *  MAPLIST AJAX
 * **************************************************************************** */

add_action( 'wp_ajax_maplist_hades', 'ajax_for_maplist' );
add_action( 'wp_ajax_nopriv_maplist_hades', 'ajax_for_maplist' );



/* * *****************************************************************************
 *  SCRIPTS ET STYLES
 * **************************************************************************** */



wp_register_style( 'leaflet_css', plugins_url( 'js/leaflet/leaflet.css', __FILE__ ) );
wp_enqueue_style( 'leaflet_css' );
wp_register_style( 'hades_content_css', plugins_url( 'css/hades_content.css', __FILE__ ) );
wp_enqueue_style( 'hades_content_css' );
wp_register_style( 'leaflet_markercluster_css', plugins_url( 'js/leaflet/MarkerCluster.css', __FILE__ ) );
wp_enqueue_style( 'leaflet_markercluster_css' );
wp_register_style( 'leaflet_markercluster_defaulet_css', plugins_url( 'js/leaflet/MarkerCluster.Default.css', __FILE__ ) );
wp_enqueue_style( 'leaflet_markercluster_defaulet_css' );
wp_register_script( 'leaflet_js', plugins_url( 'js/leaflet/leaflet.js', __FILE__ ) );
wp_enqueue_script( 'leaflet_js' );
wp_register_script( 'leaflet_markercluster_js', plugins_url( 'js/leaflet/leaflet.markercluster.js', __FILE__ ) );
wp_enqueue_script( 'leaflet_markercluster_js' );

wp_enqueue_script( 'hades_map_search', plugins_url( 'js/hades_map_search.js', __FILE__ ), array ( 'jquery' ) );
wp_localize_script( 'hades_map_search', 'Hades_map_ajax_url', admin_url( 'admin-ajax.php' ) );

wp_enqueue_script( 'hades_maplist', plugins_url( 'js/hades_maplist.js', __FILE__ ), array ( 'jquery' ) );
wp_localize_script( 'hades_maplist', 'Hades_maplist_ajax_url', admin_url( 'admin-ajax.php' ) );
wp_register_style( 'hades_maplist_css', plugins_url( 'css/hades_maplist.css', __FILE__ ) );
wp_enqueue_style( 'hades_maplist_css' );


wp_enqueue_script( 'hades_excell_search', plugins_url( 'js/hades_excell_search.js', __FILE__ ), array ( 'jquery' ) );
wp_localize_script( 'hades_excell_search', 'Hades_excell_ajax_url', admin_url( 'admin-ajax.php' ) );

wp_enqueue_script( 'hades_loc_search', plugins_url( 'js/hades_loc_search.js', __FILE__ ), array ( 'jquery' ) );
wp_localize_script( 'hades_loc_search', 'Hades_loc_ajax_url', admin_url( 'admin-ajax.php' ) );



add_action( 'admin_enqueue_scripts', 'hades_back_end_styles_and_scripts', $priority = 50 );

function custom_rewrite_basic()
    {
    add_rewrite_rule( '^hades_agenda/([0-9]+)/?', 'index.php?hades_agenda=$matches[1]', 'top' );
    add_rewrite_rule( '^hades_offre_id/([0-9]+)/?', 'index.php?hades_offre_id=$matches[1]', 'top' );
    }

add_action( 'init', 'custom_rewrite_basic' );

function custom_rewrite_tag()
    {
    add_rewrite_tag( '%hades_agenda%', '([^&]+)' );
    add_rewrite_tag( '%hades_offre_id%', '([^&]+)' );
    }

add_action( 'init', 'custom_rewrite_tag', 10, 0 );

$files = new \FilesystemIterator(__DIR__.'/functions', \FilesystemIterator::SKIP_DOTS);
foreach ($files as $file) {
    ! $files->isDir() and include $files->getRealPath();
}

add_action('admin_menu', 'Atlb\Hades\setup_menu');
add_action('admin_menu', 'Atlb\Hades\hide_unused_submenu_pages', 999);
//add_action('admin_init', 'Atlb\Hades\setup_authentication_settings');
add_action('admin_init', 'Atlb\Hades\setup_debug_settings');
add_action('wp_enqueue_scripts', 'adds_fontawesome_icons_support');
add_action('wp_ajax_xml_transform_ajax', 'Atlb\Hades\xml_transform_ajax');
add_action('wp_ajax_reset_plugin_data_ajax', 'Atlb\Hades\reset_plugin_data_ajax');
add_action('wp_ajax_reset_xml_transform_status_ajax', 'Atlb\Hades\reset_xml_transform_status_ajax');
add_action('wp_ajax_reset_feed_loading_status_ajax', 'Atlb\Hades\reset_feed_loading_status_ajax');
add_action('wp_ajax_update_flux_from_datetime_ajax', 'Atlb\Hades\update_flux_from_datetime_ajax');
add_action('wp_ajax_update_maj_en_cours_ajax', 'Atlb\Hades\update_maj_en_cours_ajax');
add_action('wp_ajax_get_jobs_status_ajax', 'Atlb\Hades\get_jobs_status_ajax');
add_action('wp_ajax_switch_time_extend_status_ajax', 'Atlb\Hades\switch_time_extend_status_ajax');
add_action('rest_api_init', 'Atlb\Hades\register_rest_routes');
add_action('wp_ajax_import_category_mapping_ajax', 'Atlb\Hades\import_category_mapping_ajax');
add_action('customize_register', 'Atlb\Hades\customize_register_attributes_groups');
add_action('customize_register', 'Atlb\Hades\customize_register_attributes_groups_single');
add_filter('acf/settings/remove_wp_meta_box', '__return_false');


$settings = get_option($option = 'hades_settings');
if ($settings['debug-mode']) {
    add_action('admin_menu', 'Atlb\Hades\setup_import_category_mapping');
    add_action('admin_menu', 'Atlb\Hades\setup_feed_loading');
    add_action('admin_menu', 'Atlb\Hades\setup_transform_xml');
    add_action('admin_menu', 'Atlb\Hades\setup_debug');
    wp_register_script(
        $handle = 'axios',
        $src = plugin_dir_url(__FILE__) . 'js/axios.min.js',
        $deps = array (),
        $ver = null
    );
    wp_register_script(
        $handle = 'qs',
        $src = plugin_dir_url(__FILE__) . 'js/qs.js',
        $deps = array (),
        $ver = null
    );
}

require __DIR__ . '/vendor/Mustache/Autoloader.php';
Mustache_Autoloader::register();
