<?php

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Field.php");

    class WCS_Radiobutton extends WCS_Field
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
            $checkboxes = "";

            foreach($this->options as $key => $value) {

                $checkboxes .= "
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='{$this->key}' id='{$this->element_id}-{$key}' />
                        <label class='form-check-label' for='{$this->element_id}-{$key}'>{$value}</label>
                    </div>                
                ";

            }

            return "
                <label class='mb-1'>{$this->name}</label>
                {$checkboxes}                
                <div id='{$this->element_id}-help-text' class='form-text'>{$this->description}</div>
                {$this->getNonceField()}
            ";

        }

        public function sanitize( $value )
        {
            return sanitize_text_field($value);
        }

    }