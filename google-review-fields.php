<?php
/**
 * Plugin Name: Google Review Count and Average Rating
 * Description: Adds options to manage Google review count and average rating, and replaces variables dynamically on the frontend.
 * Version: 1.1
 * Author: David McDonagh
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

// Check if ACF is installed and active.
if (!function_exists('get_field')) {
    add_action('admin_notices', function () {
        echo '<div class="error"><p><strong>Google Review Count and Average Rating:</strong> ACF plugin is required for this plugin to work. Please install and activate it.</p></div>';
    });
    return;
}

// Register ACF options page and fields.
add_action('acf/init', function () {
    // Add options page.
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => 'Google Review Count',
            'menu_title' => 'Google Review Count',
            'menu_slug'  => 'google-review-count',
            'capability' => 'edit_posts',
            'redirect'   => false,
        ]);
    }

    // Register fields programmatically.
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group([
            'key' => 'group_google_review_fields',
            'title' => 'Google Review Fields',
            'fields' => [
                [
                    'key' => 'field_review_count',
                    'label' => 'Review Count',
                    'name' => 'review-count',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_average_rating',
                    'label' => 'Average Rating',
                    'name' => 'average-rating',
                    'type' => 'text',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'google-review-count',
                    ],
                ],
            ],
        ]);
    }
});

// Enqueue the JavaScript file and pass ACF values.
add_action('wp_enqueue_scripts', function () {
    // Enqueue the JavaScript file.
    wp_enqueue_script(
        'google-review-replacer',
        plugin_dir_url(__FILE__) . 'js/replace-placeholders.js',
        [],
        '1.0',
        true // Load in footer
    );

    // Fetch ACF values (ensure ACF is active).
    $review_count = function_exists('get_field') ? get_field('review-count', 'option') : 'N/A';
    $average_rating = function_exists('get_field') ? get_field('average-rating', 'option') : 'N/A';

    // Pass values to JavaScript.
    wp_localize_script('google-review-replacer', 'acfData', [
        'reviewCount' => $review_count,
        'averageRating' => $average_rating,
    ]);
});
