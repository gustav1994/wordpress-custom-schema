<?php

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Field.php");

    class WCS_Emailfield extends WCS_Field
    {
        
        public function render( $post ) : string
        {
            return "
                <label for='{$this->element_id}' class='form-label'>{$this->name}</label>
                <input type='email' id='{$this->element_id}' class='form-control' name='{$this->key}' aria-describedby='{$this->element_id}-help-text' placeholder='{$this->name}'>
                <div id='{$this->element_id}-help-text' class='form-text'>{$this->description}</div>
                {$this->getNonceField()}
            ";
        }

        public function sanitize( $value )
        {
            return filter_var($value, FILTER_SANITIZE_EMAIL);
        }

    }