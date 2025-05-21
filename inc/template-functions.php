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
		echo "<li class='item item-all'><a href='$posts_page_url' data-term-id='' data-taxonomy='".esc_attr($taxonomy)."' data-post-type='".$post_type."'>All</a></li>";
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
	$post_type = sanitize_text_field($_POST['post_type'] ?? '');

	$tax_query = [
            'taxonomy' => $taxonomy,
            'field'    => 'term_id',
            'terms'    => $term_id,
    ];

	$query_args = [
        'posts_per_page' => get_option('posts_per_page'),
        'post_status'    => 'publish',
	];

	if(!empty($term_id)) {
		$query_args['tax_query'] = [$tax_query];
	}

	if($taxonomy === 'product') {
		$query_args['post_type'] = 'project';
	}

	$query = new WP_Query($query_args);

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


function the_icon($icon = '') {
	if(empty($icon)) return false;

	$output = '<span class="icon">';

	switch($icon) {
		case 'linkedin':
			$output .= '<svg id="Layer_2" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20">
						<defs>
							<style>
							.cls-1 {
								fill: currentColor;
							}
							</style>
						</defs>
						<g id="Layer_1-2" data-name="Layer 1" focusable="false">
							<path class="cls-1" d="M18.5,0H1.5C.67,0,0,.67,0,1.5v17c0,.83.67,1.5,1.5,1.5h17c.83,0,1.5-.67,1.5-1.5V1.5c0-.83-.67-1.5-1.5-1.5ZM6,17h-3v-9h3v9ZM4.5,6.25c-.97-.03-1.73-.83-1.7-1.8.03-.97.83-1.73,1.8-1.7.95.03,1.7.8,1.7,1.75-.02.98-.82,1.76-1.8,1.75ZM17,17h-3v-4.74c0-1.42-.6-1.93-1.38-1.93-.96.06-1.68.89-1.62,1.85,0,0,0,0,0,0,0,.05,0,.09,0,.14v4.67h-3v-9h2.9v1.3c.59-.9,1.62-1.44,2.7-1.4,1.55,0,3.36.86,3.36,3.66l.04,5.44Z"/>
						</g>
					</svg>';
			break;

		case 'twitter':
			$output .= '<svg width="1200" height="1227" viewBox="0 0 1200 1227" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M714.163 519.284L1160.89 0H1055.03L667.137 450.887L357.328 0H0L468.492 681.821L0 1226.37H105.866L515.491 750.218L842.672 1226.37H1200L714.137 519.284H714.163ZM569.165 687.828L521.697 619.934L144.011 79.6944H306.615L611.412 515.685L658.88 583.579L1055.08 1150.3H892.476L569.165 687.854V687.828Z" fill="currentColor"/></svg>';
			break;

		case 'facebook':
			$output .= '<svg xmlns="http://www.w3.org/2000/svg" width="28.17" height="28" viewBox="0 0 28.17 28"><path id="facebook" d="M36.17,22.085A14.085,14.085,0,1,0,19.884,36V26.157H16.306V22.085h3.578v-3.1c0-3.53,2.1-5.48,5.32-5.48a21.677,21.677,0,0,1,3.153.275v3.465H26.581a2.036,2.036,0,0,0-2.3,2.2v2.644h3.906l-.625,4.072H24.286V36A14.09,14.09,0,0,0,36.17,22.085Z" transform="translate(-8 -8)" fill="currentColor"></path></svg>';
			break;

		case 'website':
			$output .= '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="298.4 298.39 603.19 603.21"><path fill="currentColor" d="m530.53 861.84c-53.012 53.012-139.36 53.012-192.37 0-53.008-53.008-53.008-139.36 0-192.37l117.56-117.56c53.012-53.012 139.36-53.012 192.37 0 4.3516 4.2305 6.8203 10.027 6.8633 16.094s-2.3516 11.898-6.6406 16.188c-4.2891 4.293-10.121 6.6836-16.188 6.6406-6.0664-0.039062-11.867-2.5117-16.098-6.8594-35.801-35.801-92.445-35.801-128.25 0l-117.56 117.56c-35.801 35.801-35.805 92.445 0 128.25 35.801 35.801 92.445 35.801 128.25 0l117.56-117.56c4.2305-4.3516 10.027-6.8242 16.098-6.8672 6.0664-0.039062 11.898 2.3516 16.191 6.6406 4.2891 4.293 6.6797 10.125 6.6406 16.191-0.042969 6.0703-2.5156 11.867-6.8672 16.098zm213.75-213.75c-53.012 53.008-139.36 53.012-192.37 0-4.3516-4.2344-6.8203-10.031-6.8633-16.098-0.039063-6.0664 2.3516-11.895 6.6406-16.188 4.293-4.2891 10.121-6.6797 16.188-6.6406 6.0664 0.042969 11.863 2.5117 16.094 6.8594 35.801 35.801 92.445 35.801 128.25 0l117.56-117.56c35.801-35.801 35.805-92.445 0-128.25-35.801-35.801-92.445-35.801-128.25 0l-117.56 117.56h0.003906c-4.2305 4.3516-10.031 6.8242-16.098 6.8672-6.0703 0.042968-11.902-2.3477-16.195-6.6367-4.2891-4.293-6.6797-10.125-6.6367-16.195 0.042969-6.0664 2.5156-11.867 6.8672-16.098l117.56-117.56c53.012-53.012 139.36-53.012 192.37 0 53.008 53.008 53.008 139.36 0 192.37l-117.56 117.56z"></path></svg>';
			break;

		case 'instagram':
			$output .= '<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><path fill="currentColor" d="M295.42,6c-53.2,2.51-89.53,11-121.29,23.48-32.87,12.81-60.73,30-88.45,57.82S40.89,143,28.17,175.92c-12.31,31.83-20.65,68.19-23,121.42S2.3,367.68,2.56,503.46,3.42,656.26,6,709.6c2.54,53.19,11,89.51,23.48,121.28,12.83,32.87,30,60.72,57.83,88.45S143,964.09,176,976.83c31.8,12.29,68.17,20.67,121.39,23s70.35,2.87,206.09,2.61,152.83-.86,206.16-3.39S799.1,988,830.88,975.58c32.87-12.86,60.74-30,88.45-57.84S964.1,862,976.81,829.06c12.32-31.8,20.69-68.17,23-121.35,2.33-53.37,2.88-70.41,2.62-206.17s-.87-152.78-3.4-206.1-11-89.53-23.47-121.32c-12.85-32.87-30-60.7-57.82-88.45S862,40.87,829.07,28.19c-31.82-12.31-68.17-20.7-121.39-23S637.33,2.3,501.54,2.56,348.75,3.4,295.42,6m5.84,903.88c-48.75-2.12-75.22-10.22-92.86-17-23.36-9-40-19.88-57.58-37.29s-28.38-34.11-37.5-57.42c-6.85-17.64-15.1-44.08-17.38-92.83-2.48-52.69-3-68.51-3.29-202s.22-149.29,2.53-202c2.08-48.71,10.23-75.21,17-92.84,9-23.39,19.84-40,37.29-57.57s34.1-28.39,57.43-37.51c17.62-6.88,44.06-15.06,92.79-17.38,52.73-2.5,68.53-3,202-3.29s149.31.21,202.06,2.53c48.71,2.12,75.22,10.19,92.83,17,23.37,9,40,19.81,57.57,37.29s28.4,34.07,37.52,57.45c6.89,17.57,15.07,44,17.37,92.76,2.51,52.73,3.08,68.54,3.32,202s-.23,149.31-2.54,202c-2.13,48.75-10.21,75.23-17,92.89-9,23.35-19.85,40-37.31,57.56s-34.09,28.38-57.43,37.5c-17.6,6.87-44.07,15.07-92.76,17.39-52.73,2.48-68.53,3-202.05,3.29s-149.27-.25-202-2.53m407.6-674.61a60,60,0,1,0,59.88-60.1,60,60,0,0,0-59.88,60.1M245.77,503c.28,141.8,115.44,256.49,257.21,256.22S759.52,643.8,759.25,502,643.79,245.48,502,245.76,245.5,361.22,245.77,503m90.06-.18a166.67,166.67,0,1,1,167,166.34,166.65,166.65,0,0,1-167-166.34" transform="translate(-2.5 -2.5)"/></svg>';
			break;

		case 'bluesky':
			$output .= '<svg width="600" height="530" viewBox="0 0 600 530" xmlns="http://www.w3.org/2000/svg"><path d="m135.72 44.03c66.496 49.921 138.02 151.14 164.28 205.46 26.262-54.316 97.782-155.54 164.28-205.46 47.98-36.021 125.72-63.892 125.72 24.795 0 17.712-10.155 148.79-16.111 170.07-20.703 73.984-96.144 92.854-163.25 81.433 117.3 19.964 147.14 86.092 82.697 152.22-122.39 125.59-175.91-31.511-189.63-71.766-2.514-7.3797-3.6904-10.832-3.7077-7.8964-0.0174-2.9357-1.1937 0.51669-3.7077 7.8964-13.714 40.255-67.233 197.36-189.63 71.766-64.444-66.128-34.605-132.26 82.697-152.22-67.108 11.421-142.55-7.4491-163.25-81.433-5.9562-21.282-16.111-152.36-16.111-170.07 0-88.687 77.742-60.816 125.72-24.795z" fill="currentColor"/></svg>';
			break;
		
		default:
			$output .= '';
			break;
	}

	$output .= '</span>';
	return $output;
}