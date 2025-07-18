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
		$categories = get_transient('tgx_categories') ?: get_categories(['hide_empty' => true, 'orderby' => 'term_order']);
		if ($categories && !is_wp_error($categories)) {

			set_transient('tgx_categories', $categories, HOUR_IN_SECONDS);

			foreach ($categories as $category):
				$parent_posts_count = count(get_posts([
					'category' => $category->term_id,
					'post_status' => 'publish',
					'post_type' => 'post',
					'post_parent' => 0,
					'numberposts' => -1,
					'fields' => 'ids',
				]));
				$is_active = $category->term_id === $active_category_id ? ' is-active' : '';
				$is_post_list_active = $category->term_id === $active_category_id ? ' active' : '';
				?>

				<button class="sidebar__category-title<?php echo esc_attr($is_active); ?>"
					data-category-id="<?php echo esc_attr($category->term_id); ?>" type="button"
					aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>">

					<span class="sidebar__category-content">
						<?php echo esc_html($category->name); ?>
						<span class="sidebar__category-count"><?php echo esc_html($parent_posts_count); ?></span>
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
						'post_parent' => 0,
					]);


					if (empty($posts)) {
						echo '<p class="sidebar__no-posts">Нет постов в этой категории.</p>';
					} else {
						foreach ($posts as $post):
							// is_post_active = is_single() && $post->ID === $current_post_id ? ' active' : '';
							$is_post_active = is_single() && $post->ID === $current_post_id ? ' active' : '';
							$current_post = is_single() ? get_post($current_post_id) : null;
							$parent_id = $current_post ? $current_post->post_parent : 0;
							$is_parent_of_current = is_single() && $parent_id === $post->ID ? ' is-active' : '';
							?>

							<div class="sidebar__post-wrapper">

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
									class="sidebar__post-item <?php echo esc_attr($is_post_active . ' ' . ($has_children && ($is_child_list_active || $is_parent_of_current) ? 'is-active' : '')); ?>"
									data-post-id="<?php echo esc_attr($post->ID); ?>" type="button"
									aria-expanded="<?php echo $has_children && ($is_child_list_active || $is_parent_of_current) ? 'true' : 'false'; ?>">

									<span>
										<a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="sidebar__post-link">
											<?php echo esc_html(get_the_title($post->ID)); ?>
										</a>
									</span>

									<?php if ($has_children): ?>
										<svg class="sidebar__post-arrow" width="16" height="8" viewBox="0 0 16 8" fill="none"
											xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
											<path d="M3 2L8 6L13 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"
												stroke-linejoin="round" />
										</svg>
									<?php else: ?>
										<svg class="sidebar__post-icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
											<use href="#chevron-icon"></use>
										</svg>
									<?php endif; ?>
								</button>

								<?php if ($has_children): ?>

									<div class="sidebar__child-list <?php echo esc_attr($is_child_list_active); ?>"
										data-post-id="<?php echo esc_attr($post->ID); ?>">

										<?php
										foreach ($child_posts as $child_post):
											$is_child_active = is_single() && $child_post->ID === $current_post_id ? ' active' : '';
											?>

											<a href="<?php echo esc_url(get_permalink($child_post->ID)); ?>"
												class="sidebar__child-item<?php echo esc_attr($is_child_active); ?>">
												<span><?php echo esc_html(get_the_title($child_post->ID)); ?></span>
												<svg class="sidebar__child-icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
													<use href="#chevron-icon"></use>
												</svg>
											</a>

										<?php endforeach; ?>

									</div>

								<?php endif; ?>

							</div>
						<?php endforeach;
						wp_reset_postdata();
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
	<script>
		window.currentPostId = <?php echo json_encode($current_post_id); ?>;
	</script>
</section>