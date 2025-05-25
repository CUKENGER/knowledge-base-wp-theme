<?php
/**
 * Template Part: Sidebar
 * Displays Dropdown button and search for category and single post pages.
 */
if (is_single() || is_category()):
	$current_post_id = get_queried_object_id();
	$current_category_id = is_category() ? get_queried_object_id() : 0;
	if (is_single()) {
		$post_categories = wp_get_post_categories($current_post_id, ['fields' => 'ids']);
		$active_category_id = !empty($post_categories) ? $post_categories[0] : 0;
	} else {
		$active_category_id = $current_category_id;
	}
	?>
	<section class="common-sidebar">
		<div class="sidebar-input__container">
			<div class="sidebar-input__wrapper">
				<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/search-icon.svg'); ?>" alt="Поиск">
				<input type="text" class="sidebar-input" placeholder="Какой у вас вопрос?">
				<svg class="hero-input-clear" width="24" height="24" viewBox="0 0 24 24" fill="none"
					xmlns="http://www.w3.org/2000/svg">
					<circle cx="12" cy="12" r="10" fill="currentColor" />
					<path d="M9 15L15 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					<path d="M15 15L9 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</div>
			<div class="sidebar-input__search-results"></div>
		</div>
		<div class="common-sidebar__contents">
			<?php
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
				<button class="category-widget__title<?php echo esc_attr($is_active); ?>"
					data-category-id="<?php echo esc_attr($category->term_id); ?>">
					<p class="category-widget__content--title">
						<?php echo esc_html($category->name); ?>
						<span class="category-widget__count--title"><?php echo esc_html($post_count); ?></span>
					</p>
					<svg class="category-widget__arrow" width="16" height="8" viewBox="0 0 16 8" fill="none"
						xmlns="http://www.w3.org/2000/svg">
						<path d="M3 2L8 6L13 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
					</svg>
				</button>
				<div class="category-widget__post-list" data-category-id="<?php echo esc_attr($category->term_id); ?>"
					style="display: <?php echo esc_attr($display); ?>;">
					<?php
					$posts = get_posts([
						'category' => $category->term_id,
						'numberposts' => 10,
					]);
					foreach ($posts as $post):
						$is_post_active = ($post->ID == $current_post_id) ? ' active' : '';
						error_log('Sidebar Post: ID: ' . $post->ID . ', Title: ' . get_the_title($post->ID) . ', URL: ' . get_permalink($post->ID));
						?>
						<a href="<?php echo esc_url(get_permalink($post->ID)); ?>"
							class="card-btn <?php echo esc_attr($is_post_active); ?>">
							<?php echo esc_html(get_the_title($post->ID)); ?>
							<svg class="card-btn__icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
								<use href="#chevron-icon"></use>
							</svg>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
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
<?php endif; ?>