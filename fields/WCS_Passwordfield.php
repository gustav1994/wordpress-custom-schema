<?php

    if( !defined('ABSPATH') ) {
        exit;
    }

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Field.php");

    class WCS_Passwordfield extends WCS_Field
    {
        
        public function render( $post ) : string
        {
            $useInputGroup = !empty($this->suffix) || !empty($this->prefix);

            return "
                <label for='{$this->element_id}' class='form-label'>{$this->name}</label>
                
                ". ($useInputGroup ? '<div class="input-group">' : '') ."
                    
                    ". (empty($this->prefix) ? '' : "<span class='input-group-text rounded-0'>{$this->prefix}</span>") ."
                
                        <input type='password' id='{$this->element_id}' class='form-control rounded-0' name='{$this->key}' aria-describedby='{$this->element_id}-help-text' placeholder='{$this->name}'  value='{$this->getValue()}' >
                        
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