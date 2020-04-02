<?php
//namespace Atlb\Hades;

		function distance_from_where( $where )
        {
        global $wpdb;
        $loc=get_term_by('slug', get_query_var("localite"), HADES_TAXO_LOC );
        $term_vals = get_term_meta($loc->term_id);

        $x=$term_vals['x'][0];
        $y=$term_vals['y'][0];

        $coord_x =  ( $x - 5) * 111.319 ;
        $coord_y =  ( $y - 49.50) * 172.190 ;

        
        $dist = get_query_var("loc_rayon");
        
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
             WHERE  MBRContains( GeomFromText("'.$polygon.'") , s.coord ) )'  , "" );
        
        
        return $where;
        }