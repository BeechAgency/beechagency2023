<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package beechblocks
 */

//global $post;

get_header();

$page_for_posts = get_option( 'page_for_posts' );
$page_id = get_the_ID();

if(is_home()) {
    $page_id = $page_for_posts;
}
?>

	<main id="primary" class="site-main">
		<?php
		if( is_home()) :
			$post = get_page($page_id);

			setup_postdata($post);

			the_content($post);
			rewind_posts();
		endif;



		if ( have_posts() ) :
			echo '<div class="filter-list-wrapper">';
			beech_taxonomy_value_filter_list('category', '/latest');
			echo '</div>';
			echo '<div class="archive-page-articles" data-xy="grid">';

			if ( is_home() && ! is_front_page() ) :
				?><!--
				<header>
					<h1 class="page-title screen-reader-text"><?= get_the_title($page_id); ?></h1>
				</header>-->
				<?php
			endif;

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();
				$is_hidden = get_field('is_hidden');
				
				if($is_hidden) continue;
				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				//echo '<li data-xy="col 2xl:3 xl:3 lg:4 md:4 sm:6 xs:12">';
				get_template_part( 'template-parts/card', get_post_type() );
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
get_footer();
