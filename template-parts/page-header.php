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
<div class='page-header__contents-menu' id="contents-menu">
	<div class='page-header__contents-list'>
		<?php
		// Кэшируем категории
		$categories = get_transient('tgx_categories');
		if (false === $categories) {
			$categories = get_categories(['hide_empty' => true, 'orderby' => 'term_order']);
			set_transient('tgx_categories', $categories, HOUR_IN_SECONDS);
		}

		foreach ($categories as $category):
			$is_active = $category->term_id === $active_category_id ? ' is-active' : '';
			$is_post_list_active = $category->term_id === $active_category_id ? ' active' : '';
			?>
			<button class="page-header__category-title<?php echo esc_attr($is_active); ?>"
				data-category-id="<?php echo esc_attr($category->term_id); ?>" type="button"
				aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>">
				<span class="page-header__category-content">
					<?php echo esc_html($category->name); ?>
					<span class="page-header__category-count"><?php echo esc_html($category->count); ?></span>
				</span>
				<svg class="page-header__category-arrow" width="16" height="8" viewBox="0 0 16 8" fill="none"
					xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M3 2L8 6L13 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</button>
			<div class="page-header__post-list<?php echo esc_attr($is_post_list_active); ?>"
				data-category-id="<?php echo esc_attr($category->term_id); ?>">
				<?php
				$posts = get_posts([
					'category' => $category->term_id,
					'numberposts' => 10,
					'post_status' => 'publish',
					'post_parent' => 0,
				]);

				if (empty($posts)) {
					echo '<p class="page-header__no-posts">Нет постов в этой категории.</p>';
				} else {
					global $post;
					$tmp_post = $post;
					foreach ($posts as $post):
						setup_postdata($post);
						$is_post_active = is_single() && $post->ID === $current_post_id ? ' active' : '';
						$current_post = is_single() ? get_post($current_post_id) : null;
						$parent_id = $current_post ? $current_post->post_parent : 0;
						$is_parent_of_current = is_single() && $parent_id === $post->ID ? ' is-active' : '';
						?>
						<div class="page-header__post-wrapper">
							<?php
							$child_posts = get_posts([
								'post_parent' => $post->ID,
								'numberposts' => -1,
								'post_status' => 'publish',
								'post_type' => 'post',
							]);
							$has_children = !empty($child_posts);
							$is_child_list_active = is_single() && ($post->ID === $current_post_id || $parent_id === $post->ID) ? ' active' : '';
							?>
							<button
								class="page-header__post-item <?php echo esc_attr($is_post_active . ' ' . ($has_children && ($is_child_list_active || $is_parent_of_current) ? 'is-active' : '')); ?>"
								data-post-id="<?php echo esc_attr($post->ID); ?>" type="button"
								aria-expanded="<?php echo $has_children && ($is_child_list_active || $is_parent_of_current) ? 'true' : 'false'; ?>">
								<span>
									<a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="page-header__post-link">
										<?php echo esc_html(get_the_title($post->ID)); ?>
									</a>
								</span>
								<?php if ($has_children): ?>
									<svg class="page-header__post-arrow" width="16" height="8" viewBox="0 0 16 8" fill="none"
										xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
										<path d="M3 2L8 6L13 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"
											stroke-linejoin="round" />
									</svg>
								<?php else: ?>
									<svg class="page-header__post-icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
										<use href="#chevron-icon"></use>
									</svg>
								<?php endif; ?>
							</button>
							<?php if ($has_children): ?>
								<div class="page-header__child-list <?php echo esc_attr($is_child_list_active); ?>"
									data-post-id="<?php echo esc_attr($post->ID); ?>">
									<?php
									foreach ($child_posts as $child_post):
										$is_child_active = is_single() && $child_post->ID === $current_post_id ? ' active' : '';
										?>
										<a href="<?php echo esc_url(get_permalink($child_post->ID)); ?>"
											class="page-header__child-item<?php echo esc_attr($is_child_active); ?>">
											<span><?php echo esc_html(get_the_title($child_post->ID)); ?></span>
											<svg class="page-header__child-icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
												<use href="#chevron-icon"></use>
											</svg>
										</a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach;
					wp_reset_postdata();
					setup_postdata($tmp_post);
				}
				?>
			</div>
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
				<svg class="page-header__input-clear__icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
					xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<circle cx="12" cy="12" r="10" fill="currentColor" />
					<path d="M9 15L15 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					<path d="M15 15L9 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
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
<script>
	window.currentPostId = <?php echo json_encode($current_post_id); ?>;
</script>