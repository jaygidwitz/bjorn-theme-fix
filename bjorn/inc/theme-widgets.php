<?php
/* Widgets */
$bjorn_theme_options = bjorn_get_theme_options();

function bjorn_widgets_init() {
    $bjorn_theme_options = bjorn_get_theme_options();
    
    register_sidebar(
      array(
        'name' => esc_html__( 'Default Blog sidebar', 'bjorn' ),
        'id' => 'main-sidebar',
        'description' => esc_html__( 'Widgets in this area will be shown in the left or right site column on: Main Blog page, Archives, Search.', 'bjorn' )
      )
    );

    register_sidebar(
      array(
        'name' => esc_html__( 'Single Blog Post sidebar', 'bjorn' ),
        'id' => 'post-sidebar',
        'description' => esc_html__( 'Widgets in this area will be shown in the left or right site column on: Single Blog Post.', 'bjorn' )
      )
    );

    register_sidebar(
      array(
        'name' => esc_html__( 'Page sidebar', 'bjorn' ),
        'id' => 'page-sidebar',
        'description' => esc_html__( 'Widgets in this area will be shown in the left or right site column on: Page.', 'bjorn' )
      )
    );

    register_sidebar(
      array(
        'name' => esc_html__( 'WooCommerce sidebar', 'bjorn' ),
        'id' => 'woocommerce-sidebar',
        'description' => esc_html__( 'Widgets in this area will be shown in the left or right site column for woocommerce pages.', 'bjorn' )
      )
    );

    register_sidebar(
      array(
        'name' => __( 'Offcanvas Left sidebar', 'bjorn' ),
        'id' => 'offcanvas-sidebar',
        'description' => __( 'Widgets in this area will be shown in the left floating offcanvas menu sidebar that can be opened by toggle button in header. You can enable this sidebar in theme control panel.', 'bjorn' )
      )
    );

    register_sidebar(
      array(
        'name' => esc_html__( 'Footer sidebar', 'bjorn' ),
        'id' => 'footer-sidebar',
        'description' => esc_html__( 'Widgets in this area will be shown in site footer in 3 columns.', 'bjorn' )
      )
    );

    register_sidebar(
      array(
        'name' => __( 'Footer dark sidebar', 'bjorn' ),
        'id' => 'footer-sidebar-2',
        'description' => __( 'Widgets in this area will be shown in site footer in 4 column after Footer sidebar #1.', 'bjorn' )
      )
    );

    // Custom widgets
    register_widget('Bjorn_Widget_Recent_Posts');
    register_widget('Bjorn_Widget_Popular_Posts');
    register_widget('Bjorn_Widget_Posts_Slider');
    register_widget('Bjorn_Widget_Recent_Comments');
    register_widget('Bjorn_Widget_Content');
    register_widget('Bjorn_Widget_Social_Icons');
    
}

add_action( 'widgets_init', 'bjorn_widgets_init' );

/* Custom widgets */

/**
 * Recent_Posts widget class
 */
class Bjorn_Widget_Recent_Posts extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_bjorn_recent_entries', 'description' => esc_html__( "Your site&#8217;s most recent Posts with thumbnails.", 'bjorn') );
        parent::__construct('bjorn-recent-posts', esc_html__('Bjorn Recent Posts', 'bjorn'), $widget_ops);
        $this->alt_option_name = 'widget_bjorn_recent_entries';
    }

    public function widget($args, $instance) {
        $cache = array();
        if ( ! $this->is_preview() ) {
            $cache = wp_cache_get( 'widget_bjorn_recent_posts', 'widget' );
        }

        if ( ! is_array( $cache ) ) {
            $cache = array();
        }

        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo wp_kses_post($cache[ $args['widget_id'] ]);
            return;
        }

        ob_start();

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Recientes', 'bjorn' );

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
        $show_thumb = isset( $instance['show_thumb'] ) ? $instance['show_thumb'] : false;

        /**
         * Filter the arguments for the Recent Posts widget.
         *
         * @since 3.4.0
         *
         * @see WP_Query::get_posts()
         *
         * @param array $args An array of arguments used to retrieve the recent posts.
         */
        $r = new WP_Query( apply_filters( 'widget_bjorn_posts_args', array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true
        ) ) );

        if ($r->have_posts()) :
