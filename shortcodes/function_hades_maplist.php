<?php

function hades_maplist( $atts )
    {
    $attr = shortcode_atts( array (
                'test' => '',
                'h'=> '',
                'cat_in' =>'',
            ), $atts );
    $html = hades_maplist_html( $attr );
 
    return $html;
    }

function hades_maplist_html( $attr )
    {
    $attr_txt = print_r( $attr, 1 );
    
    $args = array (
                'post_type' => HADES_CPT,
                'nopaging' => true,
                'order' => 'DESC',
                'category_name' => $attr['cat_in'],
    );
    
    $json=hades_maplist_get_json_offres($args);
    
    
    $str = <<<HTML
    <div class="container-fluid">       
        <div id="hades_ml_wrapper" class=" col-sm-12" style="height:{$attr['h']}px; ">
            <div id="hades_ml_mapfilter" class=" col-sm-4">
                    <div id="ml_flt_button" class="">Filtrer<br />123<br/>résultats</div>
                    <div id="hades_ml_filter" class="" style="height:{$attr['h']}px; ">
                    
                        <form id="hades_maplist_form" />
                        Critères
                        <ul>
                        <li><input type="checkbox" name="category_name[1]" value="football" /> football</li>
                        <li><input type="checkbox" name="category_name[2]" value="natation" /> natation</li>
                        <li><input type="checkbox" name="category_name[3]" value="concerts" /> concerts</li>
                        <li><input type="checkbox" name="category_name[4]" value="expositions" /> expositions</li>
                        <li><input type="checkbox" name="category_name[5]" value="musees" /> musees</li>
                        </ul>
                        </form>
                        <input id="ml_flt_submit" type="button" value="filtrer" />
                    </div>
                
                <div id="hades_ml_map" class="" style="height:{$attr['h']}px; ">
                    carte de la province avec les résultats
                </div>
            </div>
            <div id="hades_ml_list" class=" col-sm-8" style="height:{$attr['h']}px; ">
            </div>
        </div>
            <script >
            var hades_map = L.map('hades_ml_map').setView([50, 5.4], 9);
            var hades_markers=[];
            var hades_map_marker = [];
            L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {foo: 'bar'}).addTo(hades_map);
            var hades_bounds = [];
            var hades_json_offres=$json;         
            hades_map_set_markers(hades_json_offres);  
            hades_list_set_offres(hades_json_offres); 
    
        </script>
    </div>  
         
HTML;

    return $str;
    }
  
function hades_maplist_get_json_offres($args, $desc=FALSE) {
    
    global $post;
    
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
            //$p["thumbnail"]=get_post_thumbnail_id($post->ID);
            $p["thumbnail"]=get_the_post_thumbnail_url($post->ID);
            $p["contact"]=get_post_meta( $post->ID, 'contact', true );
            $p["x"]=get_post_meta( $post->ID, 'gps_x', true );
            $p["y"]=get_post_meta( $post->ID, 'gps_y', true );
            $p["localite_commune"]=get_post_meta($post->ID, 'localite_commune', true  );
            $p["cp"]=get_post_meta($post->ID, 'cp', true  );
            $p["adresse"]=get_post_meta($post->ID, 'adresse', true  );
            $p["cat"]=get_the_category()[0]->slug;
            array_push($postes,$p ) ;
            }
         $retour=json_encode($postes);   
        }
    else
        {
        $retour=NULL;
        }
    wp_reset_postdata();   
    
    return $retour;
    }   
    
add_shortcode( 'hades_maplist', 'hades_maplist' );
