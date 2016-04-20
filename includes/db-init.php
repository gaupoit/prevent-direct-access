<?php

if ( ! defined( 'ABSPATH' ) ) exit;


class Pda_Database {

	private $jal_db_version;

	public function __construct() {
		$this->jal_db_version = '1.1';
	}

	function install() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'prevent_direct_access';
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			//table is not created. you may create the table here.
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
	    	ID mediumint(9) NOT NULL AUTO_INCREMENT,
	    	post_id mediumint(9) NOT NULL,
	    	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	    	url varchar(55) DEFAULT '' NOT NULL,
	    	is_prevented tinyint(1) DEFAULT 1,
	    	UNIQUE KEY id (id)
	    ) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
			// $wpdb->query( $sql );
			add_option( 'jal_db_version', $this->$jal_db_version );
		}

		$installed_ver = get_option( "jal_db_version" );
		error_log( "Installed ver: " . $installed_ver );
		error_log( "Jal db ver: " . $this->jal_db_version );
		if ( $installed_ver != '1.1' ) {
			error_log( " Different ");
			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
		    	hits_count mediumint(9) NOT NULL
		    ) $charset_collate;";

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			dbDelta( $sql );
			$this->jal_db_version = '1.1';
			update_option( 'jal_db_version', $this->jal_db_version );
		} else {
			error_log( " Same ");
		}
	}

	function uninstall() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'prevent_direct_access';
		$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
	}
}

?>
