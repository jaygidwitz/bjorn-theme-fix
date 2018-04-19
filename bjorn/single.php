<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Bjorn
 */

get_header();

// Count view
if (function_exists('bjorn_setPostViews')) {
	bjorn_setPostViews(get_the_ID());
}

?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php get_template_part( 'content', 'single' ); ?>


<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>