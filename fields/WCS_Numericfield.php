<?php

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Field.php");

    class WCS_Textfield extends WCS_Field
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
            return "";
        }

        public function sanitize( $value )
        {
            return floatval($value);
        }

    }