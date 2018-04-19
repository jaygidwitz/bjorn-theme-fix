<?php
/** 
 * Plugin recomendations
 **/
$bjorn_theme_options = bjorn_get_theme_options();

require_once(get_template_directory().'/inc/class-tgm-plugin-activation.php');

add_action( 'tgmpa_register', 'bjorn_register_required_plugins' );

function bjorn_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        array(
            'name'                  => esc_html__('Bjorn Custom Metaboxes', 'bjorn'), // The plugin name
            'slug'                  => 'cmb2', // The plugin slug (typically the folder name)
            'source'                => get_template_directory() . '/inc/plugins/cmb2.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '2.2.5.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        ),
        array(
            'name'                  => esc_html__('Bjorn Theme Addons', 'bjorn'), // The plugin name
            'slug'                  => 'bjorn-theme-addons', // The plugin slug (typically the folder name)
            'source'                => get_template_directory() . '/inc/plugins/bjorn-theme-addons.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '1.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
        ),
        array(
            'name'                  => esc_html__('Revolution Slider', 'bjorn'), // The plugin name
            'slug'                  => 'revslider', // The plugin slug (typically the folder name)
            'source'                => get_template_directory() . '/inc/plugins/revslider.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '5.4.5.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher
        ),
        array(
            'name'                  => esc_html__('Bjorn Page Navigation', 'bjorn'), // The plugin name
            'slug'                  => 'wp-pagenavi', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => esc_html__('Bjorn Translation Manager', 'bjorn'), // The plugin name
            'slug'                  => 'loco-translate', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => esc_html__('Instagram Feed', 'bjorn'), // The plugin name
            'slug'                  => 'instagram-feed', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => esc_html__('MailChimp for WordPress', 'bjorn'), // The plugin name
            'slug'                  => 'mailchimp-for-wp', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => esc_html__('WordPress LightBox', 'bjorn'), // The plugin name
            'slug'                  => 'responsive-lightbox', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => esc_html__('Contact Form 7', 'bjorn'), // The plugin name
            'slug'                  => 'contact-form-7', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => esc_html__('Regenerate Thumbnails', 'bjorn'), // The plugin name
            'slug'                  => 'regenerate-thumbnails', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        )

    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'            => 'bjorn',           // Text domain - likely want to be the same as your theme.
        'default_path'      => '',                          // Default absolute path to pre-packaged plugins
        'menu'              => 'install-required-plugins',  // Menu slug
        'has_notices'       => true,                        // Show admin notices or not
        'dismissable'  => true,
        'is_automatic'      => true,                       // Automatically activate plugins after installation or not
        'message'           => ''                          // Message to output right before the plugins table
    );

    tgmpa( $plugins, $config );

}

/**
 * Google Fonts Loading
 */
function bjorn_google_fonts_url() {

    $bjorn_theme_options = bjorn_get_theme_options();

    $font_url = '';
    $font_header = '';
    $font_body = '';
    $font_additional = '';

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

    if(!isset($bjorn_theme_options['font_google_disable']) || $bjorn_theme_options['font_google_disable'] == false) {

        // Header font
        if(isset($bjorn_theme_options['header_font'])) {
            $font_header = $bjorn_theme_options['header_font']['font-family'];

            if(isset($bjorn_theme_options['header_font_options'])) {
                $font_header = $font_header.':'.$bjorn_theme_options['header_font_options'];
            }
        }
        // Body font
        if(isset($bjorn_theme_options['body_font'])) {
            $font_body = '|'.$bjorn_theme_options['body_font']['font-family'];

            if(isset($bjorn_theme_options['body_font_options'])) {
                $font_body = $font_body.':'.$bjorn_theme_options['body_font_options'];
            }
        }
        // Additional font
        if(isset($bjorn_theme_options['additional_font_enable']) && $bjorn_theme_options['additional_font_enable']) {
            if(isset($bjorn_theme_options['additional_font'])) {
                $font_additional = '|'.$bjorn_theme_options['additional_font']['font-family'].'|';
            }
        }

        // Build Google Fonts request
        $font_url = add_query_arg( 'family', urlencode( $font_header.$font_body.$font_additional ), "//fonts.googleapis.com/css" );

    }
    
    return $font_url;
}

/**
 * Header banner 1 display
 */
function bjorn_header_promo_show() {
    $bjorn_theme_options = bjorn_get_theme_options();

    echo '<div class="header-promo-content">'.do_shortcode($bjorn_theme_options['header_banner_editor']).'</div>'; // This is safe place and we can't use wp_kses_post or other esc_ functions here because this is ads area where user may use Google Adsense and other Java Script banners code with <script> inside.
}

/**
 * Header banner 2 display
 */
function bjorn_header_promo2_show() {
    $bjorn_theme_options = bjorn_get_theme_options();

    echo '<div class="header-promo-content">'.do_shortcode($bjorn_theme_options['header_banner2_editor']).'</div>'; // This is safe place and we can't use wp_kses_post or other esc_ functions here because this is ads area where user may use Google Adsense and other Java Script banners code with <script> inside.
}

/**
 * Header logo display
 */
function bjorn_logo_show() {
    $bjorn_theme_options = bjorn_get_theme_options();
    $custom_logo_url = 'https://www.soriana.com/'; /* #custom add custom url */
    
    // Text logo
    if((isset($bjorn_theme_options['logo_text_enable'])) && ($bjorn_theme_options['logo_text_enable'])&&(isset($bjorn_theme_options['logo_text'])) && ($bjorn_theme_options['logo_text']!=='')) {
        ?>
        <div class="logo"><a class="logo-link logo-text" target="_blank" href="<?php echo $custom_logo_url; /* echo esc_url(home_url());  #custom change to custom url */ ?>"><?php echo wp_kses_post($bjorn_theme_options['logo_text']);?></a>
        <?php 
          if(get_bloginfo('description')!=='' && isset($bjorn_theme_options['disable_header_tagline']) && !$bjorn_theme_options['disable_header_tagline']) {
            echo '<div class="header-blog-info">';
            bloginfo('description');
            echo '</div>';
          }
        ?>
        </div>
        <?php
    // Image logo
    } else {
        ?>
        <div class="logo">
        <a class="logo-link" target="_blank" href="<?php echo $custom_logo_url; /* echo esc_url(home_url('/'));  #custom change to custom url */ ?>"><img src="<?php echo get_header_image(); ?>" alt="<?php bloginfo('name'); ?>" class="regular-logo"><img src="<?php if ( get_theme_mod( 'bjorn_header_transparent_logo' ) ) { echo esc_url( get_theme_mod( 'bjorn_header_transparent_logo' )); } else { echo esc_url(get_header_image()); }  ?>" alt="<?php bloginfo('name'); ?>" class="light-logo"></a>
        <?php 
          if(get_bloginfo('description')!=='' && isset($bjorn_theme_options['disable_header_tagline']) && !$bjorn_theme_options['disable_header_tagline']) {
            echo '<div class="header-blog-info">';
            bloginfo('description');
            echo '</div>';
          }
        ?>
        </div>
        <?php
    }

    ?>

    <?php
}

