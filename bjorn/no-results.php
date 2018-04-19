<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Bjorn
 */
?>

		<article id="post-0" class="post no-results not-found">
			<div class="entry-content">
				<div class="page-search-no-results">
				
					<h3><?php esc_html_e( 'Nada se encontró', 'bjorn' ); ?></h3>
					<?php 
					// Site 404 Banner
					bjorn_banner_show('404'); 
					?>
					<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

						<p><?php printf( wp_kses_post(__( '¿Listo para publicar por primer vez? <a href="%1$s">Inicia aquí</a>.', 'bjorn' )), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

					<?php elseif ( is_search() ) : ?>

						<p><?php esc_html_e( 'Perdón, pero nada encaja con tus términos de búsqueda. Por favor intenta de nuevo con diferentes términos.', 'bjorn' ); ?></p>
						<div class="search-form">
						<?php get_search_form(true); ?>
					</div>

					<?php else : ?>

						<p><?php esc_html_e( 'Parece que no podemos encontrar lo que buscas. Quizá buscando puedas encontrar.', 'bjorn' ); ?></p>
						<div class="search-form">
						<form method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
							<input type="search" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s" placeholder="<?php esc_html_e( 'Search &hellip;', 'bjorn' ); ?>" /><input type="submit" class="submit btn" id="searchsubmit" value="<?php esc_html_e( 'Search', 'bjorn' ); ?>" />
						</form>
					</div>

					<?php endif; ?>
					
					
				</div>
			</div>
		</article>
