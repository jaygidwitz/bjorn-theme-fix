<?php
/**
 * @package Bjorn
 */

$bjorn_theme_options = bjorn_get_theme_options();

$post_classes = get_post_class();

$current_post_format = $post_classes[4];

$post_formats_media = array('format-audio', 'format-video', 'format-gallery');

if(!isset($bjorn_theme_options['blog_post_hide_featured_image'])) {
	$bjorn_theme_options['blog_post_hide_featured_image'] = false;
} 

$post_sidebarposition = get_post_meta( get_the_ID(), '_post_sidebarposition_value', true );
$post_socialshare_disable = get_post_meta( get_the_ID(), '_post_socialshare_disable_value', true );
$post_disable_featured_image = get_post_meta( get_the_ID(), '_post_disable_featured_image_value', true );

// Demo settings
if ( defined('DEMO_MODE') && isset($_GET['post_sidebar_position']) ) {
  $bjorn_theme_options['post_sidebar_position'] = $_GET['post_sidebar_position'];
}

if(!isset($bjorn_theme_options['post_sidebar_position'])) {
	$bjorn_theme_options['post_sidebar_position'] = 'disable';
}

if(!isset($post_sidebarposition)||($post_sidebarposition == '')) {
	$post_sidebarposition = 0;
}

if($post_sidebarposition == "0") {
	$post_sidebarposition = $bjorn_theme_options['post_sidebar_position'];
}

if(is_active_sidebar( 'post-sidebar' ) && ($post_sidebarposition <> 'disable') ) {
	$span_class = 'col-md-9';
}
else {
	$span_class = 'col-md-12 post-single-content';
}