?>
        <?php echo wp_kses_post($args['before_widget']); ?>
        <?php if ( $title ) {
            echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
        } ?>
        <ul>
        <?php $i=0; ?>
        <?php while ( $r->have_posts() ) : $r->the_post(); ?>
        <?php $image_bg = ''; ?>
            <li class="clearfix">
            <?php if ( $show_thumb && has_post_thumbnail( get_the_ID() ) ) : ?>
                <div class="widget-post-thumb-wrapper-container">
                <a href="<?php the_permalink(); ?>">
                <?php if(($i==0) && (has_post_thumbnail( get_the_ID() )) ): ?>
                <?php
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'bjorn-blog-thumb');
                    $image_bg ='background-image: url('.$image[0].');';
                ?>
                <div class="widget-post-thumb-wrapper hover-effect-img" data-style="<?php echo esc_attr($image_bg);?>"></div>
                <?php endif; ?>
                <?php if($i>0): ?>
                <?php
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'bjorn-blog-thumb-widget');
                    $image_bg ='background-image: url('.$image[0].');';
                ?>
                <div class="widget-post-thumbsmall-wrapper hover-effect-img" data-style="<?php echo esc_attr($image_bg);?>"></div>
                <?php endif; ?>
                </a>
                </div>
            <?php endif; ?>
            <div class="widget-post-details-wrapper">
                <?php if ( $show_date ) : ?>
                    <span class="post-date"><?php echo get_the_date(); ?></span>
                <?php endif; ?>
                <a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
            </div>
            </li>
            <?php $i++; ?>
        <?php endwhile; ?>
        </ul>
        <?php echo wp_kses_post($args['after_widget']); ?>
<?php
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        endif;

        if ( ! $this->is_preview() ) {
            $cache[ $args['widget_id'] ] = ob_get_flush();
            wp_cache_set( 'widget_bjorn_recent_posts', $cache, 'widget' );
        } else {
            ob_end_flush();
        }
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        $instance['show_thumb'] = isset( $new_instance['show_thumb'] ) ? (bool) $new_instance['show_thumb'] : false;

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_bjorn_recent_entries']) )
            delete_option('widget_bjorn_recent_entries');

        return $instance;
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        $show_thumb = isset( $instance['show_thumb'] ) ? (bool) $instance['show_thumb'] : false;
?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'bjorn' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php esc_html_e( 'Number of posts to show:', 'bjorn' ); ?></label>
        <input id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_date' )); ?>" />
        <label for="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>"><?php esc_html_e( 'Display post date?', 'bjorn' ); ?></label></p>

        <p><input class="checkbox" type="checkbox" <?php checked( $show_thumb ); ?> id="<?php echo esc_attr($this->get_field_id( 'show_thumb' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_thumb' )); ?>" />
        <label for="<?php echo esc_attr($this->get_field_id( 'show_thumb' )); ?>"><?php esc_html_e( 'Display post featured image?', 'bjorn' ); ?></label></p>
<?php
    }
}

/**
 * Popular_Posts widget class
 */
class Bjorn_Widget_Popular_Posts extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_bjorn_popular_entries', 'description' => esc_html__( "Your site&#8217;s most viewed Posts with thumbnails.", 'bjorn') );
        parent::__construct('bjorn-popular-posts', esc_html__('Bjorn Popular Posts', 'bjorn'), $widget_ops);
        $this->alt_option_name = 'widget_bjorn_popular_entries';
    }

    public function widget($args, $instance) {
        $cache = array();
        if ( ! $this->is_preview() ) {
            $cache = wp_cache_get( 'widget_bjorn_popular_posts', 'widget' );
        }

        if ( ! is_array( $cache ) ) {
            $cache = array();
        }

        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo wp_kses_post($cache[ $args['widget_id'] ]);
            return;
        }

        ob_start();

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Populares', 'bjorn' );

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;

        $display_type = isset( $instance['display_type'] ) ? ( $instance['display_type'] ) : 'images';

        /**
         * Filter the arguments for the Popular Posts widget.
         *
         * @since 3.4.0
         *
         * @see WP_Query::get_posts()
         *
         * @param array $args An array of arguments used to retrieve the recent posts.
         */

        $r = new WP_Query( apply_filters( 'widget_bjorn_posts_args', array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'orderby' => 'meta_value',
            'meta_key'         => 'post_views_count',
            'post_status'         => 'publish',
            'orderby'                => 'meta_value_num',
            'order'                => 'DESC',
            'ignore_sticky_posts' => true
        ) ) );

        if ($r->have_posts()) :
