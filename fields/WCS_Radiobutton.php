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
            $html = "";

            foreach($this->options as $key => $value) {

                $html .= "
                    <div class='form-check'>
                        <input class='form-check-input' type='radio' name='{$this->key}' id='{$this->element_id}-{$key}' />
                        <label class='form-check-label' for='{$this->element_id}-{$key}'>{$this->name}</label>
                    </div>                
                ";

            }

            return $html;
        }

        public function sanitize( $value )
        {
            return sanitize_text_field($value);
        }

    }