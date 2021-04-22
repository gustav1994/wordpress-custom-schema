<?php

    if( !defined('ABSPATH') ) {            
        exit;
    }

    abstract class WCS_Field
    {    
        /**
         * Will save custom meta data under this name
         *
         * @var string
         */
        protected $key;

        /**
         * End-user readable name
         *
         * @var [type]
         */
        protected $name;

        /**
         * Input description
         *
         * @var string
         */
        protected $description;

        /**
         * Closure function for sanitizing value if needed
         *
         * @var callable
         */
        protected $sanitizer;

        /**
         * Callable to validate the input field data before saving
         *
         * @var callable
         */
        protected $validator;

        /**
         * Utilizes the Bootstrap grid system this will be the start position
         * in a 12 width grid system.
         * 
         * The difference between the start_position and the end_position determines the width.
         * length = end_position - start_postion
         * 
         * By definition the length cannot exceed 12 
         *
         * @var int
         */
        protected $start_position;

        /**
         * End position
         *         
         * @return int
         */
        protected $end_position;
        
        /**
         * Enforce sub-classes to implement a rendering method
         *
         * @return void
         */  
        abstract function render( $post ) : string;

        /**
         * Define how the field data should be sanitized before calling
         * wordpress function update_post_meta()
         */
        abstract function sanitize( $value );             

        /**
         * Constructor taking in the field name 
         *
         * @param string $key
         */
        public function __construct( string $key )
        {
            if( $this->validateKey($key) ) {
                
                $this->key = $key;

                add_action('save_post', [$this, "save"]);

            }

            return $this;
        }

        /**
         * Function that will hook into Wordpress Save_post and ensure that 
         * meta data will be updated on the post
         *
         * @return integer
         */
        public function save( int $post_id, $post ) : int
        {            
            $valid = is_callable($this->validator) ? $this->validator($_POST[$this->key]) : true;
            $sanitized = is_callable($this->sanitizer) ? $this->sanitizer($_POST[$this->key]) : $this->sanitize($_POST[$this->key]);            

            if( current_user_can("edit_post", $post_id) && $valid && !wp_is_post_autosave($post_id) ) {

                update_post_meta($post_id, $this->key, $sanitized);

            }   
            
            return $post_id;
        }  

        /**
         * Set readable name for the input field
         *
         * @param string $name
         * @return void
         */
        public function setName( string $name )
        {
            $this->name = $name;

            return $this;
        }

        /**
         * Set readable description for the input field
         *
         * @param string $name
         * @return void
         */
        public function setDescription( string $description )
        {
            $this->description = $description;

            return $this;
        }

        /**
         * Determines the field position in a bootstrap 12 width grid.
         * Empty spaces will be filled with offsets by the fieldgroup
         *
         * @param integer $start
         * @param integer $end
         * @return void
         */
        public function setPosition( int $start, int $end)
        {
            $length = $end - $start;

            if( $length <= 12 && $length > 0 ) {

                $this->start_position = $start;
                $this->end_position = $end;

            }

            return $this;
        }

        /**
         * Set custom sanitizer in the validator file (not needed very often)
         *
         * @param [type] $callable
         * @return void
         */
        public function setSanitizer( $callable )
        {
            if( is_callable($callable) ) {
                $this->sanitizer = $callable;
            }

            return $this;
        }

        /**
         * Set custom validator in the migration file
         *
         * @param [type] $callable
         * @return void
         */
        public function setValidator( $callable )
        {
            if( is_callable($callable) ) {
                $this->validator = $callable;
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