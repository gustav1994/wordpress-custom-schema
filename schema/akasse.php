<?php

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/WCS_Type.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/WCS_Group.php");

    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Textfield.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Numericfield.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Dropdown.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Checkbox.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Radiobutton.php");
    require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Emailfield.php");

    

    $t = (new WCS_Textfield("custom_name"))
            ->setName("Firstname")
            ->setDescription("Fill in the fristname of this person");

    echo $t->render([]);
    exit;