?>
        <?php echo wp_kses_post($args['before_widget']); ?>
        <?php if ( $title ) {
            echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
        } ?>
        <ul>
        <?php $i=0; ?>
        <?php while ( $r->have_posts() ) : $r->the_post(); ?>
        <?php $image_bg = ''; ?>
            <li class="clearfix">

    
                <?php if( ($i==0 && $display_type == 'firstimage' && (has_post_thumbnail( get_the_ID() ))) || ($display_type == 'images' && (has_post_thumbnail( get_the_ID() ))) ): ?>
                <div class="widget-post-thumb-wrapper-container">
                               
                    <?php
                        $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'bjorn-blog-thumb');
                        $image_bg ='background-image: url('.$image[0].');';

                        $categories_list = get_the_category_list( ', ' );
                    ?>
                    <a href="<?php the_permalink(); ?>"><div class="widget-post-thumb-wrapper hover-effect-img" data-style="<?php echo esc_attr($image_bg);?>"><div class="widget-post-position"><?php echo esc_html($i+1); ?></div></div></a>
                    <div class="widget-post-details-wrapper">
                        <div class="widget-post-details-wrapper-inside">
                            <div class="post-category"><?php echo wp_kses_post($categories_list); ?></div>
                            <a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
                        </div>
                    </div>
                    
                </div>
                <?php else: ?>
                    <?php
                        $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'bjorn-blog-thumb-widget');
                        $image_bg ='background-image: url('.$image[0].');';

                        $categories_list = get_the_category_list( ', ' );
                    ?>
                    <a href="<?php the_permalink(); ?>"><div class="widget-post-thumbsmall-wrapper hover-effect-img" data-style="<?php echo esc_attr($image_bg);?>"><div class="widget-post-position"><?php echo esc_html($i+1); ?></div></div></a>
                    <div class="widget-post-details-wrapper">
                        
                        <div class="post-category"><?php echo wp_kses_post($categories_list); ?></div>
                        <a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
                        
                    </div>

                <?php endif; ?>


            </li>
            <?php $i++; ?>
        <?php endwhile; ?>
        </ul>
        <?php echo wp_kses_post($args['after_widget']); ?>
<?php
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        endif;

        if ( ! $this->is_preview() ) {
            $cache[ $args['widget_id'] ] = ob_get_flush();
            wp_cache_set( 'widget_bjorn_popular_posts', $cache, 'widget' );
        } else {
            ob_end_flush();
        }
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['display_type'] = strip_tags($new_instance['display_type']);

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_bjorn_popular_entries']) )
            delete_option('widget_bjorn_popular_entries');

        return $instance;
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        $show_thumb = isset( $instance['show_thumb'] ) ? (bool) $instance['show_thumb'] : false;
        $display_type     = isset( $instance['display_type'] ) ? ( $instance['display_type'] ) : 'images';
?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'bjorn' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        
        <p><label for="<?php echo esc_attr($this->get_field_id('display_type')); ?>"><?php esc_html_e('Display type:', 'bjorn'); ?></label>
        <select id="<?php echo esc_attr($this->get_field_id('display_type')); ?>" name="<?php echo esc_attr($this->get_field_name('display_type')); ?>">
           
            <option value="images"<?php if($display_type == 'images') { echo ' selected';} ?>><?php esc_html_e('Thumbnails', 'bjorn'); ?></option>
            <option value="firstimage"<?php if($display_type == 'firstimage') { echo ' selected';} ?>><?php esc_html_e('Thumbnail in first post + Small Thubnails in other', 'bjorn'); ?></option>
            <option value="text"<?php if($display_type == 'text') { echo ' selected';} ?>><?php esc_html_e('Small thumbnails only', 'bjorn'); ?></option>
        </select>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php esc_html_e( 'Number of posts to show:', 'bjorn' ); ?></label>
        <input id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>

<?php
    }
}

/**
 * Posts_Slider widget class
 */
