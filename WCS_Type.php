<?php

    if( !defined('ABSPATH') ) {            
        exit;
    }

    require_once(ABSPATH . "/wp-includes/class-wp-post-type.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/WCS_Group.php");

    class WCS_Type
    {
        /**
         * Post type system reference.
         * Must not exceed 20 characters and may only contain lowercase alphanumeric characters, dashes, and underscores.
         *
         * @var string
         */
        protected $name;

        /**
         * Internal WP_Post_Type class. This will define all necessary
         * settings for a post type in Wordpress.
         * 
         * https://core.trac.wordpress.org/browser/tags/5.7.1/src/wp-includes/class-wp-post-type.php#L17
         *
         * @var class
         */
        protected $postInstance;

        /**
         * Array of Wordpress Meta boxes that containts fields
         * Must be instances of the WCSGroup class
         *
         * @var array
         */
        protected $groups = [];

        public function __construct( string $name, $args = [] )
        {
            if( $this->validateName($name) ) {
                
                $this->name = $name;

                if( is_a($args, 'WP_Post_Type') ) {
                    
                    $this->postInstance = $args;

                } else {

                    $this->postInstances = new WP_Post_Type($name, $args);

                }
            
                return true;
            }

            return false;
        }

        /**
         * Validate according to the definitions in Wordpress built-in function sanitize_key()
         *
         * @param string $name
         * @return bool
         */
        public function validateName( $name ) : bool
        {
            return is_string($name) && strlen($name) > 0 && strlen($name) <= 20 && preg_match("/^[a-z\_\-]{1,20}$/", $name);
        }
                
    }