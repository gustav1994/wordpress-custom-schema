<?php

    /**
     * Plugin Name: Wordpress Custom Schema     
     * Plugin URI: https://github.com/gustav1994/wordpress-custom-schema
     * Description: Use this plugin to create custom fields and associate them with custom post types
     * Version: 1.0
     * Author: Gustav Svendsen
     * Author URI: https://gustavs.dk     
     */

    if( !defined('ABSPATH') ) {            
        exit;
    }

    /* - - - - - - - - - - - - HOOK INTO THE INIT ACTION AND RENDER ALL SCHEMA'S - - - - - - - - - - - - - - - - */

    add_action("init", function(){

        $path = plugin_dir_path(__FILE__);

        $schema_locations = [$path . "schema/"];

        if( is_multisite() ) {

            $blog_id = get_current_blog_id();

            $blog = get_blog_details($blog_id, false);

            if( file_exists($path . "schema/{$blog_id}") ) {

                $schema_locations[] = $path . "schema/{$blog_id}";

            }

            if( !empty($blog->domain) && file_exists( $path . "schema/{$blog->domain}") ) {

                $schema_locations[] = $path . "schema/{$blog->domain}";

            }

        }
        
        // Load schema files from all locations
        foreach( $schema_locations as $schema_location ) {

            $iterator = new RecursiveDirectoryIterator($schema_location);

            foreach(new RecursiveIteratorIterator($iterator) as $schema_file) {
                
                if( $schema_file->getExtension() == 'php' ) {

                    include_once( $schema_file->getPathname() );

                }

            }

        }

    });

    /* - - - - - - - - - - - - LOAD SCRIPTS AND STYLESHEETS IN ADMIN INTERFACE - - - - - - - - - - - - - - - - */
    
