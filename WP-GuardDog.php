<?php
/**
 * Plugin Name: WP GuardDog
 * Description: This plugin prevents users from taking screenshots or downloading images or videos from your WordPress site. It also prevents screen recorders from being used. Additionally, it prevents browser extensions from being used.
 * Author: Johnathon M. Horner
 * Author URI: https://www.github.com/jhorner6511
 * License: GNU v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */

add_action( 'wp_loaded', function()
{
    // Disable right-click on images
    add_filter( 'wp_print_scripts', function()
    {
        ?>
        <script>
            document.addEventListener( 'contextmenu', function( e ) {
                e.preventDefault();
            }, false );
        </script>
        <?php
    } );

    // Prevent screenshots
    add_filter( 'body_class', function( $classes )
    {
        $classes[] = 'no-screenshots';
        return $classes;
    } );

    // Prevent downloads
    add_filter( 'wp_headers', function( $headers )
    {
        $headers[] = 'Content-Disposition: attachment; filename="no-download.txt"';
        return $headers;
    } );

    // Prevent video downloads
    add_filter( 'wp_embed_defaults', function( $defaults )
    {
        $defaults['disable_downloads'] = true;
        return $defaults;
    } );

    // Prevent screen recorders
    add_action( 'wp_head', function()
    {
        ?>
        <script>
            // Prevent screen recording
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({video: false}).then(function(stream) {
                    stream.stop();
                });
            }
        </script>
        <?php
    } );

    // Prevent browser extensions
    add_filter( 'script_loader_tag', function( $tag, $handle )
    {
        if ( 'chrome-extension' === $handle ) {
            return '';
        }
        return $tag;
    }, 10, 2 );
} );
