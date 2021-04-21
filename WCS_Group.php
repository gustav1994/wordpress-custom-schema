<?php

    if( !defined('ABSPATH') ) {            
        exit;
    }

    class WCS_Group
    {
        /**
         * Key name to refeer to this meta box
         *
         * @var [type]
         */
        protected $key;

        /**
         * The Meta Box Title
         *
         * @var [type]
         */
        protected $name;

        /**
         * Array of custom fields all instances of class that inherits the
         * WCS_Field instance in /wp-content/plugins/wordpress-custom-schema/fields
         *
         * @var array
         */
        protected $fields = [];

        /**
         * Initiate the field group and tell wordpress when to hook into
         * 
         * @var string $key
         */
        public function __construct( string $key )
        {
            if( $this->validateKey($key) ) {

                $this->key = $key;                

            }

            return $this;
        }
        
        /**
         * Hook into wordpress add_meta_boxes with the render function
         *
         * @return void
         */
        public function activate()
        {
            return add_action("add_meta_boxes", function(){
                
                add_meta_box($this->key, $this->name, [$this, "render"], [], 'normal', 'default');        

            });
        }

        public function render()
        {
            // Render the basic layout and also call each field to render it's content
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