<?php
/**
 * Plugin Name: Elementor Addon
 * Description: Simple hello world widgets for Elementor.
 * Version:     1.0.0
 * Author:      Elementor Developer
 * Author URI:  https://developers.elementor.com/
 */

function register_hello_world_widget( $widgets_manager ) {
	//bar-chart.php
	require_once( __DIR__ . '/widgets/pie-chart.php' );
	require_once( __DIR__ . '/widgets/line-chart.php' );
	require_once( __DIR__ . '/widgets/periods-switcher.php' );
	

	$widgets_manager->register( new \PieChart() );
	$widgets_manager->register( new \LineChart() );
	$widgets_manager->register( new \PeriodsSwitcher() );

}
add_action( 'elementor/widgets/register', 'register_hello_world_widget' );

