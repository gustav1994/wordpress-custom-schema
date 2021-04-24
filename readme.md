# Wordpress Custom Schema (WCS)
This is the wordpress plugin to use if you want to define a custom schema in your wordpress installation similar to that used in more enterprise CMS systems. By purpose this plugin will not generate any interface at all. We believe that all content models should only be created and modified by developers and allowing end-users in the wordpress administration interface to edit the schema is a security risk. Also it should only be necessary to edit the content model if also making changes to the template files which can only be done by developers as well.

WCS is inspired by migration terminology used in popular frameworks such as Laravel or Symfony. Other enterprise content managment systems allows developers schemas programmaticly. We will do the same for Wordpress.

**Wordpress Custom Schema (WCS) will not alter any database tables.**

## Terminology and plugin structure
### File structure
`/schema` Home for all your igration files. If your are using this in a Wordpress Multisite (WPMU) setup please seperate schema files in folders matching blog id's or domain names. Schema files in the root will run for all sites in the network.

* `/assets` *holds all static assets needed to display custom fields in the Wordpress admin interface. WCS uses bootstrap 5.0 prefixed to only work within the class **.bootstrap-admin** to avoid any conflict with other stylesheets in the interface.*
* `/fields` *holds all fields type that you can use to define custom fields. Each class corresponds to a different form element and is therefore rendered in different ways.*
* `post_type.php` *Custom post type object.*
* `wordpress-custom-schema.php` *Main plugin file. Will determine what schema files to load depending on the wordpress configuration.*
### Field position system
WCS uses the Twitter Bootstrap system to style everything in the wordpress meta box. In order to position fields in the group we will span use the Bootstrap grid system to span out a grid width 12 columns width. Positions will count from upper left corner and then break on 12, 24, 36 ... 72. Fields sorted ascending on the start position and can not overlap and break lines. 

| 1  | 2  | 3  | 4  | ... | 12 |
|----|----|----|----|-----|----|
| 13 | 14 | 15 | 16 | ... | 24 |
| 25 | 26 | 27 | 28 | ... | 36 |
| .  | .  | .  | .  | ... | .  |

## How to get started
WCS is not hosted in the official Wordpress Plugin channel. This plugin is soley intended to be used
by secious web programmers building enterprise websites backed by Wordpress. This plugin cannot be used without any programming knowledge. There are other alternatives out there that we can recommend if you are looking for an interface to manage custom- types and fields: [Advanced Custom Fields (ACF)](https://advancedcustomfields.com) og [Toolset](http://toolset.com) are among the most popular.
### Installation
You need access to your webhost through FTP or similar protocols.

1) Download this repo as a zip file
2) Create a folder in wp-content/plugins called wordpress-custom-schema
3) Unpack .zip on your local computer
4) Upload content of the folder to path on server you created in step 2.

## Examples
### Simple example
One post type with a single group containing 1 field. Demonstrates the most basic principle of the WCS system.

```php
require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/WCS_Type.php");
require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/WCS_Group.php");

require_once(WP_PLUGIN_DIR . "/wordpress-custom-schema/fields/WCS_Textfield.php");    

$txt = (new WCS_textfield('my-text-field'))
            ->setName('This is the label');

$group = (new WCS_Group('my-first-group'))
            ->addField($txt);

$type = (new WCS_Type('my-custom-post')
            ->setArgs('label', 'My Custom Post')
            ->setArgs('public', true)
            ->setArgs('show_ui', true)
            ->setArgs('show_in_test', true);
            ->addGroups($group)
            ->hook() // Tell wordpress to render this schema
```
### Advanced Example
This example shows you how to create a single custom post type with ...

1. two field groups
2. groups with custom positioning
3. defining full schema as inline script without variables

```php
$base = WP_PLUGIN_DIR . "/wordpress-custom-schema";

// Bases classes for post types and field groups
require_once($base . "/WCS_Type.php");
require_once($base . "/WCS_Group.php");

// Require fields classes
require_once($base . "/fields/WCS_Textfield.php");
require_once($base . "/fields/WCS_Dropdown.php");
require_once($base . "/fields/WCS_Radiobutton.php"); 

// Define schema
(new WCS_Type("advanced-custom-post"))
        ->setArgs([
            'label' => 'My Advanced Custom post',
            'public' => true,
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
                            ->setPosition(1, 3),
                        (new WCS_Numericfield("numeric-field"))
                            ->setName("Number field")
                            ->setDescription("Number field helper")
                            ->setPosition(4, 6),
                        (new WCS_Emailfield("email-field"))
                            ->setName("Email field")
                            ->setDescription("email field helper")
                            ->setPosition(7, 12),
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
```