<?php
/**
 * beechblocks functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package beechblocks
 */

$THEME_VERSION = wp_get_theme()->get('Version');

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', $THEME_VERSION  );
}


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function beechblocks_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on beechblocks, use a find and replace
		* to change 'beechblocks' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'beechblocks', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'beechblocks' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'beechblocks_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'beechblocks_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function beechblocks_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'beechblocks_content_width', 640 );
}
add_action( 'after_setup_theme', 'beechblocks_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function beechblocks_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Posts Footer', 'beechblocks' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'beechblocks' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar( array(
		'name'          =>  esc_html__( 'Projects Footer', 'beechblocks' ),
		'id'            => 'projects-footer',
		'description'   => esc_html__( 'Add widgets for the project footer.', 'beechblocks' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Pages Footer', 'beechblocks' ),
		'id'            => 'pages-footer',
		'description'   => esc_html__( 'Add widgets for the entire site footer.', 'beechblocks' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Products Footer', 'beechblocks' ),
		'id'            => 'products-footer',
		'description'   => esc_html__( 'Add widgets for the entire site footer.', 'beechblocks' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Categories Footer', 'beechblocks' ),
		'id'            => 'categories-footer',
		'description'   => esc_html__( 'Add widgets for the entire site footer.', 'beechblocks' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
}
add_action( 'widgets_init', 'beechblocks_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function beechblocks_scripts() {
	wp_enqueue_style( 'beechblocks-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'beechblocks-style', 'rtl', 'replace' );

	wp_enqueue_script( 'beechblocks-navigation', get_template_directory_uri() . '/js/navigation.js', array('lozad'), _S_VERSION, true );

	wp_enqueue_script( 'lozad', get_template_directory_uri() . '/js/vendor/lozad.min.js', [] );
	wp_enqueue_script( 'splitting', get_template_directory_uri() . '/js/vendor/splitting.min.js', [] );
	wp_enqueue_script( 'simpleParallax', get_template_directory_uri() . '/js/vendor/simpleParallax.min.js', [] );
	wp_enqueue_script( 'locomotiveScroll', get_template_directory_uri() . '/js/vendor/locomotive-scroll.min.js', [] );
	wp_enqueue_script( 'sugar', get_template_directory_uri() . '/js/sugar.js?v=', ['splitting', 'simpleParallax', 'locomotiveScroll'], _S_VERSION, true );

	 wp_localize_script('beechblocks-navigation', 'ajaxParams', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('load_posts_nonce'),
    ]);
}
add_action( 'wp_enqueue_scripts', 'beechblocks_scripts' );


/**
 * Registers an editor stylesheet for the theme.
 */
function wpdocs_theme_add_editor_styles() {
    add_editor_style( 'editor-style.css' );
}
add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-blocks.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';


/**
 * Utilities which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-utilities.php';

/**
 * Utilities which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-resets.php';

/**
 * Changes to the admin and editor
 */
require get_template_directory() . '/inc/template-editor.php';

/**
 * Add the theme updater
 */
require get_template_directory() . '/inc/template-updater.php';



// This is in use!
if ( ! function_exists( 'beechblocks_post_list_ids') ) :
	/**
	* Displays A List of Posts
	*/
	function beechblocks_post_list_ids( $args = array() ) {
		$postType = $args['post'] ?? 'post';

		$numberOfPosts	= $args['number'] ?? 4;
		$category = $args['category'] ?? null;
		$postIds = $args['posts'] ?? null;
		$templateTypes = $args['types'] ?? null;

		$hasPagination = $args['paged'] ?? false;
		$eventSort = $args['eventSort'] ?? false;

		$displayOptions = $args['options'] ?? array();

		$queryArgs = array(
			'post_status'    => 'publish',
			'posts_per_page' => $numberOfPosts,
			'post_type' => $postType
		);

		if(!empty($hasPagination)) {
			$queryArgs['paged'] = $hasPagination;
		}

		// Apply the initiative taxonomy query
		/*
		if(!empty($initiatives)) {
			//var_dump($initiatives);
	
			$initiativeSlugs = array();

			foreach($initiatives as $initative):
				$initiativeSlugs[] = $initative->slug;
			endforeach;

			$queryArgs['tax_query'] = array(
				array(
					'taxonomy' => 'initiative',
					'field' => 'slug',
					'terms' => $initiativeSlugs, // ENsure that this is an array
					'operator' => 'IN'
				)
			);
		}*/


		// Standard category filter
		if(!empty($category)) {
			$queryArgs['category_name'] = $category;
		}

		// Ensure posts don't display on the same page as itself
		if (get_post_type() !== 'page') {
			$postId = get_the_ID();
			$queryArgs['post__not_in'] = array($postId);
		}

		if(!empty($postIds)) {
			$queryArgs['post__in'] = $postIds;
		}


		$queryArgs['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key'     => 'is_hidden',
				'value'   => true,
				'compare' => '!=', // Fetch posts where the field 'is_hidden' doesn't exist or is set to a value other than 'true'
			)
		);

		$postQuery = new WP_Query(
			$queryArgs
		);

		$posts = array();

		if( $postQuery->have_posts() ) : 

			$n = 0;
			while( $postQuery->have_posts() ): $postQuery->the_post();
			
				$posts[] = get_the_ID();
				
				$n = $n + 1;
		    endwhile; 
		 endif;

		if($hasPagination) {			
			global $wp_query;
			$big = 9999999; // need an unlikely integer
			
			$pagination = '<div class="posts-pagination within-grid">';
			$pagination.= paginate_links( 
				array(
				   'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				   'format' => '?paged=%#%',
				   'current' => max( 1, get_query_var('paged') ),
				   'total' => $postQuery->max_num_pages,
				   'prev_text' => __('<svg xmlns="http://www.w3.org/2000/svg" width="7.466" height="12.812" viewBox="0 0 7.466 12.812"><path id="Path_568" data-name="Path 568" d="M-7223.768,2647.072l5.875,5.876-5.875,5.875" transform="translate(-7216.832 2659.354) rotate(-180)" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>'),
				   'next_text' => __('<svg xmlns="http://www.w3.org/2000/svg" width="7.466" height="12.812" viewBox="0 0 7.466 12.812"><path id="Path_567" data-name="Path 567" d="M-7223.768,2647.072l5.875,5.876-5.875,5.875" transform="translate(7224.298 -2646.542)" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
				 ')
				)
			);
			$pagination .= '</div>';
			$posts['pagination'] = $pagination;
		}

		wp_reset_postdata();

		return $posts;
	}
endif;


if ( ! function_exists( 'beechblocks_number_pagination') ) :
	/**
	* Displays A List of Posts
	*/
	function beechblocks_number_pagination() {
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

function beechblocks_calculate_reading_time($content) {
    // Get the word count of the content
    $word_count = str_word_count(strip_tags($content));

    // Average reading speed in words per minute
    $words_per_minute = 200;

    // Calculate the reading time in minutes
    $reading_time = ceil($word_count / $words_per_minute);

    return $reading_time;
}