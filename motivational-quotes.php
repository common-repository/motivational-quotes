<?php
/**
 * Plugin Name: Motivational Quotes
 * Plugin URI: https://github.com/motivationalquote/motivationalquote_plugin
 * Description: This plugin allows you to display random quotes on your posts and all registered users to see the list of all quotes.
* Version: 1.0
* Author: motivationalquote
* Author URI: https://profiles.wordpress.org/motivationalquote
* Text Domain: motivational-quotes
* Domain Path: /languages
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

global $motiv_quotes_db_version;
$motiv_quotes_db_version = '1.0';

/*create the initial table*/
function motivation_quotes_install() {
	global $wpdb;
	global $motiv_quotes_db_version;

	$table_name = $wpdb->prefix . 'motivational_quotes';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		author tinytext NOT NULL,
		quote text NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'motiv_quotes_db_version', $motiv_quotes_db_version );
}

/*fill the table with some basic quotes*/
function motivation_quotes_install_data() {
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'motivational_quotes';
	$quotes = array(
		"Life is 10% what happens to you and 90% how you react to it." => "Charles R. Swindoll",
		"Success usually comes to those who are too busy to be looking for it." => "Henry David Thoreau",
		"If you are not willing to risk the usual, you will have to settle for the ordinary." => "Jim Rohn",
		"Success is walking from failure to failure with no loss of enthusiasm." => "Winston Churchill",
		"No one can make you feel inferior without your consent." => "Eleanor Roosevelt",
		"It always seems impossible until it's done." => "Nelson Mandela",
		"If you really want the key to success, start by doing the opposite of what everyone else is doing." => "Brad Szollose",
		"If you spend your life trying to be good at everything, you will never be great at anything." => "Tom Rath",
		"Do it or not. There is no try." => "Yoda",
		"The question isn't who is going to let me; it's who is going to stop me." => "Ayn Rand"
		);

	foreach($quotes as $quote => $author){
		$wpdb->insert( 
			$table_name, 
			array( 
				'author' => $author, 
				'quote' => $quote, 
			) 
		);
	}
}


/*randomly select a quote from the database*/
function MotivationalQuote(){
	global $wpdb;

	$table_name = $wpdb->prefix . 'motivational_quotes';
	$quotes = $wpdb->get_results("SELECT * FROM $table_name");
	$number = rand(1, count($quotes));

	echo '<p><img style="padding:4px;" src="' . plugins_url( 'images/quote.png', __FILE__ ) . '" ><i>'.$quotes[$number-1]->quote . '</i><img style="padding:4px;" src="' . plugins_url( 'images/quote2.png', __FILE__ ) . '" ></p>';
	echo '<p style="color:#9D9D9D;font-size: smaller;">'.$quotes[$number-1]->author .'</p>';

}

register_activation_hook( __FILE__, 'motivation_quotes_install' );
register_activation_hook( __FILE__, 'motivation_quotes_install_data' );
add_shortcode('motivational-quote', 'MotivationalQuote');




add_action( 'admin_menu', 'motivation_quotes_menu' );

/*add a setting menu to the plugin for administrators*/
function motivation_quotes_menu() {
	add_menu_page( 'Motivation Quotes Options', 'Motivation quotes admin', 'manage_options', 'motivation-quotes', 'motivation_quotes_options' );
}


/*specifies options for the plugin*/
function motivation_quotes_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include plugin_dir_path( __FILE__ ).'/includes/main-options.php';
}


add_action( 'admin_menu', 'motivation_quotes_user_menu' );

/*add a setting menu to the plugin for normal users*/
function motivation_quotes_user_menu() {
	add_menu_page( 'Motivation Quotes Options for users', 'Motivation quotes users', 'read', 'motivation-quotes-users', 'motivation_quotes_user_options' );
}


/*specifies options for the plugin*/
function motivation_quotes_user_options() {
	if ( !current_user_can( 'read' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include plugin_dir_path( __FILE__ ).'/includes/liste.php';
}

function motivation_quotes_css() {
	wp_register_style('my_css', plugins_url('style.css',__FILE__ ));
	wp_enqueue_style('my_css');
}
add_action( 'admin_init','motivation_quotes_css');

?>