<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Bjorn
 */

$bjorn_theme_options = bjorn_get_theme_options();
?>

<?php

$page_sidebarposition = get_post_meta( $post->ID, '_page_sidebarposition_value', true );
$page_notdisplaytitle = get_post_meta( $post->ID, '_page_notdisplaytitle_value', true );

if(!isset($page_sidebarposition)||($page_sidebarposition == '')) {
  $page_sidebarposition = 0;
}

if($page_sidebarposition == "0") {
  $page_sidebarposition = $bjorn_theme_options['page_sidebar_position'];
}

$page_class = get_post_meta( $post->ID, '_page_class_value', true );

if(is_active_sidebar( 'page-sidebar' ) && ($page_sidebarposition <> 'disable')) {
  $span_class = 'col-md-9';
}
else {
  $span_class = 'col-md-12';
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
<div class="content-block <?php echo esc_attr($page_class); ?>">
  <?php if(!$page_notdisplaytitle): ?>
  <div class="container-fluid container-page-item-title<?php echo esc_attr($header_background_class); ?>" data-style="<?php echo esc_attr($header_background_image_style); ?>">
  <div class="row">
  <div class="col-md-12">
  <div class="page-item-title-single page-item-title-page">
      <h1><?php the_title(); ?></h1>
  </div>
  </div>
  </div>
  </div>
  <?php endif; ?>
  <div class="page-container container <?php echo esc_attr('span-'.$span_class); ?>">
    <div class="row">
      <?php if ( is_active_sidebar( 'page-sidebar' ) && ( $page_sidebarposition == 'left')) : ?>
      <div class="col-md-3 page-sidebar sidebar">
        <ul id="page-sidebar">
          <?php dynamic_sidebar( 'page-sidebar' ); ?>
        </ul>
      </div>
      <?php endif; ?>
			<div class="<?php echo esc_attr($span_class);?>">
      <div class="entry-content clearfix">
      <article>
				<?php the_content(); ?>
      </article>
      </div>
        <?php 
        // If comments are open or we have at least one comment, load up the comment template
        if ( comments_open() || '0' != get_comments_number() )
            comments_template();
        ?>
        
			</div>
      <?php if ( is_active_sidebar( 'page-sidebar' ) && ( $page_sidebarposition == 'right')) : ?>
      <div class="col-md-3 page-sidebar sidebar">
        <ul id="page-sidebar">
          <?php dynamic_sidebar( 'page-sidebar' ); ?>
        </ul>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>