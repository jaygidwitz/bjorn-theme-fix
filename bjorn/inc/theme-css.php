<?php

	add_action( 'wp_enqueue_scripts', 'bjorn_enqueue_dynamic_styles', '999' );

	function bjorn_enqueue_dynamic_styles( ) {

        require_once(ABSPATH . 'wp-admin/includes/file.php'); // required to use WP_Filesystem();

        WP_Filesystem();

        $bjorn_theme_options = bjorn_get_theme_options();
        global $wp_filesystem;

		if ( function_exists( 'is_multisite' ) && is_multisite() ){
			$cache_file_name = 'css-skin-b' . get_current_blog_id();
		} else {
			$cache_file_name = 'css-skin';
		}

        $css_cache_folder = 'css';
        $css_cache_file = get_stylesheet_directory() . '/'.$css_cache_folder.'/' . $cache_file_name . '.css';
        $css_cache_file_url = get_stylesheet_directory_uri() . '/'.$css_cache_folder.'/' . $cache_file_name . '.css';

        $ipanel_saved_date = get_option( 'ipanel_saved_date', 1 );
        $cache_saved_date = get_option( 'cache_css_saved_date', 0 );

		if( file_exists( $css_cache_file ) ) {
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

			wp_register_style( $cache_file_name, $css_cache_file_url, $cache_saved_date);
			wp_enqueue_style( $cache_file_name );

		} else {
			
			$out = '';

			$generated = microtime(true);

			$out = bjorn_get_css();
	
			$out = str_replace( array( "\t", "
", "\n", "  ", "   ", ), array( "", "", " ", " ", " ", ), $out );

			$out .= '/* CSS Generator Execution Time: ' . floatval( ( microtime(true) - $generated ) ) . ' seconds */';

            // FS_CHMOD_FILE required by WordPress guideliness - https://codex.wordpress.org/Filesystem_API#Using_the_WP_Filesystem_Base_Class

			if ( $wp_filesystem->put_contents( $css_cache_file, $out, FS_CHMOD_FILE) ) {
			
				wp_register_style( $cache_file_name, $css_cache_file_url, $cache_saved_date);
				wp_enqueue_style( $cache_file_name );

                // Update save options date
                $option_name = 'cache_css_saved_date';
                
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

	function bjorn_get_css () {
		$bjorn_theme_options = bjorn_get_theme_options();
		// ===
		ob_start();
    ?>
    <?php
    if ( defined('DEMO_MODE') && isset($_GET['header_height']) ) {
      $bjorn_theme_options['header_height'] = $_GET['header_height'];
    }

    if(isset($bjorn_theme_options['header_height']) && $bjorn_theme_options['header_height'] > 0) {
        $header_height = $bjorn_theme_options['header_height'];
    } else {
        $header_height = 170;
    } 

    if(isset($bjorn_theme_options['logo_width']) && $bjorn_theme_options['logo_width'] > 0) {
        $logo_width = $bjorn_theme_options['logo_width'];
    } else {
        $logo_width = 159;
    }

    if(isset($bjorn_theme_options['blog_homepage_slider_height']) && $bjorn_theme_options['blog_homepage_slider_height'] > 0) {
        $blog_homepage_slider_height = $bjorn_theme_options['blog_homepage_slider_height'];
    } else {
        $blog_homepage_slider_height = 490;
    } 
    
    ?>
    header .col-md-12 {
        height: <?php echo intval($header_height); ?>px;
    }
    <?php
    // Retina logo
    ?>
    
    header .logo-link img {
        width: <?php echo intval($logo_width); ?>px;
    }
    .bjorn-post-list .bjorn-post .bjorn-post-image {
        height: <?php echo intval($blog_homepage_slider_height); ?>px;
    }
    @media (min-width: 1024px)  {
        body.blog.blog-transparent-header-enable .bjorn-post-list-wrapper,
        body.blog.blog-transparent-header-enable .bjorn-post-list .bjorn-post .bjorn-post-image {
            height: <?php echo intval($blog_homepage_slider_height+$header_height); ?>px;
        }
        body.blog.blog-transparent-header-enable .bjorn-post-list .bjorn-post-details {
            padding-top: <?php echo intval($header_height+$blog_homepage_slider_height/4+40); ?>px;
        }

        body.single-post.blog-post-header-with-bg.blog-post-transparent-header-enable .container-fluid.container-page-item-title.with-bg .page-item-title-single,
        body.page.blog-post-header-with-bg.blog-post-transparent-header-enable .container-fluid.container-page-item-title.with-bg .page-item-title-single {
            padding-top: <?php echo intval(190+$header_height); ?>px;
        }
    }

    <?php 
    // Disable left/right main menu areas
    if((isset($bjorn_theme_options['enable_offcanvas_sidebar'])&&(!$bjorn_theme_options['enable_offcanvas_sidebar'])) && (isset($bjorn_theme_options['search_button_position']) && ($bjorn_theme_options['search_button_position'] !== 'main_menu'))):
    ?>
    .navbar-left-wrapper { 
        border: none;
        width: 0px;
    }
    .navbar-right-wrapper { 
        border: none;
        width: 0px;
    }
    .mainmenu-belowheader.menu-center .nav {
        padding-left: 0;
        padding-right: 0;
    }
    .navbar-center-wrapper {
        margin-left: 0;
        margin-right: 0;
    }
    <?php endif; ?>
    <?php 
    /**
    * Custom CSS
    **/
    if(isset($bjorn_theme_options['custom_css_code'])) { 

        echo wp_strip_all_tags($bjorn_theme_options['custom_css_code']); // This variable contains user Custom CSS code and can't be escaped with WordPress functions 

    } ?>
    
    /** 
    * Theme Google Font
    **/
    <?php 
        // Demo settings
        if ( defined('DEMO_MODE') && isset($_GET['header_font']) ) {
          $bjorn_theme_options['header_font']['font-family'] = $_GET['header_font'];
        }
        if ( defined('DEMO_MODE') && isset($_GET['body_font']) ) {
          $bjorn_theme_options['body_font']['font-family'] = $_GET['body_font'];
        }
        if ( defined('DEMO_MODE') && isset($_GET['additional_font']) ) {
          $bjorn_theme_options['additional_font']['font-family'] = $_GET['additional_font'];
        }

        if(isset($bjorn_theme_options['font_google_disable']) && $bjorn_theme_options['font_google_disable']) {

            $bjorn_theme_options['body_font']['font-family'] = $bjorn_theme_options['font_regular'];
            $bjorn_theme_options['header_font']['font-family'] = $bjorn_theme_options['font_regular'];
            $bjorn_theme_options['additional_font']['font-family'] = $bjorn_theme_options['font_regular'];
        }

        // Logo text font
        if(isset($bjorn_theme_options['logo_text_enable']) && $bjorn_theme_options['logo_text_enable'] && isset($bjorn_theme_options['logo_text_font'])) {

            switch ($bjorn_theme_options['logo_text_font']) {
                case 'body':
                    $logo_text_font = $bjorn_theme_options['body_font']['font-family'];
                    break;

                case 'header':
                    $logo_text_font = $bjorn_theme_options['header_font']['font-family'];
                    break;

                case 'additional':
                    $logo_text_font = $bjorn_theme_options['additional_font']['font-family'];
                    break;
            }

            ?>
            header .logo-link.logo-text {
                font-family: '<?php echo esc_attr($logo_text_font); ?>';
            }
            <?php
        }
    ?>
    h1, h2, h3, h4, h5, h6 {
        font-family: '<?php echo esc_html($bjorn_theme_options['header_font']['font-family']); ?>';
    }
    .author-bio strong,
    .blog-post-related-single .blog-post-related-title,
    .blog-post-related-item .blog-post-related-title,
    .navigation-post .nav-post-name,
    .single-post .blog-post-related h5,
    header .header-blog-info {
        font-family: '<?php echo esc_html($bjorn_theme_options['header_font']['font-family']); ?>';
    }
    body {
        font-family: '<?php echo esc_html($bjorn_theme_options['body_font']['font-family']); ?>';
        font-size: <?php echo esc_html($bjorn_theme_options['body_font']['font-size']); ?>px;
    }
    <?php if(isset($bjorn_theme_options['additional_font_enable']) && $bjorn_theme_options['additional_font_enable']): ?>
    .navbar .nav > li a,
    .navbar .navbar-toggle,
    .blog-post .post-info,
    a.btn,
    .btn,
    .btn:focus,
    input[type="submit"],
    .woocommerce #content input.button, 
    .woocommerce #respond input#submit, 
    .woocommerce a.button, 
    .woocommerce button.button,
    .woocommerce input.button, 
    .woocommerce-page #content input.button, 
    .woocommerce-page #respond input#submit, 
    .woocommerce-page a.button, 
    .woocommerce-page button.button, 
    .woocommerce-page input.button, 
    .woocommerce a.added_to_cart, 
    .woocommerce-page a.added_to_cart,
    a.more-link,
    .blog-post .post-info-date,
    .blog-post .post-categories,
    .blog-post-related.blog-post-related-loop .blog-post-related-item .blog-post-related-date,
    .page-item-title-single .post-date,
    .page-item-title-single .post-categories,
    .author-bio h5,
    .comment-metadata .author,
    .comment-metadata .date,
    .blog-post-related-single .post-categories,
    .blog-post-related-single .blog-post-related-date,
    .homepage-welcome-block h5,
    .sidebar .widget.widget_bjorn_text .bjorn-textwidget h5,
    .page-item-title-archive p,
    .navigation-post .nav-post-title,
    .navigation-paging.navigation-post a,
    .bjorn-popular-post-list-wrapper .bjorn-popular-post .bjorn-popular-post-category,
    .bjorn-popular-post-list-wrapper .bjorn-popular-post .bjorn-popular-post-date,
    .bjorn-editorspick-post-list-wrapper .bjorn-editorspick-post .bjorn-editorspick-post-date,
    .bjorn-editorspick-post-list-wrapper .bjorn-editorspick-post .bjorn-editorspick-post-category,
    .bjorn-post-list .bjorn-post-details .bjorn-post-category,
    .bjorn-post-pagination .bjorn-post-pagination-category,
    .blog-post .entry-content h5,
    .page .entry-content h5,
    .header-menu,
    .bjorn-post-list .bjorn-post-details .bjorn-post-date,
    .bjorn-popular-post-list-wrapper .bjorn-popular-post-list .bjorn-popular-post .bjorn-popular-post-details .bjorn-popular-post-category,
    .bjorn-verticalbar,
    .homepage-welcome-block .welcome-image-overlay span,
    .post-counters,
    .bjorn-editorspick-post-list-wrapper > h3,
    .container-fluid-footer .footer-menu,
    .footer-instagram-wrapper > h3,
    .blog-post .post-author,
    .blog-post-related h5,
    .blog-post-related.blog-post-related-loop .blog-post-related-item .blog-post-related-category,
    .blog-post .sticky-post-badge,
    .navigation-paging .wp-pagenavi a,
    .navigation-paging .wp-pagenavi span.current,
    .navigation-paging .wp-pagenavi span.extend,
    body .mc4wp-form .mailchimp-widget-signup-form input[type="email"],
    .page-item-title-single .post-author,
    .blog-post .tags a,
    .comment-meta .reply a,
    .sidebar .widget.widget_bjorn_recent_entries li .post-date,
    body .ig_form_container .ig_form_els input,
    body .ig_popup.ig_inspire .ig_button, 
    body .ig_popup.ig_inspire input[type="submit"], 
    body .ig_popup.ig_inspire input[type="button"],
    .sidebar .widget.widget_bjorn_recent_comments .bjorn_recentcomments .comment-date,
    .sidebar .widget.widget_bjorn_posts_slider .widget-post-slider-wrapper .post-date,
    .sidebar .widget.widget_bjorn_posts_slider .widget-post-slider-wrapper .post-category,
    .search-close-btn,
    .sidebar .widget.widget_bjorn_popular_entries li .widget-post-position,
    .sidebar .widget.widget_bjorn_popular_entries li .post-category,
    header .header-post-content .header-post-details .header-post-category,
    .bjorn-theme-block h4,
    .blog-post.blog-post-single .post-info-vertical,
    .footer-sidebar-2.sidebar .widgettitle {
        font-family: '<?php echo esc_html($bjorn_theme_options['additional_font']['font-family']); ?>';
    }
    <?php endif; ?>
    
    /**
    * Colors and color skins
    */
    <?php
    // Demo settings
    if ( defined('DEMO_MODE') && isset($_GET['color_skin_name']) ) {
      $bjorn_theme_options['color_skin_name'] = $_GET['color_skin_name'];
    }

    if(!isset($bjorn_theme_options['color_skin_name'])) {
        $color_skin_name = 'none';
    }
    else {
        $color_skin_name = $bjorn_theme_options['color_skin_name'];
    }
    // Use panel settings
    if($color_skin_name == 'none') {

        $theme_body_color = $bjorn_theme_options['theme_body_color'];
        $theme_text_color = $bjorn_theme_options['theme_text_color'];
        $theme_main_color = $bjorn_theme_options['theme_main_color'];
        $theme_header_bg_color = $bjorn_theme_options['theme_header_bg_color'];
        $theme_cat_menu_bg_color = $bjorn_theme_options['theme_cat_menu_bg_color'];
        $theme_footer_color = $bjorn_theme_options['theme_footer_color'];
        $theme_masonry_bg_color = $bjorn_theme_options['theme_masonry_bg_color'];

    }
    // Default skin
    if($color_skin_name == 'default') {
        
        $theme_body_color = '#FFFFFF';
        $theme_text_color = '#000000';
        $theme_main_color = '#9ba78a';
        $theme_header_bg_color = '#FFFFFF';
        $theme_cat_menu_bg_color = '#FFFFFF';
        $theme_footer_color = '#FFFFFF';
        $theme_masonry_bg_color = '#F5F5F5';

    }
    // Black skin
    if($color_skin_name == 'black') {

        $theme_body_color = '#FFFFFF';
        $theme_text_color = '#000000';
        $theme_main_color = '#444444';
        $theme_header_bg_color = '#FFFFFF';
        $theme_cat_menu_bg_color = '#FFFFFF';
        $theme_footer_color = '#FFFFFF';
        $theme_masonry_bg_color = '#F5F5F5';

    }
    // Grey sking
    if($color_skin_name == 'grey') {

        $theme_body_color = '#FFFFFF';
        $theme_text_color = '#000000';
        $theme_main_color = '#8e9da5';
        $theme_header_bg_color = '#FFFFFF';
        $theme_cat_menu_bg_color = '#FFFFFF';
        $theme_footer_color = '#FFFFFF';
        $theme_masonry_bg_color = '#F5F5F5';
    }
    // Light blue skin
    if($color_skin_name == 'lightblue') {

        $theme_body_color = '#FFFFFF';
        $theme_text_color = '#000000';
        $theme_main_color = '#A2C6EA';
        $theme_header_bg_color = '#FFFFFF';
        $theme_cat_menu_bg_color = '#FFFFFF';
        $theme_footer_color = '#FFFFFF';
        $theme_masonry_bg_color = '#F5F5F5';

    }
    // Blue skin
    if($color_skin_name == 'blue') {

        $theme_body_color = '#FFFFFF';
        $theme_text_color = '#000000';
        $theme_main_color = '#346DF4';
        $theme_header_bg_color = '#FFFFFF';
        $theme_cat_menu_bg_color = '#FFFFFF';
        $theme_footer_color = '#FFFFFF';
        $theme_masonry_bg_color = '#F5F5F5';

    }
    // Red
    if($color_skin_name == 'red') {

        $theme_body_color = '#FFFFFF';
        $theme_text_color = '#000000';
        $theme_main_color = '#D43034';
        $theme_header_bg_color = '#FFFFFF';
        $theme_cat_menu_bg_color = '#FFFFFF';
        $theme_footer_color = '#FFFFFF';
        $theme_masonry_bg_color = '#F5F5F5';

    }
    // Green
    if($color_skin_name == 'green') {

        $theme_body_color = '#FFFFFF';
        $theme_text_color = '#000000';
        $theme_main_color = '#00BC8F';
        $theme_header_bg_color = '#FFFFFF';
        $theme_cat_menu_bg_color = '#FFFFFF';
        $theme_footer_color = '#FFFFFF';
        $theme_masonry_bg_color = '#F5F5F5';

    }
    // Orange
    if($color_skin_name == 'orange') {

        $theme_body_color = '#FFFFFF';
        $theme_text_color = '#000000';
        $theme_main_color = '#ec9f2e';
        $theme_header_bg_color = '#FFFFFF';
        $theme_cat_menu_bg_color = '#FFFFFF';
        $theme_footer_color = '#FFFFFF';
        $theme_masonry_bg_color = '#F5F5F5';

    }
    // RedOrange
    if($color_skin_name == 'redorange') {

        $theme_body_color = '#FFFFFF';
        $theme_text_color = '#000000';
        $theme_main_color = '#F2532F';
        $theme_header_bg_color = '#FFFFFF';
        $theme_cat_menu_bg_color = '#FFFFFF';
        $theme_footer_color = '#FFFFFF';
        $theme_masonry_bg_color = '#F5F5F5';

    }
    // Brown
    if($color_skin_name == 'brown') {

        $theme_body_color = '#FFFFFF';
        $theme_text_color = '#000000';
        $theme_main_color = '#C3A36B';
        $theme_header_bg_color = '#FFFFFF';
        $theme_cat_menu_bg_color = '#FFFFFF';
        $theme_footer_color = '#FFFFFF';
        $theme_masonry_bg_color = '#F5F5F5';

    }
    ?>
    <?php if(isset($bjorn_theme_options['body_bg_image']) && $bjorn_theme_options['body_bg_image']['url'] <> ''): ?>
    html:not(.offcanvassidebar) body,
    html.offcanvassidebar body .st-sidebar-pusher {
        <?php 
        echo 'background-image: url('.esc_url($bjorn_theme_options['body_bg_image']['url']).');';
        if(isset($bjorn_theme_options['body_bg_style'])) {
            if($bjorn_theme_options['body_bg_style'] == 'repeat') {
                echo 'background-repeat: repeat;';
            }
            if($bjorn_theme_options['body_bg_style'] == 'cover') {
                echo 'background-size: cover;';
            }
        }
        ?>
    }
    <?php endif; ?>
    <?php if(isset($bjorn_theme_options['header_bg_image']) && $bjorn_theme_options['header_bg_image']['url'] <> ''): ?>
    header {
        <?php 
        
        echo 'background-image: url('.esc_url($bjorn_theme_options['header_bg_image']['url']).');';
        if(isset($bjorn_theme_options['header_bg_style'])) {

            if($bjorn_theme_options['header_bg_style'] == 'repeat') {
                echo 'background-repeat: repeat;';
            }
            if($bjorn_theme_options['header_bg_style'] == 'cover') {
                echo 'background-size: cover;';
            }
        }
        
        ?>
    }
    .container-fluid.container-page-item-title,
    .bjorn-blog-posts-slider {
        margin-top: 0;
    }
    .mainmenu-belowheader:not(.fixed),
    .mainmenu-belowheader:not(.fixed) .navbar {
        background-color: transparent!important;
    }
    <?php endif; ?>
    
    body {
        background-color: <?php echo esc_html($theme_body_color); ?>;
        color: <?php echo esc_html($theme_text_color); ?>;
    }
    .st-pusher, 
    .st-sidebar-pusher,
    .st-sidebar-menu .sidebar,
    .bjorn-popular-post-list-wrapper .bjorn-popular-post-list .bjorn-popular-post .bjorn-popular-post-details,
    .blog-post .blog-post-thumb + .post-content,
    .bjorn-editorspick-post-list-wrapper .bjorn-editorspick-post,
    .bjorn-editorspick-post-list-wrapper .bjorn-editorspick-post.bjorn-editorspick-post-small .bjorn-editorspick-post-details,
    .bjorn-editorspick-post-list-wrapper .bjorn-editorspick-post.bjorn-editorspick-post-large .bjorn-editorspick-post-details,
    .sidebar .widget.widget_bjorn_posts_slider .widget-post-slider-wrapper .widget-post-details-wrapper,
    .navigation-paging .wp-pagenavi a, .navigation-paging .wp-pagenavi span.current, .navigation-paging .wp-pagenavi span.extend {
        background-color: <?php echo esc_html($theme_body_color); ?>;
    }
    a.btn,
    .btn,
    .btn:focus,
    input[type="submit"],
    .woocommerce #content input.button, 
    .woocommerce #respond input#submit, 
    .woocommerce a.button, 
    .woocommerce button.button,
    .woocommerce input.button, 
    .woocommerce-page #content input.button, 
    .woocommerce-page #respond input#submit, 
    .woocommerce-page a.button, 
    .woocommerce-page button.button, 
    .woocommerce-page input.button, 
    .woocommerce a.added_to_cart, 
    .woocommerce-page a.added_to_cart,
    a.more-link,
    .woocommerce #content input.button.alt:hover,
    .woocommerce #respond input#submit.alt:hover,
    .woocommerce a.button.alt:hover,
    .woocommerce button.button.alt:hover,
    .woocommerce input.button.alt:hover,
    .woocommerce-page #content input.button.alt:hover,
    .woocommerce-page #respond input#submit.alt:hover,
    .woocommerce-page a.button.alt:hover,
    .woocommerce-page button.button.alt:hover,
    .woocommerce-page input.button.alt:hover,
    .btn:active,
    .btn-primary,
    .btn-primary:focus,
    .btn.alt:hover,
    header .header-promo-content .btn:hover,
    .nav > li .sub-menu,
    .blog-post .tags a:hover,
    .blog-post-related-item-details,
    .blog-post .sticky-post-badge,
    .post-social-wrapper .post-social-title a:hover,
    .navigation-paging .wp-pagenavi a:hover,
    .navigation-paging .wp-pagenavi span.current,
    #top-link,
    .sidebar .widget_calendar th,
    .sidebar .widget_calendar tfoot td,
    .sidebar .widget_tag_cloud .tagcloud a:hover,
    .sidebar .widget_product_tag_cloud .tagcloud a:hover,
    .comment-meta .reply a:hover,
    body .owl-theme .owl-controls .owl-nav div.owl-prev,
    body .owl-theme .owl-controls .owl-nav div.owl-next,
    body .owl-theme .owl-controls .owl-page.active span, 
    body .owl-theme .owl-controls.clickable .owl-page:hover span,
    body .owl-theme .owl-dots .owl-dot.active span, 
    body .owl-theme .owl-dots .owl-dot:hover span,
    .st-sidebar-menu-close-btn,
    body .ig_popup.ig_inspire .ig_button, 
    body .ig_popup.ig_inspire input[type="submit"], 
    body .ig_popup.ig_inspire input[type="button"],
    .homepage-welcome-block .background-theme {
        background-color: <?php echo esc_html($theme_main_color); ?>;
    }
    a,
    a:focus,
    blockquote:before,
    .blog-post .format-quote .entry-content:before,
    .container-fluid.container-page-item-title.with-bg .post-info-share .post-social a:hover,
    .container-fluid.container-page-item-title.with-bg .page-item-title-single .post-categories a,
    header .header-post-content .header-post-details .header-post-title a:hover,
    .header-info-text a:hover,
    .header-menu .header-menu-offcanvasmenu a:hover,
    .header-menu .header-menu-search a:hover,
    .header-menu li a:hover,
    .navbar .nav > li > a:hover,
    .blog-post .post-author a:hover,
    .blog-post .post-categories,
    .blog-post .post-header-title sup,
    .blog-post .post-header-title a:hover,
    .author-bio .author-social-icons li a:hover,
    .blog-post-related.blog-post-related-loop .blog-post-related-item .blog-post-related-title a:hover,
    .post-social-wrapper .post-social a:hover,
    .navigation-paging.navigation-post a,
    .navigation-post .nav-post-prev:hover .nav-post-name,
    .navigation-post .nav-post-next:hover .nav-post-name,
    .blog-masonry-layout .blog-post.content-block .sticky:not(.sticky-post-without-image) .post-info .post-social a:hover,
    .blog-masonry-layout .blog-post.content-block .sticky:not(.sticky-post-without-image) .post-info .post-info-comments a:hover,
    .blog-masonry-layout .blog-post.content-block .sticky:not(.sticky-post-without-image) .post-author a:hover,
    .footer-sidebar-2.sidebar .widget a:hover,
    footer a:hover,
    footer .footer-social .social-icons-wrapper a:hover,
    .sidebar .widget ul > li a:hover,
    .sidebar .widget_text a,
    .comment-metadata .author a,
    .comment-metadata .date a:hover,
    .bjorn-popular-post-list-wrapper .bjorn-popular-post-list .bjorn-popular-post .bjorn-popular-post-details .bjorn-popular-post-title h5:hover,
    .bjorn-editorspick-post-list-wrapper .bjorn-editorspick-post .bjorn-editorspick-post-title a:hover,
    .sidebar .widget.widget_bjorn_posts_slider .widget-post-slider-wrapper .post-title:hover,
    .sidebar .widget.widget_bjorn_posts_slider .widget-post-slider-wrapper .post-category,
    .sidebar .widget.widget_bjorn_posts_slider .widget-post-slider-wrapper .post-category a,
    .sidebar .widget.widget_bjorn_popular_entries li .post-category a,
    .sidebar .widget.widget_bjorn_popular_entries li .widget-post-thumb-wrapper-container .widget-post-details-wrapper .post-category a,
    .sidebar .widget.widget_bjorn_popular_entries li .widget-post-thumb-wrapper-container .widget-post-details-wrapper a:hover,
    body .select2-results .select2-highlighted,
    .social-icons-wrapper a:hover,
    .bjorn-theme-block > h2,
    .blog-post .post-info .post-info-views,
    .sidebar .widget.widget_bjorn_popular_entries li .post-category,
    .bjorn-popular-post-list-wrapper .bjorn-popular-post-list .bjorn-popular-post .bjorn-popular-post-details .bjorn-popular-post-category,
    .bjorn-post-list .bjorn-post-details .bjorn-post-category,
    .blog-post-related.blog-post-related-loop .blog-post-related-item .blog-post-related-category,
    .bjorn-editorspick-post-list-wrapper .bjorn-editorspick-post .bjorn-editorspick-post-category,
    .page-item-title-single .post-categories {
        color: <?php echo esc_html($theme_main_color); ?>;
    }
    a.btn,
    .btn,
    .btn:focus,
    input[type="submit"],
    .woocommerce #content input.button, 
    .woocommerce #respond input#submit, 
    .woocommerce a.button, 
    .woocommerce button.button,
    .woocommerce input.button, 
    .woocommerce-page #content input.button, 
    .woocommerce-page #respond input#submit, 
    .woocommerce-page a.button, 
    .woocommerce-page button.button, 
    .woocommerce-page input.button, 
    .woocommerce a.added_to_cart, 
    .woocommerce-page a.added_to_cart,
    a.more-link,
    .woocommerce #content input.button.alt:hover,
    .woocommerce #respond input#submit.alt:hover,
    .woocommerce a.button.alt:hover,
    .woocommerce button.button.alt:hover,
    .woocommerce input.button.alt:hover,
    .woocommerce-page #content input.button.alt:hover,
    .woocommerce-page #respond input#submit.alt:hover,
    .woocommerce-page a.button.alt:hover,
    .woocommerce-page button.button.alt:hover,
    .woocommerce-page input.button.alt:hover,
    .btn:active,
    .btn-primary,
    .btn-primary:focus,
    .btn.alt:hover,
    header .header-promo-content .btn:hover,
    .navbar .nav > li > a:hover,
    .sidebar .widget_calendar tbody td a,
    .bjorn-post-list-nav .bjorn-post-list-nav-prev {
        border-color: <?php echo esc_html($theme_main_color); ?>;
    }
    header {
        background-color: <?php echo esc_html($theme_header_bg_color); ?>;
    }
    .mainmenu-belowheader {
        background-color: <?php echo esc_html($theme_cat_menu_bg_color); ?>;
    }
    footer {
        background-color: <?php echo esc_html($theme_footer_color); ?>;
    }
    .blog-masonry-layout .blog-post.content-block .post-content,
    .blog-masonry-layout .post-content-wrapper {
        background-color: <?php echo esc_html($theme_masonry_bg_color); ?>;
    }
    
    <?php

    	$out = ob_get_clean();

		$out .= ' /*' . date("Y-m-d H:i") . '*/';
		/* RETURN */
		return $out;
	}
?>
