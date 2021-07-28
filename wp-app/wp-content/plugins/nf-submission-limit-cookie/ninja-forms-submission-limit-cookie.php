<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Plugin Name: Ninja Forms - Submission Limit Cookie
 * Plugin URI: http://etzelstorfer.com/en/downloads/ninja-forms-subm…ion-limit-cookie/
 * Description: Limit form submission per user via cookie
 * Version: 3.0
 * Author: Hannes Etzelstorfer
 * Author URI: http://etzelstorfer.com/en/
 * Text Domain: ninja-forms-submission-limit-cookie
 *
 * Copyright 2017 Hannes Etzelstorfer.
 */

if( version_compare( get_option( 'ninja_forms_version', '0.0.0' ), '3', '<' ) || get_option( 'ninja_forms_load_deprecated', FALSE ) ) {

    //include 'deprecated/ninja-forms-submission-limit-cookie.php';

} else {

    /**
     * Class NF_SubmissionLimitCookie
     */
    final class NF_SubmissionLimitCookie
    {
        const VERSION = '3.0';
        const SLUG    = 'submission-limit-cookie';
        const NAME    = 'Submission Limit Cookie';
        const AUTHOR  = 'Hannes Etzelstorfer';
        const PREFIX  = 'NF_SubmissionLimitCookie';

        /**
         * @var NF_SubmissionLimitCookie
         * @since 3.0
         */
        private static $instance;

        /**
         * Plugin Directory
         *
         * @since 3.0
         * @var string $dir
         */
        public static $dir = '';

        /**
         * Plugin URL
         *
         * @since 3.0
         * @var string $url
         */
        public static $url = '';

        /**
         * Main Plugin Instance
         *
         * Insures that only one instance of a plugin class exists in memory at any one
         * time. Also prevents needing to define globals all over the place.
         *
         * @since 3.0
         * @static
         * @static var array $instance
         * @return NF_SubmissionLimitCookie Highlander Instance
         */
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof NF_SubmissionLimitCookie)) {
                self::$instance = new NF_SubmissionLimitCookie();

                self::$dir = plugin_dir_path(__FILE__);

                self::$url = plugin_dir_url(__FILE__);

                /*
                 * Register our autoloader
                 */
                spl_autoload_register(array(self::$instance, 'autoloader'));
            }
            
            return self::$instance;
        }

        public function __construct()
        {
            /*
             * Required for all Extensions.
             */
            //add_action( 'admin_init', array( $this, 'setup_license') );

            add_filter( 'ninja_forms_from_restriction_settings', array($this, 'register_restriction_settings') );

            add_filter( 'ninja_forms_display_show_form', array($this, 'restrict_form_output'), 10, 3 );

            add_action( 'ninja_forms_after_submission', array($this, 'set_restriction_cookie') );
        }

        /**
         * Optional. If your extension processes or alters form submission data on a per form basis...
         */
        public function register_restriction_settings($settings)
        {
            $settings[ 'submission-limit-cookie-set' ] = NF_SubmissionLimitCookie()->config( 'PluginSettings' );
            return $settings;
        }

        /*
         * Decide wether or not show the form
         */
        public function restrict_form_output( $show_form, $form_id, $form ){
            if( $form->get_setting( 'waiting_time_between_submissions' ) && isset($_COOKIE[self::PREFIX . '_' . $form_id ]) ){
                if( $form->get_setting( 'user_sub_limit_behavior' ) == 'message-only' )
                    echo '<div class="ninja-forms-user-submission-limit">' . $form->get_setting( 'user_sub_limit_msg' ) . '</div>';
                return false;
            }
            return true;
        }


        /*
         * Set cookie to detect user on next visit
         */
        public function set_restriction_cookie( $form_data ){
            if( isset( $form_data['form_id'] ) ){
                $form = Ninja_Forms()->form( $form_data['form_id'] )->get();

                setcookie( self::PREFIX . '_' . $form_data['form_id'], 1, time() + ($form->get_setting( 'waiting_time_between_submissions' ) * 60 ), "/"); 
            }
        }

        /*
         * Optional methods for convenience.
         */
        public function autoloader($class_name)
        {
            if (class_exists($class_name)) return;

            if ( false === strpos( $class_name, self::PREFIX ) ) return;

            $class_name = str_replace( self::PREFIX, '', $class_name );
            $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

            if (file_exists($classes_dir . $class_file)) {
                require_once $classes_dir . $class_file;
            }
        }
        
        /**
         * Template
         *
         * @param string $file_name
         * @param array $data
         */
        public static function template( $file_name = '', array $data = array() )
        {
            if( ! $file_name ) return;

            extract( $data );

            include self::$dir . 'includes/Templates/' . $file_name;
        }
        
        /**
         * Config
         *
         * @param $file_name
         * @return mixed
         */
        public static function config( $file_name )
        {
            return include self::$dir . 'includes/Config/' . $file_name . '.php';
        }

        /*
         * Required methods for all extension.
         */

        public function setup_license()
        {
            if ( ! class_exists( 'NF_Extension_Updater' ) ) return;

            new NF_Extension_Updater( self::NAME, self::VERSION, self::AUTHOR, __FILE__, self::SLUG );
        }
    }

    /**
     * The main function responsible for returning The Highlander Plugin
     * Instance to functions everywhere.
     *
     * Use this function like you would a global variable, except without needing
     * to declare the global.
     *
     * @since 3.0
     * @return {class} Highlander Instance
     */
    function NF_SubmissionLimitCookie()
    {
        return NF_SubmissionLimitCookie::instance();
    }

    NF_SubmissionLimitCookie();
}
