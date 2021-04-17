# Wordpress Custom Schema (WCS)
This is the wordpress plugin to use if you want to define a custom schema in your wordpress installation similar to that used in more enterprise CMS systems. By purpose this plugin will not generate any interface at all. We believe that all content models should only be created and modified by developers and allowing end-users in the wordpress administration interface to edit the schema is a security risk. Also it should only be necessary to edit the content model if also making changes to the template files which can only be done by developers as well.

WCS is inspired by migration terminology used in popular frameworks such as Laravel or Symfony. Other enterprise content managment systems allows developers schemas programmaticly. We will do the same for Wordpress.

**Wordpress Custom Schema (WCS) will not alter any database tables.**

## Terminology and plugin structure



## How to get started
WCS is not hosted in the official Wordpress Plugin channel. This plugin is soley intended to be used
by secious web programmers building enterprise websites backed by Wordpress. This plugin cannot be used without any programming knowledge. There are other alternatives out there that we can recommend if you are looking for an interface to manage custom- types and fields: [Advanced Custom Fields (ACF)](https://advancedcustomfields.com) og [Toolset](http://toolset.com) er blandt de mest populære.

### Installation
You need access to your webhost through FTP or similar protocols.

1) Download this repo as a zip file
2) Create a folder in wp-content/plugins called wordpress-custom-schema
3) Unpack .zip on your local computer
4) Upload content of the folder to path on server you created in step 2.

### Creating your first schema file