class Bjorn_Widget_Posts_Slider extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_bjorn_posts_slider', 'description' => esc_html__( "Slider with posts details and different settings.", 'bjorn') );
        parent::__construct('bjorn-posts-slider', esc_html__('Bjorn Posts Slider', 'bjorn'), $widget_ops);
        $this->alt_option_name = 'widget_bjorn_posts_slider';
    }

    public function widget($args, $instance) {
        $cache = array();
        if ( ! $this->is_preview() ) {
            $cache = wp_cache_get( 'widget_bjorn_posts_slider', 'widget' );
        }

        if ( ! is_array( $cache ) ) {
            $cache = array();
        }

        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo wp_kses_post($cache[ $args['widget_id'] ]);
            return;
        }

        ob_start();

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;

        $catid = ( ! empty( $instance['catid'] ) ) ? ( $instance['catid'] ) : '';

        $autoplay = isset( $instance['autoplay'] ) ? $instance['autoplay'] : false;

        $posts_type = strip_tags($instance['posts_type']);

        if ( ! $number )
            $number = 5;

        /**
         * Filter the arguments for the Recent Posts widget.
         *
         * @since 3.4.0
         *
         * @see WP_Query::get_posts()
         *
         * @param array $args An array of arguments used to retrieve the recent posts.
         */

        if($posts_type == 'recent') {
            $r = new WP_Query( apply_filters( 'widget_bjorn_posts_args', array(
                'posts_per_page'      => $number,
                'no_found_rows'       => true,
                'orderby'          => 'date',
                'order'            => 'DESC',
                'cat' => $catid,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true
            ) ) );
        }

        if($posts_type == 'popular') {
            $r = new WP_Query( apply_filters( 'widget_bjorn_posts_args', array(
                'posts_per_page'      => $number,
                'no_found_rows'       => true,
                'orderby' => 'meta_value',
                'meta_key'         => 'post_views_count',
                'cat' => $catid,
                'post_status'         => 'publish',
                'orderby'                => 'meta_value_num',
                'order'                => 'DESC',
                'ignore_sticky_posts' => true
            ) ) );
        }

        if($posts_type == 'editorspick') {
            $r = new WP_Query( apply_filters( 'widget_bjorn_posts_args', array(
                'posts_per_page'      => $number,
                'no_found_rows'       => true,
                'orderby'          => 'date',
                'order'            => 'DESC',
                'meta_key'         => '_post_editorpick_value',
                'meta_value'         => 'on',
                'cat' => $catid,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true
            ) ) );
        }

        if($posts_type == 'featured') {
            $r = new WP_Query( apply_filters( 'widget_bjorn_posts_args', array(
                'posts_per_page'      => $number,
                'no_found_rows'       => true,
                'orderby'          => 'date',
                'order'            => 'DESC',
                'meta_key'         => '_post_featured_value',
                'meta_value'         => 'on',
                'cat' => $catid,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true
            ) ) );
        }
        
        if ($r->have_posts()) :
