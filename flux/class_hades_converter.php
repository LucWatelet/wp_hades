<?php
class Hades_Converter
{
    public static $instance;
    public $osx;
    private $limite_deb;
    private $limite_test;
    private $limite_fin;
    private $settings;
    private $xslt_act_ind;
    private $supp_selections;
    public $geo_loc;

    public function __construct()
    {
        //url de l'upload dir pour les pictos
        $upload_dir = wp_upload_dir();

        //calcule des dates multiple à partir d'aujourd'hui
        $this->limite_deb = date('Y-m-d');
        $this->limite_test = date('Y-m-d', (time() + 2 * (60 * 60 * 24 * 365)));
        $this->limite_fin = date('Y-m-d', (time() + (60 * 60 * 24 * 365)));
        $this->settings = get_option($option = 'hades_settings'); // FIXME: what's left of this ?

        //chargement de l'XSLT pour le champ content
        /*$this->xslt_content = new XSLTProcessor();
        $this->xslt_content->registerPHPFunctions();

        $content_docxsl = new DOMDocument();
        $content_docxsl->load(HADES_DIR . '/flux/xslt/champ_content.xsl');
        $this->xslt_content->importStylesheet($content_docxsl);
        $this->xslt_content->setParameter("", "lg", $this->settings['langue']);
        $this->xslt_content->setParameter("", "year_now", date("Y"));
        $this->xslt_content->setParameter("", "picto_path", $upload_dir['baseurl']."/pictos/");
        $this->xslt_content->setProfiling('profiling.txt');*/

        //chargement de l'XSLT pour le champ activité individuelle
        /*$this->xslt_act_ind = new XSLTProcessor();
        $this->xslt_act_ind->registerPHPFunctions();

        $act_ind_docxsl = new DOMDocument();
        $act_ind_docxsl->load(HADES_DIR . '/flux/xslt/act_ind.xsl');
        $this->xslt_act_ind->importStylesheet($act_ind_docxsl);
        $this->xslt_act_ind->setParameter("", "lg", $this->settings['langue']);
        $this->xslt_act_ind->setParameter("", "year_now", date("Y"));
        $this->xslt_act_ind->setParameter("", "picto_path", $upload_dir['baseurl']."/pictos/");
        $this->xslt_act_ind->setProfiling('profiling.txt');*/

        //chargement de l'XSLT pour le champ agenda
        /*$this->xslt_agenda = new XSLTProcessor();
        $this->xslt_agenda->registerPHPFunctions();

        $agenda_docxsl = new DOMDocument();
        $agenda_docxsl->load(HADES_DIR . '/flux/xslt/agenda.xsl');
        $this->xslt_agenda->importStylesheet($agenda_docxsl);
        $this->xslt_agenda->setParameter("", "lg", $this->settings['langue']);
        $this->xslt_agenda->setParameter("", "year_now", date("Y"));
        $this->xslt_agenda->setParameter("", "picto_path", $upload_dir['baseurl']."pictos/");
        $this->xslt_agenda->setProfiling('profiling.txt');*/

        //chargement de l'XSLT pour le champ production
        /*$this->xslt_production = new XSLTProcessor();
        $this->xslt_production->registerPHPFunctions();

        $production_docxsl = new DOMDocument();
        $production_docxsl->load(HADES_DIR . '/flux/xslt/production.xsl');
        $this->xslt_production->importStylesheet($production_docxsl);
        $this->xslt_production->setParameter("", "lg", $this->settings['langue']);
        $this->xslt_production->setParameter("", "year_now", date("Y"));
        $this->xslt_production->setParameter("", "picto_path", $upload_dir['url']."pictos/");
        $this->xslt_production->setProfiling('profiling.txt');*/

        $this->geo_loc = array();

        if (isset($this->settings['tag_selection'])) {
            $this->supp_selections = explode(",", $this->settings['tag_selection']);
        }
    }