/**
 * Main Menu display
 */
function bjorn_menu_below_header_show() {
    $bjorn_theme_options = bjorn_get_theme_options();

    // MainMenu styles
    $menu_add_class = '';

    if(isset($bjorn_theme_options['header_menu_font_decoration'])) {
        $menu_add_class .= ' mainmenu-'.esc_html($bjorn_theme_options['header_menu_font_decoration']);
    }
    if(isset($bjorn_theme_options['header_menu_font_size'])) {
        $menu_add_class .= ' mainmenu-'.esc_html($bjorn_theme_options['header_menu_font_size']);
    }
    if(isset($bjorn_theme_options['header_menu_font_weight'])) {
        $menu_add_class .= ' mainmenu-'.esc_html($bjorn_theme_options['header_menu_font_weight']);
    }
    if(isset($bjorn_theme_options['header_menu_arrow_style'])) {
        $menu_add_class .= ' mainmenu-'.esc_html($bjorn_theme_options['header_menu_arrow_style']);
    }
    if(isset($bjorn_theme_options['header_menu_margin']) && ($bjorn_theme_options['header_menu_margin'])) {
        $menu_add_class .= ' mainmenu-with-margin';
    }
    
    // Center menu
    $menu_add_class .= ' menu-center';

    if(isset($bjorn_theme_options['enable_sticky_header']) && $bjorn_theme_options['enable_sticky_header']) {

      $menu_add_class .= ' sticky-header';

    }

    ?>

    <?php
    // Main Menu

    $menu = wp_nav_menu(
        array (
            'theme_location'  => 'primary',
            'echo' => false,
            'fallback_cb'    => false,
        )
    );

    if (!empty($menu)):
 
    ?>
    <div class="mainmenu-belowheader<?php echo esc_attr($menu_add_class); ?> clearfix">
        <?php if(isset($bjorn_theme_options['enable_sticky_header_logo']) && $bjorn_theme_options['enable_sticky_header_logo']): ?>

        <?php bjorn_logo_show(); ?>

        <?php endif; ?>
        <div id="navbar" class="navbar navbar-default clearfix">
          
          <div class="navbar-inner">
              <div class="container">
             
                  <div class="navbar-toggle" data-toggle="collapse" data-target=".collapse">
                    <?php esc_html_e( 'Menu', 'bjorn' ); ?>
                  </div>
                  <div class="navbar-left-wrapper">
                    <?php // Demo settings
                    if ( defined('DEMO_MODE') && isset($_GET['enable_offcanvas_sidebar']) ) {
                      $bjorn_theme_options['enable_offcanvas_sidebar'] = $_GET['enable_offcanvas_sidebar'];
                    }

                    ?>
                   
                    <?php
                    if(isset($bjorn_theme_options['enable_offcanvas_sidebar'])&&($bjorn_theme_options['enable_offcanvas_sidebar'])): 
                    ?>
                    <div id="st-sidebar-trigger-effects"><a class="float-sidebar-toggle-btn" data-effect="st-sidebar-effect-2"><i class="fa fa-align-left"></i></a></div>
                    <?php endif; ?>
                   
                  </div>
                  <div class="navbar-center-wrapper">
                  <?php
                 
                     wp_nav_menu(array(
                      'theme_location'  => 'primary',
                      'container_class' => 'navbar-collapse collapse',
                      'menu_class'      => 'nav',
                      'fallback_cb'    => false,
                      'walker'          => new bjorn_description_walker
                      ));  
                  
                  ?>
                  </div>
                  <div class="navbar-right-wrapper">
                     <?php
                    // Top menu search
                    if(isset($bjorn_theme_options['search_button_position']) && ($bjorn_theme_options['search_button_position'] == 'main_menu')):
                    ?>
                      <div class="header-menu-search"><a class="search-toggle-btn"><i class="fa fa-search" aria-hidden="true"></i></a></div>
                  <?php endif; ?>
                  </div>
              </div>
          </div>
          
        </div>
       
    </div>
    <?php endif; ?>
    
    <?php
    // MainMenu Below header position END 
}

/**
 * Social icons display
 */
function bjorn_social_show($showtitles = false, $addwrapper = false) {
    $bjorn_theme_options = bjorn_get_theme_options();

    $social_services_arr['facebook'] = 'Facebook';
    $social_services_arr['vk'] = 'VKontakte';
    $social_services_arr['twitter'] = 'Twitter';
    $social_services_arr['google-plus'] = 'Google+';
    $social_services_arr['linkedin'] = 'LinkedIn';
    $social_services_arr['dribbble'] = 'Dribbble';
    $social_services_arr['behance'] = 'Behance';
    $social_services_arr['instagram'] = 'Instagram';
    $social_services_arr['tumblr'] = 'Tumblr';
    $social_services_arr['pinterest'] = 'Pinterest';
    $social_services_arr['vimeo-square'] = 'Vimeo';
    $social_services_arr['youtube'] = 'YouTube';
    $social_services_arr['skype'] = 'Skype';
    $social_services_arr['flickr'] = 'Flickr';
    $social_services_arr['rss'] = 'RSS';
    $social_services_arr['deviantart'] = 'Deviantart';
    $social_services_arr['500px'] = '500px';
    $social_services_arr['etsy'] = 'Etsy';
    $social_services_arr['telegram'] = 'Telegram';
    $social_services_arr['odnoklassniki'] = 'Odnoklassniki';
    $social_services_arr['houzz'] = 'Houzz';
    $social_services_arr['slack'] = 'Slack';
    $social_services_arr['qq'] = 'QQ';

    $s_count = 0;

    $social_services_html = '';

    foreach( $social_services_arr as $ss_data => $ss_value ){
      if(isset($bjorn_theme_options[$ss_data]) && (trim($bjorn_theme_options[$ss_data])) <> '') {
        $s_count++;
        $social_service_url = $bjorn_theme_options[$ss_data];
        $social_service = $ss_data;

        if($showtitles) {
            $social_title = '<span>'.$ss_value.'</span>';
        } else {
            $social_title = '';
        }

        $social_services_html .= '<a href="'.esc_url($social_service_url).'" target="_blank" class="a-'.esc_attr($social_service).'"><i class="fa fa-'.esc_attr($social_service).'"></i>'.wp_kses_post($social_title).'</a>';
      }
    }

    if($social_services_html <> '') {
        if($addwrapper) {
            echo wp_kses_post('<div class="social-icons-wrapper">'.$social_services_html.'</div>');
        } else {
            echo wp_kses_post($social_services_html);
        }
      
    }
}

