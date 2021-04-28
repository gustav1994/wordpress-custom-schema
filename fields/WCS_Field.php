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
        public $key;

        /**
         * End-user readable name
         *
         * @var string
         */
        protected $name;

        /**
         * Input description
         *
         * @var string
         */
        protected $description;

        /**
         * Id for the HTML elements
         *
         * @var string
         */
        protected $element_id;

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
         * What post types to hook into
         *
         * @var array
         */
        protected $post_types = [];

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
        public $start_position;

        /**
         * End position
         *         
         * @return int
         */
        public $end_position;

        /**
         * Should field be visible as column in Post-Type overview
         *
         * @var boolean
         */
        public $visible_column = false;

        /**
         * If we ahve already hooked into the WP eco-system
         *
         * @var boolean
         */
        protected $hooked = false;
        
        /**
         * Enforce sub-classes to implement a rendering method
         *
         * @return void
         */  
        abstract function render( $post ) : string;

        /**
         * Define how the field data should be sanitized before calling
         * wordpress function update_post_meta()
         * 
         * @param mixed $value
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
                $this->element_id = "WCS-{$this->randomString(5, 10)}";               

            } else {
                throw new Exception("Invalid field key format");
            }

            return $this;
        }

        /**
         * Function that will hook into Wordpress Save_post and ensure that 
         * meta data will be updated on the post
         *
         * @return integer
         */
        public function save( int $post_id, $post = null ) : int
        {                                    
            if( current_user_can("edit_post", $post_id) && array_key_exists($this->getNonceName(), $_POST) && wp_verify_nonce($_POST[$this->getNonceName()]) ) {

                $valid = is_callable($this->validator) ? $this->validator($_POST[$this->key]) : true;
                $sanitized = is_callable($this->sanitizer) ? $this->sanitizer($_POST[$this->key]) : $this->sanitize($_POST[$this->key]);

                if( function_exists("update_post_meta") && $valid ) {

                    update_post_meta($post_id, $this->key, $sanitized);

                } else {

                    throw new Exception("Missing wp-functin update_post_meta");

                }

            }   
            
            return $post_id;
        }  

        /**
         * Define what post-types we should activate 
         * this group for
         *
         * @param [type] $types
         * @return void
         */
        public function setPostTypes( $types )
        {
            $types = is_array($types) ? $types : [$types];

            foreach( $types as $type ) {
                if( $this->validateKey($type) ) {
                    $this->post_types[] = $type;
                } else {
                    throw new Exception("The post type key is invalid");
                }
            }

            return $this;
        }

        /**
         * Hook into wordpress sytem
         
         * @todo split up logic into several separate functions
         * @return void
         */
        public function hook( bool $force = false )
        {
            try {
                
                $this->hookSave( $force );
                $this->hookRegisterField( $force );

                if( $this->visible_column ) {
                    $this->hookVisibleColumn( $force );
                }

                $this->hooked = true;
            
            } catch( Exception $e ) {

                throw $e;

            }

            return true;
        }
        
        /**
         * Hook into the column logic
         * so admins can add columns to the post type view
         *
         * @param boolean $force
         * @return void
         */
        protected function hookVisibleColumn( bool $force = false )
        {
            if( function_exists("add_action") && function_exists("add_filter") ) {

                if( $this->hooked == false || $force ) {

                    if( $this->visible_column || $force ) {

                        $modifyColumnsArray = function( $columns ) {
                            $columns[$this->key] = __($this->name);
                            return $columns;
                        };

                        $echoFieldValue = function( string $column ) {                            
                            return $this->getValue(0, true);                            
                        };
 
                        foreach( $this->post_types as $type ) {
    
                            add_filter("manage_{$type}_posts_columns", $modifyColumnsArray);    
                            add_action("manage_{$type}_posts_custom_column", $echoFieldValue);
    
                        }
        
                        if( empty($this->post_types) ) {
        
                            add_filter("manage_posts_columns", $modifyColumnsArray);                            
                            add_action("manage_posts_custom_column", $echoFieldValue);                    
        
                        }

                    } else {
                        throw new Exception("Field is not visible as column. Use setVisibleColumn(true) or force the hook");    
                    }

                } else {
                    throw new Exception("Already hooed into WP");
                }                            

            } else {
                throw new Exception("Wordpress add_action or add_filter function was not available or already hooked in");
            }

            return $this;
        }

        /**
         * Hook into the save post wordpress filter
         *
         * @param boolean $force
         * @return void
         */
        protected function hookSave( bool $force = false )
        {
            if( function_exists("add_action") && ($this->hooked == false || $force) ) {
                
                foreach( $this->post_types as $type ) {

                    add_action("save_post_{$type}", [$this, "save"]);

                }

                if( empty($this->post_types) ) {

                    add_action('save_post', [$this, "save"]);

                }

            } else {
                throw new Exception("Wordpress add_action function was not available or already hooked in");
            }
            
            return $this;
        }

        /**
         * Register this field into wp
         * 
         * @param bool $force
         * @return object
         */
        protected function hookRegisterField( bool $force = false )
        {
            if( function_exists("add_action") ) {

                if( $this->hooked == false || $force ) {

                    foreach( $this->post_types as $type ) {

                        add_action("init", function() use ($type) {

                            register_meta('post', $this->key, [
                                'object_subtype' => $type,
                                'description' => $this->description,
                                'show_in_rest' => true
                            ]);
            
                        });    

                    }                    

                } else {
                    throw new Exception("Already hooked into WP");
                }

            }
            
            return $this;
        }

        /**
         * Just so wordpress can use this class
         *
         * @param [type] $post
         * @return void
         */
        public function output( $post = null )
        {
            $html = $this->render($post);            
            
            echo $html;

            return $html;
        }

        /**
         * Retrive value from current PHP Globals
         * or whatever is stored in the database
         *
         * @param integer $object_id
         * @return void
         */
        public function getValue( int $object_id = 0, bool $echo = false ) : string
        {
            if( array_key_exists($this->key, $_REQUEST) ) {

                $value = $_REQUEST[$this->key];

            } elseif( function_exists("get_metadata") && function_exists("get_the_ID") ) {

                $object_id = empty($object_id) ? get_the_ID() : $object_id;
                $value = get_metadata( 'post', $object_id, $this->key, true);

            } else {

                throw new Exception("WP functions get_metadata() and get_the_ID() unavailable");

            }

            if( $echo ) {
                echo (string) $value;
            }

            return $value;            
        }

        /**
         * Override the element id for the input/select field
         *
         * @param string $id
         * @return void
         */
        public function setElementId( string $id )
        {
            $this->element_id = $id;

            return $this;
        }

        /**
         * Set readable name for the input field
         *
         * @param string $name
         * @return void
         */
        public function setName( string $name )
        {
            if( strlen($name) > 0 && strlen($name) < 256 && strip_tags($name) == $name) {
                $this->name = $name;
            } else {
                throw new Exception("Invalid field name format");
            }

            return $this;
        }

        /**
         * Determine if field should be visible as column
         * in the wp admin post-type interface
         * 
         * @param bool $value
         * @return object
         */
        public function setVisibleColumn( bool $value )
        {
            $this->visible_column = $value;

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
            if( strip_tags($description) == $description ) {
                $this->description = $description;
            } else {
                throw new Exception("Invalid description format. Not HTML tags.");
            }

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

            if( $length <= 12 && $length > 0 && $start <= $end && $start > 0 ) {

                $this->start_position = $start;
                $this->end_position = $end;

            } else {
                throw new Exception("Invalid positions");
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
            } else {
                throw new Exception("Invalid sanitizer function. Not callable.");
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
            } else {
                throw new Exception("Invalid validator function. Not callable.");
            }

            return $this;
        }

        /**
         * Return a hidden field to use as cross-site protection
         *
         * @param int|string $action
         * @param string $name
         * @param bool $referer
         * @return void
         */
        protected function getNonceField()
        {
            return function_exists("wp_nonce_field") ? wp_nonce_field( -1, $this->getNonceName(), false, false ) : false;
        }

        /**
         * Return the hidden input _POST name for nonce checkers
         *
         * @return void
         */
        protected function getNonceName()
        {
            return "_WCS_{$this->key}_nonce";
        }

        /**
         * Function to validate key according to the requiements put up by
         * wordpress built in function sanitize_key
         *
         * @param string $key
         * @return bool
         */
        protected function validateKey( $key ) : bool
        {
            return is_string($key) && preg_match("/^[a-z\-\_]{1,20}$/", $key);
        }

        /**
         * Placeholder for built-in wordpress "checked function"
         *
         * @param mixed $compare
         * @param mixed $value
         * @return string
         */
        protected function checked( $compare, $value = null, bool $echo = false ) : string
        {
            $value = empty($value) ? $this->getValue() : $value;

            if( function_exists('checked') ) {
                return checked($compare, $value, $echo);
            }

            if( $echo ) {
                echo $compare == $value ? "checked='true'" : "";
            }

            return $compare == $value ? "checked='true'" : "";
        }

        /**
         * Placeholder for the wordpress built-in selected
         *
         * @param mixed $compare
         * @param mixed $value
         * @return string
         */
        protected function selected( $compare, $value = null, bool $echo = false) : string
        {
            $value = empty($value) ? $this->getValue() : $value;

            if( function_exists("selected") ) {
                return selected($compare, $value, $echo);
            }

            if( $echo ){
                echo $compare == $value ? "selected='true'" : "";
            }

            return $compare == $value ? "selected='true'" : "";
        }

        /**
         * Function for generating a random string
         *
         * @param integer $min
         * @param integer $max
         * @param boolean $letters
         * @param boolean $numbers
         * @param boolean $signs
         * @return void
         */
        protected function randomString($min = 5, $max = 20, $letters = true, $numbers = true, $signs = false)
        {
            $string = '';

            if( $letters )
                $string .= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        
            if( $numbers )
                $string .= '0123456789';
        
            if( $signs )
                $string .= '@$%&/()=+-';
        
            // Check to see if string length is
            if(strlen($string) == 0)
                return false;
        
            // Pick random length
            $length = rand($min, $max);
        
            // Repeat string if not long enough
            while( strlen($string) < $length ) {
        
                $string = $string . $string;
        
            }
        
            // Shuffle The String
            $random = str_shuffle($string);
        
            return substr($random, 0, $length);
        }

    }