<?php
/**
 * Template Part: Sidebar
 * Displays dropdown buttons and search for category and single post pages.
 *
 * @package KnowledgeBase
 */

if (!is_single() && !is_category()) {
	return;
}

$current_post_id = get_queried_object_id();
$active_category_id = is_category()
	? $current_post_id
	: (wp_get_post_categories($current_post_id, ['fields' => 'ids'])[0] ?? 0);
?>
<section class="sidebar">
	<div class="sidebar__search-container">
		<div class="sidebar__search-wrapper">
			<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/search-icon.svg'); ?>" alt="Поиск"
				aria-hidden="true">
			<input type="text" class="sidebar__search-input" placeholder="Какой у вас вопрос?">
			<svg class="sidebar__search-clear" width="24" height="24" viewBox="0 0 24 24" fill="none"
				xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
				<circle cx="12" cy="12" r="10" fill="currentColor" />
				<path d="M9 15L15 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				<path d="M15 15L9 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
		</div>
		<div class="sidebar__search-results"></div>
	</div>
	<div class="sidebar__categories">
		<?php
		$categories = get_transient('tgx_categories') ?: get_categories(['hide_empty' => true]);
		if ($categories && !is_wp_error($categories)) {
			set_transient('tgx_categories', $categories, HOUR_IN_SECONDS);
			foreach ($categories as $category):
				$is_active = $category->term_id === $active_category_id ? ' is-active' : '';
				$is_post_list_active = $category->term_id === $active_category_id ? ' active' : '';
				?>
				<button class="sidebar__category-title<?php echo esc_attr($is_active); ?>"
					data-category-id="<?php echo esc_attr($category->term_id); ?>" type="button"
					aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>">
					<span class="sidebar__category-content">
						<?php echo esc_html($category->name); ?>
						<span class="sidebar__category-count"><?php echo esc_html($category->count); ?></span>
					</span>
					<svg class="sidebar__category-arrow" width="16" height="8" viewBox="0 0 16 8" fill="none"
						xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M3 2L8 6L13 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</button>
				<div class="sidebar__post-list<?php echo esc_attr($is_post_list_active); ?>"
					data-category-id="<?php echo esc_attr($category->term_id); ?>">
					<?php
					$posts = get_posts([
						'category' => $category->term_id,
						'numberposts' => 10,
						'post_status' => 'publish',
					]);
					if (empty($posts)) {
						echo '<p class="sidebar__no-posts">Нет постов в этой категории.</p>';
					} else {
						foreach ($posts as $post):
							$is_post_active = is_single() && $post->ID === $current_post_id ? ' active' : '';
							if (WP_DEBUG) {
								error_log(sprintf('Sidebar Post: ID %d, Title: %s, URL: %s', $post->ID, get_the_title($post->ID), get_permalink($post->ID)));
							}
							?>
							<a href="<?php echo esc_url(get_permalink($post->ID)); ?>"
								class="sidebar__post-item<?php echo esc_attr($is_post_active); ?>">
								<?php echo esc_html(get_the_title($post->ID)); ?>
								<svg class="sidebar__post-icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
									<use href="#chevron-icon"></use>
								</svg>
							</a>
						<?php endforeach;
						wp_reset_postdata(); // Восстанавливаем глобальный $post
					}
					?>
				</div>
			<?php endforeach;
		}
		?>
	</div>
	<?php get_template_part('template-parts/promo-card'); ?>
	<svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;">
		<defs>
			<symbol id="chevron-icon" viewBox="0 0 8 16">
				<path d="M2 3L6 8L2 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
					fill="none" />
			</symbol>
		</defs>
	</svg>
</section>