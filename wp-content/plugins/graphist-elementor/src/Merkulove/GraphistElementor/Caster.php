<?php
/**
 * Graphist for Elementor
 * Creates modern and stylish Elementor widgets to display awesome charts and graphs.
 * Exclusively on https://1.envato.market/graphist-elementor
 *
 * @encoding        UTF-8
 * @version         1.1.0
 * @copyright       (C) 2018 - 2021 Merkulove ( https://merkulov.design/ ). All rights reserved.
 * @license         Envato License https://1.envato.market/KYbje
 * @contributors    Nemirovskiy Vitaliy (nemirovskiyvitaliy@gmail.com), Dmitry Merkulov (dmitry@merkulov.design)
 * @support         help@merkulov.design
 **/

namespace Merkulove\GraphistElementor;

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
 * SINGLETON: Caster class contain main plugin logic.
 *
 * @since 1.0.0
 *
 **/
final class Caster {

	/**
	 * The one true Caster.
	 *
     * @since 1.0.0
     * @access private
	 * @var Caster
	 **/
	private static $instance;

    /**
     * Setup the plugin.
     *
     * @since 1.0.0
     * @access public
     *
     * @return void
     **/
    public function setup() {

        /** Define hooks that runs on both the front-end as well as the dashboard. */
        $this->both_hooks();

        /** Define public hooks. */
        $this->public_hooks();

        /** Define admin hooks. */
        $this->admin_hooks();

    }

    /**
     * Define hooks that runs on both the front-end as well as the dashboard.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function both_hooks() {

        /** Hooks which should work everywhere in backend and frontend. */

    }

    /**
     * Register all of the hooks related to the public-facing functionality.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function public_hooks() {

        /** Work only on frontend area. */
        if ( is_admin() ) { return; }

        /** Public hooks and filters. */

    }

    /**
     * Register all of the hooks related to the admin area functionality.
     *
     * @since 1.0.0
     * @access private
     *
     * @return void
     **/
    private function admin_hooks() {

        /** Work only in admin area. */
        if ( ! is_admin() ) { return; }

        /**  Add custom category. */
        add_action( 'elementor/elements/categories_registered', [$this, 'add_elementor_widget_categories'] );

    }

	/**
	 * This method used in register_activation_hook
	 * Everything written here will be done during plugin activation.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function activation_hook() {

		/** Activation hook */

	}

    /**
     * Add custom category.
     *
     * @param $elements_manager Graphist.
     * @since 1.0.0
     * @return void
     */
    public function add_elementor_widget_categories( $elements_manager ) {

        $elements_manager->add_category(
            'graphist-category',
            [
                'title' => esc_html__( 'Graphist', 'graphist-elementor' ),
                'icon' => 'fa fa-plug',
            ]
        );

    }

	/**
	 * Main Caster Instance.
	 * Insures that only one instance of Caster exists in memory at any one time.
	 *
	 * @static
     * @since 1.0.0
     * @access public
     *
	 * @return Caster
	 **/
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {

			self::$instance = new self;

		}

		return self::$instance;

	}

}
