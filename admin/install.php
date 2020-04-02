<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function hades_install()
    {
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    global $wpdb;
    
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE " . HADESDBPFX . "flux_index (
		`index_id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(16) NOT NULL,
		`xpath` TEXT DEFAULT NULL,
		`actif` tinyint(1) NOT NULL DEFAULT '1',
		PRIMARY KEY (`index_id`) )" . $charset_collate . " ;";


    dbDelta( $sql );

    $sql = "CREATE TABLE `" . HADESDBPFX . "flux_url` (
		`flux_id` int(11) NOT NULL AUTO_INCREMENT,
		`url` text,
		`actif` int(1) NOT NULL,
		`temps` int(11) NOT NULL,
		`poids` int(11) NOT NULL,
		`nb_offres` int(11) NOT NULL,
		PRIMARY KEY (`flux_id`))" . $charset_collate . " ;";
    dbDelta( $sql );


    $sql = "CREATE TABLE `" . HADESDBPFX . "localites` (
		`loc_id` int(11) NOT NULL,
		`com_id` int(11) NOT NULL,
		`l_nom` varchar(32) NOT NULL,
		`c_nom` varchar(32) NOT NULL,
		`term_id` int(11) NOT NULL,
		PRIMARY KEY (`loc_id`))" . $charset_collate . " ;";
    dbDelta( $sql );


/*    $sql = "CREATE TABLE `" . HADESDBPFX . "lots` (
	`lib` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
	 `lib_fr` varchar(255) DEFAULT NULL,
	 `lib_nl` varchar(255) DEFAULT NULL,
	 `lib_en` varchar(255) DEFAULT NULL,
	 `lib_de` varchar(255) DEFAULT NULL,
	 `granule` varchar(16) DEFAULT NULL,
	 `tri` int(11) DEFAULT NULL,
	 `datein` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	 `typ` varchar(8) DEFAULT NULL,
	 `lot` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`lib`))" . $charset_collate . " ;";
    dbDelta( $sql );*/


    $sql = "CREATE TABLE `" . HADESDBPFX . "offre_categories` (
		`fk_off_id` int(11) NOT NULL,
		`fk_cat_id` varchar(24) NOT NULL,
		PRIMARY KEY (`fk_off_id`,`fk_cat_id`) )" . $charset_collate . " ;";
    dbDelta( $sql );

    $sql = "CREATE TABLE `" . HADESDBPFX . "offre_dateheure` (
		`fk_off_id` int(11) NOT NULL,
		`date_deb` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`date_fin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
        `jours` VARCHAR(34) DEFAULT NULL,
		PRIMARY KEY (`fk_off_id`,`date_deb`,`date_fin`) )" . $charset_collate . " ;";
    dbDelta( $sql );

/*    $sql = "CREATE TABLE `" . HADESDBPFX . "offre_html` (
		`fk_off_id` int(11) NOT NULL,
		`body` text,
		`resume` text,
		`excerpt` text,
		`liste` text,
		`annexes` text,
		`selections` text,
		`categories` text,
		`categories_deep` text,
		`localites` text,
		`geocodes` text,
		`horaires` text
		PRIMARY KEY (`fk_off_id`) )" . $charset_collate . " ;";
    dbDelta( $sql );*/

   /* $sql = "CREATE TABLE `" . HADESDBPFX . "offre_indexes` (
		`fk_off_id` int(11) NOT NULL,
		`nom` varchar(32) NOT NULL,
		`valeur` varchar(128) NOT NULL,
		PRIMARY KEY (`fk_off_id`,`nom`,`valeur`) )" . $charset_collate . " ;";
    dbDelta( $sql );*/

    $sql = "CREATE TABLE " . HADESDBPFX . "offre_liee (
        `fk_off_id` INT(11) DEFAULT NULL,
        `sub_off_id` INT(11) DEFAULT NULL,
        `typ` VARCHAR(1) DEFAULT NULL,
        `rel` VARCHAR(16) DEFAULT NULL,
        KEY IDX_wp_hades_offre_liee_fk_off (fk_off_id),
        KEY IDX_wp_hades_offre_liee_rel (rel),
        KEY IDX_wp_hades_offre_liee_sub_of (sub_off_id),
        KEY IDX_wp_hades_offre_liee_typ (typ),
        PRIMARY KEY (fk_off_id, sub_off_id))" . $charset_collate . " ;";
    dbDelta( $sql );    
    
    
    $sql = "CREATE TABLE `" . HADESDBPFX . "offre_localites` (
		`fk_off_id` int(11) NOT NULL,
		`fk_loc_id` smallint(6) NOT NULL,
		`fk_com_id` smallint(6) NOT NULL,
		`fk_reg_id` smallint(6) NOT NULL,
		PRIMARY KEY (`fk_off_id`,`fk_loc_id`),
		KEY `fk_com_id` (`fk_com_id`),
		KEY `fk_reg_id` (`fk_reg_id`) )" . $charset_collate . " ;";
    dbDelta( $sql );

    $sql = "CREATE TABLE `" . HADESDBPFX . "offre_node` (
        `off_id` INT(11) NOT NULL,
        `nid` INT(11) NOT NULL,
        `synopen` TINYINT(4) DEFAULT 0,
        `titre` VARCHAR(255) DEFAULT NULL,
        `last_update` DATETIME DEFAULT NULL,
        `fk_flux_id` INT(11) NOT NULL,
        `dateheure` VARCHAR(24) NOT NULL,
        PRIMARY KEY (off_id, dateheure),
        KEY fk_flux_id (fk_flux_id),
        KEY nid (nid) )" . $charset_collate . " ;";
    dbDelta( $sql );

    $sql = "CREATE TABLE `" . HADESDBPFX . "taxo_cat` (
		`fk_cat_id` varchar(24) NOT NULL,
        `xpath` VARCHAR(255) DEFAULT NULL,
		`fk_tid` int(11) NOT NULL,
		PRIMARY KEY (`fk_cat_id`,`fk_tid`),
		KEY `fk_cat_id` (`fk_cat_id`),
		KEY `fk_tid` (`fk_tid`) )" . $charset_collate . " ;";
    dbDelta( $sql );

    $sql = "CREATE TABLE IF NOT EXISTS `" . HADESDBPFX . "offre_suppr` (
		`off_id` int(11) NOT NULL,
		`suppr_date` datetime NOT NULL,
		PRIMARY KEY (`off_id`)) " . $charset_collate . " ;";
    dbDelta( $sql );

    
    $sql = "CREATE TABLE IF NOT EXISTS `" . HADESDBPFX . "offre_xml` (
		`fk_off_id` int(11) NOT NULL,
         fk_flux_id INT(11) DEFAULT NULL,
		`xml` mediumtext NOT NULL,
		`last_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `modif_date` datetime DEFAULT NULL,
		PRIMARY KEY (`fk_off_id`))" . $charset_collate . " ;";
    dbDelta( $sql );



    $sql = "CREATE TABLE " . HADESDBPFX . "selections (
        `sel_id` INT(11) NOT NULL,
        `libelle` VARCHAR(32) DEFAULT NULL,
        `classe` TINYINT(4) DEFAULT NULL,
        `tag` VARCHAR(32) DEFAULT NULL,
        PRIMARY KEY (sel_id) )" . $charset_collate . " ;";
    dbDelta( $sql );

    
    $sql = "CREATE TABLE " . HADESDBPFX . "post_spatial (
        `ID` INT(11) NOT NULL,
        `coord` POINT NOT NULL,
        PRIMARY KEY (ID),
        SPATIAL INDEX coord (coord)
      )
      ENGINE = MYISAM 
      " . $charset_collate . ";";
    
    dbDelta( $sql );    
    


    if( get_option( $option = 'hades_user' ) === false )
        {
        $hades_user = wp_insert_user(
                $userdata = array (
                    'user_login' => 'hades_user',
                    'display_name' => 'Hades User' ) );
        update_option(
                $option = 'hades_user', $new_value = $hades_user );
        }
    }
