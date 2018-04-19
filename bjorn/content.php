<?php
/**
 * @package Bjorn
 */

$post_loop_details = bjorn_get_post_details();
$post_loop_id = $post_loop_details['post_loop_id'];
$span_class = $post_loop_details['span_class'];

$bjorn_theme_options = bjorn_get_theme_options();

// Demo settings
if ( defined('DEMO_MODE') && isset($_GET['blog_post_show_author']) ) {
  if($_GET['blog_post_show_author'] == 1) {
    $bjorn_theme_options['blog_post_show_author'] = true;
  }
  if($_GET['blog_post_show_author'] == 0) {
    $bjorn_theme_options['blog_post_show_author'] = false;
  }
}

if ( defined('DEMO_MODE') && isset($_GET['blog_layout']) ) {
    $bjorn_theme_options['blog_layout'] = $_GET['blog_layout'];
}

$post_classes = get_post_class();

$current_post_format = $post_classes[4];

$post_formats_media = array('format-audio', 'format-video', 'format-gallery');
$post_formats_hidereadmore = array('format-quote', 'format-link', 'format-status');

$post_socialshare_disable = get_post_meta( get_the_ID(), '_post_socialshare_disable_value', true );

// Blog layout
# Default
if(isset($bjorn_theme_options['blog_layout'])) {
	$blog_layout = $bjorn_theme_options['blog_layout'];
} else {
	$blog_layout = 'layout_default';
}

# Text Layout
if($blog_layout == 'layout_text') {
	$blog_enable_layout_text = true;
} else {
	$blog_enable_layout_text = false;
}

# Advanced list
if($blog_layout == 'layout_list_advanced') {
	$blog_enable_advanced_list_post_design = true;
	$blog_layout = 'layout_list';
} else {
	$blog_enable_advanced_list_post_design = false;
}

# Advanced 2 columns
if($blog_layout == 'layout_2column_design_advanced') {
	$blog_enable_advanced_2column_design = true;
	$blog_layout = 'layout_2column_design';
} else {
	$blog_enable_advanced_2column_design = false;
}

# 2 columns
if($blog_layout == 'layout_2column_design') {
	$blog_enable_2column_design = true;
} else {
	$blog_enable_2column_design = false;
}

# Masonry
if($blog_layout == 'layout_masonry') {
	$blog_enable_masonry_design = true;
} else {
	$blog_enable_masonry_design = false;
}

/* Posts loops position for advanced layouts */
if(!isset($post_loop_id)) {
	$post_loop_id = 1;
}

if(((($post_loop_id-1) + 3) % 3 == 0)&&$blog_enable_advanced_2column_design) {
	$current_post_2column_advanced = true;
} else {
	$current_post_2column_advanced = false;
}

$current_post_advanced_list = false;

if(($post_loop_id % 3 == 0)&&$blog_enable_advanced_list_post_design) {
	$current_post_advanced_list = true;
} else {
	$current_post_advanced_list = false;
}

if($blog_layout == 'layout_list') {
	$current_post_list = true;
} else {
	$current_post_list = false;
}

if(is_sticky(get_the_ID())) {
	$current_post_sticky = true;
	$sticky_post_class = 'sticky';
} else {
	$current_post_sticky = false;
	$sticky_post_class = '';
}

$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'bjorn-blog-thumb');

if(has_post_thumbnail( get_the_ID() )) {
    $image_bg ='background-image: url('.$image[0].');';
}
else {
    $image_bg = '';
}

// Post Format options
// $current_post_format:
// format-gallery
// format-video
// format-audio

$post_embed_video = get_post_meta( get_the_ID(), '_bjorn_video_embed', true );

if($post_embed_video !== '') {
	$post_embed_video_output = wp_oembed_get($post_embed_video);

} else {
	$post_embed_video_output = '';
}

$post_embed_audio = get_post_meta( get_the_ID(), '_bjorn_audio_embed', true );

if($post_embed_audio !== '') {
	$post_embed_audio_output = wp_oembed_get($post_embed_audio);

} else {
	$post_embed_audio_output = '';
}

// Gallery post type
$post_embed_gallery_output = '';

