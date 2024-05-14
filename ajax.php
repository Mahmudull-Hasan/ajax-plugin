<?php
/**
 * Plugin Name: Ajax Plugin
 * Description: This is Ajox Load Plugin
 * Version: 1.0.0
 * Author: Hasan Mahmud
 * Author URI: http://hasan.me
 * Plugin URI: http://google.com
 */

class Ajax_Load {
    public function __construct() {
        add_action('init', [$this, 'init']);
    }

    public function init() {
        add_action('wp_enqueue_scripts', [$this, 'load_assets']);

        //login user ajax request
        add_action('wp_ajax_contact', [$this, 'contact']);

        //non login user ajax request normal visitor
        add_action('wp_ajax_nopriv_backup', [$this, 'backup']);
    }

    //login user ajax request
    public function contact() {

        check_ajax_referer( 'contact');

        $email      = $_POST['email'];
        $subject    = $_POST['subject'];
        $message    = $_POST['message'];

        return wp_send_json([
            'email'     => $email,
            'subject'   => $subject,
            'message'   => $message,
        ] );
    }

    //non login user ajax request normal visitor
    public function backup() {
        //wp_send_json( $_POST );
        echo "Please wait data is loading.....";
        wp_die();
    }

    public function load_assets() {

        $ajax_url   = admin_url('admin-ajax.php');
        $nonce      = wp_create_nonce( 'contact' );

        if(is_page('contact')) {
            wp_enqueue_style('form-css', plugin_dir_url(__FILE__) . 'assets/css/form.css', '1.0');
            wp_enqueue_script('main-js', plugin_dir_url(__FILE__) . 'assets/js/main.js', ['jquery'], '1.0', true);
            wp_localize_script('main-js', 'ajax_object', [
                'ajax_url' => $ajax_url,
                'nonce'    => $nonce,
            ]);

            //wp_mail( 'admin@plugin-development', $subject, $message, ['From' => $email]);
        }
        
    }
}
new Ajax_Load();