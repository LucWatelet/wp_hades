<?php
class Hades_Widget_Search extends WP_Widget
    {
    private static $instance = 0;
    protected $defaults;

    public function __construct()
        {
        $widget_ops = array (
                    'classname' => 'widget_search hades_widget_search',
                    'description' => __( 'Recherches d\'offres Hadès' ),
                    'customize_selective_refresh' => true
        );
        parent::__construct( 'hades-search', __( 'Hadès: Recherche' ), $widget_ops );
        }

    /**
     * Outputs the content for the current Search widget instance.
     *
     * @since 2.8.0
     * @access public
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Search widget instance.
     */
    public function widget( $args, $instance )
        {

        //extract( $args );
        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $settings = get_option( $option = 'hades_settings' );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );


        echo $args['before_widget'];
        if( $title )
            {
            echo $args['before_title'] . $title . $args['after_title'];
            }
        ?>

        <form action="<?php echo home_url( '/' ); ?>">
          <!-- Ici on affiche le champ « s »
          mais nous aurions pu également en faire 
          un champ de type hidden avec une valeur vide-->
          <div class="hades-search-block" >
            <label for="s">Rechercher</label>
            <input type="text" name="s" placeholder="Mots-clés..."  value="<?php the_search_query(); ?>" id="s">
          </div>


          <?php
          if( $instance['themeslist'] == 'on' )
              {
              ?>
              <div class="hades-search-block" >
                <label for="themes"><?php echo $instance['themes_title']; ?></label>
                <?php
                
                $args_thm = array (
                            'show_option_all' => __( 'Pas de préférence' ),
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'show_count' => 1,
                            'hide_empty' => 1,
                            'echo' => 0,
                            'name' => 'selection',
                            'id' => 'selection',
                            'hierarchical' => FALSE,
                            'depth' => 1,
                            'taxonomy' => HADES_TAXO_SEL,
                            'hide_if_empty' => true,
                            'value_field' => 'slug',
                );
                
                if($instance['themeslimit'] != '' ){
                    $limit= explode( ',', $instance['themeslimit'] ) ;
                    $limit=array_filter($limit);
                    if(count($limit)>0){
                        $args_thm['include']=$limit;
                        }
                    }

                // Y-a-t'il un thème actuellement sélectionné ?
                if( get_query_var( 'selection' ) && ( $t = term_exists( get_query_var( 'selection' ), HADES_TAXO_SEL ) ) )
                    {
                    $args_thm['selected'] = get_query_var( 'selection' );
                    }

                $listsel = wp_dropdown_categories( $args_thm );

                // Afficher la liste s'il existe des thèmes associés à des contenus
                if( $listsel )
                    {
                    echo $listsel;
                    }
                ?>
              </div>
          <?php } ?>



          <?php
          if( $instance['categorieslist'] == 'on' )
              {
              ?>          

              <div class="hades-search-block" >
                <label for="categorie"><?php echo $instance['categories_title']; ?></label>
                <?php
                $hades_cat_id = $settings['hades_category_parent'];

                if( $instance['categoriesroot'] )
                    {
                    $hades_cat_id = get_category_by_slug( $instance['categoriesroot'] )->term_id;
                    }

                $args_cat = array (
                            'show_option_none' => __( 'Toutes les catégories' ),
                            'option_none_value' => get_category( $hades_cat_id )->slug,
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'show_count' => 1,
                            'hide_empty' => 1,
                            'child_of' => $hades_cat_id,
                            'echo' => 0,
                            'name' => 'category_name',
                            'id' => 'category_name',
                            'hierarchical' => TRUE,
                            'depth' => 1,
                            'taxonomy' => 'category',
                            'hide_if_empty' => true,
                            'value_field' => 'slug',
                );

                // Y-a-t'il une catégorie actuellement sélectionnée ?
                if( get_query_var( 'category_name' ) && ( $t = term_exists( get_query_var( 'category_name' ), 'category' ) ) )
                    {
                    $args_cat['selected'] = get_query_var( 'category_name' );
                    }

                $listcat = wp_dropdown_categories( $args_cat );

                // Afficher la liste s'il existe des catégories associées à des contenus
                if( $listcat )
                    {
                    echo $listcat;
                    }
                ?>

              </div>
        <?php } ?>


        <?php
        if( $instance['communeslist'] == 'on' )
            {
            ?>          

              <div class="hades-search-block" >
                <label for="commune"><?php echo $instance['communes_title']; ?></label>
            <?php
            $args_com = array (
                        'show_option_all' => __( 'Toutes les communes' ),
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'show_count' => 1,
                        'hide_empty' => 1,
                        'echo' => 0,
                        'name' => 'commune',
                        'id' => 'commune',
                        'hierarchical' => FALSE,
                        'depth' => 1,
                        'taxonomy' => HADES_TAXO_COM,
                        'hide_if_empty' => true,
                        'value_field' => 'slug',
            );

            // Y-a-t'il une ville actuellement sélectionnée ?
            if( get_query_var( 'commune' ) && ( $t = term_exists( get_query_var( 'commune' ), HADES_TAXO_COM ) ) )
                {
                $args_com['selected'] = get_query_var( 'commune' );
                }

            $listcom = wp_dropdown_categories( $args_com );

            // Afficher la liste s'il existe des villes associées à des contenus
            if( $listcom )
                {
                echo $listcom;
                }
            ?>
              </div>
            <?php } ?>



        <?php
        if( $instance['localiteslist'] == 'on' )
            {
            ?>          

              <div class="hades-search-block" >
                <label for="localite"><?php echo $instance['localites_title']; ?></label>
            <?php
            if( $instance['localitesdrop'] == 'on' )
                {
                // Y-a-t'il une ville actuellement sélectionnée ?
                if( get_query_var( 'localite' ) && ( $t = term_exists( get_query_var( 'localite' ), HADES_TAXO_LOC ) ) )
                    {
                    $args_loc['selected'] = get_query_var( 'localite' );
                    }
                ?>

                    <input type="text" id="searchloc" autocomplete="off" name="" value="<?php echo get_query_var( 'localite' ); ?>" style="width:50%;" />
                    <!--<input name="action" value="search_loc_hades" type="hidden">-->
                    <div id="searchloc_list" style="display:none"></div>
                    <script >
                        jQuery('#searchloc').on("keyup", function () {
                          send_loc_ajax(this.value);
                        });

                    </script>

                    <input type="hidden" name="localite<?php echo $instance['localitesrayon'] == 'on' ? "_ray" : ""; ?>" id="searchloc_localite" value="<?php echo get_query_var( 'localite' ); ?>" />


                    <?php
                    }
                else
                    {
                    $args_loc = array (
                                'show_option_all' => __( 'Toutes les Localités' ),
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'show_count' => 1,
                                'hide_empty' => 1,
                                'echo' => 0,
                                'name' => 'localite' . ($instance['localitesrayon'] == 'on' ? "_ray" : ""),
                                'id' => 'localite',
                                'hierarchical' => FALSE,
                                'depth' => 1,
                                'taxonomy' => HADES_TAXO_LOC,
                                'hide_if_empty' => true,
                                'value_field' => 'slug',
                    );

                    // Y-a-t'il une ville actuellement sélectionnée ?
                    if( get_query_var( 'localite' ) && ( $t = term_exists( get_query_var( 'localite' ), HADES_TAXO_LOC ) ) )
                        {
                        $args_loc['selected'] = get_query_var( 'localite' );
                        }

                    $listloc = wp_dropdown_categories( $args_loc );

                    // Afficher la liste s'il existe des villes associées à des contenus
                    if( $listloc )
                        {
                        echo $listloc;
                        }
                    ?>
                  </div>


              <?php
                  }
              if( $instance['localitesrayon'] == 'on' )
                  {
                  ?>

                  Rayon : <input type="number"  name="loc_rayon" id="localitesrayon" style="width:20%;"
                                 value="<?php echo get_query_var( 'localitesrayon' ) ? get_query_var( 'localitesrayon' ) : "10"; ?>" />Km   

              <?php }
              }
          ?>



          <?php
          if( $instance['tempolist'] == 'on' )
              {
              ?>
              <div class="hades-search-block" >
                <label for="themes"><?php echo $instance['tempo_title']; ?></label>
                <select id="hades_agenda" name="hades_agenda" >
                  <option value="<?php echo date( "Ymd", time() ) . date( "Ymd", time() + (3600 * 24 * 365) ) ?>" >Pas de préférence</option>
                  <option value="<?php echo date( "Ymd", time() ) ?>" >Aujourd'hui</option>
                  <option value="<?php echo date( "Ymd", time() + (3600 * 24) ) ?>" >Demain</option>

                  <option value="<?php
                  $datedep = time();
                  while( date( 'N', $datedep ) < 5 )
                      {
                      $datedep+=(3600 * 24);
                      }
                  $n = date( 'N', $datedep );
                  echo date( "Ymd", $datedep ) . date( "Ymd", $datedep + (3600 * 24) * (7 - $n) );
                  ?>" >Ce week-end</option>
                  <option value="<?php echo date( "Ymd", time() ) . date( "Ymd", time() + (3600 * 24 * 7) ) ?>" >Les 7 prochains jours</option>
                  <option value="<?php echo date( "Ymd", time() ) . date( "Ymd", time() + (3600 * 24 * 30) ) ?>" >Les 30 prochains jours</option>

                </select>
              </div>
        <?php } ?>

          <button class="search-submit" type="submit" style="position:static"> </button>
        </form>
        <?php
        echo $args['after_widget'];
        }

    /**
     * Outputs the settings form for the Search widget.
     *
     * @since 2.8.0
     * @access public
     *
     * @param array $instance Current settings.
     */
    public function form( $instance )
        {
        $instance = wp_parse_args( (array) $instance, array (
                    'title' => '',
                    'themeslist' => '',
                    'themeslimit' => '',
                    'themeslistlimit' => array (),
                    'categorieslist' => '',
                    'categoriesroot' => '',
                    'tempolist' => '',
                    'communeslist' => '',
                    'localiteslist' => '',
                    'localitesdrop' => '',
                    'themes_title' => 'Thématiques',
                    'categories_title' => 'Catégories',
                    'tempo_title' => 'Période',
                    'communes_title' => 'Communes',
                    'localites_title' => 'Localités'
                )
        );
        $title = $instance['title'];
        $settings = get_option( $option = 'hades_settings' );
        ?>

        <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?> 
            <input class="widefat" 
                   id="<?php echo $this->get_field_id( 'title' ); ?>" 
                   name="<?php echo $this->get_field_name( 'title' ); ?>" 
                   type="text" 
                   value="<?php echo esc_attr( $title ); ?>" />
          </label>
        </p>

        <p>
          <input class="checkbox" type="checkbox" <?php checked( $instance['themeslist'], 'on' ); ?> 
                 id="<?php echo $this->get_field_id( 'themeslist' ); ?>" 
                 name="<?php echo $this->get_field_name( 'themeslist' ); ?>" /> 
          <label for="<?php echo $this->get_field_id( 'themeslist' ); ?>">Liste des thèmes</label>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'themes_title' ); ?>">Label des Thèmes 
            <input class="widefat" 
                   id="<?php echo $this->get_field_id( 'themes_title' ); ?>" 
                   name="<?php echo $this->get_field_name( 'themes_title' ); ?>" 
                   type="text" 
                   value="<?php echo esc_attr( $instance['themes_title'] ); ?>" />
          </label>
        </p>   

        <p>
          <label for="<?php echo $this->get_field_id( 'themeslimit' ); ?>">Choix de thèmes : 
            <input class="widefat" 
                   id="<?php echo $this->get_field_id( 'themeslimit' ); ?>" 
                   name="<?php echo $this->get_field_name( 'themeslimit' ); ?>" 
                   type="hidden" 
                   value="<?php echo esc_attr( $instance['themeslimit'] ); ?>" />
          </label>
        </p>

        <p>
          <?php
          $args_sel = array (
                      'show_option_all' => __( 'Choisir des sélections' ),
                      'orderby' => 'name',
                      'order' => 'ASC',
                      'show_count' => 1,
                      'hide_empty' => 0,
                      'echo' => 0,
                      'name' => $this->get_field_name( 'themeslistlimit' ) . "[]",
                      'id' => $this->get_field_id( 'themeslistlimit' ),
                      'hierarchical' => FALSE,
                      'taxonomy' => 'selection',
                      'hide_if_empty' => FALSE,
                      'selected'=> -1,
                  //'value_field' => 'slug',
          );



          $listsel = wp_dropdown_categories( $args_sel );
          $listsel = str_replace( 'id=', 'multiple="multiple" style="resize:vertical;" id=', $listsel );


          // Y-a-t'il des sélections actuellement sélectionnées ?
          $listlimit = explode( ",", $instance['themeslimit'] );

          if( is_array( $listlimit ) )
              {
              foreach( $listlimit as $key )
                  {
                  if(intval($key)>0)
                  $listsel = str_replace( ' value="' . $key . '"', ' value="' . $key . '" selected="selected"', $listsel );
                  }
              }


          // Afficher la liste s'il existe des catégories associées à des contenus
          if( $listsel )
              {
              echo $listsel;
              }
          ?>
        </p>

        <hr/>
        <p>
        <?php ?> 
          <input class="checkbox" type="checkbox" <?php checked( $instance['categorieslist'], 'on' ); ?> 
                 id="<?php echo $this->get_field_id( 'categorieslist' ); ?>" 
                 name="<?php echo $this->get_field_name( 'categorieslist' ); ?>" /> 
          <label for="<?php echo $this->get_field_id( 'categorieslist' ); ?>">Liste des catégories</label>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'categoriesroot' ); ?>">Catégorie racine</label>
          <?php
          $args_cat = array (
                      'show_option_all' => __( 'Choisir une categorie' ),
                      'orderby' => 'name',
                      'order' => 'ASC',
                      'show_count' => 1,
                      'hide_empty' => 1,
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
          ?>
        </p>     
        <p>
          <label for="<?php echo $this->get_field_id( 'categories_title' ); ?>">Label des catégories 
            <input class="widefat" 
                   id="<?php echo $this->get_field_id( 'categories_title' ); ?>" 
                   name="<?php echo $this->get_field_name( 'categories_title' ); ?>" 
                   type="text" 
                   value="<?php echo esc_attr( $instance['categories_title'] ); ?>" />
          </label>
        </p>
        <hr/>
        <p>
          <input class="checkbox" type="checkbox" <?php checked( $instance['tempolist'], 'on' ); ?> 
                 id="<?php echo $this->get_field_id( 'tempolist' ); ?>" 
                 name="<?php echo $this->get_field_name( 'tempolist' ); ?>" /> 
          <label for="<?php echo $this->get_field_id( 'tempolist' ); ?>">Liste temporelle</label>
        </p>        
        <p>
          <label for="<?php echo $this->get_field_id( 'tempo_title' ); ?>">Label des Périodes 
            <input class="widefat" 
                   id="<?php echo $this->get_field_id( 'tempo_title' ); ?>" 
                   name="<?php echo $this->get_field_name( 'tempo_title' ); ?>" 
                   type="text" 
                   value="<?php echo esc_attr( $instance['tempo_title'] ); ?>" />
          </label>
        </p>

        <hr/> 

        <p>
          <input class="checkbox" type="checkbox" <?php checked( $instance['communeslist'], 'on' ); ?> 
                 id="<?php echo $this->get_field_id( 'communeslist' ); ?>" 
                 name="<?php echo $this->get_field_name( 'communeslist' ); ?>" /> 
          <label for="<?php echo $this->get_field_id( 'communeslist' ); ?>">Liste des communes</label>
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'communes_title' ); ?>">Label des communes 
            <input class="widefat" 
                   id="<?php echo $this->get_field_id( 'communes_title' ); ?>" 
                   name="<?php echo $this->get_field_name( 'communes_title' ); ?>" 
                   type="text" 
                   value="<?php echo esc_attr( $instance['communes_title'] ); ?>" />
          </label>
        </p>

        <p>
          <input class="checkbox" type="checkbox" <?php checked( $instance['localiteslist'], 'on' ); ?> 
                 id="<?php echo $this->get_field_id( 'localiteslist' ); ?>" 
                 name="<?php echo $this->get_field_name( 'localiteslist' ); ?>" /> 
          <label for="<?php echo $this->get_field_id( 'localiteslist' ); ?>">Liste des localités</label>
          <input class="checkbox" type="checkbox" <?php checked( $instance['localitesdrop'], 'on' ); ?> 
                 id="<?php echo $this->get_field_id( 'localitesdrop' ); ?>" 
                 name="<?php echo $this->get_field_name( 'localitesdrop' ); ?>" /> 
          <label for="<?php echo $this->get_field_id( 'localitesdrop' ); ?>">Champ textuel</label>
          <input class="checkbox" type="checkbox" <?php checked( $instance['localitesrayon'], 'on' ); ?> 
                 id="<?php echo $this->get_field_id( 'localitesrayon' ); ?>" 
                 name="<?php echo $this->get_field_name( 'localitesrayon' ); ?>" /> 
          <label for="<?php echo $this->get_field_id( 'localitesrayon' ); ?>">Rayon</label>        
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'localites_title' ); ?>">Label des localités 
            <input class="widefat" 
                   id="<?php echo $this->get_field_id( 'localites_title' ); ?>" 
                   name="<?php echo $this->get_field_name( 'localites_title' ); ?>" 
                   type="text" 
                   value="<?php echo esc_attr( $instance['localites_title'] ); ?>" />
          </label>
        </p>

        <?php
        }

    /**
     * Handles updating settings for the current Search widget instance.
     *
     * @since 2.8.0
     * @access public
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings.
     */
    public function update( $new_instance, $old_instance )
        {
        $instance = $old_instance;


        $new_instance = wp_parse_args( (array) $new_instance, array ( 'title' => '' ) );
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['themeslist'] = $new_instance['themeslist'];
        $instance['themeslimit'] = implode( ",", $_POST['widget-hades-search'][$this->number]['themeslistlimit'] );
        $instance['categorieslist'] = $new_instance['categorieslist'];
        $instance['categoriesroot'] = $new_instance['categoriesroot'];
        $instance['tempolist'] = $new_instance['tempolist'];
        $instance['communeslist'] = $new_instance['communeslist'];
        $instance['localiteslist'] = $new_instance['localiteslist'];
        $instance['localitesdrop'] = $new_instance['localitesdrop'];
        $instance['localitesrayon'] = $new_instance['localitesrayon'];
        $instance['themes_title'] = $new_instance['themes_title'];
        $instance['categories_title'] = $new_instance['categories_title'];
        $instance['tempo_title'] = $new_instance['tempo_title'];
        $instance['communes_title'] = $new_instance['communes_title'];
        $instance['localites_title'] = $new_instance['localites_title'];
        return $instance;
        }

    }