/**
 * Top menu display
 */
function bjorn_top_show() {
    $bjorn_theme_options = bjorn_get_theme_options();
    ?>
    <?php if(isset($bjorn_theme_options['disable_top_menu']) && !$bjorn_theme_options['disable_top_menu']): ?>
    <?php 
    $header_add_class = '';

    if(isset($bjorn_theme_options['header_top_menu_style'])) {
      $header_top_menu_style = $bjorn_theme_options['header_top_menu_style'];
      $header_add_class .= ' '.esc_attr($header_top_menu_style);
    }

    ?>
    <div class="header-menu-bg<?php echo esc_attr($header_add_class); ?>">
      <div class="header-menu">
        <div class="container">
          <div class="row">
            <?php bjorn_logo_show(); // #custom add logo ?>  
            <?php if(isset($bjorn_theme_options['header_enable_social']) && ($bjorn_theme_options['header_enable_social'])) {
                $header_menu_col_class = 'col-md-6';
            } else {
                $header_menu_col_class = 'col-md-12';
            }
            ?>
            <div class="<?php echo esc_attr($header_menu_col_class); ?>">
              <div class="menu-top-menu-container-toggle"></div>
              <?php
              wp_nav_menu(array(
                'theme_location'  => 'top',
                'menu_class'      => 'links',
                'fallback_cb'    => false,
                ));
              ?>
            </div>
             <?php
            // Header social icons
            if(isset($bjorn_theme_options['header_enable_social']) && ($bjorn_theme_options['header_enable_social'])):
            // #custom md-3 en lugar de  6 ?>
            <div class="col-md-3"> 

                <div class="header-info-text">
                <?php
                // Top menu search
                if(isset($bjorn_theme_options['search_button_position']) && ($bjorn_theme_options['search_button_position'] == 'top_menu')):
                ?>
                <a class="search-toggle-btn"><i class="fa fa-search" aria-hidden="true"></i></a>
                <?php endif; ?>
                <span><?php echo esc_html__('Síguenos', 'bjorn'); ?></span>
                <?php bjorn_social_show(); ?>
                </div>

            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endif;
}

/**
 * Header left part display
 */
function bjorn_header_left_show() {
    $bjorn_theme_options = bjorn_get_theme_options();

    // Show header banner
    if((isset($bjorn_theme_options['header_banner_editor'])) && ($bjorn_theme_options['header_banner_editor'] <> '') && (isset($bjorn_theme_options['header_banner_position'])) && ($bjorn_theme_options['header_banner_position'] == 'left')){
        bjorn_header_promo_show();
    }

    // Show header banner
    if((isset($bjorn_theme_options['header_banner2_editor'])) && ($bjorn_theme_options['header_banner2_editor'] <> '') && (isset($bjorn_theme_options['header_banner2_position'])) && ($bjorn_theme_options['header_banner2_position'] == 'left')){
        bjorn_header_promo2_show();
    }

    // Show header logo
    if((isset($bjorn_theme_options['header_logo_position'])) && ($bjorn_theme_options['header_logo_position'] == 'left')) {
        /*bjorn_logo_show(); #custom remove normal position of logo */
    }

}

/**
 * Header center part display
 */
function bjorn_header_center_show() {
    $bjorn_theme_options = bjorn_get_theme_options();

    // Show header banner
    if((isset($bjorn_theme_options['header_banner_editor'])) && ($bjorn_theme_options['header_banner_editor'] <> '') && (isset($bjorn_theme_options['header_banner_position'])) && ($bjorn_theme_options['header_banner_position'] == 'center')){
        bjorn_header_promo_show();
    }

    // Show header banner
    if((isset($bjorn_theme_options['header_banner2_editor'])) && ($bjorn_theme_options['header_banner2_editor'] <> '') && (isset($bjorn_theme_options['header_banner2_position'])) && ($bjorn_theme_options['header_banner2_position'] == 'center')){
        bjorn_header_promo2_show();
    }

    // Show header logo
    if((isset($bjorn_theme_options['header_logo_position'])) && ($bjorn_theme_options['header_logo_position'] == 'center')) {
        /* bjorn_logo_show(); #custom remove normal position of logo */
    }

}

/**
 * Header right part display
 */
function bjorn_header_right_show() {
    $bjorn_theme_options = bjorn_get_theme_options();

    // Show header logo
    if((isset($bjorn_theme_options['header_logo_position'])) && ($bjorn_theme_options['header_logo_position'] == 'right')) {
        /* bjorn_logo_show(); #custom remove normal position of logo */
    }

    // Show header banner
    if((isset($bjorn_theme_options['header_banner_editor'])) && ($bjorn_theme_options['header_banner_editor'] <> '') && (isset($bjorn_theme_options['header_banner_position'])) && ($bjorn_theme_options['header_banner_position'] == 'right')){
        bjorn_header_promo_show();
    }

    // Show header banner
    if((isset($bjorn_theme_options['header_banner2_editor'])) && ($bjorn_theme_options['header_banner2_editor'] <> '') && (isset($bjorn_theme_options['header_banner2_position'])) && ($bjorn_theme_options['header_banner2_position'] == 'right')){
        bjorn_header_promo2_show();
    }

    ?>
<?php
}

/**
 * Homepage featured posts slider display
 */
