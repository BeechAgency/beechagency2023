<?php
    //var_dump($args);
    $id = $args['ID'] ?? get_the_ID();
    $card_index = $args['index'] ?? null;

    $post_categories = get_the_category($id); // Get the post categories

    $categories_string = '';

    if ($post_categories) {
        $category_links = array(); // Create an array to store category links

        foreach ($post_categories as $category) {
            $category_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>'; // Generate the links
        }

        $categories_string = implode(', ', $category_links); // Comma-separated string
    }
    
    $post_type = get_post_type($id);

    $video_url = null;

    if($post_type === 'project') {
        $post_tags = wp_get_post_tags($id); 
        $tags = array();

        foreach($post_tags as $tag) {
            $tags[] = $tag->name;
        }

        $categories_string = implode(', ', $tags);
        $video_url = get_field('featured_video', $id);
    }
?>
<div class="bb-card bb-post-card type-<?= $post_type ?>" data-post-id="<?= $id ?>" <?= $card_index !== null ? 'style="--_card-index: '.$card_index.';"' : ''; ?> >
    <?php if($post_type === 'project' && !empty($video_url)): ?>
    <a href="<?= get_the_permalink( $id ); ?>" title="<?= get_the_title($id); ?>" class="card-video-wrapper">
        <video width="100%" height="auto" playsinline="" loop="" muted="" autoplay="" class="bb-post-card-video lozad" data-placeholder-background="hsla(0, 0.00%, 0.00%, 1.00)" data-loaded="false">
            <source data-src="<?= $video_url ?>" src="">
            Your browser does not support the video tag.
        </video>
        <img data-src="<?= get_the_post_thumbnail_url($id); ?>" data-placeholder-background="hsla(0, 0.00%, 0.00%, 1.00)" class="post-card video-poster lozad" style="background: rgb(0, 0, 0);" data-loaded="false">
    </a>
    <?php else: ?>
    <a href="<?= get_the_permalink( $id ); ?>" title="<?= get_the_title($id); ?>">
        <?= get_the_post_thumbnail($id, 'full', null ); ?>
    </a>
    <?php endif; ?>

    <h4 class="card-title"><a href="<?= get_the_permalink( $id ); ?>"><?= get_the_title($id); ?></a></h4>
    <div class="card-excerpt">
        <?= get_the_excerpt( $id ); ?>
    </div>
    <div class="card-meta">
        <div class="card-categories"><?= $categories_string; ?></div>
        <div class="card-date"><?= get_the_date( null, $id ); ?></div>
    </div>
</div>