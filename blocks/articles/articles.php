<?php
/**
 * Feature Block!
 *
 * @param array $block The block settings and attributes.
 */


// Load values and assign defaults.
$type        = get_field( 'type' );
$display_number = get_field( 'display_number');
$display_pagination = get_field( 'display_pagination');
$post_type = get_field( 'post_type');
$category = get_field( 'category');
$specific_posts = get_field( 'specific_posts');
$link = get_field( 'link');

$display_filters = get_field( 'display_filters');
$display_count = get_field( 'display_count');
$filter_tax = get_field( 'filter_tax');

$page_id = get_the_ID();

// Support custom "anchor" values.
$anchor = '';
if ( ! empty( $block['anchor'] ) ) {
    $anchor = esc_attr( $block['anchor'] );
}

$type = $type === '' ? 'latest' : $type;
//var_dump($type);

// Create class attribute allowing for custom "className" and "align" values.

$class_name = '';

$allowed_blocks = array( 'core/heading', 'core/paragraph', 'core/button', 'core/buttons' );

$template = array(
	array('core/heading', array(
		'level' => 3,
		'placeholder' => 'Read on',
	))
);

$query_args = array(
    'post' => $post_type,
    'number' => $display_number,
    'paged' => $display_pagination
);
//var_dump($query_args);

if($display_pagination) {
    $query_args['paged'] = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
}

if(!empty($category)) {
    $query_args['category_name'] = $category;
}

$post_ids = $type === 'latest' ? beechblocks_post_list_ids($query_args) : $specific_posts;

if(empty($block['className'])) {
    $block['className'] = 'is-style-articles';
}

if(!empty($block['className'])) {
    if(strpos($block['className'], 'related')) {
        $post_ids = !empty(get_field('related', $page_id)) ? get_field('related', $page_id) : $post_ids;
    }
}

$post_count = 0;



foreach( $post_ids as $id ) {
    if(is_numeric($id)) $post_count += 1;
}

$post_count = $post_count === 0 ? 3 : $post_count;
?>

<div <?= get_block_wrapper_attributes( array(
    'class' => $class_name,
    'id' => $anchor )
    ); ?>>
    <div class="articles-header">        
        <InnerBlocks 
            allowedBlocks="<?= esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" 
            template="<?= esc_attr( wp_json_encode( $template ) ); ?>"
            className="articles__inner-blocks"
        />
    </div>
    <div class="content-list">
        <?php if(!empty($display_filters) && $display_filters === true):  

            beech_taxonomy_value_filter_list( $filter_tax , get_the_permalink( $page_id ) );
        endif; ?>
        <div class="articles-track">
            <div class="articles-wrapper list-output <?= !empty($post_type) ? $post_type[0] : ''; ?>" style="--article-count: <?= $post_count; ?>;">
                <?php 
                $paginationOutput = null;
                foreach( $post_ids as $id ):
                    if(!is_numeric($id)) {
                        $paginationOutput = $id;
                    } else {
                        get_template_part( 'template-parts/card', 'post', array('ID' => $id) );
                    }
                endforeach;
                ?>
            </div><!-- // .articles-wrapper -->
        </div><!-- // .articles-track -->
        <?php if($paginationOutput) { echo $paginationOutput; } ?>
        <?php if(!empty($link) && $link['url']): ?>
        <div class="article-footer">
            <a class="bb-btn read-more" href="<?= $link['url']; ?>" target="<?= $link['target'] ?>">
                <?= $link['title'] ?>
                <?php 
                if($display_count):
                    $post_count = wp_count_posts(!empty($post_type) ? $post_type[0] : 'post');

                    echo '<span class="count">';
                    echo $post_count->publish;
                    echo '</span>';
                endif;
                ?>
            </a>
        </div>
    <?php endif; ?>
    </div>
</div>