<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package beechblocks
 */

get_header();

$author = get_queried_object();
$author_id = $author->ID;
$display_name = $author->display_name;
$title = get_user_meta($author->ID, 'title', true);
$content = get_user_meta($author->ID, 'author_content', true);
$image_id = get_user_meta($author->ID, 'display_image', true);

$socials = [];

$socials['linkedin'] = get_user_meta($author->ID, 'linkedin', true);
$socials['facebook'] = get_user_meta($author->ID, 'facebook', true);
$socials['instagram'] = get_user_meta($author->ID, 'instagram', true);
$socials['bluesky'] = get_user_meta($author->ID, 'bluesky_account', true);
$socials['twitter'] = get_user_meta($author->ID, 'twitter', true);
$socials['website'] = $author->user_url;

?>

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="align-content-center is-style-title wp-block-beech-header">
				<div class="header__inner">
					<div class="header__inner-blocks" data-scroll data-scroll-speed="0.025">
                        <div class="author-profile">
                            <div class="author-profile__inner">
                                <div class="author-profile__image">
                                    <?php echo the_image($image_id, array('classes' => 'author-profile__image-image')); ?>
                                </div>
                                <div class="author-profile__text">
                                    <?php 
                                        echo '<h1 class="author-profile__name">'.$display_name.'</h1>'; 
                                        echo '<h2 class="author-profile__title">'.$title.'</h2>';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="prose author-prose">
                            <?php echo apply_filters( 'the_content', $content ); ?>
                        </div>
                        <div class="author-social-accounts">
                            <?php 
                            foreach($socials as $key => $value) {
                                if(!empty($value)) {
                                    echo '<a href="'.$value.'" target="_blank">'.the_icon($key).'</a>';
                                }
                            }
                            ?>
                        </div>
					</div>
				</div>
			</header><!-- .page-header -->

			<?php
			$tax = 'category';
			$path = "/latest";

			echo '<div class="content-list">';
                echo '<div class="filter-list-wrapper">';
                    beech_taxonomy_value_filter_list($tax, $path);
                echo '</div>';
			echo '<div class="archive-page-articles '.get_post_type().' list-output" data-xy="grid">';
			/* Start the Loop */
			$i = 0;
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				//echo '<li data-xy="col 2xl:3 xl:3 lg:4 md:4 sm:6 xs:12">';
				get_template_part( 'template-parts/card', 'post', array('index' => $i, 'post_type' => get_post_type()) );
				//echo '</li>';
				$i++;
			endwhile;
			echo '</div>';

			beech_number_pagination();
			echo '</div>';
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
