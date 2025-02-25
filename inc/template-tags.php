<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package beechblocks
 */

if ( ! function_exists( 'beechblocks_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function beechblocks_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'beechblocks' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'beechblocks_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function beechblocks_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'beechblocks' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;


if ( ! function_exists( 'beechblocks_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function beechblocks_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;


if ( ! function_exists( 'beech_color_boiz' ) ) :
	/**
	 * 
	 */
	function beech_color_boiz( $class = null ) {
		get_template_part('template-parts/color', 'boiz', array('class' => $class) );
	}
endif;


if ( ! function_exists( 'beech_author_profile' ) ) :

	function beech_author_profile( $attr_post_id = null ) {
		$post_id = $attr_post_id ?? get_the_ID();

		get_template_part('template-parts/author', 'profile', array('post_id' => $post_id) );
	}
endif;



/**
 * Generates a table of contents (TOC) and saves it to a meta field.
 *
 * @param string $content The content to generate the TOC from.
 * @return string The content with the TOC added.
 */
function generate_toc_and_save_to_meta($content) {
	global $post;
	
    if (!is_singular('post') || is_admin() || in_the_loop() === false) {
        return $content;
    }

    // Ensure that headings are properly matched and attributes are preserved
	// Only gets h2 and h3 headings
    $pattern = '/<h([2-3])([^>]*)>(.*?)<\/h\1>/i';
    if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
        $toc = '<div class="toc"><div class="toc-title">Outline:</div><ul>';
        $replacements = [];
        
        foreach ($matches as $key => $match) {
            $level = $match[1];
            $attributes = $match[2];
            $heading_text = strip_tags($match[3]);
            $anchor_id = 'toc-' . sanitize_title($heading_text) . '-' . $key;
            
            // Add to TOC
            $toc .= sprintf('<li class="toc-level-%d"><a href="#%s">%s</a></li>', $level, $anchor_id, $heading_text);
            
            // Replace original heading with anchored heading, preserving attributes
            $replacement = sprintf('<h%d %s id="%s">%s</h%d>', $level, $attributes, $anchor_id, $heading_text, $level);
            $replacements[$match[0]] = $replacement;
        }
        
        $toc .= '</ul></div>';
        
        // Replace the headings in the content with the anchored headings
        $content = str_replace(array_keys($replacements), array_values($replacements), $content);
        
        // Save TOC to meta field
        update_post_meta($post->ID, '_toc_meta_field', $toc);
    }
    
    return $content;
}

add_filter('the_content', 'generate_toc_and_save_to_meta');



if ( ! function_exists( 'beech_social_sharers' ) ) :

	function beech_social_sharers() {
		get_template_part('template-parts/social', 'sharers');
	}

endif;