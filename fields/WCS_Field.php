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
        public string $key;

        /**
         * End-user readable name
         *
         * @var string
         */
        protected ?string $name = null;

        /**
         * Default field value if nothing has been set
         *
         * @var mixed
         */
        protected $default_value = null;

        /**
         * Input description
         *
         * @var string
         */
        protected ?string $description = null;

        /**
         * Id for the HTML elements
         *
         * @var string
         */
        protected string $element_id = '';

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
        protected array $post_types = [];

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
        public ?int $start_position = null;

        /**
         * End position
         *         
         * @return int
         */
        public ?int $end_position = null;

        /**
         * Should field be visible as column in Post-Type overview
         *
         * @var boolean
         */
        public bool $visible_column = false;

        /**
         * If we ahve already hooked into the WP eco-system
         *
         * @var boolean
         */
        protected bool $hooked = false;
        
        /**
         * Enforce sub-classes to implement a rendering method
         *
         * @return string
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
         * @throws Exception
         */
        public function __construct( string $key )
        {
            if( $this->validateKey($key) ) {
                
                $this->key = $key;
                $this->element_id = "WCS-{$this->randomString(5, 10)}"; 
                
                $this->default_value = '';              

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
         * @throws Exception
         */
        public function save( int $post_id, $post = null ) : int
        {                                    
            if( current_user_can("edit_post", $post_id) && array_key_exists($this->getNonceName(), $_POST) && wp_verify_nonce($_POST[$this->getNonceName()]) ) {

                $value = $this->getValue();

                $valid = is_callable($this->validator) ? $this->validator($value) : true;
                $sanitized = is_callable($this->sanitizer) ? $this->sanitizer($value) : $this->sanitize($value);

                if( function_exists("update_post_meta") && $valid ) {

                    if( $value === '' || $value === null ) {

                        update_post_meta($post_id, $this->key, $this->default_value);

                    } else {

                        update_post_meta($post_id, $this->key, $sanitized);

                    }

                } else {

                    throw new Exception("Missing wp-function update_post_meta");

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
         * @throws Exception
         */
        public function setPostTypes( $types ) : object
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
         * @return void
         * @throws Exception
         * @todo split up logic into several separate functions
         */
        public function hook( bool $force = false ) : bool
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
         * @throws Exception
         */
        protected function hookVisibleColumn( bool $force = false ) : object
        {
            if( function_exists("add_action") && function_exists("add_filter") ) {

                if( $this->hooked == false || $force ) {

                    if( $this->visible_column || $force ) {

                        $modifyColumnsArray = function( $columns ) {
                            $columns[$this->key] = __($this->name);
                            return $columns;
                        };

                        $echoFieldValue = function( string $column, int $id) {                                                        
                            if( $this->key == $column) {
                                return $this->getValue($id, true);                            
                            }                            
                        };
 
                        foreach( $this->post_types as $type ) {
    
                            add_filter("manage_{$type}_posts_columns", $modifyColumnsArray);    
                            add_action("manage_{$type}_posts_custom_column", $echoFieldValue, 10, 2);
    
                        }
        
                        if( empty($this->post_types) ) {
        
                            add_filter("manage_posts_columns", $modifyColumnsArray);                            
                            add_action("manage_posts_custom_column", $echoFieldValue, 10, 2);                    
        
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
         * @throws Exception
         */
        protected function hookSave( bool $force = false ) : object
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
         * @throws Exception
         */
        protected function hookRegisterField( bool $force = false ) : object
        {
            if( function_exists("add_action") ) {

                if( $this->hooked == false || $force ) {

                    foreach( $this->post_types as $type ) {

                        $registerMeta = function() use ($type){
                            return register_meta('post', $this->key, [
                                'object_subtype' => $type,
                                'description' => $this->description,
                                'show_in_rest' => true
                            ]);
                        };

                        add_action("init", $registerMeta);
                        add_action("rest_api_init", $registerMeta);

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
        public function output( $post = null ) : string
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
         * @throws Exception
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

            if( $value === '' || $value === null ) {
                $value = $this->default_value;
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
        public function setElementId( string $id ) : object
        {
            $this->element_id = $id;

            return $this;
        }

        /**
         * Set readable name for the input field
         *
         * @param string $name
         * @return void
         * @throws Exception
         */
        public function setName( string $name ) : object
        {
            if( strlen($name) > 0 && strlen($name) < 256 && strip_tags($name) == $name) {
                $this->name = $name;
            } else {
                throw new Exception("Invalid field name format");
            }

            return $this;
        }

        /**
         * Set default value
         *
         * @param mixed $value
         * @return void
         */
        public function setDefaultValue( $value ) : object
        {
            $this->default_value = $value;

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
         * @throws Exception
         */
        public function setDescription( string $description ) : object
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
         * @throws Exception
         */
        public function setPosition( int $start, int $end) : object
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
         * @throws Exception
         */
        public function setSanitizer( $callable ) : object
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
         * @throws Exception
         */
        public function setValidator( $callable ) : object
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
        protected function getNonceName() : string
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
        protected function validateKey( string $key ) : bool
        {
            return preg_match("/^[a-z\-_]{1,20}$/", $key);
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

            if( $letters ) {
                $string .= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            }
        
            if( $numbers ) {
                $string .= '0123456789';
            }
        
            if( $signs ) {
                $string .= '@$%&/()=+-';
            }
        
            // Check to see if string length is
            if(strlen($string) == 0) {
                return false;
            }
        
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