<?php

function hades_map_search( $atts )
    {
    $a = shortcode_atts( array (
                'categories' => ''
            ), $atts );

    if( $a['categories'] )
        {
        $arg = array (
                    'orderby' => 'name',
                    'slug' => explode( ',', $a['categories'] )
        );

        $r = "
            <div class='row'>
        <div class='col-md-3 map_search_critere widget widget-area'>
        
          <form id='hades_map_search_form'>
            <h3 class='widget-title' >Rechercher </h3>
                <div class='grid-100 tablet-grid-100 mobile-grid-100 grid-parent'>";
        foreach( get_categories( $arg ) as $cat_obj )
            {
            //style='max-width:500px ;width:100%;height: 600px;float: left;'
            $r.= " 
                    <div class='bloc grid-33 tablet-grid-50 mobile-grid-100'><input type= 'checkbox' name='category_name[" . $cat_obj->cat_ID . "]' value='" . $cat_obj->slug . "' /> 
                        " . $cat_obj->cat_name . "
                        (" . $cat_obj->count . ")
                    </div>";
            }
        $r.= "</div>";



        $r.="<input type='hidden' name='action' value='get_map_hades' /> 
            </form>
			<div class='boutons aligncenter tablet-aligncenter mobile-aligncenter'>
				<button id='clickmap' value='click' >Afficher</button>
				<button id='clickexcell' value='click' >Télécharger</button>
			</div>
        </div>

       <div id='hades_map_search_map' class='col-md-9' style='height:750px' ></div>
        </div>
        <script >
            jQuery('#clickmap').click(send_map_ajax);
            jQuery('#clickexcell').click(send_excell_ajax);
            var hades_map = L.map('hades_map_search_map').setView([50, 5.4], 9);
            var hades_markers=[];
            var hades_map_marker = [];
            L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {foo: 'bar'}).addTo(hades_map);
            var hades_bounds = [];
        </script>";
        }
    else
        {
        $r = "ERREUR : Aucune catégorie de recherche n'est définie:" . print_r( $atts, 1 );
        }

    return $r;
    }

add_shortcode( 'hades_map_search', 'hades_map_search' );

