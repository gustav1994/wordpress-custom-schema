<?php

    if( !defined('ABSPATH') ) {
        exit;
    }

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Field.php");

    class WCS_Numericfield extends WCS_Field
    {
        protected $min = -99999999999999;
        protected $max = 99999999999999;

        public function setLimits( $min, $max)
        {
            $this->min = $min;
            $this->max = $max;

            return $this;
        }

        public function render( $post ) : string
        {
            $useInputGroup = !empty($this->suffix) || !empty($this->prefix);

            return "
                <label for='{$this->element_id}' class='form-label'>{$this->name}</label>
                
                ". ($useInputGroup ? '<div class="input-group">' : '') ."
                    
                    ". (empty($this->prefix) ? '' : "<span class='input-group-text rounded-0'>{$this->prefix}</span>") ."
                
                        <input type='number' min='{$this->min}' max='{$this->max}' id='{$this->element_id}' name='{$this->key}' class='form-control rounded-0' aria-describedby='{$this->element_id}-help-text' placeholder='{$this->name}'  value='{$this->getValue()}'>
                        
                        ". (empty($this->suffix) ? '' : "<span class='input-group-text rounded-0'>{$this->suffix}</span>") ."
                
                ". ($useInputGroup ? '</div>' : '') . "
                
                <div id='{$this->element_id}-help-text' class='form-text fst-italic'>{$this->description}</div>
                
                {$this->getNonceField()}
            ";

        }

        public function sanitize( $value ) : float
        {
            return floatval($value);
        }

    }