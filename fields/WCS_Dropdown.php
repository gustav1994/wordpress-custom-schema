<?php

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Field.php");

    class WCS_Dropdown extends WCS_Field
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
            $options_arr = [];

            foreach($this->options as $key => $value) {

                $options_arr[] = "<option value='{$key}' {$this->selected($key)}>{$value}</option>";

            }

            return "
                <label for='{$this->element_id}' class='form-label'>{$this->name}</label>                
                <select name='{$this->key}' id='{$this->element_id}' class='form-control' aria-describedby='{$this->element_id}-help-text'>
                    ". implode("", $options_arr) . "
                </select>
                <div id='{$this->element_id}-help-text' class='form-text'>{$this->description}</div>
                
                {$this->getNonceField()}
            ";

        }

        public function sanitize( $value ) : string
        {
            return sanitize_text_field($value);
        }

    }