?>
        <?php echo wp_kses_post($args['before_widget']); ?>
        <?php if (( $title )&&($title !== '')) {
            echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
        }

        $rand_id = rand(10000,10000000);

        ?>
        <div class="widget-post-slider-wrapper owl-carousel widget-post-slider-wrapper-<?php echo esc_attr($rand_id);?>">
        
        <?php while ( $r->have_posts() ) : $r->the_post(); ?>
        <?php 

        $image_bg = '';

        $categories_list = get_the_category_list( ', ' );

        ?>
            <div class="widget-post-slide">
          
                <div class="widget-post-thumb-wrapper-container">
                    <a href="<?php the_permalink(); ?>">
                    <?php if(has_post_thumbnail( get_the_ID() )): ?>
                    <?php
                        $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'bjorn-blog-thumb');
                        $image_bg ='background-image: url('.$image[0].');';
                    ?>
                    <div class="widget-post-thumb-wrapper hover-effect-img" data-style="<?php echo esc_attr($image_bg);?>"></div>
                    <?php endif; ?>
                    </a>
                </div>
          
                <div class="widget-post-details-wrapper">
                    <div class="widget-post-details-wrapper-inside">
                        <div class="post-category"><?php echo wp_kses_post($categories_list); ?></div>
                        <a class="post-title" href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
                        <div class="post-date"><?php echo get_the_date(); ?></div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        </div>
        <?php
            if($autoplay) {
                $autoplay_bool = 'true';
            } else {
                $autoplay_bool = 'false';
            }


            wp_add_inline_script( 'bjorn-script', '(function($){
                $(document).ready(function() {
                    var owl = $(".sidebar .widget.widget_bjorn_posts_slider .widget-post-slider-wrapper.widget-post-slider-wrapper-'.esc_attr($rand_id).'");

                    owl.owlCarousel({
                        loop: true,
                        items:1,
                        autoplay:'.esc_attr($autoplay_bool).',
                        autowidth: false,
                        autoplayTimeout:4000,
                        autoplaySpeed: 1000,
                        navSpeed: 1000,
                        dots: false,
                        responsive: {
                            1199:{
                                items:1
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
                    });

                });})(jQuery);');
            
        ?>
        <?php echo wp_kses_post($args['after_widget']); ?>
<?php
        // Reset the global $the_post as this query will have stomped on it
        wp_reset_postdata();

        endif;

        if ( ! $this->is_preview() ) {
            $cache[ $args['widget_id'] ] = ob_get_flush();
            wp_cache_set( 'widget_bjorn_posts_slider', $cache, 'widget' );
        } else {
            ob_end_flush();
        }
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['catid'] = $new_instance['catid'];
        $instance['posts_type'] = strip_tags($new_instance['posts_type']);
        $instance['autoplay'] = isset( $new_instance['autoplay'] ) ? (bool) $new_instance['autoplay'] : false;

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_bjorn_posts_slider']) )
            delete_option('widget_bjorn_posts_slider');

        return $instance;
    }

    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $catid     = isset( $instance['catid'] ) ? ( $instance['catid'] ) : '';
        $posts_type     = isset( $instance['posts_type'] ) ? ( $instance['posts_type'] ) : 'recent';
        $autoplay = isset( $instance['autoplay'] ) ? (bool) $instance['autoplay'] : false;
       
?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title (Leave empty to disable title):', 'bjorn' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
    
        <p><label for="<?php echo esc_attr($this->get_field_id('posts_type')); ?>"><?php esc_html_e('Posts type:', 'bjorn'); ?></label>
        <select id="<?php echo esc_attr($this->get_field_id('posts_type')); ?>" name="<?php echo esc_attr($this->get_field_name('posts_type')); ?>">
           
            <option value="recent"<?php if($posts_type == 'recent') { echo ' selected';} ?>><?php esc_html_e('Latest posts', 'bjorn'); ?></option>
            <option value="popular"<?php if($posts_type == 'popular') { echo ' selected';} ?>><?php esc_html_e('Popular', 'bjorn'); ?></option>
            <option value="editorspick"<?php if($posts_type == 'editorspick') { echo ' selected';} ?>><?php esc_html_e('Editors Pick', 'bjorn'); ?></option>
            <option value="featured"<?php if($posts_type == 'featured') { echo ' selected';} ?>><?php esc_html_e('Featured', 'bjorn'); ?></option>
        </select>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'catid' )); ?>"><?php esc_html_e( 'Category ID:', 'bjorn' ); ?></label>
        <input id="<?php echo esc_attr($this->get_field_id( 'catid' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'catid' )); ?>" type="text" value="<?php echo esc_attr($catid); ?>" size="3" /> <span>(show posts from this category only)</span></p>
        
        <p><label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php esc_html_e( 'Number of posts to show:', 'bjorn' ); ?></label>
        <input id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>
        
        <p><input class="checkbox" type="checkbox" <?php checked( $autoplay ); ?> id="<?php echo esc_attr($this->get_field_id( 'autoplay' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'autoplay' )); ?>" />
        <label for="<?php echo esc_attr($this->get_field_id( 'autoplay' )); ?>"><?php esc_html_e( 'Slider autoplay', 'bjorn' ); ?></label></p>
<?php
    }
}

/**
 * Recent_Comments widget class
 *
 */
