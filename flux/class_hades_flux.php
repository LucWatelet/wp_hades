<?php
/**
 * Description of Hades_Flux
 *
 * @author l.watelet
 */
class Hades_Flux
    {
    static $instance;
    public $flux_id;
    public $url;
    public $actif;
    public $temps_load;
    public $temps_parse;
    public $poids;
    public $nb_offres;
    public $tempdir;
    public $xml = FALSE;
    public $compress = FALSE;
    public $timeout = 360;
    private $settings;
    private $context;

    function __construct()
        {
        $this->settings = get_option( $option = 'hades_settings' );
        $http = array (
                    'http' => array (
                                'method' => "GET",
                                "timeout" => $this->timeout,
                                'header' => "Authorization: Basic " . base64_encode( HADES_FEED_USERNAME . ":" . HADES_FEED_PASSWORD ),
                                'request_fulluri' => true
                    )
        );
        if ( WP_HTTP_Proxy::is_enabled() ) {
          $http['http']['proxy'] = WP_PROXY_HOST . ':' . WP_PROXY_PORT;
        }
        $this->context = stream_context_create( $http );
        }

    /*     * *************************************************************************
     * Retourne le flux défini par $flux_id
     * ************************************************************************ */

    public function get_flux( $flux_id )
        {
        global $wpdb;
        $results = $wpdb->get_row( "SELECT * FROM `" . HADESDBPFX . "flux_url` WHERE flux_id = " . intval( $flux_id ) );
        foreach( $results as $key => $value )
            {
            $this->$key = $value;
            }
        return $results;
        }

    /*     * *************************************************************************
     * Retourne un flux construit sur base d'un identifiant d'offre ou d'un tableau d'iendtifiants
     * ************************************************************************ */

    public function get_off_id_flux( $off_ids )
        {
        if( is_array( $off_ids ) )
            {
            $off_id_list = implode( ",", $off_ids );
            }
        else
            {
            $off_id_list = $off_ids;
            }
        $this->url = HADES_FEED_URL .'?tbl=xmlcomplet&off_id=' . $off_id_list;
        $this->actif = 1;
        $this->flux_id = rand( 100, 9999 );
        }

    /*     * *************************************************************************
     * Retourne Tous les flux
     * ************************************************************************ */

    public function get_all_flux()
        {
        global $wpdb;
        $results = $wpdb->get_results( "SELECT * FROM `" . HADESDBPFX . "flux_url` " );
        return $results;
        }

    /*     * *************************************************************************
     * Ajoute un nouveau flux
     * ************************************************************************ */

    public function add_new( $i = NULL )
        {
        global $wpdb;
        if( $i )
            {
            $wpdb->insert(
                    HADESDBPFX . "flux_url", array (
                        'url' => stripslashes( $i['url'] ), // string
                        'actif' => ($i['actif'] != 0 ? '1' : '0')// integer (number)
                    ), array (
                        '%s',
                        '%d'
                    )
            );
            }
        else
            {
            $wpdb->insert(
                    HADESDBPFX . "flux_url", array (
                        'url' => HADES_FLUX_URL.'?tbl=xmlcomplet&', // string
                        'actif' => '0'// integer (number)
                    ), array (
                        '%s',
                        '%d'
                    )
            );
            }
        }

    /*     * *************************************************************************
     * Enregistre un flux
     * ************************************************************************ */

    public function save( $f )
        {

        if( is_object( $f ) )
            {
            $f = get_object_vars( $f );
            }
        global $wpdb;
        $check = $wpdb->update(
                HADESDBPFX . "flux_url", array (
                    'url' => stripslashes( $f['url'] ), // string
                    'actif' => ($f['actif'] != 0 ? '1' : '0'), // integer (number)
                    'temps' => $f['temps'], // integer (number)
                    'poids' => $f['poids'], // integer (number)
                    'nb_offres' => $f['nb_offres'], // integer (number)
                ), array ( 'flux_id' => $f['flux_id'] ), array (
                    '%s',
                    '%d',
                    '%f',
                    '%f',
                    '%d',
                ), array ( '%d' )
        );
        return $check;
        }

    /*     * ************************************************************************
     * Supprime un flux
     * ************************************************************************ */

    public function delete( $flux_id )
        {
        global $wpdb;
        hades_set_time( 300 );
        $check = $wpdb->delete( HADESDBPFX . "flux_url", array ( 'flux_id' => $flux_id ) );
        return $check;
        }

    /*     * *************************************************************************
     * Télécharge le contenu flux
     * ************************************************************************ */

    public function download()
        {
        
        global $hades_syn_text_log;
        
        if( ob_get_level() == 0 )
            ob_start();
        hades_set_time( 300 );
        if(!$hades_syn_text_log){
        echo jsbarre( $this->temps, "flux" . $this->flux_id );
        }
        echo "Ouverture de la connection ...\r\n";
        ob_flush();
        flush();

        //Ajoute une limite de date pour une différentielle.
        if( $this->from_datetime )
            $url_flux = $this->url . "&from_datetime=" . $this->from_datetime . "";
        else
            $url_flux = $this->url;


        $temps = microtime( TRUE );

        $this->tempdir = HADES_TMP . '/';

        if( preg_match( "#.*compress=gz.*#", $url_flux ) )
            {
            hadeslog( 'log', "Chargement d'un fichier compressé depuis :" . $url_flux );
            echo "Chargement d'un fichier compressé depuis :" . $url_flux . "\r\n";
            $this->compress = TRUE;
            $tmp_file_ext = ".gz";
            }
        else
            {
            hadeslog( 'log', "Chargement d'un fichier XML. depuis :" . $url_flux . "" );
            echo "Chargement d'un fichier XML depuis :" . $url_flux . "\r\n";
            $this->xml = TRUE;
            $tmp_file_ext = ".xml";
            }

        ob_flush();
        flush();

        $handlein = fopen( $url_flux, 'rb', false, $this->context );
        if( !$handlein )
            {
            echo "<div class='error notice'><p>";
            trigger_error( "Impossible d'ouvrir le flux depuis :" . $url_flux, E_USER_ERROR );
            echo "</p></div>";
            }
        $handleout = fopen( $this->tempdir . "Tmpfile" . $this->flux_id . $tmp_file_ext, 'wb' );
        if( !$handleout )
            {
            echo "<div class='error notice'><p>";
            trigger_error( "Impossible d'ouvrir :" . $this->tempdir . "Tmpfile" . $this->flux_id . $tmp_file_ext, E_USER_ERROR );
            echo "</p></div>";
            }

        echo "Transfert de données en cours ... patientez.\r\n";

        ob_flush();
        flush();

        stream_set_timeout( $handlein, 240 );
        stream_set_timeout( $handleout, 240 );
        $this->nboctets = stream_copy_to_stream( $handlein, $handleout );
        fclose( $handlein );
        fclose( $handleout );

        hadeslog( 'log', "Fichier chargé :" . $this->tempdir . 'Tmpfile' . $this->flux_id . $tmp_file_ext . " : " . intval( $this->nboctets / 1000 . "Ko" ) );

        /* Décompression du fichier si le flux est compressé */
        if( $this->compress )
            {
            echo "Décompression en cours ... patientez.\r\n";
            ob_flush();
            flush();

            $gz = gzopen( $this->tempdir . 'Tmpfile' . $this->flux_id . '.gz', 'r' );
            if( !$gz )
                {
                echo "<div class='error notice'><p>";
                trigger_error( "Impossible d'ouvrir le fichier compressé. :" . "Tmpfile" . $this->flux_id . ".gz", E_USER_ERROR );
                echo "</p></div>";
                }
            $dest = fopen( $this->tempdir . 'Tmpfile' . $this->flux_id . '.xml', 'w' );
            if( !$dest )
                {
                gzclose( $gz );
                echo "<div class='error notice'><p>";
                trigger_error( "Impossible d'ouvrir le fichier pour décompresser. :" . "Tmpfile" . $this->flux_id . ".gz", E_USER_ERROR );
                echo "</p></div>";
                }
            while( !gzeof( $gz ) )
                {
                fwrite( $dest, gzread( $gz, 4096 ) );
                }
            hadeslog( 'log', "Fichier décompressé : " .
                    (filesize( $this->tempdir . "Tmpfile" . $this->flux_id . ".xml" ) < 1000 ?
                            (filesize( $this->tempdir . "Tmpfile" . $this->id . ".xml" ) . "Octets") :
                            intval( filesize( $this->tempdir . "Tmpfile" . $this->id . ".xml" ) / 1000 ) . "Ko") . "\r\n" );

            gzclose( $gz );
            fclose( $dest );
            }

        ob_flush();
        flush();


        $this->tmpfile = $this->tempdir . 'Tmpfile' . $this->flux_id . '.xml';
        $this->temps_load = microtime( TRUE ) - $temps;
        echo "Transfert de données effectué !\r\n";

        ob_flush();
        flush();
        }

 
    /*     * *************************************************************************
     * Découpe le contenu xml du flux et enregistre les fragements xml pour chaque offre
     * ************************************************************************ */

    public function set_in_db_xml()
        {
        hades_set_time( 300 );
        global $wpdb;


        //On verifie que le fichier est défini
        if( !$this->tmpfile )
            {
            hadeslog( 'log', "Le fichier XML  \"" . $this->tmpfile . "\"  de flux n'est pas défini." );
            return FALSE;
            }
        //On ouvre le fichier
        $reader = new XMLReader();
        $XMLouvert = $reader->open( $this->tmpfile );

        //On verifie que le fichier est ouvert
        if( !$XMLouvert )
            {
            hadeslog( 'log', "Le fichier XML  \"" . $this->tmpfile . "\"  de flux n'a pu être ouvert." );
            return FALSE;
            }

        //Avance du curseur jusqu'à la première offre
        $this->off_xml_count = 0;
        $ok = TRUE;
        while( $reader->name != 'offre' && $ok && !$reader->getAttribute( "suppr_date" ) )
            {
            $ok = $reader->read();
            }

        if( !$ok or $reader->getAttribute( "suppr_date" ) )
            {
            hadeslog( 'log', "Aucune offre à traiter." );
            $this->off_xml_count = 0;
            }
        else
            {
            //Parcours chaque noeud offre.
            hadeslog( 'log', "Découpage du ficher XML." );
            echo "<h4>Extraction des offres contenues dans le fichier</h4>";
            $liaisons = array ();
            $categories = array ();
            $localites = array ();
            $localite_meta = array ();
            $dateheures = array ();
            $tab_off_id = array ();
            $selections = array ();

            echo "Hadès ID N° ";
            do
                {
                $off_current_id = $reader->getAttribute( "id" );

                //Enregistre le l'offre (XML) ou met l'xml à jour dans la table offre_xml
                $str_xml = $reader->readOuterXml();
                $offre_xml = simplexml_load_string( $str_xml );
                $modif_date = $offre_xml->modif_date[0];


                $wpdb->get_results( "INSERT INTO `" . HADESDBPFX . "offre_xml` (`fk_off_id`,`fk_flux_id`,`last_update`,`modif_date`, `xml`) 
                  VALUES(" . $off_current_id . "," . $this->flux_id . ",NOW(), \"" . $modif_date . "\", \"" . addslashes( $str_xml ) . "\") 
                  ON DUPLICATE KEY UPDATE `xml`=\"" . addslashes( $str_xml ) . "\",`modif_date`=\"" . $modif_date . "\"  ;" );

                $this->off_xml_count++;
                echo $off_current_id . ", ";


                $resenfants = $offre_xml->enfants[0]->offre;

                if( $resenfants )
                    {
                    foreach( $resenfants as $value )
                        {
                        $liaisons[] = "(" . $off_current_id . "," . $value["id"] . ",'" . $value["typ"] . "','" . $value["rel"] . "')";
                        }
                    }
                $resparents = $offre_xml->parents[0]->offre;

                if( $resparents )
                    {

                    foreach( $resparents as $value )
                        {
                        $liaisons[] = "(" . $off_current_id . "," . $value["id"] . ",'" . $value["typ"] . "','" . $value["rel"] . "')";
                        }
                    }
                $rescategories = $offre_xml->categories[0]->categorie;

                if( $rescategories )
                    {

                    foreach( $rescategories as $value )
                        {
                        $categories[] = "(" . $off_current_id . ",'" . $value["id"] . "')";
                        }
                    }
                $reslocalisation = $offre_xml->localisation[0]->localite;

                if( $reslocalisation )
                    {

                    foreach( $reslocalisation as $value )
                        {
                        $localites[] = "(" . $off_current_id . "," . $value["id"] . "," . $value->com_id[0] . "," . $value->reg_id[0] . ")";
                        $localite_meta[(string) $value->l_nom[0]] = (object) array ( 'x' => (string) $value->x[0], 'y' => (string) $value->y[0] );
                        }
                    }

                $resselection = $offre_xml->selections[0]->selection;


                if( $resselection )
                    {
                    foreach( $resselection as $value )
                        {
                        $selections[(string) $value["id"]] = "(" . $value["id"] . "," . $value["cl"] . ",'" . $value->lib[0] . "' )";
                        }
                    }



                $reshoraires = $offre_xml->horaires[0]->horaire;

                if( $reshoraires )
                    {

                    foreach( $reshoraires as $horvalue )
                        {
                        foreach( $horvalue->horline as $value )
                            {
                            if( (string) $value->libelle[0] == "date-heure" )
                                $dateheures[] = "(" . $off_current_id . ",
                                    '" . date_eu_to_my( $value->date_deb[0] ) . "',
                                    '" . date_eu_to_my( $value->date_fin[0] ) . "',
                                    '" . $value->jours[0] . "')";
                            }
                        }
                    }

                $tab_off_id[] = $off_current_id;

                ob_flush();
                flush();
                }
            while( $reader->next( 'offre' ) );
            }



        if( count( $tab_off_id ) > 0 )
            {
            $strlist_off_id = implode( ",", $tab_off_id );
            $wpdb->get_results( "DELETE FROM `" . HADESDBPFX . "offre_liee` WHERE fk_off_id IN(" . $strlist_off_id . ") ;" );
            $wpdb->get_results( "DELETE FROM `" . HADESDBPFX . "offre_categories` WHERE fk_off_id IN(" . $strlist_off_id . ") ;" );
            $wpdb->get_results( "DELETE FROM `" . HADESDBPFX . "offre_dateheure` WHERE fk_off_id IN(" . $strlist_off_id . ") ;" );
            $wpdb->get_results( "DELETE FROM `" . HADESDBPFX . "offre_localites` WHERE fk_off_id IN(" . $strlist_off_id . ") ;" );
            }

        if( count( $liaisons ) > 0 )
            {
            $wpdb->get_results( "REPLACE INTO `" . HADESDBPFX . "offre_liee` (`fk_off_id`,`sub_off_id`,`typ`,`rel`) "
                    . " VALUES " . implode( ",", $liaisons ) . " ;" );
            }

        if( count( $localites ) > 0 )
            {
            $wpdb->get_results( "REPLACE INTO `" . HADESDBPFX . "offre_localites` (`fk_off_id`,`fk_loc_id`,`fk_com_id`,`fk_reg_id`) "
                    . " VALUES " . implode( ",", $localites ) . " ;" );

            foreach( $localite_meta as $name => $obj )
                {
                $t = get_term_by( 'name', $name, HADES_TAXO_LOC );
                update_term_meta( $t->term_id, 'x', $obj->x );
                update_term_meta( $t->term_id, 'y', $obj->y );
                }
            }

        if( count( $categories ) > 0 )
            {
            $wpdb->get_results( "REPLACE INTO `" . HADESDBPFX . "offre_categories` (`fk_off_id`,`fk_cat_id`) "
                    . " VALUES " . implode( ",", $categories ) . " ;" );
            }

        if( count( $selections ) > 0 )
            {
            $wpdb->get_results( "INSERT IGNORE INTO `" . HADESDBPFX . "selections` (`sel_id`,`classe`,`libelle`) "
                    . " VALUES " . implode( ",", $selections ) . " ;" );
            }

        if( count( $dateheures ) > 0 )
            {
            $wpdb->get_results( "REPLACE INTO `" . HADESDBPFX . "offre_dateheure` (`fk_off_id`,`date_deb`,`date_fin`,`jours`) "
                    . " VALUES " . implode( ",", $dateheures ) . " ;" );
            }
        hadeslog( 'log', "Nombre d'offres XML insérées dans offres_xml : " . $this->off_xml_count . " ." );
        }

    /*     * *************************************************************************
     * Découpe le contenu xml du flux de suppression enregistre les fragements xml pour chaque offre
     * ************************************************************************ */

    public function set_in_db_suppr()
        {
        hades_set_time( 300 );
        global $wpdb;

        //On verifie que le fichier est défini
        if( !$this->tmpfile )
            {
            hadeslog( 'log', "Le fichier XML  \"" . $this->tmpfile . "\"  de flux n'est pas défini." );
            return FALSE;
            }
        //On ouvre le fichier
        $reader = new XMLReader();
        //$reader->setParserProperty(XMLReader::VALIDATE, TRUE);
        $XMLouvert = $reader->open( $this->tmpfile );

        //On verifie que le fichier est ouvert
        if( !$XMLouvert )
            {
            hadeslog( 'log', "Le fichier XML  \"" . $this->tmpfile . "\"  de flux n'a pu être ouvert." );
            return FALSE;
            }

        //Avance du curseur jusqu'à la première offre
        echo "<h4>Extraction des offres contenues dans le fichier</h4>";
        $off_last = FALSE;
        $this->off_xml_count = 0;
        $ok = TRUE;
        while( $reader->name != 'supressions' && $ok )
            {
            $ok = $reader->read();
            }
        while( $reader->name != 'offre' && $ok )
            {
            $ok = $reader->read();
            }

        $tab_off_id_to_del = array ();

        if( !$ok )
            {
            hadeslog( 'log', "Aucune offre en suppression." );
            echo "<br/>Aucune offre en suppression.<br/>";
            $this->off_xml_count = 0;
            }
        else
            {
            //Parcours chaque noeud offre.
            hadeslog( 'log', "Découpage du ficher XML des suppressions ." );
            echo "<h4>Extraction des offres contenues dans le fichier de suppression</h4>";

            echo "Hadès ID N° ";

			//entete de la requête
			$query_head.="INSERT IGNORE INTO `" . HADESDBPFX . "offre_suppr` (`off_id`,`suppr_date`) VALUES ";
			$query_list="";

			do
                {
                $off_current_id = $reader->getAttribute( "id" );

                //Enregistre l'id et la date de l'offre supprimée dans la table offre_suppr
                $str_xml = $reader->readOuterXml();
                $wpdb->get_results( "INSERT IGNORE INTO `" . HADESDBPFX . "offre_suppr` (`off_id`,`suppr_date`) 
				//Empile les valeurs dans la requête d'ajout
                  VALUES(" . $off_current_id . ", \"" . addslashes( $reader->getAttribute( "suppr_date" ) ) . "\");" );
				$query_list.="(" . $off_current_id . ", \"" . addslashes( $reader->getAttribute( "suppr_date" ) ) . "\"),";

				//Exécutionde la requête toutes les 10000 valeurs
				if($cpt_list++ % 10000 == 0){
					$wpdb->get_results($query_head.substr( $query_list, 0, -1 ).";" );
					$query_list="";
					echo " - <b>Query sent</b> - ";
                    }
                  //$wpdb->get_results( "INSERT IGNORE INTO `" . HADESDBPFX . "offre_suppr` (`off_id`,`suppr_date`) 
                  //VALUES(" . $off_current_id . ", \"" . addslashes( $reader->getAttribute( "suppr_date" ) ) . "\");" );

                $this->off_xml_count++;
                echo $off_current_id . ", ";
                $tab_off_id_to_del[] = $off_current_id;
                ob_flush();
                flush();
                }
            while( $reader->next( 'offre' ) );

			//Exécution de la dernière requête ( moins de 10000 valeurs restantes)
			$wpdb->get_results($query_head.substr( $query_list, 0, -1 ).";" );
			echo " - <b>Query sent</b> - ";

            }


        if( count( $tab_off_id_to_del ) > 0 )
            {
            $strlist_off_id = implode( ",", $tab_off_id_to_del );

            $wpdb->get_results( "DELETE FROM `" . HADESDBPFX . "offre_liee` WHERE fk_off_id IN(" . $strlist_off_id . ") ;" );
            $wpdb->get_results( "DELETE FROM `" . HADESDBPFX . "offre_categories` WHERE fk_off_id IN(" . $strlist_off_id . ") ;" );
            $wpdb->get_results( "DELETE FROM `" . HADESDBPFX . "offre_dateheure` WHERE fk_off_id IN(" . $strlist_off_id . ") ;" );
            $wpdb->get_results( "DELETE FROM `" . HADESDBPFX . "offre_localites` WHERE fk_off_id IN(" . $strlist_off_id . ") ;" );
            $wpdb->get_results( "DELETE FROM `" . HADESDBPFX . "offre_xml` WHERE fk_off_id IN(" . $strlist_off_id . ") ;" );
            }

        //suppression des POSTS signalés par H2O  
        echo "<br/>Suppression des Posts (via Db): ";

        $result_suppr = $wpdb->get_results(
                "SELECT p.post_id "
                . "FROM " . HADESDBPFX . "offre_suppr s "
                . "JOIN " . $wpdb->prefix . "postmeta p ON (s.off_id=p.meta_value AND p.meta_key='hades_id');" );

        foreach( $result_suppr as $post_id )
            {
            hades_set_time( 15 );
            wp_delete_post( $post_id->post_id, $force_delete = false );
            echo $post_id->post_id . ", ";
            }

        hadeslog( 'log', "Nombre d'offres XML supprimées dans offres_xml : " . $this->off_xml_count . " ." );
        }

    /*     * *************************************************************************
     * Recherche et supprime les offres liées (parents ou enfants) qui n'ont pas d'entrée propre dans les flux
     * ************************************************************************ */

    function suppr_offres_ext()
        {
        hades_set_time( 300 );
        global $wpdb;
        echo "<p>Suppression de liaisons hors-flux (off_id):</p>";

        hadeslog( 'log', "Suppression de liaisons hors-flux (off_id)" );
        $offres_ext = $wpdb->get_results( "SELECT t1.fk_off_id as fk_off_id, GROUP_CONCAT( t1.sub_off_id SEPARATOR ',') as sub_off_id, o.xml 
                  FROM " . HADESDBPFX . "offre_liee t1 
                  LEFT JOIN " . HADESDBPFX . "offre_liee t2 ON t1.sub_off_id = t2.fk_off_id
                  LEFT JOIN " . HADESDBPFX . "offre_xml o ON t1.fk_off_id=o.fk_off_id
                  WHERE t2.fk_off_id IS NULL AND t1.typ='l' AND t1.rel!='agenda'
                  GROUP BY t1.fk_off_id
                  ORDER BY t1.fk_off_id; " );
        $sub_off_id_removed = 0;
        foreach( $offres_ext as $offre_ext )
            {
            $tab_ext_id = explode( ',', $offre_ext->sub_off_id );
            $offre_xml = simplexml_load_string( $offre_ext->xml );
            if( $offre_xml )
                {
                foreach( $tab_ext_id as $ext_id )
                    {
                    $node = $offre_xml->xpath( "enfants/offre[@id='" . $ext_id . "']|parents/offre[@id='" . $ext_id . "']" );
                    if( count( $node ) > 0 && $node[0]["id"] > 0 )
                        {
                        echo "[/" . $node[0]["id"] . "], ";
                        unset( $node[0][0] );
                        $sub_off_id_removed++;
                        }
                    }

                $wpdb->update( HADESDBPFX . "offre_xml", array ( 'xml' => $offre_xml->asXML() ), array ( 'fk_off_id' => $offre_ext->fk_off_id ) );
                }
            ob_flush();
            flush();
            }
        hadeslog( 'log', "Suppression de " . $sub_off_id_removed . " liaisons hors-flux (off_id)" );
        }

    /*     * *************************************************************************
     * Recherche et importe les offres liées (parents ou enfants) qui n'ont pas d'entrée propre dans les flux
     * ************************************************************************ */

    function import_offres_ext()
        {
        hades_set_time( 300 );
        global $wpdb;
        echo "<p>Importation des liaisons hors-flux (off_id):</p>";
        echo "<p>[ Fonction non-implémentée ]</p>";
        }

    function loadallflux( $reset = FALSE )
        {
        global $wpdb;
        global $hades_syn_text_log;


        ob_flush();
        flush();
        if( $reset )
            {
            //$o = new hades_offres();
            echo "<div class='updated notice'><h3>Suppression de toutes les offres existantes</h3>";
            //$o->delete_all_nodes();
            $from_datetime_all = "2000-01-01 00:00:00";
            $from_datetime_suppr = date( "Y-m-d" ) . " 00:00:00";
            echo "</div>";
            }
        else
            {
            $from_datetime_all = $this->settings['flux_from_datetime'];
            $from_datetime_suppr = $this->settings['flux_from_datetime'];

            if( $hades_syn_text_log )
                {
                echo "CRON de Mise à jour des offres modifiées après le " . $from_datetime_all . "\r\n";
                }
            else
                {
                echo "<div class='updated notice'><h3>Mise à jour des offres modifiées après</h3>";
                echo "Date:" . $from_datetime_all;
                echo "</div>";
                }

            if( !$from_datetime_all )
                {
                die( "date limite  de mise à jour incorrecte." );
                }
            }

        ob_flush();
        flush();

        $fluxes = $this->get_all_flux();

        foreach( $fluxes as $flux )
            {
            $this->get_flux( $flux->flux_id );
            if( $this->actif )
                {
                $this->from_datetime = $from_datetime_all;
                if( $hades_syn_text_log )
                    {
                    echo " *** Flux N°" . $flux->flux_id . " date limite :" . $this->from_datetime . " *** \r\n";
                    }
                else
                    {
                    echo "<div class='updated notice'><h3>Flux N°" . $flux->flux_id . " date limite :" . $this->from_datetime . "</h3>";
                    }


                //Téléchargement des fichiers de flux
                $this->download();
                
                if( $hades_syn_text_log )
                    {
                    echo $this->xml ? "Réception d'un fichier XML\r\n" : "";
                    echo $this->compress ? "Réception d'un fichier compressé\r\n" : "";
                    echo "- Dossier temporaire : " . $this->tempdir . "\r\n";
                    echo "- Fichier : " . $this->tmpfile . "\r\n";
                    echo "-Taille : " . ($this->nboctets < 1000 ? ($this->nboctets . "Octets") : intval( $this->nboctets / 1000 ) . "Ko") . "\r\n\r\n";
                    }
                else
                    {
                    echo $this->xml ? "<h4> Réception d'un fichier XML</h4>" : "";
                    echo $this->compress ? "<h4> Réception d'un fichier compressé </h4>" : "";
                    echo "<ul><li><em> Dossier temporaire : </em>" . $this->tempdir . "</li>";
                    echo "<li><em>Fichier : </em>" . $this->tmpfile . " </li>";
                    echo "<li><em>Taille : </em>" . ($this->nboctets < 1000 ? ($this->nboctets . "Octets") : intval( $this->nboctets / 1000 ) . "Ko") . "</li></ul>";
                    }


                //Découpage des fichiers de flux en lignes dans la table offre_xml 
                $this->set_in_db_xml();
                if( $hades_syn_text_log )
                    {
                    echo "=>" . $this->off_xml_count . " Offres XML enregistrées \r\n";
                    }
                else
                    {
                    echo "<ul><li> <b>" . $this->off_xml_count . "</b> Offres XML enregistrées </li>";
                    }


                //Sauvegarde des méta données du flux
                $this->temps = max( array ( $this->temps_load + $this->temps_parse, $this->temps ) );
                $this->poids = max( array ( $this->nboctets, $this->poids ) );
                $this->nb_offres = max( array ( $this->off_xml_count, $this->nb_offres ) );
                $this->save( $this );

                if( $hades_syn_text_log )
                    {
                    echo "\r\n";
                    }
                else
                    {
                    echo "</div>";
                    }

                ob_flush();
                flush();
                }
            else
                {
                if( $hades_syn_text_log )
                    {
                    echo "\r\n";
                    }
                else
                    {
                    echo "</div>";
                    }
                echo "<div class='updated notice'><h3>Flux N°" . $flux->flux_id . " : Désactivé </h3></div>";
                }
            }

        //suppresion des liaisons avec des offres externes    
        if( $hades_syn_text_log )
            {
            echo " *** Liaisons d'offres hors flux *** \r\n";
            }
        else
            {
            echo " <div class='updated notice'><h3>Liaisons d'offres hors flux</h3>";
            }

        $this->suppr_offres_ext();
        if( $hades_syn_text_log )
            {
            echo "\r\n";
            }
        else
            {
            echo "</div>";
            }

        ob_flush();
        flush();
        /*
          $pt = new hades_parser();
          $pt->set_TODOlist( array ( "do_offre_index", "do_offre_localites", "do_offre_categories", "do_offre_dateheure", "do_offre_xslt", "do_offre_node", "dom_liste_index" ) );
          $pt->parse();
          echo "<ul><li> <b>" . $pt->off_total_count . "</b> Offres traitées </li>";
         */

        //traitement des offres périmées
        if( $hades_syn_text_log )
            {
            echo " *** Offres périmées ou supprimées ***  \r\n";
            }
        else
            {
            echo "<div class='updated notice'><h3>Offres périmées ou supprimées</h3> ";
            }

        $this->from_datetime = $from_datetime_suppr;
        $this->flux_id = "suppr";
        $this->url = HADES_FEED_URL . "?tbl=xmlcomplet&cat_id=none";

        if( $hades_syn_text_log )
            {
            echo "Flux suppression date limite :" . $this->from_datetime . "\r\n";
            }
        else
            {
            echo "<h4>Flux suppression date limite :" . $this->from_datetime . "</h4>";
            }

        //Téléchargement des fichiers de flux
        $this->download();

        /* $this->flux_suppr_reader(); */
        $this->set_in_db_suppr();
        $this->settings['flux_from_datetime'] = date( "Y-m-d H:i:s" );
        update_option( 'hades_settings', $this->settings );

        if( $hades_syn_text_log )
            {
            echo "\r\n";
            }
        else
            {
            echo "</div>";
            }

        ob_flush();
        flush();
        }
        
    function loadoneflux( $flux )
        {
        global $wpdb;
        global $hades_syn_text_log;

        ob_flush();
        flush();

            $this->get_flux( $flux->flux_id );
            if( $this->actif )
                {
                if( $hades_syn_text_log )
                    {
                    echo " *** Flux N°" . $flux->flux_id . "  *** \r\n";
                    }
                else
                    {
                    echo "<div class='updated notice'><h3>Flux N°" . $flux->flux_id . " </h3>";
                    }


                //Téléchargement des fichiers de flux
                $this->download();
                if( $hades_syn_text_log )
                    {
                    echo $this->xml ? "Réception d'un fichier XML\r\n" : "";
                    echo $this->compress ? "Réception d'un fichier compressé\r\n" : "";
                    echo "- Dossier temporaire : " . $this->tempdir . "\r\n";
                    echo "- Fichier : " . $this->tmpfile . "\r\n";
                    echo "-Taille : " . ($this->nboctets < 1000 ? ($this->nboctets . "Octets") : intval( $this->nboctets / 1000 ) . "Ko") . "\r\n\r\n";
                    }
                else
                    {
                    echo $this->xml ? "<h4> Réception d'un fichier XML</h4>" : "";
                    echo $this->compress ? "<h4> Réception d'un fichier compressé </h4>" : "";
                    echo "<ul><li><em> Dossier temporaire : </em>" . $this->tempdir . "</li>";
                    echo "<li><em>Fichier : </em>" . $this->tmpfile . " </li>";
                    echo "<li><em>Taille : </em>" . ($this->nboctets < 1000 ? ($this->nboctets . "Octets") : intval( $this->nboctets / 1000 ) . "Ko") . "</li></ul>";
                    }


                //Découpage des fichiers de flux en lignes dans la table offre_xml 
                $this->set_in_db_xml();
                if( $hades_syn_text_log )
                    {
                    echo "=>" . $this->off_xml_count . " Offres XML enregistrées \r\n";
                    }
                else
                    {
                    echo "<ul><li> <b>" . $this->off_xml_count . "</b> Offres XML enregistrées </li>";
                    }


                //Sauvegarde des méta données du flux
                $this->temps = max( array ( $this->temps_load + $this->temps_parse, $this->temps ) );
                $this->poids = max( array ( $this->nboctets, $this->poids ) );
                $this->nb_offres = max( array ( $this->off_xml_count, $this->nb_offres ) );
                $this->save( $this );

                if( $hades_syn_text_log )
                    {
                    echo "\r\n";
                    }
                else
                    {
                    echo "</div>";
                    }

                ob_flush();
                flush();
                }
            else
                {
                if( $hades_syn_text_log )
                    {
                    echo "\r\n";
                    }
                else
                    {
                    echo "</div>";
                    }
                echo "<div class='updated notice'><h3>Flux N°" . $flux->flux_id . " : Désactivé </h3></div>";
                }
 
        //suppresion des liaisons avec des offres externes    
        if( $hades_syn_text_log )
            {
            echo " *** Liaisons d'offres hors flux *** \r\n";
            }
        else
            {
            echo " <div class='updated notice'><h3>Liaisons d'offres hors flux</h3>";
            }

        $this->suppr_offres_ext();
        if( $hades_syn_text_log )
            {
            echo "\r\n";
            }
        else
            {
            echo "</div>";
            }

            
        ob_flush();
        flush();
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
