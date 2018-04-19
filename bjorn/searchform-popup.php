<?php
/**
 * The template for displaying search forms in Bjorn search popup
 *
 * @package Bjorn
 */
?>
	<form method="get" id="searchform_p" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input type="search" class="field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" id="s_p" placeholder="<?php echo esc_html__('Buscar &hellip;', 'bjorn' ); ?>" />
		<input type="submit" class="submit btn" id="searchsubmit_p" value="<?php echo esc_html__( 'Buscar', 'bjorn' ); ?>" />
	</form>
