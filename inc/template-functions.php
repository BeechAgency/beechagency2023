<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package beechblocks
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function beechblocks_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'beechblocks_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function beechblocks_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'beechblocks_pingback_header' );


function beechblocks_register_custom_menus() {
    // Define the menu locations and their names
    $menu_locations = array(
        'header-menu' => 'Header Menu',
        'footer-menu' => 'Footer Menu',
		'footer-social' => 'Footer Social', 
    );

    // Register the menu locations
    register_nav_menus($menu_locations);
}

// Hook into the 'init' action to register the menu locations
add_action('init', 'beechblocks_register_custom_menus');


function beech_logo_svg() {
	echo '<svg id="beechLogo" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 649.26 134.76">
	<defs>
	  <style>
		.logo-letter {
		  fill: currentColor;
		  stroke-width: 0px;
		}
	  </style>
	</defs>
	<path class="logo-letter" d="m84.22,131.55H0V3.21h84.22c25.51,0,42.03,14.28,42.03,30.64s-9.46,28.72-23.74,32.41c9.31,2.41,23.74,10.43,23.74,31.44,0,17.49-17.81,33.85-42.03,33.85Zm-12.35-89.52h-21.18v13.15h21.18c3.53,0,6.58-2.89,6.58-6.58s-3.05-6.58-6.58-6.58Zm0,37.7h-21.18v13.15h21.18c3.53,0,6.58-3.05,6.58-6.58s-3.05-6.58-6.58-6.58Z"/>
	<path class="logo-letter" d="m134.6,131.55V3.21h115.99v38.82h-65.29v13.15h31.76v24.55h-31.76v13.15h65.29v38.66h-115.99Z"/>
	<path class="logo-letter" d="m260.7,131.55V3.21h115.99v38.82h-65.29v13.15h31.76v24.55h-31.76v13.15h65.29v38.66h-115.99Z"/>
	<path class="logo-letter" d="m448.88,134.76c-36.74,0-66.42-30.16-66.42-67.38S412.14,0,448.88,0c33.21,0,56.79,24.55,60.32,56.79h-41.71c-1.6-12.35-9.95-17.33-16.68-17.33-8.66,0-17.33,8.02-17.33,27.91s8.66,27.91,17.33,27.91c6.58,0,14.92-4.97,16.52-16.84h41.87c-3.53,32.09-27.27,56.31-60.32,56.31Z"/>
	<path class="logo-letter" d="m598.56,131.55v-45.08h-30v45.08h-50.69V3.21h50.69v46.36h30V3.21h50.7v128.34h-50.7Z"/>
  </svg>';
	//get_template_part( 'assets/beech-logo.svg', null, array() );
}


if ( ! function_exists( 'beech_number_pagination') ) :
	/**
	* Displays A List of Posts
	*/
	function beech_number_pagination() {
		$args = array(
			'base'               => '%_%',
			'format'             => '?paged=%#%',
			'total'              => 1,
			'current'            => 0,
			'show_all'           => false,
			'end_size'           => 1,
			'mid_size'           => 2,
			'prev_next'          => true,
			'prev_text'          => __('«'),
			'next_text'          => __('»'),
			'type'               => 'plain',
			'add_args'           => false,
			'add_fragment'       => '',
			'before_page_number' => '',
			'after_page_number'  => ''
		);
		
		global $wp_query;
		$big = 9999999; // need an unlikely integer
		
		echo '<div class="posts-pagination">';
		echo paginate_links( 
			array(
			   'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			   'format' => '?paged=%#%',
			   'current' => max( 1, get_query_var('paged') ),
			   'total' => $wp_query->max_num_pages,
			   'prev_text' => __('<svg xmlns="http://www.w3.org/2000/svg" width="7.466" height="12.812" viewBox="0 0 7.466 12.812"><path id="Path_568" data-name="Path 568" d="M-7223.768,2647.072l5.875,5.876-5.875,5.875" transform="translate(-7216.832 2659.354) rotate(-180)" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>'),
			   'next_text' => __('<svg xmlns="http://www.w3.org/2000/svg" width="7.466" height="12.812" viewBox="0 0 7.466 12.812"><path id="Path_567" data-name="Path 567" d="M-7223.768,2647.072l5.875,5.876-5.875,5.875" transform="translate(7224.298 -2646.542)" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
			 ')
			)
		);
		echo '</div>';
	}	/*»«*/

endif;



function beech_taxonomy_value_filter_list($taxonomy = 'product', $posts_page_url = '/', $post_type = 'post') {

	//var_dump(is_tax() || is_category());
	$queried_object = get_queried_object();
	$current_page = '';
    
	if ( $queried_object ) {
        $current_page = $queried_object->slug;
    }

	// Get all terms from the custom taxonomy
	$terms = get_terms(array(
		'taxonomy' => $taxonomy,
		'hide_empty' => false, // Include terms with no posts
	));

	if (!empty($terms)) {
		echo "<div class='filter-list-inner tax-$taxonomy'>";
		echo "<div class='title sr-only'>Filter:</div><ul class='filter-list tax-$taxonomy' data-taxonomy='".$taxonomy."' data-post-type='".$post_type."'>";
		echo "<li class='item item-all'><a href='$posts_page_url' data-term=''>All</a></li>";
		foreach ($terms as $term) {
			// Count the number of posts for each term
			$term_post_count = $term->count;
			$count = $term_post_count;

			$term_link = get_term_link($term, $taxonomy);

			$active_class = '';
			if($current_page === $term->slug) {
				$active_class = 'active';
			}

			if($taxonomy === 'product') {
				$landing_page = get_field('landing_page',$taxonomy."_".$term->term_id);
				$landing_page_id = is_countable($landing_page) ? $landing_page[0] : null;

				if(!empty($landing_page_id)):
					$term_link = get_the_permalink( $landing_page_id );
				endif;
			}
			if($count > 0):
				echo '<li class="item '.$active_class.' '.$term->slug.'"><a href="' . esc_url($term_link) . '"data-taxonomy="' . esc_attr($taxonomy) . '" data-term-id="' . esc_attr($term->term_id) . '" >' . esc_html($term->name);
				echo '<span class="count">' . $count . '</span></a></li>';
			endif;
		}
		echo '</ul></div>';
	}
}


// Disable WordPress' automatic image scaling feature
function beech_image_filter_threshold() {
	return false;
}

add_filter( 'big_image_size_threshold', 'beech_image_filter_threshold' );


/*
* Load category posts
*/
add_action('wp_ajax_load_category_posts', 'load_category_posts');
add_action('wp_ajax_nopriv_load_category_posts', 'load_category_posts');

function load_category_posts() {
    check_ajax_referer('load_posts_nonce', 'nonce');

	$taxonomy  = sanitize_text_field($_POST['taxonomy'] ?? '');
    $term_id = absint($_POST['term_id'] ?? 0);

    $query = new WP_Query([
        'tax_query' => [[
            'taxonomy' => $taxonomy,
            'field'    => 'term_id',
            'terms'    => $term_id,
        ]],
        'posts_per_page' => get_option('posts_per_page'),
        'post_status'    => 'publish',
    ]);

    if ($query->have_posts()) {
        ob_start();
		$i = 0;
        while ($query->have_posts()) {
            $query->the_post();

			$is_hidden = get_field('is_hidden');
			
			if($is_hidden) continue;

            echo get_template_part( 'template-parts/card', 'post', array('index' => $i) );
			$i++;
        }
        wp_reset_postdata();
        wp_send_json_success(ob_get_clean());
    } else {
        wp_send_json_error('No posts found.');
    }
}