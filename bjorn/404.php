<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Bjorn
 */

get_header(); ?>
<div class="content-block">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="page-404">
					<h1><?php esc_html_e("404", 'bjorn'); ?></h1>
					<?php 
					// Site 404 Banner
					bjorn_banner_show('404'); 
					?>
					<p><?php esc_html_e( 'La página que buscas no se encuentra.', 'bjorn' ); ?></p>
					<p><?php esc_html_e( 'Quizá escribiste mal la dirección o el vínculo está desactualizado. Intenta buscando en nuestro sitio.', 'bjorn' ); ?></p>
					<div class="search-form">
						<?php get_search_form(true); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>