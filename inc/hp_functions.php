<?php

/**
 * HelloPixel theme functions
 * Version 1.0
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * Block Comments
 */

    // Redirect any user trying to access comments page
    add_action ('admin_init', function () {
        global $pagenow;
        if($pagenow === 'edit-comments.php'){
            wp_redirect(admin_url());
            exit;
        }
        // Remove comments metabox from dashboard
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        // Disable support for comments and trackbacks in post types
        foreach (get_post_types() as $post_type) {
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    });
    
    // Close comments on the front-end
    add_filter('comments_open', '__return_false', 20, 2);
    add_filter('pings_open', '__return_false', 20, 2);
    
    // Hide existing comments
    add_filter('comments_array', '__return_empty_array', 10, 2);
    
    // Remove comments page in menu
    add_action('admin_menu', function () {
        remove_menu_page('edit-comments.php');
    });
    
    // Remove comments links from admin bar
    add_action('init', function () {
        if (is_admin_bar_showing()) {
            remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
        }
    });


/**
 * Redirect Author Archive
 */
    add_action('template_redirect', function () {
        global $wp_query;
        if(is_author()){
            wp_redirect(get_option('home'), 302); 
            exit; 
        }
    });
    

/**
 * Classic editor for posts
 */
    add_action( 'current_screen', function() {
    $currentScreen = get_current_screen();

    // Disable block editor for all Posts
    if ( $currentScreen->id === 'post' ) {
        add_filter( 'use_block_editor_for_post', '__return_false', 10 );
        return;
    }

    // Only continue for Pages
    if ( $currentScreen->id !== 'page' ) {
        return;
    }

    // Get the page currently being edited
    $post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0;

    // Get the Posts Page and Privacy Policy Page IDs
    $posts_page_id   = (int) get_option( 'page_for_posts' );
    $privacy_page_id = (int) get_option( 'wp_page_for_privacy_policy' );

    if ( $post_id === $posts_page_id || $post_id === $privacy_page_id ) {
        add_filter( 'use_block_editor_for_post', '__return_false', 10 );
    }
} );


/**
 * Roffy Pre Debug
 */
    function roffy_pre($printMe, $title = false) {
        if ($title) echo '<h2>'.$title.'</h2>';
        echo '<hr><pre style="text-align:left;font-size:0.7em;line-height:1.1em;z-index:9999;">';
        print_r($printMe);
        echo '</pre><hr>';
    }


/**
 * Database Query Count Debug
 */
    add_action('wp_footer', function() {
        echo "<script>console.log('Database Query Count: ".get_num_queries()."');</script>";
    });


/**
 * Development Environment check
 */
    function is_dev_env() {
        $host = wp_parse_url( home_url(), PHP_URL_HOST );
        return str_ends_with( $host, '.localhost' );
    }


/**
 * Remove Patterns and Openverse
 */
    function disable_block_patterns() {

        remove_theme_support( 'core-block-patterns' );

    }
    add_action( 'after_setup_theme', 'disable_block_patterns', 11 );
    add_filter( 'should_load_remote_block_patterns', '__return_false' );
    add_filter( 'block_editor_settings_all', function ( $settings ) {

        $settings['enableOpenverseMediaCategory'] = false;

        return $settings;

    } );



/**
 * Return Structured Menu Array
 */
    function build_nav_menu_tree( array $items ) {

        $items_by_parent = [];

        foreach ( $items as $item ) {
            $parent_id = (int) $item->menu_item_parent;
            $items_by_parent[ $parent_id ][] = $item;
        }

        $build_branch = function ( int $parent_id ) use ( &$build_branch, &$items_by_parent ) {

            $branch = [];

            if ( empty( $items_by_parent[ $parent_id ] ) ) {
                return $branch;
            }

            foreach ( $items_by_parent[ $parent_id ] as $item ) {

                $children = $build_branch( (int) $item->ID );

                if ( ! empty( $children ) ) {
                    $item->children = $children;
                }

                $branch[ $item->ID ] = $item;
            }

            return $branch;
        };

        return $build_branch( 0 );
    }

    function nav_menu_to_tree( $menu ) {

        $items = wp_get_nav_menu_items( $menu );

        return ! empty( $items )
            ? build_nav_menu_tree( $items )
            : null;
    }


/**
 * wp-login.php styling
 */
    function hellopixel_login_styles() {
        $css_path = get_stylesheet_directory() . '/css/login.css';
        wp_enqueue_style(
            'hellopixel-login',
            get_stylesheet_directory_uri() . '/css/login.css',
            [],
            file_exists( $css_path ) ? filemtime( $css_path ) : null
        );
    }
    add_action( 'login_enqueue_scripts', 'hellopixel_login_styles' );

    
/**
 * wp-login.php HelloPixel Credit link
 */
    function hellopixel_login_footer() {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const nav = document.getElementById('nav');

                if (nav) {
                    nav.insertAdjacentHTML(
                        'beforeend',
                        '<br><span class="hellopixel-credit"><a href="https://hellopixel.co.uk" target="_blank" rel="noopener">Developed by HelloPixel</a></span>'
                    );
                }
            });
        </script>
        <?php
    }
    add_action( 'login_footer', 'hellopixel_login_footer' );


/**
 * wp-login.php Logo
 */
    function hellopixel_login_logo_url() {
        return home_url();
    }
    add_filter( 'login_headerurl', 'hellopixel_login_logo_url' );
    function hellopixel_login_logo_title() {
        return get_bloginfo( 'name' );
    }
    add_filter( 'login_headertext', 'hellopixel_login_logo_title' );

/**
 * wp-login.php Favicon
 */
    add_action( 'login_head', function() {
        ?>
            <link rel="icon" type="image/x-icon" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/favicon/favicon.ico' ); ?>">
        <?php
    } );
    
/**
 * HelloPixel User Avatar
 */
    add_filter( 'get_avatar_url', function( $url, $id_or_email, $args ) {

    $user = false;

    if ( is_numeric( $id_or_email ) ) {
        $user = get_user_by( 'id', $id_or_email );
    } elseif ( is_object( $id_or_email ) && isset( $id_or_email->user_id ) ) {
        $user = get_user_by( 'id', $id_or_email->user_id );
    } elseif ( is_string( $id_or_email ) ) {
        $user = get_user_by( 'email', $id_or_email );
    }

    if ( $user && untrailingslashit( $user->user_url ) === 'https://hellopixel.co.uk' ) {
        return get_stylesheet_directory_uri() . '/images/hellopixel-avatar.png';
    }

    return $url;

}, 10, 3 );

/**
 * Remove [...] from Excerpt
 */
function custom_excerpt_more($more) {
    return '';
}
add_filter('excerpt_more', 'custom_excerpt_more');