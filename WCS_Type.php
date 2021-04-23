<?php

    if( !defined('ABSPATH') ) {            
        exit;
    }

    require_once(ABSPATH . "/wp-includes/class-wp-post-type.php");

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
         * Arguments passed to register_post_type
         *
         * @var array
         */
        protected $args = [];

        /**
         * Array of Wordpress Meta boxes that containts fields
         * Must be instances of the WCSGroup class
         *
         * @var array
         */
        protected $groups = [];

        /**
         * Post type keys already created in the Wordpress Core
         *
         * @var array
         */
        private $reserved_keys = ['post','page','attachment','revision','nav_menu_item','custom_css','customize_changeset','oembed_cache','user_request','wp_block','action','author','order','theme'];

        /**
         * Set the post type key
         *
         * @param string $key
         */
        public function __construct( string $key, array $args = [] )
        {
            if( $this->validateKey($key) ) {
                
                $this->key = $key;
                $this->args = $args;

            } else {
                // @todo throw exception
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
        public function setArgs( $key, $value = null )
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
         */
        public function hook() : bool
        {
            if( get_post_type_object($this->key) === null ) {

                register_post_type($this->key, $this->args);

            }

            // Hook each group into the current post type
            foreach( $this->groups as $group ) {

                $group->setPostTypes($this->key);

                $group->hook();

            }

            return true;
        }
        
        /**
         * Add one or more instances of WCS_Group
         *
         * @param [type] $groups
         * @return void
         */
        public function addGroups( $groups )
        {
            $groups = is_array($groups) ? $groups : [$groups];

            foreach( $groups as $group ) {
                if( is_a($group, "WCS_Group") ) {
                    $this->groups[$group->key] = $group;
                } else {
                    // @todo throw exception
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
        private function validateKey( $key ) : bool
        {
            return is_string($key) && preg_match("/^[a-z\-\_]{1,20}$/", $key);
        }
   
    }