    public function convert_xml_to_wp($hades_id)
    {
        global $wpdb;
        echo "\r\n<p>Hadès ID N°" . $hades_id. "lg:".$this->settings['langue'];

        //recherche des Posts suivant l'identifiant Hadès
        hades_set_time(60);

        //les posts correspondant à hades_id
        $posts_with_id_hades = get_posts(array(
            'numberposts' => -1,
            'post_type' => HADES_CPT,
            'meta_key' => 'hades_id',
            'meta_value' => $hades_id
        ));

        //les nodes correspondant à hades_id
        $result = $wpdb->get_results("
            SELECT  n.`nid`,
                n.`synopen`,
                n.`titre`,
                n.`dateheure`,
                x.`xml`,
                x.`fk_off_id`,
                x.`last_update`,
                x.`fk_flux_id`
            FROM `" . HADESDBPFX . "offre_xml` x
            LEFT OUTER JOIN `" . HADESDBPFX . "offre_node` n
            ON x.`fk_off_id` = n.`off_id`
            WHERE fk_off_id=" . $hades_id . "     
            ORDER BY n.`dateheure`");

        $this->osx = simplexml_load_string($result[0]->xml);

        $post_to_set['post_type'] = $this->post_type();
        $post_to_set['post_title'] = $this->post_title();
        /*$post_to_set['post_content'] = $this->post_content();*/
        $post_to_set['post_excerpt'] = (string) @$this->osx->xpath("descriptions/description[lib='info']/texte[@lg='"
            . $this->settings['langue']."']")[0] . " "
            . (string) @$this->osx->xpath("descriptions/description[lib='general']/texte[@lg='"
            . $this->settings['langue']."']")[0];
        $post_to_set['post_author'] = get_option($option = 'hades_user');
        $post_to_set['post_status'] = 'publish';
        $post_to_set['post_date'] = (string) $this->osx->modif_date[0];
        $post_to_set['post_category'] = $this->post_category();

        echo " (C" . implode(',', $post_to_set['post_category']) . ") ==> ";

        $post_to_set['meta_input']['hades_id'] = (int) $this->osx['id'];
        $post_to_set['meta_input']['date_create'] = (string) @$this->osx->cree_date[0];
        $post_to_set['meta_input']['gps_x'] = (float) @$this->osx->geocodes[0]->geocode[0]->x[0];
        $post_to_set['meta_input']['gps_y'] = (float) @$this->osx->geocodes[0]->geocode[0]->y[0];
        $post_to_set['meta_input']['libregratuit'] = count($this->osx->xpath("attributs/attribut[@id='acces_gratui']"));

        $contact = $this->post_contact();
        $post_to_set['meta_input']['contact'] = @$contact['body'];
        $post_to_set['meta_input']['url'] = @$contact['url'];
        $post_to_set['meta_input']['email'] = @$contact['E-mail'];
        $post_to_set['meta_input']['tel'] = @$contact['Tél'];
        $post_to_set['meta_input']['cp'] = @$contact['cp'];
        $post_to_set['meta_input']['adresse'] = @$contact['adresse'];

        $post_to_set['meta_input']['localite_commune'] = $this->post_localite_commune();

        $post_to_set['meta_input']['toutes_dates'] = (string) @$this->osx->xpath("horaires/horaire[lib='Dates et heures']/texte[@lg='"
            .@$this->settings['langue']
            ."']")[0];
        $lcpn = $this->osx->xpath("//attributs/attribut[@id='lb_lcpn']/val/text()"); // returns an array
        if (!empty($lcpn)) {
            $post_to_set['meta_input']['lcpn'] = (string) $lcpn[0];
        }

        if (isset($this->osx->enfants->offre[0])) {
            /*    $post_to_set['meta_input']['sub_act_ind'] = $this->post_sub_act_ind();
                $post_to_set['meta_input']['sub_agenda'] = $this->post_sub_agenda();
                $post_to_set['meta_input']['sub_production'] = $this->post_sub_production();*/
        }
        $post_to_set['meta_input']['sub_annexe'] = $this->post_sub_annexe();

        $cf_remove_list = array();
        $categories_cf = Atlb\Hades\get_categories_custom_field_from_xml_offer($this->osx);
        if ($categories_cf) {
            $post_to_set['meta_input']['hades_categories'] = $categories_cf;
        } else {
            array_push($cf_remove_list, 'hades_categories');
        }

        $lots = array(
            'lot_accueil' => 'accueil',
            'lot_activite' => 'activities',
            'lot_capacite' => 'capacities',
            'lot_carto' => 'map',
            'lot_cuisine' => 'food',
            'lot_descript' => 'description',
            'lot_equip' => 'equipemnt',
            'lot_equip_grp' => 'equipemnt_grp',
            'lot_info' => 'information',
            'lot_horaire' => 'openings',
            'lot_label' => 'labels',
            'lot_mice' => 'mice',
            'lot_restrict' => 'constraints',
            'lot_ser_grp' => 'services_grp',
            'lot_service' => 'services',
            'lot_tarif'=>'tarif'
        );
        foreach ($lots as $lot_name => $cf_name) {
            $cf = Atlb\Hades\get_generic_custom_field_from_xml_offer($this->osx, $lot_name);
            if ($cf) {
                $post_to_set['meta_input']['lot_' . $cf_name] = $cf;
            } else {
                array_push($cf_remove_list, 'lot_'. $cf_name);
            }
        }

        $medias_cf = Atlb\Hades\get_medias_custom_field_from_xml_offer($this->osx);
        if ($medias_cf) {
            $post_to_set['meta_input']['medias'] = $medias_cf;
        } else {
            array_push($cf_remove_list, 'medias');
        }

        $contact_cf = Atlb\Hades\get_contact_custom_field_from_xml_offer($this->osx);
        if ($contact_cf) {
            $post_to_set['meta_input']['lot_contact'] = $contact_cf;
        }

        $titles_cf = Atlb\Hades\get_titles_custom_field_from_xml_offer($this->osx);
        if ($titles_cf) {
            $post_to_set['meta_input']['titles'] = $titles_cf;
        } else {
            array_push($cf_remove_list, 'titles');
        }

        $parents_id = array_map(
            function ($element) {
                return (int) $element->id;
            },
            $this->osx->xpath('//offre[parent::parents]/@id')
        );
        $related_offers = array();
        $related_offers_xml = $this->osx->xpath('//offre[parent::parents|parent::enfants]');
        foreach ($related_offers_xml as $offer) {
            $attributes = $offer->attributes();
            $offer_id = (int) $attributes['id'];
            $related_offers[$offer_id]['id'] = $offer_id;
            if (in_array($offer_id, $parents_id)) {
                $related_offers[$offer_id]['relationship_position'] = 'parent';
            } else {
                $related_offers[$offer_id]['relationship_position'] = 'child';
            }
            if (isset($attributes['typ'])) {
                $related_offers[$offer_id]['relationship_weight'] = (string) $attributes['typ'];
            }
            if (isset($attributes['rel'])) {
                $related_offers[$offer_id]['relationship_type'] = (string) $attributes['rel'];
            }
        }
        $post_to_set['meta_input']['related_offers'] = json_encode($related_offers);

        foreach ($related_offers as $offer_id => $offer) {
            $xml_offer = $this->osx->xpath('//offre[@id="' . $offer['id']  . '"]');
            $xml_offer = $xml_offer[0];
            $titles_cf = Atlb\Hades\get_titles_custom_field_from_xml_offer($xml_offer);
            if ($titles_cf) {
                $post_to_set['meta_input'][$offer_id . '_titles'] = $titles_cf;
            } else {
                array_push($cf_remove_list, $offer_id . '_titles');
            }
            foreach ($lots as $lot_name => $cf_name) {
                $cf = Atlb\Hades\get_generic_custom_field_from_xml_offer($xml_offer, $lot_name);
                if ($cf) {
                    $post_to_set['meta_input'][$offer_id . '_' . 'lot_' . $cf_name] = $cf;
                } else {
                    array_push($cf_remove_list, $offer_id . '_' . 'lot_' . $cf_name);
                }
            }
            $medias_cf = Atlb\Hades\get_medias_custom_field_from_xml_offer($xml_offer);
            if ($medias_cf) {
                $post_to_set['meta_input'][$offer_id . '_' . 'medias'] = $medias_cf;
            } else {
                array_push($cf_remove_list, $offer_id . '_' . 'medias');
            }
        }

        $sel = $this->post_selection();
        $com = $this->post_commune();
        $loc = $this->post_localite();
        $taxos = array_merge($sel, $com, $loc);
        $post_to_set['tax_input'] = $taxos;

        echo " Sel(" . implode(', ', $sel['selection']) . ") - ";

        if (is_countable($com['commune'])) {
            if (count($com['commune']) > 0) {
                echo " Com(" . implode(', ', $com['commune']) . ") - ";
            }
        }
        if (is_countable($loc['localite'])) {
            if (count($loc['localite']) > 0) {
                echo " Loc(" . implode(', ', $loc['localite']) . ") - ";
            }
        }
        $g_locs = $this->osx->localisation[0]->localite;
        if (is_countable($g_locs)) {
            foreach ($g_locs as $g_loc) {
                $this->geo_loc[(string) $g_loc->l_nom[0]]['x'] = (string) $g_loc->x;
                $this->geo_loc[(string) $g_loc->l_nom[0]]['y'] = (string) $g_loc->y;
            }
        }

        /* *****************************************************************************
         *  TRAITEMENT DES MEDIAS IMAGES
         * **************************************************************************** */

        $medias = $this->osx->xpath("medias/media[@ext='jpg']/url");
        if (count($medias) >= 1) {
            $urlmedia = (string) $medias[0];
            $thumbnail_attach_id = $this->get_post_thumbnail($urlmedia, $post_to_set['post_title'], $posts_with_id_hades[0]->ID);
        }

        /* *****************************************************************************
         *  TRAITEMENT DATE-HEURES ET POST MULTIPLES
         * **************************************************************************** */

        $post_pile = array();
        $hadeslist = array();
        foreach ($posts_with_id_hades as $post_with_id_hades) {
            $index = get_post_meta($post_with_id_hades->ID, 'dateheure_id', true);
            $post_pile[$index] = $post_with_id_hades->ID;
        }
        //echo "<hr/>PostPile " . count( $post_pile ) . " dates : <br/> ";
        //print_r( $post_pile );

        $horls = $this->osx->xpath("horaires/horaire/horline[libelle='date-heure' and ouvert='1']");

        //echo "<hr/>HorLIgne " . count( $horls ) . " dates : <br/> ";
        //var_dump( $horls );

        if (count($horls) >= 1) {
            $hadeslist = $this->multiply_by_dateheure($hades_id, $horls);
        //echo "multiply<br/>";
        } else {
            $hadeslist = array( (object) array( 'id' => $hades_id . '#' ) );
            //echo "unique<br/>";
        }

        //echo "<hr/>Hadeslist contient " . count( $hadeslist ) . " dates : <br/>  ";
        //var_dump( $hadeslist );

        foreach ($hadeslist as $dateheure) {
            hades_set_time(15);
            if (key_exists($dateheure->id, $post_pile)) {
                //echo "<hr/>Mise à jour du Post Hadès N°" . $hade_id . " / " ; var_dump($dateheure,1);echo " <br/>";
                $post_id = $post_pile[$dateheure->id];
                $post_to_set['meta_input']['dateheure_id'] = $dateheure->id;
                if (isset($dateheure->date_deb)) {
                    $post_to_set['meta_input']['date_deb'] = $dateheure->date_deb;
                }
                if (isset($dateheure->date_fin)) {
                    //si la fin est passée, on passe au suivant
                    if ($dateheure->date_fin < date("Y-m-d")) {
                        echo " -" . $dateheure->date_fin;
                        continue;
                    }
                    $post_to_set['meta_input']['date_fin'] = $dateheure->date_fin;
                }
                if (isset($dateheure->date_long)) {
                    $post_to_set['meta_input']['date_long'] = $dateheure->date_long;
                }
                if (isset($dateheure->date_long) && $dateheure->date_long > @$this->settings['event_long']) {
                    $post_to_set['meta_input']['date_tjaff'] = "1";
                } else {
                    $post_to_set['meta_input']['date_tjaff'] = "0";
                }

                $post_to_set['meta_input']['date_titre'] = date_vers_texte(@$dateheure->date_deb, @$dateheure->date_fin);
                //echo "<br/>".$post_to_set['meta_input']['date_titre'];

                $post_to_set['ID'] = $post_id;
                // if ( '1' === 'LCPN' ) { $post_to_set['post_status'] = 'draft'; }
                $resultat = wp_update_post($post_to_set, true);
                foreach ($cf_remove_list as $meta_key) {
                    delete_post_meta($post_id, $meta_key);
                }

                if (is_wp_error($resultat)) {
                    echo "\r\n L'update de  " . $post_id . " a échoué. \r\n";
                    var_dump($resultat);
                }

                wp_set_post_categories($post_id, $post_to_set['post_category']);
                foreach ($post_to_set["tax_input"] as $taxonomie => $taxons) {
                    wp_set_post_terms($post_id, $taxons, $taxonomie);
                }
                set_post_thumbnail($post_id, @$thumbnail_attach_id);

                //print_r( $post_to_set );
                unset($post_pile[$dateheure->id]);
                echo " ~" . $post_id;
            } else {
                //echo "Création du post dateheure_id " . $hade_id . " / " ; var_dump($dateheure,1); echo " <br/>";
                $post_to_set['meta_input']['dateheure_id'] = $dateheure->id;
                if ($dateheure->date_deb) {
                    $post_to_set['meta_input']['date_deb'] = $dateheure->date_deb;
                }
                if ($dateheure->date_fin) {
                    $post_to_set['meta_input']['date_fin'] = $dateheure->date_fin;
                }
                if ($dateheure->date_long) {
                    $post_to_set['meta_input']['date_long'] = $dateheure->date_long;
                }
                if ($dateheure->date_long > $this->settings['event_long']) {
                    $post_to_set['meta_input']['date_tjaff'] = "1" . $this->settings['event_long'];
                } else {
                    $post_to_set['meta_input']['date_tjaff'] = "0" . $this->settings['event_long'];
                }

                $post_to_set['meta_input']['date_titre'] = date_vers_texte($dateheure->date_deb, $dateheure->date_fin);
                //echo "<br/>".$post_to_set['meta_input']['date_titre'];

                $post_to_set['ID'] = null;
                $post_id = wp_insert_post($post_to_set);

                $post_to_set['ID'] = $post_id;
                //if ( '1' === 'LCPN' ) { $post_to_set['post_status'] = 'draft'; }
                $resultat = wp_update_post($post_to_set, false);
                if (is_wp_error($resultat)) {
                    echo "\r\n L'update de  " . $post_id . " a échoué. \r\n";
                    var_dump($resultat);
                }

                wp_set_post_categories($post_id, $post_to_set['post_category']);
                foreach ($post_to_set["tax_input"] as $taxonomie => $taxons) {
                    wp_set_post_terms($post_id, $taxons, $taxonomie);
                }
                //print_r( $post_to_set );
                set_post_thumbnail($post_id, $thumbnail_attach_id);

                echo " +" . $post_id;
                //update_postmeta_cache(array($post_id));
            }
        }
        unset($dateheure);
        unset($hadeslist);

        if (count($post_pile)) {
            echo "\r\n Suppression des Posts " . implode(',', $post_pile);
        }
        foreach ($post_pile as $post_id) {
            hades_set_time(15);
            wp_delete_post($post_id, $force_delete = true);
        }

        return true;
    }

    /*  ******************************************************************************
     *  CHAMPS DE POST
     * **************************************************************************** */

    private function post_type()
    {
        return HADES_CPT;
    }

    private function post_title()
    {
        $cat = $this->osx->categories[0]->categorie['id'];
        $label = "";
        $capacité = "";

        $nbs = $this->osx->xpath("attributs/attribut[@id='lb_cgt']/val");
        $nb = (int) @$nbs[0];

        switch ($cat) {
            case "hotel":
                $label = str_repeat("&#x2B50;", $nb);
                $nbp = $this->osx->xpath("attributs/attribut[@id='nb_ps']/val");
                $capacité = "(" . $nbp[0] . "&#x1F465;)";
                break;
            case "mbl_trm":
            case "mbl_vac":
                $label = str_repeat("&#x1F511;", $nb);
                $nbpas = $this->osx->xpath("attributs/attribut[@id='nb_ps_min']/val");
                $nbpa = (int) $nbpas[0];
                $nbpbs = $this->osx->xpath("attributs/attribut[@id='nb_ps_max']/val");
                $nbpb = (int) $nbpbs[0];
                if ($nbpa != $nbpb) {
                    $capacité = "(" . $nbpa . "&#x2192;" . $nbpb . "&#x1F465;)";
                } else {
                    $capacité = "(" . $nbpb . "&#x1F465;)";
                }
                break;
            case "git_citad":
            case "git_big_cap":
            case "git_rural":
            case "chbre_chb":
            case "chbre_hote":
            case "git_ferme":
            case "git_rural":
                $label = str_repeat("&#x1F33F;", $nb);
                $nbpas = $this->osx->xpath("attributs/attribut[@id='nb_ps_min']/val");
                $nbpa = (int) $nbpas[0];
                $nbpbs = $this->osx->xpath("attributs/attribut[@id='nb_ps_max']/val");
                $nbpb = (int) $nbpbs[0];
                if ($nbpa != $nbpb && $nbpa > 0) {
                    $capacité = "(" . $nbpa . "&#x2192;" . $nbpb . "&#x1F465;)";
                } else {
                    $capacité = "(" . $nbpb . "&#x1F465;)";
                }
                break;
            case "camping":
                $label = str_repeat("&#x2B50;", $nb);
                $nbp = $this->osx->xpath("attributs/attribut[@id='nb_emp_sai']/val");
                $capacité = "(" . $nbp[0] . "&#x26FA;)";
                break;
            default:
                str_repeat("&#x1F506;", $nb);
                break;
        }
        $titre = (string) $this->osx->xpath("titre[@lg='fr']")[0];

        return $titre;
        echo  " ".$titre." ";

        return $titre . "  " . $label . " " . $capacité;
            
        /*$titre = (string) $this->osx->xpath( "titre[@lg='".$this->settings['langue']."']" )[0];
        echo  " ".$titre." ";
        return $titre;*/
    }

    private function post_category()
    {
        global $wpdb;
        static $cat_list;

        if (!is_array($cat_list)) {
            $cat_list = array();
            $results = $wpdb->get_results("
                SELECT fk_cat_id ,xpath, fk_tid
                FROM `" . HADESDBPFX . "taxo_cat`
                ORDER BY fk_cat_id");
            foreach ($results as $value) {
                if (!is_array(@$cat_list[$value->fk_cat_id])) {
                    $cat_list[$value->fk_cat_id] = array();
                }
                $cat_list[$value->fk_cat_id][] = $value;
            }
        }

        $post_category = array();
        foreach ($this->osx->categories[0]->categorie as $cat) {
            if (is_array($cat_list) && is_array(@$cat_list[(string) $cat['id']])) {
                foreach ($cat_list[(string) $cat['id']] as $cat_obj) {
                    if (!$cat_obj->xpath) {
                        $post_category[] = (integer) $cat_obj->fk_tid;
                    } else {
                        if (@$this->osx->xpath($cat_obj->xpath)[0]) {
                            $post_category[] = (integer) $cat_obj->fk_tid;
                        }
                    }
                }
            }
        }

        return array_unique($post_category);
    }

    private function post_content()
    {
        $content = $this->xslt_content->transformToXml($this->osx);
        $body = "<div class='" . HADES_CPT . "'>";
        $body.=$content;
        $body.="</div>";

        return $body;
    }

    private function post_sub_act_ind()
    {
        $act_ind = $this->xslt_act_ind->transformToXml($this->osx);

        return $act_ind;
    }

    private function post_sub_agenda()
    {
        $agenda = "";
        if ($this->osx->xpath("/offre/enfants/offre/horaires/horaire/horline[libelle='date-heure']")) {
            echo "enfants event détectés";
            $agenda = $this->xslt_agenda->transformToXml($this->osx);
        }

        return $agenda;
    }

    private function post_sub_production()
    {
        $production = "";
        if ($this->osx->xpath("/offre/enfants/offre[@rel='production']")) {
            echo "enfants production détectés";
            $production = $this->xslt_production->transformToXml($this->osx);
        }

        return $production;
    }

    private function post_sub_annexe()
    {
        $act_ind=null;
		/*
        $xslt_act_ind = new XSLTProcessor();
        $act_ind_docxsl = new DOMDocument();
        $act_ind_docxsl->load( HADES_DIR . '/flux/xslt/annexe.xsl' );
        $xslt_act_ind->importStylesheet( $act_ind_docxsl );
        $xslt_act_ind->setParameter( "", "lg", $this->settings['langue'] );
        $xslt_act_ind->setProfiling( 'profiling.txt' );
        $act_ind = $xslt_act_ind->transformToXml( $this->osx ); */

        return $act_ind;
    }

    private function post_contact()
    {
        $txt = "";
        $ret = array();
        //sélectionne le premier contact public trouvé dans l'offre puis dans un parent
        $con = @$this->osx->xpath("contacts/contact[lib='contact'] | parents/offre/contacts/contact[lib='contact']")[0];

        /* Contact public */
        if ($con) {
            $ctxt = array();
            if ((string) $con->noms[0] || (string) $con->prenoms[0]) {
                $ctxt[] = (string) $con->civilite[0] . " " . (string) $con->noms[0] . " " . (string) $con->prenoms[0];
            }
            if ((string) $con->societe[0]) {
                $ctxt[] = (string) $con->societe[0];
            }
            if (count($ctxt) > 0) {
                $txt .="<p>" . implode("<br/>", $ctxt) . "</p>";
            }

            $coms = $con->communications->communication;
            if ($coms) {
                $com_txt = array();
                foreach ($coms as $com) {
                    switch ($com['typ']) {
                        case "url":
                            $com_txt[$com['tri'] . rand(100, 999)] = (string) $com->xpath("lib[@lg='".$this->settings['langue']."']")[0] . ":" .
                                    "<a href='" . (string) $com->val[0] . "'>" . (string) $com->val[0] . "</a>";
                            $ret['url'] = (string) $com->val[0];

                            break;
                        default:
                            $com_txt[$com['tri'] . rand(100, 999)] = (string) @$com->xpath("lib[@lg='".$this->settings['langue']."']")[0] . ":" . (string) $com->val[0];
                            $ret[(string) $com->xpath("lib[@lg='".$this->settings['langue']."']")[0]] = (string) @$com->val[0];

                            break;
                    }
                }
				if(is_array($com_txt)){
					$com_txt=array_filter($com_txt);	
					ksort($com_txt);
					}
                if (count($com_txt) > 0) {
                    $txt .="<p>" . implode("<br/>", $com_txt) . "</p>";
                }
            }
        }

        /* Adresse principale */
        $con2 = @$this->osx->xpath("contacts/contact[lib='ap'] | parents/offre[@rel='agenda']/contacts/contact[lib='ap']")[0];

        if ($con2) {
            $adtxt = array();
            if ((string) @$con2->societe[0] && (string) $con2->societe[0] != (string) $con->societe[0]) {
                $adtxt[] = (string) $con2->societe[0];
            }
            if ((string) @$con2->adresse[0]) {
                $adtxt[] = (string) $con2->adresse[0] . ($con2->numero[0] ? ", " . (string) $con2->numero[0] . " " . (string) $con2->boite[0] : "");
                $ret['adresse'] = (string) $con2->adresse[0] . ($con2->numero[0] ? ", " . (string) $con2->numero[0] . " " . (string) $con2->boite[0] : "");
            }
            if ((string) @$con2->postal[0] || @(string) $con2->l_nom[0]) {
                $adtxt[] = (string) $con2->postal[0] . "-" . (string) $con2->l_nom[0];
                $ret['cp'] = (string) $con2->postal[0];
            }
            if (count($adtxt) > 0) {
                $txt .="<p>" . implode("<br/>", $adtxt) . "</p>";
            }
        }
        $body = "<div class='" . HADES_CPT . " contact'>";
        $body.=$txt;
        $body.="</div>";

        $ret['body'] = $body;

        return $ret;
    }

    private function post_localite_commune()
    {
        /* va rechercher la localité dans la racine de l'offre, si absente, va chercher dans les parents */

        $locs = $this->osx->xpath("localisation/localite");
        if ($locs) {
            $txt_tab = array();
            foreach ($locs as $loc) {
                if ((string) $loc->l_nom != (string) $loc->c_nom) {
                    $txt_tab[] = (string) $loc->l_nom . " (" . (string) $loc->c_nom . ")";
                } else {
                    $txt_tab[] = (string) $loc->l_nom;
                }
            }

            return implode(', ', array_unique($txt_tab));
        } else {
            $locs = $this->osx->xpath("parents/offre/localisation/localite");
            if ($locs) {
                $txt_tab = array();
                foreach ($locs as $loc) {
                    if ((string) $loc->l_nom != (string) $loc->c_nom) {
                        $txt_tab[] = (string) $loc->l_nom . " (" . (string) $loc->c_nom . ")";
                    } else {
                        $txt_tab[] = (string) $loc->l_nom;
                    }
                }

                return implode(', ', array_unique($txt_tab));
            }
        }

        return '';
    }

    private function post_commune()
    {
        /* $deindex_com : les communes qui ne doivent pas être ajoutées dans la taxonomie "Communes"  */
        static $deindex_com = array("Houyet", "Beauraing", "Bièvre", "Ciney", "Dinant", "Gedinne", "Lierneux", "Montmédy", "Rochefort", "Trois-Ponts", "Vresse", "Vresse-sur-Semois");
        /* va rechercher la commune dans la racine de l'offre, si absente, va chercher dans les parents */

        $coms = $this->osx->xpath("localisation/localite/c_nom");
        $communes = array();
        if ($coms) {
            $communes = array();
            foreach ($coms as $com) {
                if (!in_array((string) $com, $deindex_com)) {
                    $communes[HADES_TAXO_COM][] = (string) $com;
                }
            }
        } else {
            $coms = $this->osx->xpath("parents/offre/localisation/localite/c_nom");
            if ($coms) {
                $communes = array();
                foreach ($coms as $com) {
                    if (!in_array((string) $com, $deindex_com)) {
                        $communes[HADES_TAXO_COM][] = (string) $com;
                    }
                }
            }
        }

        return $communes;
    }

    private function post_localite()
    {
        $locs = $this->osx->xpath("localisation/localite/l_nom");
        $localites = array();
        if ($locs) {
            foreach ($locs as $loc) {
                $localites[HADES_TAXO_LOC][] = (string) $loc;
            }
        } else {
            $locs = $this->osx->xpath("parents/offre/localisation/localite/l_nom");
            if ($locs) {
                foreach ($locs as $loc) {
                    $localites[HADES_TAXO_LOC][] = (string) $loc;
                }
            }
        }

        return $localites;
    }

    private function post_selection()
    {
        $selection[HADES_TAXO_SEL] = array();
        $sels = $this->osx->xpath("selections/selection[@cl='4' or @cl='3' or @cl='2']/lib");
        if ($sels) {
            foreach ($sels as $sel) {
                $selection[HADES_TAXO_SEL][] = (string) $sel;
            }
        }
        if (is_array($this->supp_selections)) {
            $sels = $this->osx->xpath("selections/selection[@cl!='4' or @cl!='3' or @cl!='2']");
            if ($sels) {
                foreach ($sels as $sel) {
                    if (in_array($sel["id"], $this->supp_selections)) {
                        $selection[HADES_TAXO_SEL][] = (string) $sel->lib;
                    }
                }
            }
        }

        return $selection;
    }

    private function multiply_by_dateheure($hades_id, $horl)
    {
        $fourchette_date_unique = array();
        $idx = array();

        // cadrer d'abord $horl  les lignes d'horaire pour évacuer les doublonnages
        // et les encodages exotiques.
        // ne garder qu'un tableau de valeurs uniques pour date_deb date_fin et jours'
        foreach ($horl as $hor) {
            $fourchette_id = (string) $hor->date_deb[0] . (string) $hor->date_fin[0] . (string) $hor->jours[0];

            //si on a déjà traité cette fourchette, on passe au suivant
            if (in_array($fourchette_id, $fourchette_date_unique)) {
                continue;
            }

            //sinon on conserve la fourchette traité et on va plus loin
            $fourchette_date_unique[] = $fourchette_id;
            $dhidx = (string) @$hor->date_deb[0] . (string) @$hor->date_fin[0];
			if(!isset($fourchette_list_jr[$dhidx]))
				{
				$fourchette_list_jr[$dhidx]="";
				}
            $fourchette_list_jr[$dhidx].=((string) @$hor->jours[0] ? "," . (string) @$hor->jours[0] : "");
            $fourchette_list_deb[$dhidx] = (string) @$hor->date_deb[0];
            $fourchette_list_fin[$dhidx] = (string) @$hor->date_fin[0];
        }

        // echo "<br/> fouchette liste :".print_r($fourchette_list_deb,1). " || ".print_r($fourchette_list_fin,1)  . " || ".print_r($fourchette_list_jr,1)."<br/>";
        foreach (array_keys($fourchette_list_deb) as $dhkey) {
            $dh_jours = $fourchette_list_jr[$dhkey];
            $dh_date_deb = date_eu_to_my($fourchette_list_deb[$dhkey]);
            $dh_date_fin = date_eu_to_my($fourchette_list_fin[$dhkey]);
            $d_deb = DateTime::createFromFormat("Y-m-d", $dh_date_deb);
            $d_fin = DateTime::createFromFormat("Y-m-d", $dh_date_fin);
            $dh_date_long = intval(($d_fin->getTimestamp() - $d_deb->getTimestamp()) / (60 * 60 * 24)) + 1;

            if ($dh_date_deb != $dh_date_fin && strlen($dh_jours) > 1 && $dh_date_long > 14) {
                // problème avec les encodages de 2018
                $dd = $dh_date_deb < $this->limite_deb ? $this->limite_deb : $dh_date_deb;
                $df = $dh_date_fin > $this->limite_test ? $this->limite_fin : $dh_date_fin;
                //echo "<br/>fouchette de génération -> " . $dd . "-> " . $df . " jours:" . $dh_jours . "<br/>";

                $list_date_fourchette = getDateForSpecificDayBetweenDates($dd, $df, $dh_jours);
                // echo "<br/>liste de jours générés -> " . print_r( $list_date_fourchette, 1 ) . "<br/>";
                foreach ($list_date_fourchette as $date_fourchette) {
                    //echo "<br/>traite " . $date_fourchette['deb']."->".$date_fourchette['fin'];
                    $dateheure_id = $hades_id . "#" . $date_fourchette['deb'] . $date_fourchette['fin'];
                    $idx[$dateheure_id] = (object) array(
                        'id' => $dateheure_id,
                        'date_deb' => $date_fourchette['deb'],
                        'date_fin' => $date_fourchette['fin'],
                        'date_long' => 1,
                    );
                }
            } else {
                $dateheure_id = $hades_id . "#" . $dh_date_deb . $dh_date_fin;
                $idx[$dateheure_id] = (object) array(
                    'id' => $dateheure_id,
                    'date_deb' => $dh_date_deb,
                    'date_fin' => $dh_date_fin,
                    'date_long' => $dh_date_long,
                );
            }
        }
        ksort($idx);
        if (count($idx) > 400) {
            trigger_error("Erreur de multiplication de dates sur l'offre N°" . $hades_id, E_USER_WARNING);
            $idx = array_slice($idx, 0, 1);
        }

        return $idx;
    }

    private function get_post_thumbnail($media_url, $media_titre, $post_id)
    {
        $image_url = $media_url;
        $image_name = basename($media_url);
        $upload_dir = wp_upload_dir();

        // $image_url=str_replace(array("http://www.ftlb.be/","https://www.ftlb.be/"), "ftp://172.31.1.21/pools/A/A0/FTLB/Share FTLB/", $image_url);
        // $image_url=str_replace("http://www.ftlb.be/", $_GET["urlimg"], $image_url);

        echo " [url image]=[" . $image_url."]";

        $post_thumbnail_id = get_post_thumbnail_id($post_id);

        // Si l'image et l'attachement existe on renvoie l'attachement Id
        if ($post_thumbnail_id > 0) {
            $attached = get_attached_file($post_thumbnail_id);
            $imageattached=substr($attached, strrpos($attached, '/') + 1);
            if (  $attached && $imageattached==$image_name   ) {
                echo " (" . $image_name . "->Existe & attachée : " . $imageattached . " ) ";
                return $post_thumbnail_id;
            }
        }

        // Si l'image existe, on renvoie l'attachement Id du post d'origine
        if (file_exists($upload_dir['path'] . '/' . $image_name)) {
            if (!$post_thumbnail_id) {
                $post_thumbnail_id = $this->get_image_id_from_filename($image_name);
            }
            echo " (" . $image_name . "->Existe dans ".$upload_dir['path']." ) ";
            return $post_thumbnail_id;
        }
        echo " (" . $image_name;
        $image_url = str_replace('https:', 'http:', $image_url);
        $image_data =  wp_remote_retrieve_body(wp_remote_get($image_url));

        // $unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name );
        // $filename = basename( $unique_file_name ) . '.jpg';
        $filename = $image_name;
        if (!$image_data) {
            echo "->Image vide)";
            return null;
        }

        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }
        file_put_contents($file, $image_data);
        $wp_filetype = wp_check_filetype($file, null);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => $filename,
            'post_content' => '',
            'post_status' => 'inherit'
        );
        global $wp_rewrite;

        $wp_rewrite = new wp_rewrite;
        $attach_id = wp_insert_attachment($attachment, $file);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);
        echo "->Chargé)";

