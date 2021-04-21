<?php

    if( !defined('ABSPATH') ) {            
        exit;
    }

    abstract class WCS_Field
    {        
        abstract function render();
    }