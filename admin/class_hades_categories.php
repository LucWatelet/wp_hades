<?php
class Hades_Categories
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

    public function get_all_cat()
        {
        global $wpdb;
        $results = $wpdb->get_results( "SELECT c.fk_cat_id, count( * ) AS nb
										FROM `" . HADESDBPFX . "offre_categories` c
										GROUP BY c.fk_cat_id" );
        return $results;
        }

    public function get_free_cat()
        {
        global $wpdb;
        $results = $wpdb->get_results( "SELECT c.fk_cat_id, count( * ) AS nb
										FROM `" . HADESDBPFX . "offre_categories` c
                                        LEFT JOIN `" . HADESDBPFX . "taxo_cat` t ON c.fk_cat_id = t.fk_cat_id
										WHERE t.fk_cat_id IS NULL
										GROUP BY c.fk_cat_id" );
        return $results;
        }

    public function get_all_cat_tax()
        {
        global $wpdb;
        $results = $wpdb->get_results( "SELECT wt.term_id, w.fk_cat_id, w.xpath
					FROM " . HADESDBPFX . "taxo_cat w
					LEFT JOIN " . $wpdb->prefix . "terms wt ON w.fk_tid = wt.term_id;" );

        $result = array ();

        foreach( $results as $obj )
            {
            if( !is_array( $result[$obj->term_id] ) )
                $result[$obj->term_id] = array ();
            $result[$obj->term_id][] = $obj;
            }

        return $result;
        }

    public function get_cat_tax_array()
        {
        global $wpdb;
        $results = $wpdb->get_results( "SELECT w.fk_cat_id, w.fk_tid
					FROM " . HADESDBPFX . "taxo_cat w ;" );
        $cattax = array ();
        foreach( $results as $obj )
            {
            if( !is_array( $cattax[$obj->fk_cat_id] ) )
                $cattax[$obj->fk_cat_id] = array ();
            $cattax[$obj->fk_cat_id][] = $obj->fk_tid;
            }

        return $cattax;
        }

    public function save( $cattax_list )
        {

        /* 		$query = "TRUNCATE TABLE `" . HADESDBPFX . "taxo_cat`;\n";
          $query.="INSERT INTO `" . HADESDBPFX . "taxo_cat` (`fk_cat_id`, `fk_tid`) VALUES ";

          foreach ($cattax_list as $cattax) {
          if ($cattax->fk_tid && $cattax->fk_tid != 'none') {
          $values[] = "('" . $cattax->fk_cat_id . "','" . $cattax->fk_tid . "')";
          }
          }

          $query.=implode(", ", $values) . ";\n";
          $mod = $this->db->multi_query($query);
          if ($mod) {
          while ($this->db->next_result());
          }
          if ($this->db->error != NULL) {
          trigger_error('Erreur de query [hades_cat.save()] à la sauvegarde des catégories et taxos : ' . $this->db->error . "<p>" . $query . "</p>", E_USER_ERROR);
          }
         */
        return TRUE;
        }

    public function save_taxocat( $cattax_list )
        {
        global $wpdb;
		$compte=0;
        if(count($cattax_list)>=1) {
			$query = "REPLACE INTO `" . HADESDBPFX . "taxo_cat` (`fk_cat_id`, `xpath`, `fk_tid`) VALUES ";
			foreach( $cattax_list as $term_id => $cat_tab )
				{
				foreach( $cat_tab as $cat_obj )
					{
					if( $cat_obj['fk_cat_id'] )
						{
						$values[] = "('" . $cat_obj['fk_cat_id'] . "','" . $cat_obj['xpath'] . "','" . $term_id . "')";
						$compte++;
						}
					}
				}
				
			if ($compte>=1 ){	
				$query.=implode( ", ", $values ) . ";";
				$mod = $wpdb->query( $query );
				if($mod !==FALSE ){
					$wpdb->query( "TRUNCATE TABLE `" . HADESDBPFX . "taxo_cat`;" );
					$wpdb->query( $query );
				}
				return TRUE;
				}
			return FALSE;
			}
		return FALSE;
        }

    }