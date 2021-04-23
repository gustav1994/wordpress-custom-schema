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
        public $key;

        /**
         * The Meta Box Title
         *
         * @var [type]
         */
        protected $name;

        /**
         * Initial description of the field group
         *
         * @var string
         */
        protected $description;

        /**
         * Define what post-types this group should be activated for
         *
         * @var array
         */
        protected $post_types = [];

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
        public function hook()
        {
            if( function_exists("add_action") ) {

                foreach( $this->post_types as $type ) {

                    add_action("add_meta_boxes_{$type}", function(){
                        
                        add_meta_box($this->key, $this->name, [$this, "output"], $type, 'normal', 'default');        

                    });

                }

            }

            return true;
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
            foreach( $types as $type ) {
                if( $this->validateKey($type) ) {
                    $this->post_types[] = $type;
                }
            }

            return $this;
        }

        /**
         * Set the end-uset meta box name
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
         * Set the field group description
         *
         * @param string $description
         * @return void
         */
        public function setDescription( string $description )
        {
            $this->description = $description;

            return $this;
        }

        /**
         * Add one or more fields to this group
         *
         * @param [type] $fields
         * @return void
         */
        public function addFields( $fields )
        {
            $fields = is_array($fields) ? $fields : [$fields];

            foreach( $fields as $field ) {
                $this->fields[$field->key] = $field;
            }     
            
            // Sort by the start_position ascending
            usort($this->fields, function($a, $b){  
                                
                if( is_numeric($a->start_position) && is_numeric($b->start_position) ) {
                    return $a->start_position < $b->start_position ? -1 : 1;                              
                } elseif( is_numeric($a->start_position) && !is_numeric($b->start_position) ) {
                    return -1;
                }

                return 1;
            });            

            return $this;
        }        

        /**
         * Wordpress would like us to echo out the rendered group
         *
         * @return void
         */
        public function output( $post = null )
        {
            $html = $this->render($post);

            echo $html;

            return $html;
        }

        /**
         * Render the meta box content by rendering every field in the group into
         * the bootstrap 12 width grid
         *
         * @param [type] $post
         * @return string
         */
        public function render( $post = null ) : string
        {
            $grid = $this->renderGrid(array_map(function($field) use ($post) {

                return [
                    $field->start_position,
                    $field->end_position,
                    $field->render($post)
                ];

            }, $this->fields));

            return "
                <div class='wp-admin-bootstrap'> 
                    <p class='mb-4'>{$this->description}</p>
                    {$grid}                
                </div>
            ";
        }

        /**
         * Take postions and generate the Bootstrap grid.
         * 
         * Fields:
         *      start_postion : Start position in the 12 column width bootstrap grid
         *      end_position : End position in grid
         *      length : end_position - start_position
         * 
         * Columns can not overlap. Start position determines the order
         * Columns can not span several lines (limits: 12, 24, 36, 48, 60, 72, 84 ....)
         * Missing start and end positions will be placed last with a 6-column width         
         *
         * @param array $cols : array([start, end, content], [start, end, content], ..., [start, end, content])
         * @return string
         */
        protected function renderGrid( array $positions ) : string
        {                             
            $columns = [];
            
            $firstEmptyPosition = 1;

            foreach( $positions as $position ) {

                $startPosition = intval($position[0]);
                $endPosition = intval($position[1]);
                $content = strval($position[2]);

                // Push cols to avoid overlap
                if( $startPosition < $firstEmptyPosition ) {
                    $endPosition = $firstEmptyPosition + $endPosition - $startPosition;
                    $startPosition = $firstEmptyPosition;                    
                }

                // Generate offset-cols if necesary
                if( $startPosition > $firstEmptyPosition ) {

                    for( $i = 0; $i < $startPosition - $firstEmptyPosition; $i++){
                        $columns[] = "<div class='col-md-1'>&nbsp;</div>";
                    }
                    
                }

                $row = floor($startPosition / 12) + 1;

                // Column should not break lines
                if( $endPosition > $row * 12) {
                    $endPosition = $row * 12;
                }

                $length = 1 + $endPosition - $startPosition;                
                
                $columns[] = "<div class='col-md-{$length}'>{$content}</div>";

                $firstEmptyPosition = $endPosition + 1;
            }

            return "
                <div class='row g-4'>
                    ". implode('', $columns) . "
                </div>
            ";
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

    }