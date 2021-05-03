<?php

    /**
     * Values i WPC_Fields
     *      - særskilt funktion til at returnere values
     *      - prefill dem 
     * 
     * WPC_Type
     *      - addGroups
     *      - How to handle all paramters ?
     *      - activate : if not already registered
     *          - activate all groups
     * 
     */

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


    (new WCS_Type("advanced-custom-post"))
        ->setArgs([
            'label' => 'My Advanced Custom post',
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_rest' => true
        ])        
        ->addGroups([
            (new WCS_Group("demo-group"))
                ->setName("My first Demo Group")
                ->setDescription("Make a looooong field group description ....")
                ->addFields([
                        (new WCS_Textfield("text-field"))
                            ->setName("Text field")
                            ->setDescription("Text field helper")
                            ->setPosition(1, 3)
                            ->setDefaultValue('default value')
                            ->setVisibleColumn(true),
                        (new WCS_Numericfield("numeric-field"))
                            ->setName("Number field")
                            ->setDescription("Number field helper")
                            ->setPosition(5, 6),
                        (new WCS_Emailfield("email-field"))
                            ->setName("Email field")
                            ->setDescription("email field helper")
                            ->setPosition(10, 12),
                        (new WCS_Passwordfield("password-field"))
                            ->setName("Password field")
                            ->setDescription("Password field helper")
                            ->setPosition(12, 24),
                        (new WCS_Textarea("textarea-field"))
                            ->setName("Textarea field")
                            ->setDescription("Textarea field helper")
                            ->setPosition(24, 30),
                        (new WCS_Dropdown("dropdown-field"))
                            ->setName("Select field")
                            ->setDescription("Select field helper")
                            ->setOptions([1 => "first", 2 => "SEcond"])
                            ->setPosition(31, 36),
                        (new WCS_Checkbox("checkbox-field"))
                            ->setName("Checkbox field")
                            ->setDescription("Checkbox field helper")
                            ->setPosition(37, 48),
                        (new WCS_Radiobutton("radio-button-group"))
                            ->setName("Radio field headline here")
                            ->setDescription("Radio field helper") 
                            ->setOptions([1 => "first", 2 => "SEcond"]) 
                            ->setPosition(48, 60)
                    ]),
            (new WCS_Group("second-demo-group"))
                ->setName("My Second Demo Group")
                ->setDescription("Make a looooong field group description again ...")
                ->addFields([
                    (new WCS_Textfield("text-field-sec"))
                        ->setName("Text field")
                        ->setDescription("Text field helper")
                        ->setPosition(1, 3),
                    (new WCS_Numericfield("numeric-field-sec"))
                        ->setName("Number field")
                        ->setDescription("Number field helper")
                        ->setPosition(4, 6),
                    (new WCS_Emailfield("email-field-sec"))
                        ->setName("Email field")
                        ->setDescription("email field helper")
                        ->setPosition(7, 12),
                    (new WCS_Passwordfield("password-field-sec"))
                        ->setName("Password field")
                        ->setDescription("Password field helper")
                        ->setPosition(12, 24),                
                ])
        ])->hook();