function bjorn_blog_slider_show() {

    $bjorn_theme_options = bjorn_get_theme_options();

    // Revolution slider
    if(isset($bjorn_theme_options['blog_enable_revolution_slider']) && $bjorn_theme_options['blog_enable_revolution_slider']) {
      echo '<div class="bjorn-revolution-slider">'.do_shortcode("[rev_slider alias='BLOG_SLIDER']").'</div>';
    } else {
    // Theme slider
      // Demo settings
      if ( defined('DEMO_MODE') && isset($_GET['blog_homepage_slider_fullwidth']) ) {
        if($_GET['blog_homepage_slider_fullwidth'] == 1) {
          $bjorn_theme_options['blog_homepage_slider_fullwidth'] = 1;
        }
        if($_GET['blog_homepage_slider_fullwidth'] == 0) {
          $bjorn_theme_options['blog_homepage_slider_fullwidth'] = 0;
        }
      }

      if ( defined('DEMO_MODE') && isset($_GET['blog_homepage_slider_post_details_layout']) ) {
          $bjorn_theme_options['blog_homepage_slider_post_details_layout'] = $_GET['blog_homepage_slider_post_details_layout'];
      }

      if ( defined('DEMO_MODE') && isset($_GET['blog_enable_homepage_merge_slider']) ) {
        if($_GET['blog_enable_homepage_merge_slider'] == 1) {
          $bjorn_theme_options['blog_enable_homepage_merge_slider'] = true;
        }
        if($_GET['blog_enable_homepage_merge_slider'] == 0) {
          $bjorn_theme_options['blog_enable_homepage_merge_slider'] = false;
        }
      }

      if ( defined('DEMO_MODE') && isset($_GET['blog_enable_homepage_center_slide']) ) {
        if($_GET['blog_enable_homepage_center_slide'] == 1) {
          $bjorn_theme_options['blog_enable_homepage_center_slide'] = true;
        }
        if($_GET['blog_enable_homepage_center_slide'] == 0) {
          $bjorn_theme_options['blog_enable_homepage_center_slide'] = false;
        }
      }

      if ( defined('DEMO_MODE') && isset($_GET['blog_homepage_slider_items']) ) {
          $bjorn_theme_options['blog_homepage_slider_items'] = $_GET['blog_homepage_slider_items']; 
      }

      if ( defined('DEMO_MODE') && isset($_GET['blog_enable_readmore']) ) {
        if($_GET['blog_enable_readmore'] == 1) {
          $bjorn_theme_options['blog_enable_readmore'] = true;
        }
        if($_GET['blog_enable_readmore'] == 0) {
          $bjorn_theme_options['blog_enable_readmore'] = false;
        }
      }

      if ( defined('DEMO_MODE') && isset($_GET['blog_disable_homepage_slider_description']) ) {
        if($_GET['blog_disable_homepage_slider_description'] == 1) {
          $bjorn_theme_options['blog_disable_homepage_slider_description'] = true;
        }
        if($_GET['blog_disable_homepage_slider_description'] == 0) {
          $bjorn_theme_options['blog_disable_homepage_slider_description'] = false;
        }
      }
      
      $posts_per_page = 100;

      $args = array(
          'posts_per_page'   => $posts_per_page,
          'orderby'          => 'date',
          'order'            => 'DESC',
          'meta_key'         => '_post_featured_value',
          'meta_value'         => 'on',
          'post_type'        => 'post',
          'post_status'      => 'publish',
          'suppress_filters' => 0 
      );

      $posts = get_posts( $args );

      $total_posts = sizeof($posts);

      if($total_posts > 0) {

          if(isset($bjorn_theme_options['blog_homepage_slider_autoplay'])) {
              $slider_autoplay = $bjorn_theme_options['blog_homepage_slider_autoplay'];
          } else {
              $slider_autoplay = 3000;
          }

          if($slider_autoplay > 0) {
              $slider_autoplay_bool = 'true';
          } else {
              $slider_autoplay_bool = 'false';
          }

          if(isset($bjorn_theme_options['blog_enable_homepage_center_slide']) && $bjorn_theme_options['blog_enable_homepage_center_slide']) {
              $homepage_center_slide = 'true';
          } else {
              $homepage_center_slide = 'false';
          }

          if(isset($bjorn_theme_options['blog_enable_homepage_merge_slider']) && $bjorn_theme_options['blog_enable_homepage_merge_slider']) {
              $homepage_merge_slider = 'true';
          } else {
              $homepage_merge_slider = 'false';
          }

          if($slider_autoplay == 1) {
              $slider_autoplay_class = ' autoplay ';
          } else {
              $slider_autoplay_class = ' ';
          }

          if(isset($bjorn_theme_options['blog_homepage_slider_navigation'])) {
              $slider_navigation = $bjorn_theme_options['blog_homepage_slider_navigation'];
          } else {
              $slider_navigation = 1;
          }

          if(isset($bjorn_theme_options['blog_homepage_slider_margin'])) {
              $slider_margin = $bjorn_theme_options['blog_homepage_slider_margin'];
          } else {
              $slider_margin = 30;
          }

          if(isset($bjorn_theme_options['blog_enable_homepage_transparent_header'])&&$bjorn_theme_options['blog_enable_homepage_transparent_header']) {
            $slider_margin = 0;
          }

          if(isset($bjorn_theme_options['blog_homepage_slider_pagination'])) {
              $slider_pagination = $bjorn_theme_options['blog_homepage_slider_pagination'];
          } else {
              $slider_pagination = 'false';
          }

          if($slider_navigation == 1) {
              $slider_navigation = 'true';
          } else {
              $slider_navigation = 'false';
          }

          if(isset($bjorn_theme_options['blog_homepage_slider_post_details_layout'])) {
              $post_details_layout = $bjorn_theme_options['blog_homepage_slider_post_details_layout'];
          } else {
              $post_details_layout = 'horizontal';
          }

          echo '<div class="bjorn-post-list-wrapper '.esc_attr($slider_autoplay_class).'clearfix">';
          
          echo '<div id="bjorn-post-list" class="bjorn-post-list">';

          echo '<div class="owl-carousel">';

          $i = 0;
          $j = 0;

          foreach($posts as $post) {

              setup_postdata($post);

              $limit = 21;
              
              $excerpt = explode(' ', get_the_excerpt(), $limit);
              if (count($excerpt)>=$limit) {
                  array_pop($excerpt);
                  $excerpt = implode(" ",$excerpt).'...';
              } else {
                  $excerpt = implode(" ",$excerpt);
              } 

              $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);

              if((isset($bjorn_theme_options['blog_homepage_slider_fullwidth'])&&$bjorn_theme_options['blog_homepage_slider_fullwidth'])) {
                  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full');
              } else {
                  $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'bjorn-blog-thumb');
              }

              if(has_post_thumbnail( $post->ID )) {
                  $image_bg ='background-image: url('.$image[0].');';
              }
              else {
                  $image_bg = '';
              }

              $categories_list = get_the_category_list(', ', 0, $post->ID); // This variable is Safe and does not need esc functions

              if($homepage_merge_slider == 'true') {
                  if($j == 0) {
                      $data_merge = 2;
                  }
                  if($j == 1) {
                      $data_merge = 1;
                  }
                  if($j == 2) {
                      $data_merge = 1;
                      $j = -1;
                  }
              } else {
                  $data_merge = 0;
              }

              $post_comments = get_comments_number($post->ID);

              if((isset($bjorn_theme_options['blog_homepage_slider_readmore'])&&$bjorn_theme_options['blog_homepage_slider_readmore'])) {
                $read_more_button_html = '<a class="btn alt" href="'.esc_url(get_permalink($post->ID)).'">'.esc_html__('Read more', 'bjorn').'</a>';
              } else {
                $read_more_button_html = '';
              }
    
              $bjorn_post_classes = ' bjorn-post-layout-'.$post_details_layout;

              echo '<div class="bjorn-post'.esc_attr($bjorn_post_classes).' bjorn-theme-block" data-merge="'.esc_attr($data_merge).'">';

              echo '<div class="bjorn-post-image" data-style="'.esc_attr($image_bg).'">';

            // Post details
              echo '<div class="bjorn-post-details">
              <div class="bjorn-post-category">'.wp_kses_post($categories_list).'</div>
              <div class="bjorn-post-title"><a href="'.esc_url(get_permalink($post->ID)).'"><h2 class="lined">'.esc_html($post->post_title).'</h2></a></div>';

              // Hide post description
              if(isset($bjorn_theme_options['blog_homepage_slider_description']) && $bjorn_theme_options['blog_homepage_slider_description']) {

                echo '<div class="bjorn-post-description">'.esc_html($excerpt).'</div>';

              }

              // Post details
              if(isset($bjorn_theme_options['blog_homepage_slider_details']) && $bjorn_theme_options['blog_homepage_slider_details']) {

                ob_start();
                do_action('bjorn_post_views', $post);
                $post_views = ob_get_clean();

                $post_info_html = '<span class="bjorn-post-comments"><a href="'.get_comments_link( $post->ID ).'"><i class="fa fa-comment" aria-hidden="true"></i>'.$post_comments.'</a></span><span class="bjorn-post-views"><i class="fa fa-eye" aria-hidden="true"></i>'.$post_views.'</span>';
              } else {
                $post_info_html = '';
              }

              echo '<div class="bjorn-post-date">'.get_the_time( get_option( 'date_format' ), $post->ID ).wp_kses_post($post_info_html).'</div>
              '.wp_kses_post($read_more_button_html).'</div>';

              echo '</div>';

              echo '</div>';

              $i++;
              $j++;

          } 

          wp_reset_postdata();


          echo '</div>';
          echo '</div>';

          echo '</div>';
     
          // Slider items per row
          if(!isset($bjorn_theme_options['blog_homepage_slider_items'])) {
              $bjorn_theme_options['blog_homepage_slider_items'] = 2;
          }

          $slider_slides = $bjorn_theme_options['blog_homepage_slider_items'];

          wp_add_inline_script( 'bjorn-script', '(function($){
          $(document).ready(function() {

              var owlpostslider = $("#bjorn-post-list .owl-carousel").owlCarousel({
                  loop: true,
                  items:'.esc_js($slider_slides).',
                  center:'.esc_js($homepage_center_slide ).',
                  merge:'.esc_js($homepage_merge_slider ).',
                  autoplay:'.esc_js($slider_autoplay_bool).',
                  autowidth: false,
                  autoplayTimeout:'.esc_js($slider_autoplay).',
                  autoplaySpeed: 1000,
                  navSpeed: 1000,
                  nav: '.esc_js($slider_navigation).',
                  dots: '.esc_js($slider_pagination).',
                  navText: false,
                  margin: '.esc_js($slider_margin).',
                  responsive: {
                      1199:{
                          items:'.esc_js($slider_slides).'
                      },
                      979:{
                          items:1
                      },
                      768:{
                          items:1
                      },
                      479:{
                          items:1
                      },
                      0:{
                          items:1
                      }
                  }
              })

          });})(jQuery);');

          // Add slider pagination
          wp_add_inline_script( 'bjorn-script', '(function($){
          $(document).ready(function() { var slider_pagination_width = 100/$(".bjorn-post-list .owl-item:not(.cloned)").length;

$(".bjorn-blog-posts-slider .bjorn-post-list .owl-theme .owl-dots .owl-dot").css("width", slider_pagination_width+"%");});})(jQuery);');


      
          
      }
    }
        
}