        return $attach_id;
        //    }
    }

    public function delete_xml_to_wp($hades_id)
    {
        global $wpdb;

        if ($hades_id > 0) {
            // recherche des Posts suivant l'identifiant Hadès
            $settings = get_option($option = 'hades_settings');
            // les posts correspondant à hades_id
            $posts_with_id_hades = get_posts(
                array(
                    'numberposts' => -1,
                    'post_type' => HADES_CPT,
                    'meta_key' => 'hades_id',
                    'meta_value' => $hades_id
                )
            );
            $wpdb->delete(HADESDBPFX . "offre_xml", array('fk_off_id' => $hades_id));

            if (count($posts_with_id_hades) > 0) {
                echo "<hr/>Hadès ID N°" . $hades_id;
                foreach ($posts_with_id_hades as $post_with_id_hades) {
                    hades_set_time(30);
                    wp_delete_post($post_with_id_hades->ID, $force_delete = true);
                    echo " -" . $post_with_id_hades->ID;
                }
            }
        }

        

        return true;
    }

    /* ******************************************************************************
     *  Récupère un id de thumbnail au départ du nom de fichier
     * **************************************************************************** */

    public function get_image_id_from_filename($image_name)
    {
        global $wpdb;
        $attachment = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type='attachment' AND post_title='$image_name'");
        return $attachment;
    }

    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
