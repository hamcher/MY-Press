<?php

/**
 * Plugin Name: Visual Inspector Sync
 * Description: Visual Inspector Sync is WordPress-plugin to sync all changes done in Visual Inspector directly into your website without bothering about CSS code etc.
 * Plugin URI: https://www.canvasflip.com/visual-inspector/
 * Author: CanvasFlip.com
 * Version: 0.1
 * Author URI: https://www.canvasflip.com/visual-inspector/
 *
 * Text Domain: visual inspector by canvasflip
 * 
 */
//Exit if accessed directly

if (!defined('ABSPATH')) {
    exit;
}

//This will bring visual inspector plugin in admin menu
add_action('admin_menu', 'viPluginMenu');

function viPluginMenu() {
    $page_title = 'Visual Inspector Plugin';
    $menu_title = 'Visual Inspector';
    $capability = 'manage_options';
    $menu_slug = 'canvasflip';
    $icon_url = '';
    $position = 20;

    // Parent menu options
    add_menu_page($page_title, $menu_title, $capability, $menu_slug, 'vi_plugin_projects', $icon_url, $position);

    //parent menu slug set to null, after which submenu will not appear in admin menu
    //add_submenu_page('options.php', 'VI Projects', 'VI Projects', 'manage_options', 'projects', 'vi_plugin_projects');
}

// This function will directly call sub menu option
function vi_plugin_projects() {
    require_once(plugin_dir_path(__FILE__) . 'includes/viPluginProjects.php');
}

/**
 * Register settings
 *
 * @since 1.0
 */
function vi_register_settings() {
    register_setting('vi_settings_group', 'vi_settings');
}

add_action('admin_init', 'vi_register_settings');

/**
 * Enqueue link to add CSS through PHP.
 *
 * This is a typical WP Enqueue statement, except that the URL of the stylesheet is simply a query var.
 * This query var is passed to the URL, and when it is detected by vi_plugin_custom_css(),
 * it writes its PHP/CSS to the browser.
 */
function vi_register_style() {
    $url = home_url();

    if (is_ssl()) {
        $url = home_url('/', 'https');
    }

    wp_register_style('vi-plugin-custom', add_query_arg(array('vi_custom_css' => 1), $url));

    wp_enqueue_style('vi-plugin-custom');
}

add_action('wp_enqueue_scripts', 'vi_register_style', 99);

function vi_print_css() {

    // Only print CSS if this is a stylesheet request.
    if (!isset($_GET['vi_custom_css']) || intval($_GET['vi_custom_css']) !== 1) {
        return;
    }

    ob_start();
    header('Content-type: text/css');

    vi_get_css();

    die();
}

add_action('plugins_loaded', 'vi_print_css');

function vi_get_css() {
    $options = get_option('vi_settings');
    $raw_content = isset($options['vi_css']) ? $options['vi_css'] : '';
    $content = wp_kses($raw_content, array('\'', '\"'));
    $content = str_replace('&gt;', '>', $content);
    echo $content; // WPCS: xss okay.
}

?>