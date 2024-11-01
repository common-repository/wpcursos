<?php
/**
 * Instalador WpCursos
 */

global $wpcursos_db_version;
$wpcursos_db_version = "1.0";

function wpCursos_install() {
	global $wpdb;
	global $wpcursos_db_version;

	$table_name = $wpdb->prefix . "wpcursos_prematriculas";
      
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		data_matricula datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		nome_aluno VARCHAR(255) DEFAULT '' NOT NULL,
		email_aluno VARCHAR(100) DEFAULT '' NOT NULL,
		telefone_aluno VARCHAR(25) DEFAULT '' NOT NULL,
		turma_id mediumint(11) NOT NULL,
		obs text NOT NULL,
		UNIQUE KEY id (id)
	);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	dbDelta( $sql );
 
	add_option( "wpcursos_db_version", $wpcursos_db_version );
}