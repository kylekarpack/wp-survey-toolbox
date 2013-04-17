<?php
/*
Plugin Name: WP Survey Toolbox
Plugin URI: http://kylekarpack.com/survey-toolbox
Description: Allows WordPress users to create surveys and collect and view the results from the WordPress dashboard 
Version: 0.1
Author: Kyle Karpack, Wiley Bennett, Brad Arnesen, and Michellene Steinberg
Author URI: http://kylekarpack.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Include Functions
function toolbox_install() {
	global $wpdb;
	
	//************Create Tables***************
	
	// Settings table
	$create_tbl_settings = "CREATE TABLE " . $wpdb->prefix . "wp_survey_toolbox_settings (
	  setting_id mediumint(9) NOT NULL AUTO_INCREMENT,
	  PRIMARY KEY  (setting_id)
	);";
	
	// Questions table
	$create_tbl_questions = "CREATE TABLE " . $wpdb->prefix . "wp_survey_toolbox_questions (
	  qid mediumint(9) NOT NULL AUTO_INCREMENT,
	  question text NOT NULL,
	  type text NOT NULL,
	  answers text NOT NULL,
	  PRIMARY KEY  (qid)
	);";
	
	// Surveys table
	$create_tbl_surveys = "CREATE TABLE " . $wpdb->prefix . "wp_survey_toolbox_surveys (
	  sid mediumint(9) NOT NULL AUTO_INCREMENT,
	  title text NOT NULL,
	  PRIMARY KEY  (sid)
	);";
	
	//FOREIGN KEYING FOR THESE TWO!!!
	  //FOREIGN KEY (sid) REFERENCES " . $wpdb->prefix . "wp_survey_toolbox_surveys(sid),
	  //FOREIGN KEY (qid) REFERENCES " . $wpdb->prefix . "wp_survey_toolbox_questions(qid)
	
	// Responses table
	$create_tbl_responses = "CREATE TABLE " . $wpdb->prefix . "wp_survey_toolbox_responses (
	  rid mediumint(9) NOT NULL AUTO_INCREMENT,
	  sid mediumint(9) NOT NULL,
	  qid mediumint(9) NOT NULL,
	  value text,
	  PRIMARY KEY  (rid)
	);";
	
	// Lookup table
	$create_tbl_lookup = "CREATE TABLE " . $wpdb->prefix . "wp_survey_toolbox_lookup (
	  sid mediumint(9) NOT NULL,
	  qid mediumint(9) NOT NULL
	);";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta($create_tbl_settings);
	dbDelta($create_tbl_questions);
	dbDelta($create_tbl_surveys);
	dbDelta($create_tbl_responses);
	dbDelta($create_tbl_lookup);
}

// Add the data tables
register_activation_hook( __FILE__, 'toolbox_install' );

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'It was worth a shot, but you can\'t access survey functionality this way. Sorry!';
	exit;
}

add_action('admin_menu', 'surveytoolbox_menu');
function surveytoolbox_menu() {
	// Add the top main menu item in the admin sidebar
	if (function_exists('add_menu_page')) {
		add_menu_page('Survey Toolbox', 'Survey Toolbox', 'activate_plugins', 'wp-survey-toolbox/wp-survey-toolbox.php', '', plugins_url('wp-survey-toolbox/images/icon.png'), 100);
	}
	// Add submenu items
	if (function_exists('add_submenu_page')) {
		add_submenu_page('wp-survey-toolbox/wp-survey-toolbox.php', 'Manage Surveys', 'Manage Surveys', 'activate_plugins', 'wp-survey-toolbox/survey-builder.php');
		add_submenu_page('wp-survey-toolbox/wp-survey-toolbox.php', 'See Results', 'See Results', 'activate_plugins', 'wp-survey-toolbox/survey-builder.php');
		//add_submenu_page('wp-survey-toolbox/wp-survey-toolbox.php', 'See Results', 'See Results', 'activate_plugins', 'wp-survey-toolbox/results.php');
	}
}
	
?>