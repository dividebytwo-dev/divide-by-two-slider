<?php
/**
 * Plugin Name: Divide by Two Slider
 * Description: A lightweight slider that simply works.
 * Version: 1.0
 * Author: Divide by Two
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Load widget class
require_once plugin_dir_path( __FILE__ ) . 'includes/class-divide-by-two-slider-widget.php';

// Register widget
function dbt_register_slider_widget() {
    register_widget( 'Divide_By_Two_Slider_Widget' );
}
add_action( 'widgets_init', 'dbt_register_slider_widget' );

// Load scripts and styles
function dbt_slider_enqueue_assets() {
    // Core Flickity CSS
    wp_enqueue_style(
        'flickity-css',
        plugin_dir_url( __FILE__ ) . 'assets/css/flickity.css',
        array(),
        '2.3.0'
    );

    // Flickity core
    wp_enqueue_script(
        'flickity-js',
        plugin_dir_url( __FILE__ ) . 'assets/js/flickity.pkgd.min.js',
        array( 'jquery' ),
        '2.3.0',
        true
    );

    // Flickity Fade plugin (must load after Flickity)
    wp_enqueue_script(
        'flickity-fade',
        plugin_dir_url( __FILE__ ) . 'assets/js/flickity-fade.js',
        array( 'flickity-js' ),
        '1.0',
        true
    );

    // Your custom init script (depends on both Flickity + Fade)
    wp_enqueue_script(
        'dbt-slider-init',
        plugin_dir_url( __FILE__ ) . 'assets/js/slider-init.js',
        array( 'jquery', 'flickity-js', 'flickity-fade' ),
        '1.0',
        true
    );

    // Custom CSS
    wp_enqueue_style(
        'dbt-slider-style',
        plugin_dir_url( __FILE__ ) . 'assets/css/slider.css',
        array(),
        '1.0'
    );

    // For sortable admin behavior (if needed)
    wp_enqueue_script( 'jquery-ui-sortable' );
}
add_action( 'wp_enqueue_scripts', 'dbt_slider_enqueue_assets' );

// Admin scripts
add_action('admin_enqueue_scripts', function() {
    wp_enqueue_media();
    wp_enqueue_editor();

    wp_enqueue_script(
        'dbt-slider-admin',
        plugin_dir_url(__FILE__) . 'assets/js/slider-admin.js',
        ['jquery'],
        '1.0',
        true
    );
});

/*
add_action('admin_notices', function() {
    echo '<div class="notice notice-info"><p>Slider Admin JS URL: ' . esc_html( plugins_url('assets/js/slider-admin.js', __FILE__) ) . '</p></div>';
});
*/