/**
 * Editors pick's block display
 */
function bjorn_blog_editorspick_posts_show() {

    $bjorn_theme_options = bjorn_get_theme_options();

    if(isset($bjorn_theme_options['blog_homepage_editorspick_posts_layout'])) {
        $editorspick_posts_layout = $bjorn_theme_options['blog_homepage_editorspick_posts_layout'];
    } else {
        $editorspick_posts_layout = 'masonry';
    }

    if(isset($bjorn_theme_options['blog_homepage_editorspick_posts_limit'])) {
        $editorspick_posts_limit = $bjorn_theme_options['blog_homepage_editorspick_posts_limit'];
    } else {
        $editorspick_posts_limit = 2;
    }

    if(isset($bjorn_theme_options['blog_homepage_editorspick_posts_category'])) {
        $editorspick_posts_category = $bjorn_theme_options['blog_homepage_editorspick_posts_category'];
    } else {
        $editorspick_posts_category = '';
    }

    if($editorspick_posts_limit == 2) {

        if($editorspick_posts_layout == 'masonry') {
            $posts_limit = 5;
        }
        if($editorspick_posts_layout == 'small') {
            $posts_limit = 8;
        }
        if($editorspick_posts_layout == 'large') {
            $posts_limit = 4;
        }

    } else {
        if($editorspick_posts_layout == 'masonry') {
            $posts_limit = 3;
        }
        if($editorspick_posts_layout == 'small') {
            $posts_limit = 4;
        }
        if($editorspick_posts_layout == 'large') {
            $posts_limit = 2;
        }
    }

    $args = array(
        'posts_per_page'   => $posts_limit,
        'orderby'          => 'date',
        'order'            => 'DESC',
        'category_name'         => $editorspick_posts_category,
        'meta_key'         => '_post_editorpick_value',
        'meta_value'         => 'on',
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'suppress_filters' => 0 
    );

    $posts = get_posts( $args );

    $total_posts = sizeof($posts);

    if($total_posts > 0) {

        echo '<div class="bjorn-editorspick-post-list-wrapper bjorn-theme-block clearfix">';

        if(isset($bjorn_theme_options['blog_homepage_editorspick_posts_subtitle']) && $bjorn_theme_options['blog_homepage_editorspick_posts_subtitle'] !== '') {
            echo '<h4>'.esc_html($bjorn_theme_options['blog_homepage_editorspick_posts_subtitle']).'</h4>';
        }

        if(isset($bjorn_theme_options['blog_homepage_editorspick_posts_title']) && $bjorn_theme_options['blog_homepage_editorspick_posts_title'] !== '') {
            echo '<h2 class="lined">'.esc_html($bjorn_theme_options['blog_homepage_editorspick_posts_title']).'</h2>';
        }
        
        echo '<div id="bjorn-editorspick-post-list" class="bjorn-editorspick-post-list">';
        echo '<div class="row bjorn-editorspick-post-row">';

        $i = 0;

        foreach($posts as $post) {

            setup_postdata($post);
            
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'bjorn-blog-thumb');

            if(has_post_thumbnail( $post->ID )) {
                $image_bg ='background-image: url('.$image[0].');';
            }
            else {
                $image_bg = '';
            }

            $categories_list = get_the_category_list( ', ', 0, $post->ID );

            if($editorspick_posts_layout == 'masonry') {
                $i_divider = -1;
                $i_show_big = array(2);

                if($editorspick_posts_limit == 1) {
                  $i_show_big = array(1);
                }
            }
            if($editorspick_posts_layout == 'small') {
                $i_divider = 4;
                $i_show_big = array(-1);
            }
            if($editorspick_posts_layout == 'large') {
                $i_divider = 2;
                $i_show_big = array(0, 1, 2, 3, 4, 5);
            }

            if($i == $i_divider) {
                echo '</div><div class="row bjorn-editorspick-post-row">';
            }

            // Masonry layout
            if(in_array($i, $i_show_big)) {

                $limit = 50;

                $excerpt = explode(' ', get_the_excerpt(), $limit);
                if (count($excerpt)>=$limit) {
                    array_pop($excerpt);
                    $excerpt = implode(" ",$excerpt).'...';
                } else {
                    $excerpt = implode(" ",$excerpt);
                } 

                $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);

                // Big post without image
                if($editorspick_posts_layout !== 'masonry' || $editorspick_posts_limit == 1) {
                  echo '<div class="col-md-6">';
                } else {
                  if($i == 2) {
                    echo '<div class="col-md-6">';
                  }
                  
                }

                echo '<div class="bjorn-editorspick-post bjorn-editorspick-post-large">';

                  if(has_post_thumbnail( $post->ID )) {

                    echo '<a href="'.esc_url(get_permalink($post->ID)).'"><div class="bjorn-editorspick-post-image hover-effect-img" data-style="'.esc_attr($image_bg).'"></div></a>';
                  }

                  echo '<div class="bjorn-editorspick-post-details">
                    
                    <div class="bjorn-editorspick-post-category">'.wp_kses_post($categories_list).'</div>
                    <div class="bjorn-editorspick-post-title"><a href="'.esc_url(get_permalink($post->ID)).'"><h2>'.esc_html($post->post_title).'</h2></a></div>
                    <div class="bjorn-editorspick-post-date">'.get_the_time( get_option( 'date_format' ), $post->ID ).'</div>
                
                </div>';

                if($editorspick_posts_layout !== 'masonry' || $editorspick_posts_limit == 1) {
                  echo '</div>';
                } else {
                  if($i == 2) {
                    echo '</div>';
                  }
                  
                }

                echo '</div>';
    
            } else {

                // Small post with image
                if($editorspick_posts_layout !== 'masonry' || $editorspick_posts_limit == 1) {
                  echo '<div class="col-md-3">';
                } else {
                  if($i == 0 || $i == 3) {
                    echo '<div class="col-md-3">';
                  }
                }

                echo '<div class="bjorn-editorspick-post bjorn-editorspick-post-small">';
                if(has_post_thumbnail( $post->ID )) {

                  echo '<a href="'.esc_url(get_permalink($post->ID)).'"><div class="bjorn-editorspick-post-image hover-effect-img" data-style="'.esc_attr($image_bg).'"></div></a>
                <div class="bjorn-editorspick-post-details">';
                }
                echo '<div class="bjorn-editorspick-post-category">'.wp_kses_post($categories_list).'</div>
                    <div class="bjorn-editorspick-post-title"><a href="'.esc_url(get_permalink($post->ID)).'"><h2>'.esc_html($post->post_title).'</h2></a></div>
                    <div class="bjorn-editorspick-post-date">'.get_the_time( get_option( 'date_format' ), $post->ID ).'</div>
                </div>';

                echo '</div>';

                if($editorspick_posts_layout !== 'masonry' || $editorspick_posts_limit == 1) {
                  echo '</div>';
                } else {
                  if($i == 1 || $i == 4) {
                    echo '</div>';
                  }
                  
                }

            }

            $i++;

        }

        wp_reset_postdata();

        echo '</div>';
        echo '</div>';

        echo '</div>';
    }

        
}

