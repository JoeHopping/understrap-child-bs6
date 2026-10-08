<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Register blocks.
 */
function register_custom_blocks() {

    register_block_type(
        get_stylesheet_directory() . '/blocks/basic-content'
    );

    // register_block_type(
    //     get_stylesheet_directory() . '/blocks/cards'
    // );

    // register_block_type(
    //     get_stylesheet_directory() . '/blocks/centered-txt'
    // );

    register_block_type(
        get_stylesheet_directory() . '/blocks/hero'
    );

    // register_block_type(
    //     get_stylesheet_directory() . '/blocks/hero-split'
    // );

    register_block_type(
        get_stylesheet_directory() . '/blocks/image-banner'
    );

    // register_block_type(
    //     get_stylesheet_directory() . '/blocks/narrow-content'
    // );

    // register_block_type(
    //     get_stylesheet_directory() . '/blocks/quote'
    // );

    // register_block_type(
    //     get_stylesheet_directory() . '/blocks/team-grid'
    // );

    // register_block_type(
    //     get_stylesheet_directory() . '/blocks/testimonial'
    // );

    // register_block_type(
    //     get_stylesheet_directory() . '/blocks/video'
    // );

}

add_action( 'init', 'register_custom_blocks' );


/**
 * Debug custom blocks.
 */
// function debug_custom_block( $block_name, $block_directory ) {

//     $block_path = get_stylesheet_directory() . '/blocks/' . $block_directory;
//     $block_json = $block_path . '/block.json';

//     error_log( 'HelloPixel block path: ' . $block_path );

//     error_log(
//         'HelloPixel block.json exists: ' .
//         ( file_exists( $block_json ) ? 'YES' : 'NO' )
//     );

//     if ( file_exists( $block_json ) ) {

//         error_log(
//             'HelloPixel block.json contents: ' .
//             file_get_contents( $block_json )
//         );

//     }

//     error_log(
//         'HelloPixel block registered: ' .
//         (
//             WP_Block_Type_Registry::get_instance()->is_registered( $block_name )
//             ? 'YES'
//             : 'NO'
//         )
//     );

// }

// add_action( 'init', function () {

//     debug_custom_block(
//         'hellopixel/centered-txt',
//         'centered_txt'
//     );

// }, 20 );