if($current_post_format == 'format-gallery') {

	// Default layout
	$blog_thumb_size = 'bjorn-blog-thumb';

	// 2 column layout
	if($blog_enable_2column_design && $span_class == 'col-md-12') {
		$blog_thumb_size = 'bjorn-blog-thumb-2column';
	}

	if($blog_enable_2column_design && $span_class == 'col-md-9') {
		$blog_thumb_size = 'bjorn-blog-thumb-2column-sidebar';
	}

	// Advanced 2 column layout
	if($blog_enable_2column_design && $current_post_2column_advanced && $span_class == 'col-md-12') {
		$blog_thumb_size = 'bjorn-blog-thumb';
	}

	if($blog_enable_2column_design && $current_post_2column_advanced && $span_class == 'col-md-9') {
		$blog_thumb_size = 'bjorn-blog-thumb-sidebar';
	}
	
	$gallery_images_data = bjorn_cmb2_get_images_src( get_the_ID(), '_bjorn_gallery_file_list', $blog_thumb_size );

	if(sizeof($gallery_images_data) > 0) { 

		$post_gallery_id = 'blog-post-gallery-'.get_the_ID();
		$post_embed_gallery_output = '<div class="blog-post-gallery-wrapper hover-effect-img owl-carousel" id="'.$post_gallery_id.'" style="display: none;">';

		foreach ($gallery_images_data as $gallery_image) {
			$post_embed_gallery_output .= '<div class="blog-post-gallery-image"><a href="'.esc_url(get_the_permalink()).'"><img src="'.$gallery_image.'" alt="'.get_the_title().'"/></a></div>';
		}

		$post_embed_gallery_output .= '</div>';

	    wp_add_inline_script( 'bjorn-script', '(function($){
	            $(document).ready(function() {

	                $("#'.$post_gallery_id.'").owlCarousel({
	                    items: 1,
	                    autoplay: true,
	                    autowidth: false,
	                    autoplayTimeout:2000,
	                    autoplaySpeed: 1000,
	                    navSpeed: 1000,
	                    nav: true,
	                    dots: false,
	                    loop: true,
	                    navText: false,
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
		
	}
}

// Post Loops Middle Banner
if(($post_loop_id - 1 == floor(get_option('posts_per_page')/2))&&(!$blog_enable_2column_design)&&(!$blog_enable_masonry_design)) {
	bjorn_banner_show('posts_loop_middle');
}

?>
<?php if(!has_post_thumbnail( get_the_ID() )) { $sticky_post_class .= ' sticky-post-without-image'; } else { $sticky_post_class .= ''; } ?>
<div class="content-block blog-post clearfix<?php if($blog_enable_advanced_2column_design) { echo ' blog-post-advanced-2column';} if($current_post_advanced_list) { echo ' blog-post-advanced-list';} if($current_post_2column_advanced) { echo ' current-blog-post-advanced-2column';} if($blog_enable_layout_text) { echo ' blog-post-text-layout';} if($current_post_list) { echo ' blog-post-list-layout';} if($blog_enable_2column_design) { echo ' blog-post-2-column-layout';}?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class($sticky_post_class); ?>>

		<div class="post-content-wrapper"<?php if(($current_post_sticky)&&($blog_enable_masonry_design)&&($image_bg !== '')&&(!in_array($current_post_format, $post_formats_media))) { echo ' data-style="'.esc_attr($image_bg).'"'; } ?>>
			<?php if(($current_post_sticky)&&($blog_enable_masonry_design)): ?>
				<div class="sticky-post-badge<?php if($image_bg == '') { echo ' sticky-post-without-image'; } ?>"><i class="fa fa-star" aria-hidden="true"></i>
</div>
			<?php endif; ?>

			
			<?php 
			// List post thumb
			if(($current_post_list)&&!$blog_enable_masonry_design&&!$blog_enable_layout_text):
			?>
				<?php
	          	if(($image_bg !== '')&&(!in_array($current_post_format, $post_formats_media))):
				?>
				<a class="blog-post-thumb hover-effect-img" href="<?php the_permalink(); ?>" rel="bookmark" data-style="<?php echo esc_attr($image_bg); ?>"></a>
				<?php endif;?>
				<?php 
					if(in_array($current_post_format, $post_formats_media)) {

						if( ($post_embed_video_output !== '')||($post_embed_audio_output !== '')||($post_embed_gallery_output !== '') ) {

							echo '<div class="blog-post-thumb">';

							// Post media
							if($current_post_format == 'format-video') {
								echo '<div class="blog-post-media blog-post-media-video">';
								echo ($post_embed_video_output);// escaping does not needed here, WordPress OEMBED function used for this var
								echo '</div>';
							}
							elseif($current_post_format == 'format-audio') {
								echo '<div class="blog-post-media blog-post-media-audio">';
								echo ($post_embed_audio_output);// escaping does not needed here, WordPress OEMBED function used for this var
								echo '</div>';
							}
							elseif($current_post_format == 'format-gallery') {
								echo '<div class="blog-post-media blog-post-media-gallery clearfix">';
								echo wp_kses_post($post_embed_gallery_output);
								echo '</div>';

								
							}

							echo '</div>';

						}
					}
				
				?>
			<?php endif;?>
			
			<?php 
			// Masonry thumbnail
			if($blog_enable_masonry_design) {

				if ( has_post_thumbnail() && (!$current_post_sticky)&&!in_array($current_post_format, $post_formats_media) ):
				?>

				<div class="blog-post-thumb">
					<a href="<?php the_permalink(); ?>" rel="bookmark" class="hover-effect-img">
					<?php the_post_thumbnail('bjorn-blog-thumb-2column-sidebar'); ?>
					</a>
				</div>
				<?php
				endif;

				// Masonry media posts
				if(in_array($current_post_format, $post_formats_media)) {

					if( ($post_embed_video_output !== '')||($post_embed_audio_output !== '')||($post_embed_gallery_output !== '') ) {

						echo '<div class="blog-post-thumb">';

						// Post media
						if($current_post_format == 'format-video') {
							echo '<div class="blog-post-media blog-post-media-video">';
							echo ($post_embed_video_output);// escaping does not needed here, WordPress OEMBED function used for this var
							echo '</div>';
						}
						elseif($current_post_format == 'format-audio') {
							echo '<div class="blog-post-media blog-post-media-audio">';
							echo ($post_embed_audio_output);// escaping does not needed here, WordPress OEMBED function used for this var
							echo '</div>';
						}
						elseif($current_post_format == 'format-gallery') {
							echo '<div class="blog-post-media blog-post-media-gallery clearfix">';
							echo wp_kses_post($post_embed_gallery_output);
							echo '</div>';

							
						}
						
						echo '</div>';

					}
				}
			}
			?>
			<?php 
			// Regular post thumb
			if((!$current_post_list)&&(!$blog_enable_masonry_design)&&(!$blog_enable_layout_text)) {
				
				// Post media
				if(($current_post_format == 'format-video')&&($post_embed_video_output !== '')) {
					echo '<div class="blog-post-thumb">';
					echo '<div class="blog-post-media blog-post-media-video">';
					echo ($post_embed_video_output);// escaping does not needed here, WordPress OEMBED function used for this var
					echo '</div>';
					echo '</div>';
				}
				elseif(($current_post_format == 'format-audio')&&($post_embed_audio_output !== '')) {
					echo '<div class="blog-post-thumb">';
					echo '<div class="blog-post-media blog-post-media-audio">';
					echo ($post_embed_audio_output);// escaping does not needed here, WordPress OEMBED function used for this var
					echo '</div>';
					echo '</div>';
				}
				elseif(($current_post_format == 'format-gallery')&&($post_embed_gallery_output !== '')) {
					echo '<div class="blog-post-thumb">';
					echo '<div class="blog-post-media blog-post-media-gallery clearfix">';
					echo wp_kses_post($post_embed_gallery_output);
					echo '</div>';
					echo '</div>';

					
				} else {
					// Post thumbnail sizes
					if ( has_post_thumbnail() ):

						// Default layout
						$blog_thumb_size = 'bjorn-blog-thumb';

						// 2 column layout
						if($blog_enable_2column_design && $span_class == 'col-md-12') {
							$blog_thumb_size = 'bjorn-blog-thumb-2column';
						}

						if($blog_enable_2column_design && $span_class == 'col-md-9') {
							$blog_thumb_size = 'bjorn-blog-thumb-2column-sidebar';
						}

						// Advanced 2 column layout
						if($blog_enable_2column_design && $current_post_2column_advanced && $span_class == 'col-md-12') {
							$blog_thumb_size = 'bjorn-blog-thumb';
						}

						if($blog_enable_2column_design && $current_post_2column_advanced && $span_class == 'col-md-9') {
							$blog_thumb_size = 'bjorn-blog-thumb-sidebar';
						}
						
					?>
						<div class="blog-post-thumb">
						<a href="<?php the_permalink(); ?>" rel="bookmark" class="hover-effect-img">
						<?php the_post_thumbnail($blog_thumb_size); ?>
						</a>
						</div>
					
					<?php
					endif;
				}

				
			}
         
			?>
			<div class="post-content">

				<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list(  ', '  );
				if ( $categories_list ) :
				?>
				
				<div class="post-categories"><?php printf( esc_html__( '%1$s', 'bjorn' ), $categories_list ); ?></div>
				
				<?php endif; // End if categories ?>
	
				<h2 class="entry-title post-header-title lined"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?><?php if($current_post_sticky&&!$blog_enable_masonry_design) { echo '<sup><i class="fa fa-star" aria-hidden="true"></i>
</sup>'; } ?></a></h2>
				<?php if(isset($bjorn_theme_options['blog_post_show_author'])&&($bjorn_theme_options['blog_post_show_author'])): ?>
				<div class="post-subtitle-container">
					<div class="post-author"><?php esc_html_e('By','bjorn'); ?><div class="post-author-avatar"><?php echo get_avatar( get_the_author_meta('email'), '25' ); ?></div><?php the_author_posts_link();?></div>
				</div>
				<?php endif; ?>

				<?php
				// Don't show short content if user disabled it
				if((isset($bjorn_theme_options['blog_post_loop_type']) && $bjorn_theme_options['blog_post_loop_type']!=='none') || $blog_enable_masonry_design):?>
				<div class="entry-content">
					<?php 
					
					// Post content
					if($blog_enable_masonry_design) {
						if(in_array($current_post_format, $post_formats_hidereadmore)) {
							the_content('');

						} else {
							the_excerpt();
						}
						?>
						<?php
			
					} else {
						// Post loop type excerpt
						if(isset($bjorn_theme_options['blog_post_loop_type']) && $bjorn_theme_options['blog_post_loop_type']=='excerpt') {
							if(in_array($current_post_format, $post_formats_hidereadmore)) {
								the_content(esc_html__('Read more', 'bjorn'));
							} else {
								the_excerpt();
								?>
								<a href="<?php the_permalink(); ?>" class="more-link btn alt"><?php esc_html_e('Read more', 'bjorn'); ?></a>
								<?php
							}
						}

						// Post loop type content
						if(isset($bjorn_theme_options['blog_post_loop_type']) && $bjorn_theme_options['blog_post_loop_type']=='content') {
						
							the_content(esc_html__('Read more', 'bjorn'));

							?>
							<?php
						}

						wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bjorn' ),
							'after'  => '</div>',
						) );
					}
					
					?>
				</div><!-- .entry-content -->
				<?php endif; ?>

			</div>
			<div class="post-info clearfix">
				<div class="post-info-date"><?php the_time(get_option( 'date_format' ));  ?></div>
				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
				<div class="post-info-comments"><?php comments_popup_link( '<i class="fa fa-comment-o" aria-hidden="true"></i>
', '<i class="fa fa-comment" aria-hidden="true"></i>1', '<i class="fa fa-comment" aria-hidden="true"></i>%'); ?></div>
				<?php endif; ?>

				<?php if(isset($bjorn_theme_options['blog_enable_post_views_counter'])&&($bjorn_theme_options['blog_enable_post_views_counter'])): ?>
				<div class="post-info-views"><i class="fa fa-eye" aria-hidden="true"></i><?php do_action('bjorn_post_views'); // this action called from plugin ?></div>
				<?php endif; ?>


				<?php if(isset($bjorn_theme_options['blog_list_show_share'])&&($bjorn_theme_options['blog_list_show_share'])): ?>
				<?php if(!isset($post_socialshare_disable) || !$post_socialshare_disable): ?>
				<div class="post-info-share">
					<?php do_action('bjorn_social_share'); // this action called from plugin ?>
				</div>
				<?php endif; ?>
				<?php endif; ?>


			</div>
			<div class="clear"></div>

		</div>

	</article>
	<?php if(isset($bjorn_theme_options['blog_list_show_related'])&&($bjorn_theme_options['blog_list_show_related'])&&!$blog_enable_masonry_design&&!$blog_enable_layout_text&&!$blog_enable_2column_design): ?>
		<?php get_template_part( 'related-posts-loop' ); ?>
	<?php endif; ?>
</div>

