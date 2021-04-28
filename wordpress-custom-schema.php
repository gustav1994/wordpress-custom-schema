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

            if( !empty($blog->domain) && file_exists( $path . "schema/{$blog->domain}") ) {

                $schema_locations[] = $path . "schema/{$blog->domain}/";

            }

        }
        
        // Load schema files from all locations
        foreach( $schema_locations as $schema_location ) {

            foreach( scandir($schema_location) as $file ) {

                $absPath = $schema_location . $file;

                if( is_file($absPath) && substr($file, -4) === ".php" ) {

                    include_once( $absPath );

                }

            }

        }

    });

    /* - - - - - - - - - - - - LOAD SCRIPTS AND STYLESHEETS IN ADMIN INTERFACE - - - - - - - - - - - - - - - - */
    
    add_action('admin_enqueue_scripts', function(){

        wp_enqueue_style('bootstrap', plugin_dir_url(__FILE__) . '/assets/css/admin.min.css');           

    });