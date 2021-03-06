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
        protected string $key;

        /**
         * Arguments passed to register_post_type
         *
         * @var array
         */
        protected array $args = [];

        /**
         * Array of Wordpress Meta boxes that containts fields
         * Must be instances of the WCSGroup class
         *
         * @var array
         */
        protected array $groups = [];

        /**
         * Keep state of the hook int WP
         *
         * @var boolean
         */
        protected bool $hooked = false;

        /**
         * Set the post type key
         *
         * @param string $key
         * @throws Exception
         */
        public function __construct( string $key, array $args = [] )
        {
            if( $this->validateKey($key) ) {
                
                $this->key = $key;
                $this->args = array_merge([

                    'supports' => ['title','editor','revisions','excerpt','thumbnail','custom-fields'],
                    'has_archive' => true

                ], $args);
                
            } else {

                throw new Exception("Invalid key-format");

            }           

            return $this;
        }

        /**
         * Set or update and argument
         *
         * @param string $key
         * @param [type] $value
         * @return void
         */
        public function setArgs($key, $value = null ) : object
        {
            $key = is_array($key) ? $key : [$key => $value];
            
            foreach($key as $index => $value) { 
                $this->args[$index] = $value;
            }

            return $this;
        }

        /**
         * Hook into the wordpress eco-system and register post-type
         * along all its groups that will render the custom fields
         *
         * @return boolean
         * @throws Exception
         */
        public function hook( bool $force = false ) : bool
        {   
            if( function_exists("register_post_type") && function_exists('get_post_type_object') ) {            

                if( $this->hooked == false || $force ) {

                    if( get_post_type_object($this->key) === null ) {

                        register_post_type($this->key, $this->args);

                    }

                    // Hook each group into the current post type
                    foreach( $this->groups as $group ) {

                        $group->setPostTypes($this->key);

                        $group->hook();

                    }
                    
                    $this->hooked = true;

                } else {
                    throw new Exception("Type was already hooked into Wordpress");
                }

            } else {
                throw new Exception("Wordpress functions register_post_type() and get_post_type_object was not available");
            }

            return true;
        }

        /**
         * Add one or more instances of WCS_Group
         *
         * @param [type] $groups
         * @return void
         * @throws Exception
         */
        public function addGroups( $groups ) : object
        {
            $groups = is_array($groups) ? $groups : [$groups];

            foreach( $groups as $group ) {
                if( is_a($group, "WCS_Group") ) {
                    $this->groups[$group->key] = $group;
                } else {
                    throw new Exception("Group must inherit the WCS Group class");
                }
            }

            return $this;
        }

        /**
         * Function to validate key according to the requiements put up by
         * wordpress built in function sanitize_key
         *
         * @param string $key
         * @return bool
         */
        private function validateKey(string $key ) : bool
        {
            return preg_match("/^[a-z\-_]{1,20}$/", $key);
        }
   
    }