class Bjorn_Widget_Recent_Comments extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_bjorn_recent_comments', 'description' => esc_html__( 'Your site&#8217;s most recent comments with date.', 'bjorn' ) );
        parent::__construct('bjorn-recent-comments', esc_html__('Bjorn Recent Comments', 'bjorn'), $widget_ops);
        $this->alt_option_name = 'widget_bjorn_recent_comments';
    }


    public function widget( $args, $instance ) {
        global $comments, $comment;

        $cache = array();
        if ( ! $this->is_preview() ) {
            $cache = wp_cache_get('widget_bjorn_recent_comments', 'widget');
        }
        if ( ! is_array( $cache ) ) {
            $cache = array();
        }

        if ( ! isset( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo wp_kses_post($cache[ $args['widget_id'] ]);
            return;
        }

        $output = '';

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Lo que comentan', 'bjorn' );

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;

        /**
         * Filter the arguments for the Recent Comments widget.
         *
         * @since 3.4.0
         *
         * @see WP_Comment_Query::query() for information on accepted arguments.
         *
         * @param array $comment_args An array of arguments used to retrieve the recent comments.
         */
        $comments = get_comments( apply_filters( 'widget_comments_args', array(
            'number'      => $number,
            'status'      => 'approve',
            'post_status' => 'publish'
        ) ) );

        $output .= $args['before_widget'];
        if ( $title ) {
            $output .= $args['before_title'] . $title . $args['after_title'];
        }

        $output .= '<ul id="bjorn_recentcomments">';
        if ( $comments ) {
            // Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
            $post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
            _prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );

            foreach ( (array) $comments as $comment) {
                $output .= '<li class="bjorn_recentcomments">';
                /* translators: comments widget: 1: comment author, 2: post link */
                $output .= '<span class="comment-date">'.get_comment_date( '', $comment->comment_ID ).'</span><a href="' . esc_url( get_comment_link( $comment->comment_ID ) ) . '">' . get_the_title( $comment->comment_post_ID ) . '</a>';
                
                $output .= '</li>';
            }
        }
        $output .= '</ul>';
        $output .= $args['after_widget'];

        echo wp_kses_post($output);

        if ( ! $this->is_preview() ) {
            $cache[ $args['widget_id'] ] = $output;
            wp_cache_set( 'widget_bjorn_recent_comments', $cache, 'widget' );
        }
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['number'] = absint( $new_instance['number'] );

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_bjorn_recent_comments']) )
            delete_option('widget_bjorn_recent_comments');

        return $instance;
    }

    public function form( $instance ) {
        $title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'bjorn' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p><label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php esc_html_e( 'Number of comments to show:', 'bjorn' ); ?></label>
        <input id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3" /></p>
<?php
    }
}

/**
 * Social buttons widget class
 *
 */
class Bjorn_Widget_Social_Icons extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_bjorn_social_icons', 'description' => esc_html__( 'Show social follow icons set in theme admin panel.', 'bjorn' ) );
        parent::__construct('bjorn-social-icons', esc_html__('Bjorn Social Icons', 'bjorn'), $widget_ops);
        $this->alt_option_name = 'widget_bjorn_social_icons';
    }

    public function widget( $args, $instance ) {
        $bjorn_theme_options = bjorn_get_theme_options();

        $cache = array();

        if ( ! $this->is_preview() ) {
            $cache = wp_cache_get('widget_bjorn_social_icons', 'widget');
        }
        if ( ! is_array( $cache ) ) {
            $cache = array();
        }

        if ( ! isset( $args['widget_id'] ) )
            $args['widget_id'] = $this->id;

        if ( isset( $cache[ $args['widget_id'] ] ) ) {
            echo wp_kses_post($cache[ $args['widget_id'] ]);
            return;
        }

        $output = '';

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Suscríbete y Síguenos', 'bjorn' );

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $output .= $args['before_widget'];
        if ( $title ) {
            $output .= $args['before_title'] . $title . $args['after_title'];
        }

        $output .= '<div class="textwidget">';

        $output_end = '</div>';
        $output_end .= $args['after_widget'];

        echo wp_kses_post($output); // This variable contains WordPress widget code and can't be escaped with WordPress functions 

        bjorn_social_show(false, true);

        echo wp_kses_post($output_end);

        if ( ! $this->is_preview() ) {
            $cache[ $args['widget_id'] ] = $output;
            wp_cache_set( 'widget_bjorn_social_icons', $cache, 'widget' );
        }
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        $alloptions = wp_cache_get( 'alloptions', 'options' );
        if ( isset($alloptions['widget_bjorn_social_icons']) )
            delete_option('widget_bjorn_social_icons');

        return $instance;
    }

    public function form( $instance ) {
        $title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
?>
        <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'bjorn' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
    }
}

/**
 * Bjorn Content widget class
 *
 * @since 2.8.0
 */
