<?php
/**
 * Template part for displaying a single post in the posts grid
 */
?>
<a href="<?php the_permalink(); ?>" class="post-link">
	<article class="post">
		<div class="post-cover">
			<?php if (has_post_thumbnail()): ?>
				<?php
				the_post_thumbnail('post-cover', [
					'alt' => get_the_title(),
					'class' => 'post-cover-image',
					'loading' => 'lazy',
					'sizes' => '(max-width: 790px) 100vw, (max-width: 980px) 360px, 360px',
				]);
				?>
			<?php else: ?>
				<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/post.png'); ?>" alt="Post placeholder"
					class="post-cover-image" loading="lazy">
			<?php endif; ?>
		</div>
		<div class="post-tags">
			<span class="post-tags-date"><?php echo date_i18n('j F', strtotime(get_the_date('c'))); ?></span>
			<svg class="post-tags-dot" width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
				<circle opacity="0.5" cx="2" cy="2" r="2" fill="currentColor" />
			</svg>
			<div class="post-tags-icon">
				<?php
				$categories = get_the_category();
				$category = !empty($categories) ? $categories[0] : null;
				$category_name = $category ? esc_html($category->name) : 'Прочее';
				$category_slug = $category ? $category->slug : 'prochee';
				$category_icons = tgh_get_category_icons();

				// Получаем URL иконки
				$icon_url = isset($category_icons[$category_slug]) ? $category_icons[$category_slug] : $category_icons['prochee'];
				$icon_path = str_replace(get_site_url(), ABSPATH, $icon_url);
				$svg_content = ''; // По умолчанию пусто
				
				// Логирование
				error_log('Post: ' . get_the_title() . ', Category: ' . $category_name . ', Icon: ' . $icon_url);

				// Проверка SVG
				if (file_exists($icon_path)) {
					$svg_content = file_get_contents($icon_path);
					if ($svg_content && stripos($svg_content, '<svg') !== false) {
						error_log('SVG for ' . $category_name . ': loaded');
					} else {
						error_log('Invalid SVG for category: ' . $category_name . ', Path: ' . $icon_path);
						$svg_content = ''; // Не показываем иконку
					}
				} else {
					error_log('SVG not found: ' . $icon_path);
					$svg_content = ''; // Не показываем иконку
				}
				?>
				<?php if ($svg_content): ?>
					<span class="post-category-icon"><?php echo $svg_content; ?></span>
				<?php endif; ?>
				<span class="post-tags-title"><?php echo $category_name; ?></span>
			</div>
		</div>
		<div class="post-content">
			<h2 class="post-title"><?php the_title(); ?></h2>
			<p class="post-text"><?php echo wp_strip_all_tags(get_the_excerpt()); ?></p>
		</div>
		<div class="post-author">
			<span class="post-author-avatar">
				<?php echo get_avatar(get_the_author_meta('ID'), 30, 'mystery', 'Аватар пользователя'); ?>
			</span>
			<span class="post-author-name"><?php the_author(); ?></span>
		</div>
	</article>
</a>