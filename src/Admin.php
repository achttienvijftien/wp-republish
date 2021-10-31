<?php
/**
 * This file contains the logic for Bootstrap.
 *
 * @package AchttienVijftien\Plugin\Republish
 */

namespace AchttienVijftien\Plugin\Republish;

use AchttienVijftien\Plugin\Republish\Admin\SetDate;

/**
 * Admin only functionality.
 *
 * @package AchttienVijftien\Plugin\Republish
 */
class Admin {

    /**
     * Admin constructor.
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        $this->init();
    }

    /**
     * Initialize admin functions.
     */
    public function enqueue_scripts( $hook ): void {

        // bail eary if not on post.php
        if ( 'post.php' !== $hook ) {
            return;
        }

        wp_enqueue_script( 'wp-republish-js', plugin_dir_url( __FILE__ ) . '../assets/js/index.js', array(), '0.1.0' );
        wp_enqueue_style( 'wp-republish-css', plugin_dir_url( __FILE__ ) . '../assets/css/style.css', array(), '0.1.0' );
    }

    /**
     * Initialize admin functions.
     */
    public function init(): void {
        new SetDate();
    }
}
