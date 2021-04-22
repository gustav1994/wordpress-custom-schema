<?php

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Field.php");

    class WCS_Textarea extends WCS_Field
    {        
        public function render( $post ) : string
        {
            return "";
        }

        public function sanitize( $value )
        {
            return sanitize_text_field($value);
        }

    }