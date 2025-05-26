<?php
/**
 * Template Part: PageHeader
 * Displays Dropdown button and search for category and single post pages.
 */
$current_post_id = get_queried_object_id();
$current_category_id = is_category() ? get_queried_object_id() : 0;
if (is_single()) {
	$post_categories = wp_get_post_categories($current_post_id, ['fields' => 'ids']);
	$active_category_id = !empty($post_categories) ? $post_categories[0] : 0;
} else {
	$active_category_id = $current_category_id;
}
?>
<div class="overlay" aria-hidden="true"></div>
<div class='page-header__contents-menu' id="contents-menu">
	<div class='page-header__contents-list'>
		<?php
		// Кэшируем категории
		$categories = get_transient('tgx_categories');
		if (false === $categories) {
			$categories = get_categories(['hide_empty' => true]);
			set_transient('tgx_categories', $categories, HOUR_IN_SECONDS);
		}

		foreach ($categories as $category):
			$post_count = $category->count;
			$is_active = ($category->term_id == $active_category_id) ? ' is-active' : '';
			$display = ($category->term_id == $active_category_id) ? 'flex' : 'none';
			?>
			<button class="page-header__category-title<?php echo esc_attr($is_active); ?>"
				data-category-id="<?php echo esc_attr($category->term_id); ?>">
				<p class="page-header__category-content">
					<?php echo esc_html($category->name); ?>
					<span class="page-header__category-count"><?php echo esc_html($post_count); ?></span>
				</p>
				<svg class="page-header__category-arrow" width="16" height="8" viewBox="0 0 16 8" fill="none"
					xmlns="http://www.w3.org/2000/svg">
					<path d="M3 2L8 6L13 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</button>
			<div class="page-header__post-list" data-category-id="<?php echo esc_attr($category->term_id); ?>"
				style="display: <?php echo esc_attr($display); ?>;">
				<?php
				$posts = get_posts([
					'category' => $category->term_id,
					'numberposts' => 10,
				]);
				foreach ($posts as $post):
					$is_post_active = ($post->ID == $current_post_id) ? ' active' : '';
					error_log('Page Header Post: ID ' . $post->ID . ', Title: ' . get_the_title($post->ID) . ', URL: ' . get_permalink($post->ID));
					?>
					<a href="<?php echo esc_url(get_permalink($post->ID)); ?>"
						class="page-header__post-item<?php echo esc_attr($is_post_active); ?>">
						<?php echo esc_html(get_the_title($post->ID)); ?>
						<svg class="page-header__post-icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
							<use href="#chevron-icon"></use>
						</svg>
					</a>
				<?php endforeach; ?>
			</div>
			<?php wp_reset_postdata(); // Восстанавливаем глобальный $post ?>
		<?php endforeach; ?>
	</div>
	<div class='contents-menu__btn-container'>
		<button class="contents-menu__btn">Закрыть</button>
	</div>
</div>
<section class='page-header--tablet'>
	<div class='page-header__wrapper'>
		<button class='page-header__contents-btn' aria-expanded="false" aria-controls="contents-menu">Содержание</button>
		<div class='page-header__input-container'>
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path
					d="M10.5 3C14.6421 3 18 6.35786 18 10.5C18 12.2105 17.4259 13.7861 16.4619 15.0479L20.707 19.293L20.7754 19.3691C21.0957 19.7619 21.0731 20.3409 20.707 20.707C20.3409 21.0731 19.7619 21.0957 19.3691 20.7754L19.293 20.707L15.0479 16.4619C13.7861 17.4259 12.2105 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3ZM10.5 5C7.46243 5 5 7.46243 5 10.5C5 13.5376 7.46243 16 10.5 16C13.5376 16 16 13.5376 16 10.5C16 7.46243 13.5376 5 10.5 5Z"
					fill="#ABB0BA" />
			</svg>
			<input class='page-header__input' type="text" placeholder='Какой у вас вопрос?'>
			<button class='page-header__input-clear' style='display: none;'>
				<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M12 4L4 12M4 4L12 12" stroke="#ABB0BA" stroke-width="2" stroke-linecap="round" />
				</svg>
			</button>
			<div class='page-header__search-results'></div>
		</div>
	</div>
</section>
<svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;">
	<defs>
		<symbol id="chevron-icon" viewBox="0 0 8 16">
			<path d="M2 3L6 8L2 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
				fill="none" />
		</symbol>
	</defs>
</svg>