<?php

	add_action( 'wp_enqueue_scripts', 'bjorn_enqueue_dynamic_js', '999' );

	function bjorn_enqueue_dynamic_js( ) {

		require_once(ABSPATH . 'wp-admin/includes/file.php'); // required to use WP_Filesystem();
		
		WP_Filesystem();

		$bjorn_theme_options = bjorn_get_theme_options();
		global $wp_filesystem;

		if ( function_exists( 'is_multisite' ) && is_multisite() ){
			$cache_file_name = 'js-skin-b' . get_current_blog_id();
		} else {
			$cache_file_name = 'js-skin';
		}

		$js_cache_folder = 'js';
		$js_cache_file = get_stylesheet_directory() . '/'.$js_cache_folder.'/' . $cache_file_name . '.js';
		$js_cache_file_url = get_stylesheet_directory_uri() . '/'.$js_cache_folder.'/' . $cache_file_name . '.js';

        $ipanel_saved_date = get_option( 'ipanel_saved_date', 1 );
        $cache_saved_date = get_option( 'cache_js_saved_date', 0 );

		if( file_exists( $js_cache_file ) ) {
			$cache_status = 'exist';

            if($ipanel_saved_date > $cache_saved_date) {
                $cache_status = 'no-exist';
            }

		} else {
			$cache_status = 'no-exist';
		}

        if ( defined('DEMO_MODE') ) {
            $cache_status = 'no-exist';
        }

		if ( $cache_status == 'exist' ) {

			wp_register_script( $cache_file_name, $js_cache_file_url, $cache_saved_date);
			wp_enqueue_script( $cache_file_name );

		} else {
			
			$out = '';

			$js_generated = microtime(true);

			$out = bjorn_get_js();

			$out .= '/* JS Generator Execution Time: ' . floatval( ( microtime(true) - $js_generated ) ) . ' seconds */';

			// FS_CHMOD_FILE required by WordPress guideliness - https://codex.wordpress.org/Filesystem_API#Using_the_WP_Filesystem_Base_Class
			if ( $wp_filesystem->put_contents( $js_cache_file, $out, FS_CHMOD_FILE) ) {

				wp_register_script( $cache_file_name, $js_cache_file_url, $cache_saved_date);
				wp_enqueue_script( $cache_file_name );

                // Update save options date
                $option_name = 'cache_js_saved_date';
                
                $new_value = microtime(true) ;

                if ( get_option( $option_name ) !== false ) {

                    // The option already exists, so we just update it.
                    update_option( $option_name, $new_value );

                } else {

                    // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
                    $deprecated = null;
                    $autoload = 'no';
                    add_option( $option_name, $new_value, $deprecated, $autoload );
                }
			}
		
		}
	}

	function bjorn_get_js () {
		$bjorn_theme_options = bjorn_get_theme_options();
		// ===
		ob_start();
    ?>
    (function($){
    $(document).ready(function() {
        
        
        <?php if(isset($bjorn_theme_options['custom_js_code'])) { 

        	echo $bjorn_theme_options['custom_js_code'];  // This variable contains user Custom JS code and can't be escaped with WordPress functions 

        } ?>

    });
    })(jQuery);
    <?php

    	$out = ob_get_clean();

		$out .= ' /*' . date("Y-m-d H:i") . '*/';
		/* RETURN */
		return $out;
	}

?>