<?php
include_once("xlsxwriter.class.php");

function ajax_for_excell($cats_list)
{
    global $post;

    if (isset($_POST['category_name']) && is_array($_POST['category_name'])) {
        $cats_list=implode(",", $_POST['category_name']);
    }

    // Query Arguments
    $args = array(
                'post_type' => HADES_CPT,
                'nopaging' => true,
                'order' => 'DESC',
                'category_name' =>$cats_list ,
    );
    $query_hades = new WP_Query($args);
    if ($query_hades->have_posts()) {
        $postes = array();
        $filename = "scs.xlsx";
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $rows = array();
        $writer = new XLSXWriter();
        $writer->setAuthor('Service de la culture et des sports');
        
        $styles[0] = array( 'font'=>'Arial','font-size'=>10,'fill'=>'#eee');
        $styles[1] = array( 'font'=>'Arial','font-size'=>10,'fill'=>'');
        
        $writer->writeSheetHeader(
            'Feuil1',
            array(
                    "Catégorie"=>'string',
                    "Titre"=>'string',
                    "Adresse"=>'string',
                    "Code Postal"=>'0',
                    "Localité"=>'string',
                    "Gps x"=>'0.00000',
                    "Gps y"=>'0.00000',
                    "Tél"=>'string',
                    "Email"=>'string',
                    "Website"=>'string'),
            $col_options = array('widths'=>[12,30,30,12,20,10,10,20,30,30])
        );
        
        while ($query_hades->have_posts()) {
            $p=array();
            $query_hades->the_post();
            $p[] = get_the_category()[0]->slug;
            $p[] = $post->post_title;
            $p[] = get_post_meta($post->ID, 'adresse', true);
            $p[] = get_post_meta($post->ID, 'cp', true);
            $p[] = get_post_meta($post->ID, 'localite_commune', true);
            $p[] = get_post_meta($post->ID, 'gps_x', true);
            $p[] = get_post_meta($post->ID, 'gps_y', true);
            $p[] = get_post_meta($post->ID, 'tel', true);
            $p[] = get_post_meta($post->ID, 'email', true);
            $p[] = get_post_meta($post->ID, 'url', true);
            if ($i==0) {
                $i=1;
            } else {
                $i=0;
            }
            $writer->writeSheetRow('Feuil1', $p, $styles[$i]);
            $p=array();
        }
        $writer->writeToStdOut();
    } else {
        // no posts found
    }
    wp_reset_postdata();
    exit(0);
}
