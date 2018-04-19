<?php
/*
*	Related posts
*/
?>
<?php

$bjorn_theme_options = bjorn_get_theme_options();

//for use in the loop, list post titles related to first tag on current post
$tags = wp_get_post_tags(get_the_ID ());
$cats = wp_get_post_categories(get_the_ID ());

$original_post = $post;

if(!isset($bjorn_theme_options['blog_list_related_by'])) {
	$bjorn_theme_options['blog_list_related_by'] = 'tags';
}

$args = '';

// If by tags
if($bjorn_theme_options['blog_list_related_by'] == 'tags' && $tags) {

	$intags = array();

	foreach ($tags as $tag) {
		$intags[] = $tag->term_id;
	}
	
	$args = array(
		'tag__in' => $intags,
		'post__not_in' => array(get_the_ID ()),
		'posts_per_page'=> 3
	);
}

// If by categories
if($bjorn_theme_options['blog_list_related_by'] == 'cats' && $cats) {
	
	$args = array(
		'category__in' => $cats,
		'post__not_in' => array(get_the_ID ()),
		'posts_per_page'=> 3
	);
}

$my_query = new WP_Query($args);

if( $my_query->have_posts() ) {

	echo '<div class="blog-post-related blog-post-related-loop clearfix">';
	echo '<h5>'.esc_html__('You may also like','bjorn').'</h5>';

	while ($my_query->have_posts()) : $my_query->the_post(); 
		$post_image_data = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'bjorn-blog-thumb');
		
		if(has_post_thumbnail( $post->ID )) {
		    $post_image = 'background-image: url('.$post_image_data[0].');';
		    $post_class = '';
		}
		else {
		    $post_image = '';
		    $post_class = ' blog-post-related-no-image';
		}

	?>
	<div class="blog-post-related-item<?php echo esc_attr($post_class); ?>">
	
	<a href="<?php the_permalink() ?>">
	<div class="blog-post-related-image hover-effect-img" data-style="<?php echo esc_attr($post_image);?>"></div>
	</a>

	<div class="blog-post-related-item-inside">
	<?php
	/* translators: used between list items, there is a space after the comma */
	$categories_list = get_the_category_list(  ', '  );
	if ( $categories_list ) :
	?>
	
	<div class="blog-post-related-category"><?php echo wp_kses_post($categories_list); ?></div>
		
	<?php endif; // End if categories ?>
	<div class="blog-post-related-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></div>
	<div class="blog-post-related-date"><?php echo get_the_time( get_option( 'date_format' ), get_the_ID() );?></div>
	
	</div>
	
	</div>
	<?php
	endwhile;

	echo '<div class="blog-post-related-separator clearfix"></div>';
	echo '</div>';

}

$post = $original_post;

?>
