<?php

    if( !defined('ABSPATH') ) {            
        exit;
    }

    class WCS_Type
    {
        /**
         * Post type system reference.
         * Must not exceed 20 characters and may only contain lowercase alphanumeric characters, dashes, and underscores.
         *
         * @var string
         */
        protected $key;

        /**
         * Array of Wordpress Meta boxes that containts fields
         * Must be instances of the WCSGroup class
         *
         * @var array
         */
        protected $groups = [];

        /**
         * Define the custom post type name along with it's arguments.
         * Will register new post type if it not already exists
         *
         * @param string $name
         * @param array $args
         */
        public function __construct( string $key )
        {          
            return $this;
        }

        /**
         * 1. Create post-type if it does not exists
         * 2. 
         *
         * @return void
         */
        public function activate()
        {

        }

        /**
         * Function to validate key according to the requiements put up by
         * wordpress built in function sanitize_key
         *
         * @param string $key
         * @return bool
         */
        private function validateKey( $key ) : bool
        {
            return is_string($key) && preg_match("/^[a-z\-\_]{1,20}$/", $key);
        }
   
    }