<?php

    if( !defined('ABSPATH') ) {
        exit;
    }

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Field.php");

    class WCS_Dropdown extends WCS_Field
    {
        protected $options = [];

        public function setOptions( array $options)
        {
            foreach( $options as $key => $value ) {

                $this->options[$key] = $value;
                
            }

            return $this;
        }

        public function render( $post ) : string
        {
            $useInputGroup = !empty($this->suffix) || !empty($this->prefix);

            $options_arr = [];

            foreach($this->options as $key => $value) {

                $options_arr[] = "<option value='{$key}' {$this->selected($key)}>{$value}</option>";

            }

            return "
                <label for='{$this->element_id}' class='form-label'>{$this->name}</label>     
                
                ". ($useInputGroup ? '<div class="input-group">' : '') ."
                    
                    ". (empty($this->prefix) ? '' : "<span class='input-group-text rounded-0'>{$this->prefix}</span>") ."
                           
                        <select name='{$this->key}' id='{$this->element_id}' class='form-control rounded-0' aria-describedby='{$this->element_id}-help-text'>
                            ". implode("", $options_arr) . "
                        </select>
                        
                        ". (empty($this->suffix) ? '' : "<span class='input-group-text rounded-0'>{$this->suffix}</span>") ."
                
                ". ($useInputGroup ? '</div>' : '') . "
                
                <div id='{$this->element_id}-help-text' class='form-text fst-italic'>{$this->description}</div>
                
                {$this->getNonceField()}
            ";

        }

        public function sanitize( $value ) : string
        {
            return sanitize_text_field($value);
        }

    }