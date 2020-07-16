<?php
    /**
     *  @package fuse-galleries
     *
     *  Plugin Name: Fuse Page Galleries
     *  Plugin URI: https://fusecms.org/plugins/fuse-galleries
     *  Description: Add image galleries to your sites pages, post and other content types quickly and easily.
     *  Version: 1.0
     *  Author: Fuse CMS
     *  Author URI: https://fusecms.org
     *  License: GPLv2 or later
     *  Text Domain: fuse
     */

    namespace Fuse\Plugin\Galleries;


    define ('FUSE_PLUGIN_GALLERIES_BASE_URI', __DIR__);
    define ('FUSE_PLUGIN_GALLERIES_BASE_URL', plugins_url ('', __FILE__));


    $setup = new Setup ();