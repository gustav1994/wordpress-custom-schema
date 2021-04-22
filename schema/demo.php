<?php

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/WCS_Type.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/WCS_Group.php");

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Textfield.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Numericfield.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Textarea.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Passwordfield.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Dropdown.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Checkbox.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Radiobutton.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Emailfield.php");

    
        echo (new WCS_Textfield("text-field"))
                ->setName("Text field")
                ->setDescription("Text field helper")
                ->render([]);

        echo "<hr/>";

        echo (new WCS_Numericfield("numeric-field"))
                ->setName("Number field")
                ->setDescription("Number field helper")
                ->render([]);

        echo "<hr/>";

        echo (new WCS_Emailfield("email-field"))
                ->setName("Email field")
                ->setDescription("email field helper")
                ->render([]);

        echo "<hr/>";

        echo (new WCS_Passwordfield("password-field"))
                ->setName("Password field")
                ->setDescription("Password field helper")
                ->render([]);

        echo "<hr/>";

        echo (new WCS_Textarea("textarea-field"))
                ->setName("Textarea field")
                ->setDescription("Textarea field helper")
                ->render([]);

        echo "<hr/>";

        echo (new WCS_Dropdown("dropdown-field"))
                ->setName("Select field")
                ->setDescription("Select field helper")
                ->setOptions([1 => "first", 2 => "SEcond"])
                ->render([]);

        echo "<hr/>";

        echo (new WCS_Checkbox("checkbox-field"))
                ->setName("Checkbox field")
                ->setDescription("Checkbox field helper")                
                ->render([]);
                
        echo "<hr/>";

        echo (new WCS_Radiobutton("radio-button-group"))
                ->setName("Radio field")
                ->setDescription("Radio field helper") 
                ->setOptions([1 => "first", 2 => "SEcond"])               
                ->render([]);

    exit;