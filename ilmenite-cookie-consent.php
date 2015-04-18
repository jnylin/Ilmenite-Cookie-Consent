<?php
/*
 *	Plugin Name: Ilmenite Cookie Consent
 *	Plugin URI: https://github.com/bernskioldmedia/Ilmenite-Cookie-Consent
 *	Description: A simple, developer-friendly WordPress plugin that lets visitors know that the site is using cookies.
 *	Author: Bernskiold Media
 *	Version: 0.1.0
 *	Author URI: http://www.bernskioldmedia.com/
 *	Text Domain: ilcc
 *	Domain Path: /languages
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class Ilmenite_Cookie_Consent {

	/**
	 * The Plugin Path
	 * @var string
	 */
	public $plugin_path;

	/**
	 * The Plugin URL
	 * @var string
	 */
	public $plugin_url;

	/**
	 * The Plugin Version
	 * @var string
	 */
	public $plugin_version;

	/**
	* @var The single instance of the class
	*/
	protected static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {

		// Set Developer Mode Constant
		define( 'ILCC_DEV_MODE', false );

		// Set the plugin path
		$this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );

		// Set the plugin URL
		$this->plugin_url = untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) );

		// Set the plugin version
		$this->plugin_version = '0.1.0';

		// Add Scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

		// Add Styles
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );

	}

	/**
	 * Load the Translations
	 */
	public function add_textdomain() {
		load_plugin_textdomain( 'ilcc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Enqueue Scripts
	 */
	public function scripts() {

		// Register Scripts
		wp_register_script( 'ilmenite-cookie-consent', $this->plugin_url . '/assets/js/cookie-banner.js', false, $this->plugin_version, true );

		// Localize the script
		wp_localize_script( 'ilmenite-cookie-consent', 'ilcc', array(
			'cookieConsentText' => sprintf( apply_filters( 'ilcc_consent_text', __( '<span>This website uses cookies to enhance the browsing experience. </span>By continuing you give us permission to deploy cookies as per our <a href="%s" rel="nofollow">privacy and cookies policy</a>.' ) ), '#' ),
			'acceptText'		=> apply_filters( 'ilcc_accept_text', __( 'I Understand', 'ilcc' ) ),
		) );

		// Enqueue them
		wp_enqueue_script( 'ilmenite-cookie-consent' );

	}

	/**
	 * Enqueue Styles
	 */
	public function styles() {

		// Register them...
		wp_register_style( 'ilmenite-cookie-consent', $this->plugin_url . '/assets/css/cookie-banner.css', false, $this->plugin_version, 'all' );

		// Enqueue if developer mode isn't turned on
		if ( ! ILCC_DEV_MODE ) {
			wp_enqueue_style( 'ilmenite-cookie-consent' );
		}

	}

}

function IlmeniteCookieConsent() {
    return Ilmenite_Cookie_Consent::instance();
}

// Initialize the class instance only once
IlmeniteCookieConsent();