class Bjorn_Widget_Content extends WP_Widget {

    public function __construct() {
        $widget_ops = array('classname' => 'widget_bjorn_text', 'description' => esc_html__('Add widget with any HTML content or shortcodes inside.', 'bjorn'));
        $control_ops = array('width' => 400, 'height' => 350);
        parent::__construct('bjorn-text', esc_html__('Bjorn Content', 'bjorn'), $widget_ops, $control_ops);
    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        /**
         * Filter the content of the Text widget.
         *
         * @since 2.3.0
         *
         * @param string    $widget_text The widget content.
         * @param WP_Widget $instance    WP_Widget instance.
         */
        $text = apply_filters( 'widget_bjorn_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
        echo wp_kses_post($args['before_widget']);

        ?>
        <div class="bjorn-textwidget-wrapper <?php echo !empty( $instance['paddings'] ) ? ' bjorn-textwidget-no-paddings' : ''; ?>">
        <?php
        if ( ! empty( $title ) ) {
            echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
        } 
        if(!empty( $instance['button_target'])) {
            $button_target = '_blank';
        } else {
            $button_target = '_self';
        }
        if(!empty( $instance['bg_image'])) {
            $style = 'background-image: url('.esc_url($instance['bg_image']).');';
        } else {
            $style = '';
        }

        if(!empty( $instance['custom_padding'])) {
            $style .= 'padding: '.esc_attr($instance['custom_padding']).';';
        }

        if(!empty( $instance['text_color'])) {
            $style .= 'color: '.esc_attr($instance['text_color']).';';
        }

        if(!empty( $instance['text_align'])) {
            $style .= 'text-align: '.esc_attr($instance['text_align']).';';
        }
        
        ?>
            <div class="bjorn-textwidget" data-style="<?php echo esc_attr($style); ?>"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?><?php echo !empty( $instance['button_text'] ) ? '<a class="btn" href="'.esc_url($instance['button_url']).'" target="'.esc_attr($button_target).'">'.esc_html($instance['button_text']).'</a>' : ''; ?></div>
        </div>
        <?php
        echo wp_kses_post($args['after_widget']);
    }

    /**
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['custom_padding'] = strip_tags($new_instance['custom_padding']);
        $instance['text_color'] = strip_tags($new_instance['text_color']);
        $instance['text_align'] = strip_tags($new_instance['text_align']);
        $instance['button_text'] = strip_tags($new_instance['button_text']);
        $instance['button_url'] = strip_tags($new_instance['button_url']);
        $instance['bg_image'] = strip_tags($new_instance['bg_image']);
        
        if ( current_user_can('unfiltered_html') )
            $instance['text'] =  $new_instance['text'];
        else
            $instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
        $instance['filter'] = ! empty( $new_instance['filter'] );
        $instance['paddings'] = ! empty( $new_instance['paddings'] );
        $instance['button_target'] = ! empty( $new_instance['button_target'] );

        return $instance;
    }

    /**
     * @param array $instance
     */
    public function form( $instance ) {

        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' ); 
        wp_enqueue_script( 'wp-color-picker' ); 

        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '',  'button_text' => '','bg_image' => '','button_url' => '', 'custom_padding' => '', 'text_color' => '', 'text_align' => '') );
        $title = strip_tags($instance['title']);
        $button_text = strip_tags($instance['button_text']);
        $custom_padding = strip_tags($instance['custom_padding']);
        $bg_image = strip_tags($instance['bg_image']);
        $text_color = strip_tags($instance['text_color']);
        $text_align = strip_tags($instance['text_align']);
        
