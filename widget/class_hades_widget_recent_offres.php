<?php
class Hades_Widget_Recent_Offres extends WP_Widget
    {
    private static $instance = 0;
    protected $defaults;

    function __construct()
        {
        $widget_ops = array (
                    'classname' => 'Hades_Widget_Recent_Offres',
                    'description' => 'Widget affichant les offres récentes'
        );
        parent::__construct( 'hades-recent', 'Hadès: Offres récentes', $widget_ops );
        }

    public function widget( $args, $instance )
        {

        $instance['title'] = ( isset( $instance['title'] ) ? $instance['title'] : '' );
        $instance['show_offre'] = ( isset( $instance['show_offre'] ) ? $instance['show_offre'] : '' );
        $instance['categoriesroot'] = ( isset( $instance['categoriesroot'] ) ? $instance['categoriesroot'] : '' );


        echo $args['before_widget'];

        echo $args['before_title'];
        echo $instance['title'];
        echo $args['after_title'];
        
        add_filter('posts_groupby', 'query_group_by_hades_id');
        
        $loop = new WP_Query(
                array (
                    'post_type' => HADES_CPT,
                    'recent_widget'  =>TRUE,      
                    'posts_per_page' => $instance['show_post'],
                    //'cat' => '14,15,16,17,18,19,20',
                    'category_name' => $instance['categoriesroot'],
                    'meta_key' => 'hades_id',
                    'orderby' => array ( 'meta_value_num' => 'DESC'),
                    
                )
        );

       
        remove_filter('posts_groupby', 'query_group_by_hades_id');
        
        if( $loop->have_posts() ) :
            while( $loop->have_posts() ) : $loop->the_post();
                ?>
                <div class="media rdn-home-news-area">
                  <div class="media-left home-news-image hades-home-news-image">
                    <a href="<?php the_permalink(); ?>" class="post-thumbnail" title="<?php the_title(); ?>">
                      <?php the_post_thumbnail(); ?>
                    </a>
                  </div>
                  <div class="media-body home-news-body">
                    <h5 class="news-title"><a href="<?php the_permalink(); ?>">
                        <span class="date_titre"><?php echo get_post_meta($loop->post->ID, 'date_titre', true ); ?></span>
                        <br/><?php the_title(); ?></a>
                    </h5>
                  </div>
                </div>
                <?php
            endwhile;
        endif;

        echo $args['after_widget'];
        }

    public function form( $instance )
        {
        $settings = get_option( 'hades_settings' );
    
        $instance = wp_parse_args( (array) $instance, array (
                    'title' => '',
                    'show_post' => 4,
                    'categoriesroot' => '',
                )
        );        
        
        ?>

        <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>">Titre</label> 
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <p>
          <label for="<?php echo $this->get_field_id( 'show_post' ); ?>">Nombre d'offres</label> 
          <input class="widefat" id="<?php echo $this->get_field_id( 'show_post' ); ?>" name="<?php echo $this->get_field_name( 'show_post' ); ?>" type="number" value="<?php echo esc_attr( $instance['show_post'] ); ?>" />
        </p>

        
        <p>
          <label for="<?php echo $this->get_field_id( 'categoriesroot' ); ?>">Catégories</label>
          <?php
          /*$args_cat = array (
                      'show_option_all' => __( 'Choisir une categorie' ),
                      'orderby' => 'name',
                      'order' => 'ASC',
                      'show_count' => 1,
                      'hide_empty' => 1,
                      'child_of' => 2,
                      'echo' => 0,
                      'name' => $this->get_field_name( 'categoriesroot' ),
                      'id' => $this->get_field_id( 'categoriesroot' ),
                      'hierarchical' => TRUE,
                      'depth' => 3,
                      'taxonomy' => 'category',
                      'hide_if_empty' => true,
                      'value_field' => 'slug',
          );

          // Y-a-t'il une catégorie actuellement sélectionnée ?
          if( $instance['categoriesroot'] && ( $t = term_exists( $instance['categoriesroot'], 'category' ) ) )
              {
              $args_cat['selected'] = $instance['categoriesroot'];
              }

          $listcat = wp_dropdown_categories( $args_cat );

          // Afficher la liste s'il existe des catégories associées à des contenus
          if( $listcat )
              {
              echo $listcat;
              }
           * 
           */
          
          wp_category_checklist( 
                  $post_id=0, 
                  $descendants_and_self=$settings['hades_category_parent'], 
                  $selected_cats=array() 
                  );
          ?>
        </p>     
        
        <?php
        }

    public function update( $new_instance, $old_instance )
        {

        $instance = array ();
        $instance['title'] = (!empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
        $instance['show_post'] = (!empty( $new_instance['show_post'] ) ) ? $new_instance['show_post'] : '';
        $instance['categories_title'] = (!empty( $new_instance['categories_title'] ) ) ? $new_instance['categories_title'] : '';
        $instance['categoriesroot'] = (!empty( $new_instance['categoriesroot'] ) ) ? $new_instance['categoriesroot'] : '';
        return $instance;
        }

    }
    
function  query_group_by_hades_id($groupby){
       global $wpdb;
       
       return $wpdb->postmeta . '.meta_value';
    }