/**
 * Ads banner display
 */
function bjorn_banner_show($banner_id) {

    $bjorn_theme_options = bjorn_get_theme_options();

    $banner_option_name = 'banner_enable_'.$banner_id;
    $banner_content_name = 'banner_'.$banner_id.'_content';

    if(isset($bjorn_theme_options[$banner_option_name]) && $bjorn_theme_options[$banner_option_name]) {

        echo '<div class="bjorn-ad-block bjorn-ad-block-'.$banner_id.' clearfix">';
        echo do_shortcode($bjorn_theme_options[$banner_content_name]); // This is safe place and we can't use wp_kses_post or other esc_ functions here because this is ads area where user may use Google Adsense and other Java Script banners code with <script> inside.

        echo '</div>';
    }

}

/**
 * Homepage welcome block display
 */
function bjorn_welcome_block_show() {

    $bjorn_theme_options = bjorn_get_theme_options();

    if(isset($bjorn_theme_options['blog_enable_homepage_welcome_block']) && $bjorn_theme_options['blog_enable_homepage_welcome_block'] &&isset($bjorn_theme_options['blog_homepage_welcome_block_content']) && $bjorn_theme_options['blog_homepage_welcome_block_content']!=='') {

        echo '<div class="container">';

        echo '<div class="homepage-welcome-block">';
        echo '<div class="row">';
        
        echo '<div class="homepage-welcome-block-content clearfix">';

        if(isset($bjorn_theme_options['blog_homepage_welcome_block_content'])) {
            echo do_shortcode(wp_kses_post($bjorn_theme_options['blog_homepage_welcome_block_content']));
        }

        echo '</div>';
        

        echo '</div>';
        echo '</div>';

        echo '</div>';
    }

}

/**
 * Homepage popular posts slider display
 */
