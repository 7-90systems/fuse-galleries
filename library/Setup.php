<?php
    /**
     *  Set up our plugin.
     */

    namespace Fuse\Plugin\Galleries;

    use Fuse\Queue;


    class Setup {

        /**
         *  Object constructor.
         */
        public function __construct () {
            // Add our JavaScript and CSS files
            add_filter ('fuse_javascript_admin_dependencies', array ($this, 'adminJavascript'));
            add_filter ('fuse_css_admin_dependencies', array ($this, 'adminCss'));

            // Set up our galleries
            $gallery = new Gallery ();

            // Admin area if needed
            if (is_admin ()) {
                $admin = new Admin ();
            } //if ()
        } // __construct ()



        /**
         *  Set up our CSS files.
         */
        public function adminCss ($deps) {
            wp_register_style ('fuse_galleries_admin', FUSE_PLUGIN_GALLERIES_BASE_URL.'/assets/css/admin.css');
            
            $deps [] = 'fuse_galleries_admin';
            
            return $deps;
        } // adminCss ()

        /**
         *  Set up our JavaScript files.
         */
        public function adminJavascript ($deps) {
            wp_register_script ('fuse_galleries_admin', FUSE_PLUGIN_GALLERIES_BASE_URL.'/assets/javascript/admin.js', array (
                'jquery',
                    'jquery-ui-core',
                    'jquery-ui-sortable'
            ));
            
            $deps [] = 'fuse_galleries_admin';
            
            return $deps;
        } // adminJavascript ()

    } // class Setup