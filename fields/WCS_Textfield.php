<?php

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Field.php");

    class WCS_Textfield extends WCS_Field
    {
        
        public function render( $post ) : string
        {
            return "
                <label for='{$this->element_id}' class='form-label'>{$this->name}</label>
                <input type='text' id='{$this->element_id}' class='form-control' name='{$this->key}' aria-describedby='{$this->element_id}-help-text' placeholder='{$this->name}' value='{$this->getValue()}'>
                <div id='{$this->element_id}-help-text' class='form-text'>{$this->description}</div>
                {$this->getNonceField()}
            ";

        }

        public function sanitize( $value ) : string
        {
            return sanitize_text_field($value);
        }

    }