function bjorn_popularposts_slider_show() {

    $bjorn_theme_options = bjorn_get_theme_options();

    if(isset($bjorn_theme_options['blog_enable_homepage_popular_slider']) && $bjorn_theme_options['blog_enable_homepage_popular_slider']) {
        
        if(isset($bjorn_theme_options['blog_homepage_popular_slider_limit'])) {
            $popular_posts_limit = $bjorn_theme_options['blog_homepage_popular_slider_limit'];
        } else {
            $popular_posts_limit = 10;
        }

        if(isset($bjorn_theme_options['blog_homepage_popular_slider_category'])) {
            $popular_posts_category = $bjorn_theme_options['blog_homepage_popular_slider_category'];
        } else {
            $popular_posts_category = '';
        }

        $args = array(
            'posts_per_page'   => $popular_posts_limit,
            'order'            => 'DESC',
            'category_name'         => $popular_posts_category,
            'orderby' => 'meta_value',
            'meta_key'         => 'post_views_count',
            'post_type'        => 'post',
            'post_status'      => 'publish',
            'suppress_filters' => 0 
        );

        $posts = get_posts( $args );

        $total_posts = sizeof($posts);

        if($total_posts > 0) {

            echo '<div class="bjorn-popular-post-list-wrapper clearfix container bjorn-theme-block">';
            
            if(isset($bjorn_theme_options['blog_homepage_popular_posts_subtitle']) && $bjorn_theme_options['blog_homepage_popular_posts_subtitle'] !== '') {
                echo '<h4 class="lined">'.esc_html($bjorn_theme_options['blog_homepage_popular_posts_subtitle']).'</h4>';
            }

            if(isset($bjorn_theme_options['blog_homepage_popular_posts_title']) && $bjorn_theme_options['blog_homepage_popular_posts_title'] !== '') {
                echo '<h2 class="lined">'.esc_html($bjorn_theme_options['blog_homepage_popular_posts_title']).'</h2>';
            }

            echo '<div class="row clearfix">';
            echo '<div class="col-md-12">';
            echo '<div class="bjorn-popular-post-list-nav">';
                echo '<div class="bjorn-popular-post-list-nav-next">';
                echo '</div>';
                echo '<div class="bjorn-popular-post-list-nav-prev">';
                echo '</div>';
            echo '</div>';
            echo '<div class="bjorn-popular-post-list-content">';
            echo '<div class="bjorn-popular-post-list-content-inner">';
            echo '<div id="bjorn-popular-post-list" class="bjorn-popular-post-list owl-carousel">';

            foreach($posts as $post) {

                setup_postdata($post);
                
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'bjorn-blog-thumb');

                if(has_post_thumbnail( $post->ID )) {
                    $image_bg ='background-image: url('.$image[0].');';
                    $post_class = '';
                }
                else {
                    $image_bg = '';
                    $post_class = ' bjorn-popular-post-no-image';
                }

                $categories_list = get_the_category_list( ', ', 0, $post->ID );

                $post_comments = get_comments_number($post->ID);

                echo '<div class="bjorn-popular-post'.esc_attr($post_class).'">';

                if(has_post_thumbnail( $post->ID )) {
                  echo '<a href="'.esc_url(get_permalink($post->ID)).'"><div class="bjorn-popular-post-image hover-effect-img" data-style="'.esc_attr($image_bg).'"></div></a>';
                }

                echo '<div class="bjorn-popular-post-details">
                    <div class="bjorn-popular-post-category">'.wp_kses_post($categories_list).'</div>
                    <div class="bjorn-popular-post-title"><a href="'.esc_url(get_permalink($post->ID)).'"><h5>'.esc_html($post->post_title).'</h5></a></div>
                    <div class="bjorn-popular-post-date">'.get_the_time( get_option( 'date_format' ), $post->ID ).'</div>
                </div>';

                echo '</div>';

            }

            wp_reset_postdata();

            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            echo '</div>';

            $slider_slides = 4;

            if(isset($bjorn_theme_options['blog_homepage_popular_slider_autoplay'])) {
                $slider_autoplay = $bjorn_theme_options['blog_homepage_popular_slider_autoplay'];
            } else {
                $slider_autoplay = 3000;
            }

            if($slider_autoplay > 0) {
                $slider_autoplay_bool = 'true';
            } else {
                $slider_autoplay_bool = 'false';
            }

            wp_add_inline_script( 'bjorn-script', '(function($){
            $(document).ready(function() {
                var owl = $(".bjorn-popular-post-list-wrapper .bjorn-popular-post-list");

                owl.owlCarousel({
                    loop: true,
                    items:'.esc_js($slider_slides).',
                    autoplay:'.esc_js($slider_autoplay_bool).',
                    autowidth: false,
                    autoplayTimeout:'.esc_js($slider_autoplay).',
                    autoplaySpeed: 1000,
                    navSpeed: 1000,
                    dots: false,
                    responsive: {
                        1199:{
                            items:'.esc_js($slider_slides).'
                        },
                        979:{
                            items:2
                        },
                        768:{
                            items:2
                        },
                        479:{
                            items:1
                        },
                        0:{
                            items:1
                        }
                    }
                });

                $(".bjorn-popular-post-list-wrapper .bjorn-popular-post-list-nav .bjorn-popular-post-list-nav-next").click(function(){
                    owl.trigger(\'next.owl.carousel\', [1000]);
                })
                $(".bjorn-popular-post-list-wrapper .bjorn-popular-post-list-nav .bjorn-popular-post-list-nav-prev").click(function(){
                    owl.trigger(\'prev.owl.carousel\', [1000]);
                })

            });})(jQuery);');

        }

    }
}

/**
 * Footer shortcode block display
 */
function bjorn_footer_shortcode_show() {
  $bjorn_theme_options = bjorn_get_theme_options();

  if(isset($bjorn_theme_options['footer_shortcode_display']) && ($bjorn_theme_options['footer_shortcode_display'])):
  ?>
  <div class="container">
    <div class="footer-shortcode-block">
    <?php echo do_shortcode($bjorn_theme_options['footer_shortcode_code']); ?>
    </div>
  </div>
  <?php
  endif;
}

/**
 * Footer Instagram block display
 */
function bjorn_footer_instagram_show() {

  $bjorn_theme_options = bjorn_get_theme_options();
  
  // Instagram feed
  if(isset($bjorn_theme_options['footer_instagram_display']) && ($bjorn_theme_options['footer_instagram_display'])) {

      echo '<div class="footer-instagram-wrapper">';
      
      if(isset($bjorn_theme_options['footer_instagram_title']) && $bjorn_theme_options['footer_instagram_title'] <> '') {
        echo '<div class="container"><div class="row"><div class="col-md-12 bjorn-theme-block">';
        echo '<h4>'.esc_html($bjorn_theme_options['footer_instagram_title']).'</h4>';
        echo '<h2 class="lined">'.esc_html($bjorn_theme_options['footer_instagram_subtitle']).'</h2>';
        echo '</div></div></div>';
      }

      echo do_shortcode('[instagram-feed]');
      echo '</div>';
    
  }

}

/**
 * Footer HTML block display
 */
