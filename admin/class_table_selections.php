<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of hades_index_table
 *
 * @author l.watelet
 */
if( !class_exists( 'WP_List_Table' ) )
    {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    }
    
class hades_table_selections extends WP_List_Table
    {
    public $index_items;

    function load_items( $items )
        {
        $this->index_items = array ();
        foreach( $items as $item )
            {
            $this->index_items[] = (array) $item;
            }
        }

    function get_columns()
        {
        $columns = array (
                    'sel_id' => 'ID',
                    'classe' => 'Classe',
                    'libelle' => 'Libellé Hadès',
                    'tag' => 'Étiquette WP',
        );
        return $columns;
        }

    function prepare_items()
        {
        $columns = $this->get_columns();
        $hidden = array ();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array ( $columns, $hidden, $sortable );
        $this->items = $this->index_items;
        }

    function column_default( $item, $column_name )
        {
        switch( $column_name )
            {
            case 'sel_id':
                return $item['sel_id'];
            case 'classe':
                return $item['classe'];
            case 'libelle':
                return $item['libelle'];
            case 'tag':
                if( $item['tag'] != '' )
                    {
                    return $item['tag'];
                    }
                else
                    {
                    return "<input name='sel_id[" . $item['sel_id'] . "]' type='text' value=''/></td></tr>";
                    }

            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
            }
        }

    function get_sortable_columns()
        {
        $sortable_columns = array (
                    'sel_id' => array ( 'sel_id', false ),
                    'classe' => array ( 'classe', false ),
                    'libelle' => array ( 'libelle', false ),
                    'tag' => array ( 'tag', false ),
        );
        return $sortable_columns;
        }

    }