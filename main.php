<?php
/*
Plugin Name: Start Simple Share
Plugin URI: http://z2a.co/
Description: Start Simple Share plugin helps you to share articles to social network like-facebook, twitter, pinterest, stumbleupon, tumblr, linkedin. 
Version: 0.0.5
Author: Micro Solutions Bangladesh
Author URI: http://microsolutionsbd.com/
Text Domain: msbd-sssp
License: GPL2
*/

define('MSBD_SSSP_URL', trailingslashit(plugins_url(basename(dirname(__FILE__)))));

class MsbdStartSimpleShare {
    
    var $version = '0.0.5';
    var $plugin_name = 'Start Simple Share';

    /**
     * @var msbd_adsmp_options_obj
     */
    var $sssp_options_obj;    


    /**
     * @var todo note
     */
    var $sssp_options_name;


    /**
     * The variable that stores all current options
     */
    var $sssp_options;
    

    function __construct() {
        global $wpdb;
        
        $this->sssp_options_name = "_msbd_sssp_options";
        $this->sssp_options_obj = new MsbdSSSOptions($this);
        $this->admin = new MsbdSSSAdmin($this);
        
        add_action('init', array(&$this, 'init'));
        add_action('wp_enqueue_scripts', array(&$this, 'load_scripts_styles'), 100);

        add_filter('the_content', array(&$this, 'show_share_buttons'),  11);
        add_filter('the_excerpt', array(&$this, 'show_share_buttons'),  11);
        add_shortcode( 'msbd-sssp', array(&$this, 'show_share_buttons_shortcode') );
    }



    function init() {
        $this->sssp_options_obj->update_options();
        $this->sssp_options = $this->sssp_options_obj->get_option();
    }
    /* end of function : init() */


    function load_scripts_styles() {
        if( $this->sssp_options['sssp_font_awesome']=="yes" ) {
            
            wp_register_style('font-awesome', MSBD_SSSP_URL . 'font-awesome/css/font-awesome.min.css', false, '4.2.0', 'all');
            wp_enqueue_style('font-awesome');
        }
         
        wp_enqueue_style( "msbd-sssp", MSBD_SSSP_URL . 'css/msbd-sssp.css', false, false );
    }



    function show_share_buttons_shortcode($atts = null, $content = null) {
        return msbd_share_buttons_markup(true); //default false
    }


    function show_share_buttons($content) {
        global $post;

        $htmlContent = $content;
        $share_buttons = msbd_share_buttons_markup(false);
        $arrSettings = $this->sssp_options;

        // switch for placement of ssba
        switch ( $arrSettings['sssp_position'] ) {

            case 'before': // before the content
                $htmlContent = $share_buttons . $content;
                break;

            case 'after': // after the content
                $htmlContent = $content . $share_buttons;
                break;

            case 'both': // before and after the content
                $htmlContent = $share_buttons . $content . $share_buttons;
                break;
        }
        
        return $htmlContent;
    }



} // End of Class MsbdStartSimpleShare



require_once('libs/msbd-helper-functions.php');

if (!class_exists('MsbdSSSAdminHelper')) {
    require_once('libs/views/admin-view-helper-functions.php');
}

if (!class_exists('MsbdSSSOptions')) {
    require_once('libs/msbd-sssp-options.php');
}

if (!class_exists('MsbdSSSAdmin')) {
    require_once('libs/msbd-sssp-admin.php');
}

require_once('libs/sssp_buttons.php');



global $sssPO;
$sssPO = new MsbdStartSimpleShare();

/* end of file main.php */
