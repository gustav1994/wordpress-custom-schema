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


        public function __construct() {}

        public function hook() : bool {}
        
        public function addGroups( $groups ) {}

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