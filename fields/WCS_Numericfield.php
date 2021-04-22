<?php

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
            return "
                <label for='{$this->element_id}' class='form-label'>{$this->name}</label>
                <input type='number' min='{$this->min}' max='{$this->max}' id='{$this->element_id}' name='{$this->key}' class='form-control' aria-describedby='{$this->element_id}-help-text' placeholder='{$this->name}'>
                <div id='{$this->element_id}-help-text' class='form-text'>{$this->description}</div>
                {$this->getNonceField()}
            ";

        }

        public function sanitize( $value )
        {
            return floatval($value);
        }

    }