<?php
class Hades_Selections
    {
    /** @var object(mysqli) ressource mysqli d'accès à la base de données */
    /** @var string Requête de mise à jour */
    protected $query;

    /** @var bool Si TRUE, les tables impactées sont vidées avant d'être remplie avec les offres reçues.
     * Si FALSE, chaque offre est supprimée individuellemnt avant d'être insérée */
    public function __construct()
        {
        //On ouvre une ressource à la base de données
        }

    public function get_all_sel()
        {
        global $wpdb;
        $results = $wpdb->get_results( "SELECT sel_id, classe, libelle, tag FROM `" . HADESDBPFX . "selections` ORDER BY classe DESC, libelle ASC " );
        return $results;
        }

    public function save( $modif )
        {
        global $wpdb;
        
        foreach( $modif['sel_id'] as $key => $value )
            {
            if( $key > 0 && $value != '' )
                {
                $results = $wpdb->get_results( "UPDATE " . HADESDBPFX . "selections SET tag='" . addslashes($value) . "' WHERE sel_id=" . $key . ";" );
                }
            }

        return TRUE;
        }

    }