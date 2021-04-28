<?php

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Field.php");

    class WCS_Checkbox extends WCS_Field
    {

        public function render( $post ) : string
        {
            return "
                <input type='checkbox' id='{$this->element_id}' class='form-check-input' name='{$this->key}' aria-describedby='{$this->element_id}-help-text' placeholder='{$this->name}' value='1' {$this->checked(1)}>
                <label for='{$this->element_id}' class='form-check-label'>{$this->name}</label>                
                <div id='{$this->element_id}-help-text' class='form-text'>{$this->description}</div>
                {$this->getNonceField()}
            ";

        }

        public function sanitize( $value ) : bool
        {
            return boolval($value);
        }

    }