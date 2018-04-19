<?php

    /*
     *    === Define the Path ===
    */
    defined('BJORN_IPANEL_PATH') ||
    
        define( 'BJORN_IPANEL_PATH' , get_template_directory() . '/inc/iPanel/' );

    /*
     *    === Define the Version of iPanel ===
    */
    define( 'BJORN_IPANEL_VERSION' , '1.1' );    
    

    
    /*
     *    === Define the Classes Path ===
    */
    if ( defined('BJORN_IPANEL_PATH') ) {
        define( 'BJORN_IPANEL_CLASSES_PATH' , BJORN_IPANEL_PATH . 'classes/' );
    } else {

        define( 'BJORN_IPANEL_CLASSES_PATH' , get_template_directory() . '/inc/iPanel/classes/' );
    }
    
    function bjorn_iPanelLoad(){
        require_once BJORN_IPANEL_CLASSES_PATH . 'ipanel.class.php';
		if( file_exists(BJORN_IPANEL_PATH . 'options.php') )
			require_once BJORN_IPANEL_PATH . 'options.php';
    }
    
    if ( defined('BJORN_IPANEL_PLUGIN_USAGE') ) {
        if ( BJORN_IPANEL_PLUGIN_USAGE === true ) {
            add_action('plugins_loaded', 'bjorn_iPanelLoad');
        } else {
            add_action('init', 'bjorn_iPanelLoad');
        }
    } else {
        add_action('init', 'bjorn_iPanelLoad');
    }