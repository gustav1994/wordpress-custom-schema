<?php

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Field.php");

    class WCS_Textarea extends WCS_Field
    {        
        public function render( $post ) : string
        {
            return "
                <label for='{$this->element_id}' class='form-label'>{$this->name}</label>
                <textarea id='{$this->element_id}' cols='4' class='form-control' name='{$this->key}' aria-describedby='{$this->element_id}-help-text' placeholder='{$this->name}'>{$this->getValue()}</textarea>
                <div id='{$this->element_id}-help-text' class='form-text'>{$this->description}</div>
                
                {$this->getNonceField()}
            ";

        }

        public function sanitize( $value ) : string
        {
            return sanitize_text_field($value);
        }

    }