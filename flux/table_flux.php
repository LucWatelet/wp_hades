<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of hades_flux_table
 *
 * @author l.watelet
 */
if (!class_exists('WP_List_Table')) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class table_flux extends WP_List_Table {

	public $flux_items;

	function load_items($items) {
		$this->flux_items = array();
		foreach ($items as $item) {
			$this->flux_items[] = (array) $item;
		}
	}

	function get_columns() {
		$columns = array(
					'flux_id' => 'ID',
					'url' => 'URL du Flux',
					'actif' => 'Actif',
					'temps' => 'Durée',
					'poids' => 'Taille',
					'nb_offres' => 'Nombre d\'offres',
		);
		return $columns;
	}
    
    function column_width_style(){
        echo '<style> 
            .column-flux_id {width: 6%;}
            .column-actif {width: 8%;}
            .column-temps {width: 6%;}
            .column-poids {width: 6%;}
            .column-nb_offres {width: 15%;}
            </style>';
        }

	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $this->flux_items;
	}

	function column_default($item, $column_name) {
		switch ($column_name) {
			case 'flux_id':
				return $item['flux_id'];
			case 'temps':
				$s = $this->nombre_sign($item['temps']) . "s";
				return $s;
			case 'poids':
				$s = $this->nombre_sign(intval($item['poids'])) . "o";
				return $s;
			case 'nb_offres':
				return $item['nb_offres'] . " offres";

			case 'url':
				$status = $item['actif'] ? 'Désactiver' : 'Activer';
				$actions = array(
					'Modifier' => sprintf('<a href="?post_type=%s&page=%s&action=%s&flux=%s">Modifier</a>', $_REQUEST['post_type'], $_REQUEST['page'], 'addmodif', $item['flux_id']),
					'Désactiver' => sprintf('<a href="?post_type=%s&page=%s&action=%s&flux=%s">%s</a>', $_REQUEST['post_type'], $_REQUEST['page'], 'deactiver', $item['flux_id'], $status),
					'Charger' => sprintf('<a href="?post_type=%s&page=%s&action=%s&flux=%s">Charger</a>', $_REQUEST['post_type'], $_REQUEST['page'], 'load', $item['flux_id']),
					'LastImport' => sprintf('<a href="?post_type=%s&page=%s&action=%s&flux=%s">Voir le dernier fichier importé</a>', $_REQUEST['post_type'], $_REQUEST['page'], 'lastimport', $item['flux_id']),
					'Effacer' => sprintf('<a href="?post_type=%s&page=%s&action=%s&flux=%s">Effacer</a>', $_REQUEST['post_type'], $_REQUEST['page'], 'delete', $item['flux_id']),
				);
				return sprintf('%1$s %2$s', $item['url'], $this->row_actions($actions));

			case 'actif':
				if ($item['actif'] == '1')
					return '<span class="dashicons dashicons-yes"></span>';
				else
					return '<span class="dashicons dashicons-no"></span>';

			default:
				return print_r($item, true); //Show the whole array for troubleshooting purposes
		}
	}

	function get_sortable_columns() {
		$sortable_columns = array(
					'flux_id' => array('flux_id', false),
					'url' => array('url', false),
					'actif' => array('actif', false)
		);
		return $sortable_columns;
	}

	function nombre_sign($n) {
		if (is_numeric($n) && $n != 0) {
			$mul = array();
			$mul[12] = "µ";
			$mul[9] = "m";
			$mul[6] = "";
			$mul[3] = "k";
			$mul[0] = "M";
			$rang = (-1) * log10($n);
			if ($n < 1) {
				$exposant = floor($rang / 3) * 3;
			} else {
				$exposant = intval($rang / 3) * 3;
			}
			$f = $n * pow(10, $exposant);
			return sprintf("%.2f", $f) . $mul[$exposant + 6];
		} else
			return "0";
	}

}