        $button_url = strip_tags($instance['button_url']);
        $text = esc_textarea($instance['text']);
?> 
        <p><input id="<?php echo esc_attr($this->get_field_id('paddings')); ?>" name="<?php echo esc_attr($this->get_field_name('paddings')); ?>" type="checkbox" <?php checked(isset($instance['paddings']) ? $instance['paddings'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr($this->get_field_id('paddings')); ?>"><?php esc_html_e('Disable paddings in widget', 'bjorn'); ?></label></p>
        <p><label for="<?php echo esc_attr($this->get_field_id('custom_padding')); ?>"><?php esc_html_e('Custom padding for content:', 'bjorn'); ?></label>
        <input class="" id="<?php echo esc_attr($this->get_field_id('custom_padding')); ?>" name="<?php echo esc_attr($this->get_field_name('custom_padding')); ?>" type="text" placeholder="<?php esc_html_e('For ex.: 10px 5px 10px 5px', 'bjorn'); ?>" value="<?php echo esc_attr($custom_padding); ?>" /></p>
        <p><label for="<?php echo esc_attr($this->get_field_id('text_align')); ?>"><?php esc_html_e('Text align:', 'bjorn'); ?></label>
        <select id="<?php echo esc_attr($this->get_field_id('text_align')); ?>" name="<?php echo esc_attr($this->get_field_name('text_align')); ?>">
            <option value="<?php echo esc_attr($text_align); ?>" selected><?php echo esc_attr($text_align); ?></option>
            <option value="left"><?php esc_html_e('Left', 'bjorn'); ?></option>
            <option value="center"><?php esc_html_e('Center', 'bjorn'); ?></option>
            <option value="right"><?php esc_html_e('Right', 'bjorn'); ?></option>
        </select>
        </p><p><label class="label-text-color" for="<?php echo esc_attr($this->get_field_id('text_color')); ?>"><?php esc_html_e('Text color:', 'bjorn'); ?></label>
        <input class="select-text-color" id="<?php echo esc_attr($this->get_field_id('text_color')); ?>" name="<?php echo esc_attr($this->get_field_name('text_color')); ?>" placeholder="<?php esc_html_e('For example: #ffffff', 'bjorn'); ?>" type="text" value="<?php echo esc_attr($text_color); ?>" /></p>
        <p><label for="<?php echo esc_attr($this->get_field_id('bg_image')); ?>"><?php esc_html_e('Background image url:', 'bjorn'); ?></label><br/>
        <input class="" id="<?php echo esc_attr($this->get_field_id('bg_image')); ?>" name="<?php echo esc_attr($this->get_field_name('bg_image')); ?>" type="text" value="<?php echo esc_attr($bg_image); ?>" /><a class="button upload-widget-bg-image" data-input_id="<?php echo esc_attr($this->get_field_id('bg_image')); ?>" data-uploader_button_text="<?php esc_html_e('Select background image', 'bjorn'); ?>" data-uploader_title="<?php esc_html_e('Add background image to widget', 'bjorn'); ?>"><?php esc_html_e( 'Select image', 'bjorn' ); ?></a></p>
        <p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Widget Title:', 'bjorn'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
     
        <p><label for="<?php echo esc_attr($this->get_field_id( 'text' )); ?>"><?php esc_html_e( 'Content:', 'bjorn' ); ?></label>
        <p><a class="button upload-widget-image" data-textarea_id="<?php echo esc_attr($this->get_field_id('text')); ?>" data-uploader_button_text="Add image to content" data-uploader_title="Add image to widget content"><?php esc_html_e( 'Add Image to content', 'bjorn' ); ?></a></p>
        <textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr($this->get_field_id('text')); ?>" name="<?php echo esc_attr($this->get_field_name('text')); ?>"><?php echo esc_attr($text); ?></textarea>
        </p>
         <p><input id="<?php echo esc_attr($this->get_field_id('filter')); ?>" name="<?php echo esc_attr($this->get_field_name('filter')); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr($this->get_field_id('filter')); ?>"><?php esc_html_e('Automatically add paragraphs', 'bjorn'); ?></label></p>

           <p><label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php esc_html_e('Button Text:', 'bjorn'); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" placeholder="<?php esc_html_e('Leave empty to disable button', 'bjorn'); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>" /></p>
        <p><label for="<?php echo esc_attr($this->get_field_id('button_url')); ?>"><?php esc_html_e('Button URL:', 'bjorn'); ?></label>
        <input class="" id="<?php echo esc_attr($this->get_field_id('button_url')); ?>" name="<?php echo esc_attr($this->get_field_name('button_url')); ?>" type="text" value="<?php echo esc_attr($button_url); ?>" /> <input id="<?php echo esc_attr($this->get_field_id('button_target')); ?>" name="<?php echo esc_attr($this->get_field_name('button_target')); ?>" type="checkbox" <?php checked(isset($instance['button_target']) ? $instance['button_target'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr($this->get_field_id('button_target')); ?>"><?php esc_html_e('Open in new tab', 'bjorn'); ?></label></p>
    
       
<?php

    }
}

?>