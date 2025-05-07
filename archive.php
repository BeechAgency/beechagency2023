<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package beechblocks
 */

get_header();
?>

	<main id="primary" class="site-main <?= get_post_type() ==='project' ? 'is-style-projects':''; ?> ">

		<?php if ( have_posts() ) : ?>

			<header class="align-content-center is-style-title wp-block-beech-header">
				<div class="header__inner">
					<div class="header__inner-blocks">
						<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="archive-description">', '</div>' );
						?>
					</div>
				</div>
			</header><!-- .page-header -->

			<?php
			$tax = 'category';
			$path = "/latest";
			if(get_post_type() === 'project') {
				$tax = 'product';
				$path = '/work';
			}


			echo '<div class="filter-list-wrapper">';
			beech_taxonomy_value_filter_list($tax, $path);
			echo '</div>';
			echo '<div class="archive-page-articles '.get_post_type().'" data-xy="grid">';
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				//echo '<li data-xy="col 2xl:3 xl:3 lg:4 md:4 sm:6 xs:12">';
				get_template_part( 'template-parts/card', 'post' );
				//echo '</li>';

			endwhile;
			echo '</div>';

			beech_number_pagination();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
//get_sidebar();
if(get_post_type() === 'project') {
	dynamic_sidebar('products-footer');
} else {
	dynamic_sidebar('categories-footer');
}
get_footer();
