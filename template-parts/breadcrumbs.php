<?php
/**
 * Template Part: Breadcrumbs
 * 
 * Displays breadcrumbs for category, single post, and page templates.
 */
?>
<section class='common-breadcrumbs'>
	<a href="<?php echo esc_url(home_url('/')); ?>" class="common-breadcrumbs-link">База знаний</a>
	<span class="common-breadcrumbs-divider">/</span>
	<?php
	// Дебаг
	error_log('Breadcrumbs: is_category=' . is_category() . ', is_single=' . is_single() . ', is_page=' . is_page() . ', Post ID=' . get_the_ID() . ', Categories=' . print_r(get_the_category(), true));

	if (is_category()) {
		$category = get_queried_object();
		if ($category) {
			echo '<span>' . esc_html($category->name) . '</span>';
		}
	} elseif (is_single()) {
		$categories = get_the_category();
		$main_category = !empty($categories) ? $categories[0] : null;
		if ($main_category) {
			echo '<a href="' . esc_url(get_category_link($main_category->term_id)) . '" class="common-breadcrumbs-link">' . esc_html($main_category->name) . '</a>';
			echo '<span class="common-breadcrumbs-divider">/</span>';
		} else {
			echo '<span>Без категории</span>';
			echo '<span class="common-breadcrumbs-divider">/</span>';
		}
		$current_post = get_post(get_queried_object_id());
		$parent_id = $current_post->post_parent;
		if ($parent_id) {
			$parent_post = get_post($parent_id);
			if ($parent_post) {
				echo '<a href="' . esc_url(get_permalink($parent_id)) . '" class="common-breadcrumbs-link">' . esc_html($parent_post->post_title) . '</a>';
				echo '<span class="common-breadcrumbs-divider">/</span>';
			}
		}
		echo '<span>' . esc_html(get_the_title()) . '</span>';
	} else {
		echo '<span>' . esc_html(get_the_title()) . '</span>';
	}
	?>
</section>