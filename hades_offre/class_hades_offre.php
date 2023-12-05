<?php
class Hades_Offre
    {
    static $instance;

    public function __construct()
        {
        if( !post_type_exists( HADES_CPT ) )
            {
            add_action( 'init', array ( &$this, 'register_hades_offre' ) );
            }
        add_action( 'pre_get_posts', array ( &$this, 'add_hades_offre_to_query' ) );
        add_filter( 'manage_posts_columns', array ( &$this, 'hades_posts_columns' ), 2 );
        add_action( 'manage_posts_custom_column', array ( &$this, 'hades_posts_columns_content' ), 10, 2 );
        add_action( 'template_include', array ( &$this, 'hades_special_template' ) );
        $this->add_taxonomy( HADES_TAXO_LOC );
        $this->add_taxonomy( HADES_TAXO_COM );
        $this->add_taxonomy( HADES_TAXO_SEL );

        //$this->save();
        }

    public function hades_posts_columns( $defaults )
        {
        $defaults['date_titre'] = "Date Event";
        $defaults['hades_id'] = "Hadès ID";
        $defaults['dateheure_id'] = "Hadès IDD";
        $defaults['localite_commune'] = "Localités";
        return $defaults;
        }

    public function hades_posts_columns_content( $column_name, $post_ID )
        {
        switch( $column_name )
            {
            case 'hades_id':
                echo get_post_meta( $post_ID, 'hades_id', true );
                break;

            case 'date_titre':
                echo get_post_meta( $post_ID, 'date_titre', true );
                break;

            case 'localite_commune':
                echo get_post_meta( $post_ID, 'localite_commune', true );
                break;

            case 'dateheure_id':
                echo get_post_meta( $post_ID, 'dateheure_id', true );
                break;
            }

        return TRUE;
        }

    /*     * *****************************************************************************
     *  REGISTER HADES_OFFRE
     * **************************************************************************** */

    public function register_hades_offre()
        {
        $labels = array (
                    'name' => _x( 'Offres Hadès', 'Post Type General Name', 'text_domain' ),
                    'singular_name' => _x( 'Offre Hadès', 'Post Type Singular Name', 'text_domain' ),
                    'menu_name' => __( 'Offres Hadès', 'text_domain' ),
                    'name_admin_bar' => __( 'Offre Hadès', 'text_domain' ),
                    'archives' => __( 'Offre Hadès archivées', 'text_domain' ),
                    'parent_item_colon' => __( 'Parent Offre:', 'text_domain' ),
                    'all_items' => __( 'Toutes les offres', 'text_domain' ),
                    'add_new_item' => __( 'Ajouter une nouvelle Offre', 'text_domain' ),
                    'add_new' => __( 'Ajouter une Offre', 'text_domain' ),
                    'new_item' => __( 'Nouvelle Offre', 'text_domain' ),
                    'edit_item' => __( 'Editer une Offre', 'text_domain' ),
                    'update_item' => __( 'Modifier une Offre', 'text_domain' ),
                    'view_item' => __( 'Voir une Offre', 'text_domain' ),
                    'search_items' => __( 'Chercher des Offres', 'text_domain' ),
                    'not_found' => __( 'Pas d\'offre trouvée', 'text_domain' ),
                    'not_found_in_trash' => __( 'Pas d\'offre trouvée en Trash', 'text_domain' ),
                    /*
                      'featured_image' => __('Featured Image', 'text_domain'),
                      'set_featured_image' => __('Set featured image', 'text_domain'),
                      'remove_featured_image' => __('Remove featured image', 'text_domain'),
                      'use_featured_image' => __('Use as featured image', 'text_domain'),
                     */
                    'insert_into_item' => __( 'Insert into Offre', 'text_domain' ),
                    'uploaded_to_this_item' => __( 'Uploaded to this Offre', 'text_domain' ),
                    'items_list' => __( 'Offres list', 'text_domain' ),
                    'items_list_navigation' => __( 'Offres list navigation', 'text_domain' ),
                    'filter_items_list' => __( 'Filter Offres list', 'text_domain' ),
        );

        $args = array (
                    'label' => __( 'Offre Hadès', 'text_domain' ),
                    'description' => __( 'Offres Hadès', 'text_domain' ),
                    'labels' => $labels,
                    'supports' => array ( 'title', 'excerpt', 'editor', 'thumbnail', 'custom-fields' ),
                    'taxonomies' => array ( 'category' ), // hades_offres
                    'hierarchical' => false,
                    'public' => true,
                    'show_ui' => true,
                    'show_in_menu' => true,
                    'menu_position' => 5,
                    'show_in_admin_bar' => true,
                    'show_in_nav_menus' => true,
                    'can_export' => true,
                    'has_archive' => true,
                    'exclude_from_search' => false,
                    'publicly_queryable' => true,
                    'capability_type' => 'post',
                    'menu_icon' => 'dashicons-index-card',
        );

// Register le post type

        register_post_type( HADES_CPT, $args );

        $this->add_meta_box(
                'Hades Meta', array (
                    array ( 'label' => 'Identifiant Hadès', 'id' => 'hades_id', 'type' => 'text' ),
                    array ( 'label' => 'ID_date', 'id' => 'dateheure_id', 'type' => 'text' ),
                    array ( 'label' => 'Date déb.', 'id' => 'date_deb', 'type' => 'text' ),
                    array ( 'label' => 'Date Fin', 'id' => 'date_fin', 'type' => 'text' ),
                    array ( 'label' => 'Longueur', 'id' => 'date_long', 'type' => 'text' ),
                    array ( 'label' => 'Tjr à l\'affiche', 'id' => 'date_tjaff', 'type' => 'text' ),
                    array ( 'label' => 'Date FR', 'id' => 'date_titre', 'type' => 'text' ),
                    array ( 'label' => 'Toutes les dates', 'id' => 'toutes_dates', 'type' => 'text' ),
                    array ( 'label' => 'Localités', 'id' => 'localite_commune', 'type' => 'text' ),
                    array ( 'label' => 'GPS long', 'id' => 'gps_x', 'type' => 'text' ),
                    array ( 'label' => 'GPS lat', 'id' => 'gps_y', 'type' => 'text' ),
                /* array ( 'label' => 'Activités', 'id' => 'sub_act_ind', 'type' => 'custom_html' ),
                  array ( 'label' => 'Annexes', 'id' => 'sub_annexe', 'type' => 'custom_html' ),
                  array ( 'label' => 'Agenda', 'id' => 'sub_agenda', 'type' => 'custom_html' ), */
                )
        );
        }

    /*     * *****************************************************************************
     *  ADD HADES_OFFRE TO QUERY
     * **************************************************************************** */

    public function add_hades_offre_to_query( $query )
        {

        global $wpdb;

        if(isset($_GET['localite_ray']))
        $query->query_vars['localite_ray'] = $_GET['localite_ray'];
        if(isset($_GET['loc_rayon']))
        $query->query_vars['loc_rayon'] = $_GET['loc_rayon'];
        if(isset($_GET['datefork']))
        $query->query_vars['datefork'] = $_GET['datefork'];   

        
        //var_dump($query);
        $settings = get_option( 'hades_settings' );
        //$events_cat = get_category( $settings['hades_event_category_parent'] )->slug;
        //$hades_cat = get_category( $settings['hades_category_parent'] )->slug;

        $cats_in_hades = $this->hades_cats_racine( $query );

        // on récupère ou on crée un tableau meta_query
        $meta_queries = $query->get( 'meta_query', array () );
        $order_queries = $query->get( 'orderby', array () );
        
        $meta_queries['relation'] = 'AND';



        if( is_home() && $query->is_main_query() || is_date() || is_feed() || is_category() || is_tag() )
            {
            if( $settings['force_excerpt_in_results'] === 'checked' )
                {
                add_filter( // careful, it breaks shortcodes
                        $tag = 'the_content', $function_to_callback = 'hades_excerpt' );
                }
            $query->set( 'post_type', array ( 'post', 'page', HADES_CPT, 'nav_menu_item' ) );
            }

        if( $query->get( 'recent_widget' ) )
            {
            return $query;
            }


        //["query_vars"]=> array(53) { ["cat"]=> string(2) "82"
        //affichage d'événement suivant une indication catégorie ou de sélection
     /*   if( $query->is_category() && $cats_in_hades['event'] || $query->is_tax( 'selection' ) )
            {

            $meta_queries['date_fin_clause'] = array (
                        'key' => 'date_fin',
            );

            $meta_queries['date_deb_clause'] = array (
                        'key' => 'date_deb',
            );

            $meta_queries['date_long_clause'] = array (
                        'key' => 'date_long',
            );

            $meta_queries['localite_commune_clause'] = array (
                        'key' => 'localite_commune',
            );

            $meta_queries['date_tjaff_clause'] = array (
                        'key' => 'date_tjaff',
            );



            $order_queries = array (
                        'date_tjaff_clause' => 'ASC'
                        , 'date_fin_clause' => 'ASC'
                        , 'date_deb_clause' => 'ASC'
                        , 'date_long_clause' => 'ASC'
                        , 'localite_commune_clause' => 'ASC'
            );
            $query->set( 'meta_query', $meta_queries );
            $query->set( 'orderby', $order_queries );
            //echo "<small>Tri des post : Cats Events</small>";
            }



        //affichage d'événements suivant une indication de date            
        elseif( $query->get( 'hades_agenda' ) || isset( $_GET['hades_agenda'] ) )
            {
            $dates = get_details_from_hades_agenda( $query->get( 'hades_agenda' ) );

            $meta_queries['date_fin_clause'] = array (
                        'key' => 'date_fin',
                        'value' => $dates->date_deb,
                        'compare' => '>=',
                        'type' => 'DATETIME',
            );

            $meta_queries['date_deb_clause'] = array (
                        'key' => 'date_deb',
                        'value' => $dates->date_fin,
                        'compare' => '<=',
                        'type' => 'DATETIME',
            );

            $meta_queries['date_long_clause'] = array (
                        'key' => 'date_long',
                    //'compare' => 'EXISTS',
            );

            $meta_queries['localite_commune_clause'] = array (
                        'key' => 'localite_commune',
                    //'compare' => 'EXISTS',
            );

            $meta_queries['date_tjaff_clause'] = array (
                        'key' => 'date_tjaff',
            );

            $order_queries = array (
                        'date_tjaff_clause' => 'ASC'
                        , 'date_fin_clause' => 'ASC'
                        , 'date_deb_clause' => 'ASC'
                        , 'date_long_clause' => 'ASC'
                        , 'localite_commune_clause' => 'ASC'
            );
            $query->set( 'meta_query', $meta_queries );
            $query->set( 'orderby', $order_queries );
            //echo "<small>Tri des post : Dates Events</small>";
            }


        //affichage d'événement suivant une indication catégorie ou de sélection             
        elseif( $query->is_category() && $cats_in_hades['hades'] )
            {

            $meta_queries['localite_commune_clause'] = array (
                        'key' => 'localite_commune',
            );

            $order_queries = array (
                        'localite_commune_clause' => 'ASC'
            );
            $query->set( 'meta_query', $meta_queries );
            $query->set( 'orderby', $order_queries );
            //echo "<small>Tri des post : Cats Hades</small>";
            }
*/
        //affichage d'une offre toute seule par sont identifiant Hades
        if( $query->get( 'hades_offre_id' ) )
            {

            $post_id = $wpdb->get_var( "SELECT post_id FROM wp_postmeta WHERE meta_key='hades_id' AND meta_value=" . $query->query_vars['hades_offre_id'] );
            $query->set( 'p', $post_id );
            $query->set( 'post_type', HADES_CPT );
            $query->set( 'name', NULL );
            }

        //$query->set( 'posts_per_page', 300 );

      /*  if( isset( $_GET['loc_rayon'] ) && $_GET['loc_rayon']>0 && isset( $_GET['localite_ray'] ) && $_GET['localite_ray']!="" )
            {
            add_filter( 'posts_where', array ( &$this, 'distance_from_where' ) );
            $query->set( 'posts_per_page', -1 );
            }*/
        
        return $query;
        }

    function distance_from_where( $where )
        {
        global $wpdb;
        $loc=get_term_by('slug', $_GET['localite_ray'], HADES_TAXO_LOC );
        $term_vals = get_term_meta($loc->term_id);

        $x=$term_vals['x'][0];
        $y=$term_vals['y'][0];

        $coord_x =  ( $x - 5) * 111.319 ;
        $coord_y =  ( $y - 49.50) * 172.190 ;

        
        $dist = intval($_GET['loc_rayon']);
        
       /* $where .= $wpdb->prepare( ' AND '.$wpdb->prefix.'posts.ID ' .
                'IN ( SELECT s.ID FROM ' . HADESDBPFX . 'post_spatial s 
             WHERE  ST_Distance(s.coord, POINT("' . $coord_x.",".$coord_y . '"))<=' . $dist . ')  '
                , "" );
        echo  '<p> AND '.$wpdb->prefix.'posts.ID ' .'IN ( SELECT s.ID FROM ' . HADESDBPFX . 'post_spatial s  WHERE  ST_Distance(s.coord, POINT("' . $coord_x.",".$coord_y . '"))<=' . $dist . ') </p> ';*/
        
       $polygon="POLYGON((". 
                ($coord_x - $dist) ." ".($coord_y - $dist).",". 
                ($coord_x + $dist) ." ".($coord_y - $dist).",". 
                ($coord_x + $dist) ." ".($coord_y + $dist).",". 
                ($coord_x - $dist) ." ".($coord_y + $dist).",". 
                ($coord_x - $dist) ." ".($coord_y - $dist)."))";

        
        $where .= $wpdb->prepare( ' AND '.$wpdb->prefix.'posts.ID ' .
                'IN ( SELECT s.ID FROM ' . HADESDBPFX . 'post_spatial s 
             WHERE  MBRContains( ST_GeomFromText("'.$polygon.'") , s.coord ) )'  , "" );
        
        
        return $where;
        }

    /*     * *****************************************************************************
     *  TEMPLATE SPECIAUX
     * **************************************************************************** */

    function hades_special_template( $template )
        {
        global $posts, $wp_query;
        //print_r($wp_query);
        //$wp_query=new WP_Query();
        $time = microtime( true );
        $settings = get_option( 'hades_settings' );

        if( !is_singular() )
            {
            $cats_in_hades = $this->hades_cats_racine();
            }

        /*
        echo "<small style='margin:0px;'>Debug info =>" .
        " categorie event:" . ($cats_in_hades['event'] ? "yes " : "no ") .
        " | hades:" . ($cats_in_hades['hades'] ? "yes " : "no ") .
        " - categorie:" . $cat_slug . " id:" . $cat_id . " tree:" . $cats_in_hades['tree'] .
        " - selection:" . $wp_query->is_tax( 'selection' ) .
        " - hades_agenda:" . $wp_query->get( 'hades_agenda' ) .
        " - commune:" . $wp_query->is_tax( 'commune' ) .
        " - localite:" . $wp_query->is_tax( 'localite' ) .
        "</small>";
         */
        ;


        /* template avec "toujours à l'affiche" */
        if( $cats_in_hades['event'] || $wp_query->get( 'hades_agenda' ) )
            {
            
                    
            $new_template = locate_template( array ($settings['liste_hades_event_template']) );
            if( '' != $new_template )
                {
                //echo "<h2>Toujours à l'affiche trouvé</h2>";
                return $new_template;
                }
            //echo "<h2>Toujours à l'affiche non trouvé</h2>";
            }

        if( $cats_in_hades['hades'] || $wp_query->is_tax( 'selection' ) || $wp_query->is_tax( 'commune' ) || $wp_query->is_tax( 'localite' ) )
            {
            $new_template = locate_template( array ( 'liste-hades_offre.php' ) );
            if( '' != $new_template )
                {
                //echo "<h2>Toujours à l'affiche trouvé</h2>";
                return $new_template;
                }
            //echo "<h2>Toujours à l'affiche non trouvé</h2>";
            }

        return $template;
        }

    /*     * *****************************************************************************
     *  TAXONOMIES
     * **************************************************************************** */

    public function add_taxonomy( $name, $args = array (), $labels = array () )
        {
        if( !empty( $name ) )
            {
// Taxonomy properties
            $taxonomy_name = strtolower( str_replace( ' ', '_', $name ) );
            $taxonomy_labels = $labels;
            $taxonomy_args = $args;


            if( !taxonomy_exists( HADES_CPT ) )
                {

                /* ========= Création de la taxonomie ******* */

//Mise en capitales de la première lettre de chaque mot
                $name = ucwords( str_replace( '_', ' ', $name ) );
                $plural = $name . 's';

//Initialisation de la taxonomie avec des labels par défaut et des labels transmis
                $labels = array_merge(
// Default
                        array (
                            'name' => _x( $plural, 'taxonomy general name' ),
                            'singular_name' => _x( $name, 'taxonomy singular name' ),
                            'search_items' => __( 'Search ' . $plural ),
                            'all_items' => __( 'All ' . $plural ),
                            'parent_item' => __( 'Parent ' . $name ),
                            'parent_item_colon' => __( 'Parent ' . $name . ':' ),
                            'edit_item' => __( 'Edit ' . $name ),
                            'update_item' => __( 'Update ' . $name ),
                            'add_new_item' => __( 'Add New ' . $name ),
                            'new_item_name' => __( 'New ' . $name . ' Name' ),
                            'menu_name' => __( $name ),
                        ),
                        // Labels transmis
                        $taxonomy_labels
                );

//Initialisation de la taxonomie avec des arguments par défaut et des arguments transmis
                $args = array_merge(
// Default
                        array (
                            'label' => $plural,
                            'labels' => $labels,
                            'public' => true,
                            'show_ui' => true,
                            'show_in_nav_menus' => true,
                            '_builtin' => false,
                        ),
                        // Arguments transmis
                        $taxonomy_args
                );

// AJout de la taxonomie aux Offre Hadès
                add_action( 'init', function() use($taxonomy_name, $args )
                    {
                    register_taxonomy( $taxonomy_name, HADES_CPT, $args );
                    }
                );
                }
            else
                {

                /* ========= Enregistrement simple de la taxonomie ========= */

                add_action( 'init', function() use( $taxonomy_name)
                    {
                    register_taxonomy_for_object_type( $taxonomy_name, HADES_CPT );
                    }
                );
                }
            }
        }

    /*     * *****************************************************************************
     *  METABOXES
     * **************************************************************************** */

    public function add_meta_box( $title, $fields = array (), $context = 'normal', $priority = 'default' )
        {
        if( !empty( $title ) )
            {

// Meta variables
            $box_id = strtolower( str_replace( ' ', '_', $title ) );
            $box_title = ucwords( str_replace( '_', ' ', $title ) );
            $box_context = $context;
            $box_priority = $priority;

// Make the fields global
            global $custom_fields;
            $custom_fields[$title] = $fields;

            add_action( 'admin_init', function() use( $box_id, $box_title, $box_context, $box_priority, $fields )
                {
                add_meta_box(
                        $box_id, $box_title, function( $post, $data )
                    {
                    global $post;

// Nonce field for some validation
                    wp_nonce_field( plugin_basename( __FILE__ ), 'hades_offre' );

// Get all inputs from $data
                    $custom_fields = $data['args'][0];

// Get the saved values
                    $meta = get_post_custom( $post->ID );

// Check the array and loop through it
                    if( !empty( $custom_fields ) )
                        {
                        /* Loop through $custom_fields */
                        $field_id_type = array ();
                        foreach( $custom_fields as $field )
                            {
                            $field_id_name = $field['id'];

                            echo '<label for="' . $field_id_name . '">' . $field['label'] . '</label> '
                            . '<input '
                            . 'type="text" '
                            . 'name="custom_meta[' . $field_id_name . ']" '
                            . 'id="' . $field_id_name . '" '
                            . 'value="' . $meta[$field_id_name][0] . '" /> &nbsp; &nbsp;';

                            $field_id_type[] = array ( $field['id'], $field['type'] );
                            }
                        }
                    }, HADES_CPT, $box_context, $box_priority, array ( $fields )
                );
                }
            );
            }
        }

    public function save()
        {

        add_action( 'save_post', function()
            {
// Deny the WordPress autosave function
            if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
                return;

            if( !wp_verify_nonce( $_POST['hades_offre'], plugin_basename( __FILE__ ) ) )
                return;

            global $post;

            if( isset( $_POST ) && isset( $post->ID ) && get_post_type( $post->ID ) == HADES_CPT )
                {
                global $custom_fields;

// Loop through each meta box
                foreach( $custom_fields as $title => $fields )
                    {
// Loop through all fields
                    foreach( $fields as $label => $type )
                        {
                        $field_id_name = strtolower( str_replace( ' ', '_', $title ) ) . '_' . strtolower( str_replace( ' ', '_', $label ) );

                        update_post_meta( $post->ID, $field_id_name, $_POST['custom_meta'][$field_id_name] );
                        }
                    }
                }
            }
        );
        }

    private function hades_cats_racine( $query = NULL )
        {
        global $wp_query;

        $settings = get_option( 'hades_settings' );

        $racine_events_cat = get_category( $settings['hades_event_category_parent'] )->slug;
        $racine_hades_cat = get_category( $settings['hades_category_parent'] )->slug;
        $cat_in = array ();
        $cat_in['event'] = FALSE;
        $cat_in['hades'] = FALSE;

        if( $query && $query->query_vars["cat"] )
            {
            $cat_id = $query->query_vars["cat"];
            }
        else
            {
            $cat_slug = $wp_query->get( 'category_name' );
            $cat_id = @get_category_by_slug( $cat_slug )->term_id;
            }

        if( !$cat_id )
            {
            return $cat_in;
            }



        $str_tree_cat = get_category_parents( $cat_id, FALSE, "/", TRUE );

        if( !is_string( $str_tree_cat ) || $str_tree_cat == '' )
            {
            return $cat_in;
            }

        $cat_in['tree'] = $str_tree_cat;

        if( preg_match( "#/" . $racine_events_cat . "/#", $str_tree_cat ) )
            {
            $cat_in['event'] = TRUE;
            }
        if( preg_match( "#^" . $racine_hades_cat . "/#", $str_tree_cat ) )
            {
            $cat_in['hades'] = TRUE;
            }

        return $cat_in;
        }

    public static function get_instance()
        {
        if( !isset( self::$instance ) )
            {
            self::$instance = new self();
            }
        return self::$instance;
        }

    }