function bjorn_footer_htmlblock_show() {

  $bjorn_theme_options = bjorn_get_theme_options();

  if(isset($bjorn_theme_options['footer_htmlblock_display']) && ($bjorn_theme_options['footer_htmlblock_display'])) {

    if(isset($bjorn_theme_options['footer_htmlblock_bg_image']) && $bjorn_theme_options['footer_htmlblock_bg_image']['url'] <> '') {
      $style = 'background-image: url('.esc_url($bjorn_theme_options['footer_htmlblock_bg_image']['url']).');';
    } else {
      $style = '';
    }

    ?>
    <div class="footer-html-block" data-style="<?php echo esc_attr($style); ?>">
    <?php echo wp_kses_post($bjorn_theme_options['footer_htmlblock_content']); ?>
    </div>
    <?php

  }
}

/**
 *  Blog post excerpt display override
 */
function bjorn_excerpt_more( $more ) {
    return '...';
}
add_filter('excerpt_more', 'bjorn_excerpt_more');

/**
 *  Blog post read more display override
 */
function bjorn_modify_read_more_link() {
    return '<a class="more-link btn" href="' . esc_url(get_permalink()) . '">'.esc_html__('Read more', 'bjorn').'</a>';
}
add_filter( 'the_content_more_link', 'bjorn_modify_read_more_link' );

/**
 *  Custom BODY CSS classes
 */
add_filter( 'body_class', 'bjorn_body_classes' );
function bjorn_body_classes($classes) {

  $bjorn_theme_options = bjorn_get_theme_options();

  // Blog styles
  $blog_style = 1;

  $blog_style_class = Array();

  $blog_style_class[] = 'blog-style-'.$blog_style;


  // Single Post page related classes
  if(isset($bjorn_theme_options['blog_enable_post_transparent_header'])) {
    $blog_enable_post_transparent_header = $bjorn_theme_options['blog_enable_post_transparent_header'];
  } else {
    $blog_enable_post_transparent_header = false;
  }

  // Demo settings
  if ( defined('DEMO_MODE') && isset($_GET['blog_enable_post_transparent_header']) ) {
    if($_GET['blog_enable_post_transparent_header'] == 0) {
      $blog_enable_post_transparent_header = false;
    }
    if($_GET['blog_enable_post_transparent_header'] == 1) {
      $blog_enable_post_transparent_header = true;
    }
  }

  if($blog_enable_post_transparent_header) {
    $classes[] = 'blog-post-transparent-header-enable';
  } else {
    $classes[] = 'blog-post-transparent-header-disable';
  }

  if(isset($bjorn_theme_options['blog_enable_small_page_width'])) {
    $blog_enable_small_page_width = $bjorn_theme_options['blog_enable_small_page_width'];
  } else {
    $blog_enable_small_page_width = true;
  }

  // Demo settings
  if ( defined('DEMO_MODE') && isset($_GET['blog_enable_small_page_width']) ) {
    if($_GET['blog_enable_small_page_width'] == 0) {
      $blog_enable_small_page_width = false;
    }
    if($_GET['blog_enable_small_page_width'] == 1) {
      $blog_enable_small_page_width = true;
    }
  }

  if($blog_enable_small_page_width) {

    $classes[] = 'blog-small-page-width';

  }

  // Slider related classes
  if(isset($bjorn_theme_options['blog_enable_homepage_slider'])) {
    $blog_enable_homepage_slider = $bjorn_theme_options['blog_enable_homepage_slider'];
  } else {
    $blog_enable_homepage_slider = false;
  }

  if($blog_enable_homepage_slider) {

    $classes[] = 'blog-slider-enable';

    if(isset($bjorn_theme_options['blog_enable_homepage_transparent_header'])) {
      $blog_enable_homepage_transparent_header = $bjorn_theme_options['blog_enable_homepage_transparent_header'];
    } else {
      $blog_enable_homepage_transparent_header = false;
    }

    // Demo settings
    if ( defined('DEMO_MODE') && isset($_GET['blog_enable_homepage_transparent_header']) ) {
      if($_GET['blog_enable_homepage_transparent_header'] == 0) {
        $blog_enable_homepage_transparent_header = false;
      }
      if($_GET['blog_enable_homepage_transparent_header'] == 1) {
        $blog_enable_homepage_transparent_header = true;
      }
    }

    if($blog_enable_homepage_transparent_header) {
      $classes[] = 'blog-transparent-header-enable';
    } else {
      $classes[] = 'blog-transparent-header-disable';
    }

  } else {
    $classes[] = 'blog-slider-disable';
  }

  if(isset($bjorn_theme_options['blog_enable_dropcaps']) && $bjorn_theme_options['blog_enable_dropcaps']) {
    $classes[] = 'blog-enable-dropcaps';
  }

  if(isset($bjorn_theme_options['enable_images_animations']) && $bjorn_theme_options['enable_images_animations']) {
    $classes[] = 'blog-enable-images-animations';
  }

  return $classes;
}

/**
 * CMB2 images file list display
 *
 * @param  string  $file_list_meta_key The field meta key. ('wiki_test_file_list')
 * @param  string  $img_size           Size of image to show
 */
function bjorn_cmb2_get_images_src( $post_id, $file_list_meta_key, $img_size = 'medium' ) {

    // Get the list of files
    $files = get_post_meta( $post_id, $file_list_meta_key, 1 );

    $attachments_image_urls_array = Array();

    foreach ( (array) $files as $attachment_id => $attachment_url ) {
        
        $current_attach = wp_get_attachment_image_src( $attachment_id, $img_size );

        $attachments_image_urls_array[] = $current_attach[0];
     
    }

    if($attachments_image_urls_array[0] == '') {
        $attachments_image_urls_array = array();
    }
     
    return $attachments_image_urls_array;
    
}

/**
 * Menu Links Customization
 */
class bjorn_description_walker extends Walker_Nav_Menu{
      function start_el(&$output, $item, $depth = 0, $args = Array(), $current_object_id = 0 ){
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
           $class_names = $value = '';
           $classes = empty( $item->classes ) ? array() : (array) $item->classes;
           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
          
           $add_class = '';
           
           $post = get_post($item->object_id);          

               $class_names = ' class="'.$add_class.' '. esc_attr( $class_names ) . '"';
               $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
               $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
               $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
               $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';

                    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

                if (is_object($args)) {
                    $item_output = $args->before;
                    $item_output .= '<a'. $attributes .'>';
                    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID );
                    $item_output .= $args->link_after;
                    if($item->description !== '') {
                        $item_output .= '<span>'.$item->description.'</span>';
                    }
                    
                    $item_output .= '</a>';
                    $item_output .= $args->after;
                    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

                }
     }
}
