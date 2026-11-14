<?php
/**
 * Main plugin coordinator.
 *
 * @package AllpaCodeUbigeoPeru
 */

namespace AllpaCodeUbigeoPeru;

use AllpaCodeUbigeoPeru\Admin\Admin;
use AllpaCodeUbigeoPeru\PublicApp\Assets;
use AllpaCodeUbigeoPeru\PublicApp\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plugin {
	private static ?Plugin $instance = null;

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function boot(): void {
		Settings::register_defaults();

		add_action( 'rest_api_init', array( new RestController(), 'register_routes' ) );
		add_filter( 'wpcf7_form_elements', 'do_shortcode' );

		$woocommerce = new WooCommerceIntegration();
		add_action( 'wp_enqueue_scripts', array( $woocommerce, 'enqueue_assets' ) );
		add_action( 'woocommerce_checkout_update_order_meta', array( $woocommerce, 'save_order_meta' ) );
		add_filter( 'woocommerce_checkout_fields', array( $woocommerce, 'checkout_fields' ) );

		( new Assets() )->hooks();
		( new Shortcode() )->hooks();

		if ( is_admin() ) {
			( new Admin() )->hooks();
		}
	}
}
