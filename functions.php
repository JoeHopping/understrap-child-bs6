<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * HelloPixel Functions
 * Understrap Functions
 * Register Blocks
 */
include_once( 'inc/hp_functions.php' );
include_once( 'inc/understrap_functions.php' );
include_once( 'inc/register_blocks.php' );


/**
 * SCF JSON configuration directory.
 */
add_filter( 'acf/settings/save_json', function () {

    return get_stylesheet_directory() . '/inc/scf-json';

} );


/**
 * SCF JSON loading.
 */
add_filter( 'acf/settings/load_json', function () {

    return [
        get_stylesheet_directory() . '/inc/scf-json',
    ];

} );


/**
 * Disable SCF administration outside development.
 */
if ( !is_dev_env() ) {

    add_filter( 'acf/settings/show_admin', '__return_false' );
    add_filter( 'acf/settings/show_updates', '__return_false', 100 );

}


/**
 * Tweak some WP admin styles
 */
function my_custom_admin_styles() {
    echo '<style>
        @media (min-width: 1000px) {
            .interface-interface-skeleton__sidebar {
                width: 400px !important;
                max-width: 400px !important;
            }

            .interface-complementary-area, .interface-complementary-area__fill {
                width: 100% !important
            }
        }
    </style>';
}
add_action('admin_head', 'my_custom_admin_styles');


/**
 * HelloPixel Editor Button
 */
function hellopixel_editor_button_assets() {

    $js_file  = get_stylesheet_directory() . '/js/editor/button.js';
    $css_file = get_stylesheet_directory() . '/css/editor/button.css';

    wp_enqueue_script(
        'hellopixel-editor-button',
        get_stylesheet_directory_uri() . '/js/editor/button.js',
        array( 'jquery' ),
        filemtime( $js_file ),
        true
    );

    wp_enqueue_style(
        'hellopixel-editor-button',
        get_stylesheet_directory_uri() . '/css/editor/button.css',
        array(),
        filemtime( $css_file )
    );

    wp_localize_script(
        'hellopixel-editor-button',
        'helloPixelEditorButton',
        array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'hellopixel_editor_button' ),
        )
    );
}

add_action(
    'admin_enqueue_scripts',
    'hellopixel_editor_button_assets'
);


/**
 * Get published pages for the editor button modal.
 */
function hellopixel_get_editor_button_pages() {

    check_ajax_referer(
        'hellopixel_editor_button',
        'nonce'
    );

    $pages = get_pages(
        array(
            'sort_column' => 'post_title',
            'sort_order'  => 'ASC',
            'post_status' => 'publish',
        )
    );

    $results = array();

    foreach ( $pages as $page ) {

        $results[] = array(
            'id'    => $page->ID,
            'title' => $page->post_title,
            'url'   => get_permalink( $page->ID ),
        );
    }

    wp_send_json_success( $results );
}

add_action(
    'wp_ajax_hellopixel_get_editor_button_pages',
    'hellopixel_get_editor_button_pages'
);


/**
 * Only display HelloPixel blocks in the editor
 */
    function hellopixel_allowed_blocks( $allowed_block_types, $editor_context ) {

        $registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

        return array_keys(
            array_filter(
                $registered_blocks,
                function ( $block ) {
                    return str_starts_with( $block->name, 'hellopixel/' )
                        || $block->name === 'contact-form-7/contact-form-selector';
                }
            )
        );
    }

    add_filter( 'allowed_block_types_all', 'hellopixel_allowed_blocks', 10, 2 );


/**
 * Enqueue the focal point picker assets.
 */
function hellopixel_focal_point_admin_assets( $hook ) {

    // Only load on post/page editing screens.
    if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        return;
    }

    wp_enqueue_script(
        'hellopixel-focal-point',
        get_stylesheet_directory_uri() . '/js/editor/focal-point.js',
        array( 'jquery' ),
        filemtime( get_stylesheet_directory() . '/js/editor/focal-point.js' ),
        true
    );

    wp_enqueue_style(
        'hellopixel-focal-point',
        get_stylesheet_directory_uri() . '/css/editor/focal-point.css',
        array(),
        filemtime( get_stylesheet_directory() . '/css/editor/focal-point.css' )
    );
}

add_action( 'admin_enqueue_scripts', 'hellopixel_focal_point_admin_assets' );