// Post media
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

	$gallery_images_data = bjorn_cmb2_get_images_src( get_the_ID(), '_bjorn_gallery_file_list', 'bjorn-blog-thumb' );
	
	if(sizeof($gallery_images_data) > 0) { 

		$post_gallery_id = 'blog-post-gallery-'.get_the_ID();
		$post_embed_gallery_output = '<div class="blog-post-gallery-wrapper owl-carousel" id="'.$post_gallery_id.'" style="display: none;">';

		foreach ($gallery_images_data as $gallery_image) {
			$post_embed_gallery_output .= '<div class="blog-post-gallery-image"><a href="'.esc_url($gallery_image).'" rel="lightbox" title="'.get_the_title().'"><img src="'.esc_url($gallery_image).'" alt="'.get_the_title().'"/></a></div>';
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

$header_background_image = get_post_meta( get_the_ID(), '_bjorn_header_image', true );

if(isset($header_background_image) && ($header_background_image!== '')) {
	$header_background_image_style = 'background-image: url('.$header_background_image.');';
	$header_background_class = ' with-bg';

} else {
	$header_background_image_style = '';
	$header_background_class = '';
}

?>

<div class="content-block">
<div class="container-fluid container-page-item-title<?php echo esc_attr($header_background_class); ?>" data-style="<?php echo esc_attr($header_background_image_style); ?>">
	<div class="row">
	<div class="col-md-12">
	<div class="page-item-title-single">
		<?php
		$categories_list = get_the_category_list(  ', '  );
		if ( $categories_list ) :
		?>
	    <div class="post-categories"><?php printf( '%1$s', $categories_list ); ?></div>
	    <?php endif; // End if categories ?>

	    <h1><?php the_title(); ?></h1>
	    <div class="post-date"><?php the_time(get_option( 'date_format' ));  ?></div>
	    
	    <?php if(isset($bjorn_theme_options['blog_enable_singlepost_header_info'])&&($bjorn_theme_options['blog_enable_singlepost_header_info'])): ?>
	    <div class="post-info clearfix">
			<?php if(!isset($post_socialshare_disable) || !$post_socialshare_disable): ?>
			<div class="post-info-share">
				<?php do_action('bjorn_social_share'); // this action called from plugin ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
	</div>
	</div>
</div>
<div class="post-container container <?php echo esc_attr('span-'.$span_class); ?>">
	<div class="row">
<?php if ( is_active_sidebar( 'post-sidebar' ) && ( $post_sidebarposition == 'left')) : ?>
		<div class="col-md-3 post-sidebar sidebar">
		<ul id="post-sidebar">
		  <?php dynamic_sidebar( 'post-sidebar' ); ?>
		</ul>
		</div>
		<?php endif; ?>
		<div class="<?php echo esc_attr($span_class); ?>">
			<div class="blog-post blog-post-single clearfix">
				<?php if(isset($bjorn_theme_options['blog_enable_author_info_vertical'])&&($bjorn_theme_options['blog_enable_author_info_vertical'])): ?>
				<div class="post-info-vertical">
					<div class="post-info-vertical-author">
						<div class="post-info-vertical-author-avatar">
						<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ))); ?>"><?php echo get_avatar( get_the_author_meta('email'), '60' ); ?></a>
						</div>
					<?php the_author_posts_link();?>
					</div>
					<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
					<div class="post-info-vertical-comments">
					<?php comments_popup_link( __( '0 Comments', 'bjorn' ), __( '1 Comment', 'bjorn' ), __( '% Comments', 'bjorn' ) ); ?>
					</div>
					<?php endif; ?>
					<?php if(isset($bjorn_theme_options['blog_enable_post_views_counter'])&&($bjorn_theme_options['blog_enable_post_views_counter'])): ?>
					<div class="post-info-vertical-views"><?php do_action('bjorn_post_views'); // this action called from plugin ?> <?php esc_html_e('Views', 'bjorn'); ?></div>
					<?php endif; ?>
					
					<?php if(isset($bjorn_theme_options['blog_post_show_share'])&&($bjorn_theme_options['blog_post_show_share'])): ?>
					<?php if(!isset($post_socialshare_disable) || !$post_socialshare_disable): ?>
					<div class="post-info-vertical-share">
						<div class="post-info-share">
							<?php do_action('bjorn_social_share'); // this action called from plugin ?>
						</div>
					</div>
					<?php endif; ?>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="post-content-wrapper">
			
						<div class="post-content">
							<?php 
							if ( has_post_thumbnail()&&!in_array($current_post_format, $post_formats_media)&&(!$post_disable_featured_image ) && (!$bjorn_theme_options['blog_post_hide_featured_image']) ): // check if the post has a Post Thumbnail assigned to it.
							?>
							<div class="blog-post-thumb">
							
								<?php the_post_thumbnail('bjorn-blog-thumb'); ?>
							
							</div>
							<?php endif; ?>
							<?php

							if(in_array($current_post_format, $post_formats_media)) {
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
								echo '<div class="blog-post-media blog-post-media-gallery">';
								echo wp_kses_post($post_embed_gallery_output);
								echo '</div>';
							}
								echo '</div>';
							}
							?>		
							<?php 
							// Single Blog Post page Top banner
							bjorn_banner_show('single_post_top');
							?>							
							<?php if ( is_search() ) : // Only display Excerpts for Search ?>
							<div class="entry-summary">
								<?php the_excerpt(); ?>
							</div><!-- .entry-summary -->
							<?php else : ?>
							<div class="entry-content">
								<?php the_content('<div class="more-link">'.esc_html__( 'Continue reading...', 'bjorn' ).'</div>' ); ?>
								<?php
									wp_link_pages( array(
										'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bjorn' ),
										'after'  => '</div>',
									) );
								?>
							</div><!-- .entry-content -->
							
							<?php 
							// Single Blog Post page Bottom banner
							bjorn_banner_show('single_post_bottom');
							?>

							<?php
								/* translators: used between list items, there is a space after the comma */
								$tags_list = get_the_tag_list( '', ' ' );
								if ( $tags_list ) :
							?>
							<div class="tags clearfix">
								<?php echo wp_kses_post($tags_list); ?>
							</div>
							<?php endif; // End if $tags_list ?>
							
							<?php endif; ?>
							</div>
			
					</div>
				</article>

				<?php if(isset($bjorn_theme_options['blog_enable_post_info_bottom'])&&($bjorn_theme_options['blog_enable_post_info_bottom'])): ?>
				<div class="post-info clearfix">
					<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
					<div class="post-info-comments"><?php comments_popup_link( __( '<i class="fa fa-comment-o" aria-hidden="true"></i>
Leave a comment', 'bjorn' ), '<i class="fa fa-comment" aria-hidden="true"></i>1', '<i class="fa fa-comment" aria-hidden="true"></i>%' ); ?></div>
					<?php endif; ?>

					<?php if(isset($bjorn_theme_options['blog_enable_post_views_counter'])&&($bjorn_theme_options['blog_enable_post_views_counter'])): ?>
					<div class="post-info-views"><i class="fa fa-eye" aria-hidden="true"></i><?php do_action('bjorn_post_views'); // this action called from plugin ?></div>
					<?php endif; ?>
					<?php if(isset($bjorn_theme_options['blog_post_show_share'])&&($bjorn_theme_options['blog_post_show_share'])): ?>
					<?php if(!isset($post_socialshare_disable) || !$post_socialshare_disable): ?>
					<div class="post-info-share">
						<?php do_action('bjorn_social_share'); // this action called from plugin ?>
					</div>
					<?php endif; ?>
					<?php endif; ?>

				</div>
				<?php endif; ?>
			</div>
			
			<div class="blog-post-single-separator"></div>
			
			<?php if(isset($bjorn_theme_options['blog_enable_author_info'])&&($bjorn_theme_options['blog_enable_author_info'])): ?>
				<?php if ( is_single() && get_the_author_meta( 'description' ) ) : ?>
					<?php get_template_part( 'author-bio' ); ?>
				<?php endif; ?>
			<?php endif; ?>
			
			<?php 
			if(isset($bjorn_theme_options['blog_post_navigation']) && $bjorn_theme_options['blog_post_navigation']) {
				bjorn_content_nav( 'nav-below' ); 
			}
			?>

			<?php if(isset($bjorn_theme_options['blog_post_show_related'])&&($bjorn_theme_options['blog_post_show_related'])): ?>
			<?php get_template_part( 'related-posts-loop' ); ?>
			<?php endif; ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() ) 
					
					comments_template();
			?>
			
		</div>
		<?php if ( is_active_sidebar( 'post-sidebar' ) && ( $post_sidebarposition == 'right')) : ?>
		<div class="col-md-3 post-sidebar sidebar">
		<ul id="post-sidebar">
		  <?php dynamic_sidebar( 'post-sidebar' ); ?>
		</ul>
		</div>
		<?php endif; ?>
	</div>
	</div>
</div>
