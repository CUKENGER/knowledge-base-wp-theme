<?php
function tgx_theme_enqueue_scripts()
{
	// Стили
	$css_files = [
		'tgx-reset-styles' => ['path' => '/css/reset.css', 'deps' => []],
		'tgx-custom-styles' => ['path' => '/css/styles.css', 'deps' => ['tgx-reset-styles']],
		'tgx-header-styles' => ['path' => '/css/header.css', 'deps' => ['tgx-custom-styles']],
		'tgx-index-styles' => ['path' => '/css/index.css', 'deps' => ['tgx-custom-styles']],
		'tgx-footer-styles' => ['path' => '/css/footer.css', 'deps' => ['tgx-custom-styles']],
	];

	foreach ($css_files as $handle => $file) {
		$file_path = get_template_directory() . $file['path'];
		if (file_exists($file_path)) {
			wp_enqueue_style(
				$handle,
				get_template_directory_uri() . $file['path'],
				$file['deps'],
				filemtime($file_path)
			);
		}
	}

	if (is_single() || is_category()) {
		$conditional_css = [
			'tgx-page-header-styles' => '/css/page-header.css',
			'tgx-sidebar-styles' => '/css/sidebar.css',
			'tgx-breadcrumbs-styles' => '/css/breadcrumbs.css',
		];
		foreach ($conditional_css as $handle => $path) {
			$file_path = get_template_directory() . $path;
			if (file_exists($file_path)) {
				wp_enqueue_style(
					$handle,
					get_template_directory_uri() . $path,
					['tgx-custom-styles'],
					filemtime($file_path)
				);
			}
		}
	}

	if (is_category()) {
		$category_css = get_template_directory() . '/css/category.css';
		if (file_exists($category_css)) {
			wp_enqueue_style(
				'tgx-category-styles',
				get_template_directory_uri() . '/css/category.css',
				['tgx-custom-styles'],
				filemtime($category_css)
			);
		}
	}

	if (is_single()) {
		$single_css = get_template_directory() . '/css/single.css';
		if (file_exists($single_css)) {
			wp_enqueue_style(
				'tgx-single-styles',
				get_template_directory_uri() . '/css/single.css',
				['tgx-custom-styles'],
				filemtime($single_css)
			);
		}
	}

	// Скрипты
	$js_files = [
		'tgx-search-modal' => ['path' => '/js/search-modal.js', 'deps' => ['jquery']],
		'tgx-main-js' => ['path' => '/js/script.js', 'deps' => ['jquery']],
		'tgx-categories-scroll' => ['path' => '/js/categories-scroll.js', 'deps' => []],
		'tgx-copy-block' => ['path' => '/js/copy-block.js', 'deps' => []], // Для шорткода [copy-block]
	];

	foreach ($js_files as $handle => $file) {
		$file_path = get_template_directory() . $file['path'];
		if (file_exists($file_path)) {
			wp_enqueue_script(
				$handle,
				get_template_directory_uri() . $file['path'],
				$file['deps'],
				filemtime($file_path),
				true
			);
		}
	}

	// Локализация для search-modal.js
	wp_localize_script('tgx-search-modal', 'tgxSettings', [
		'ajaxUrl' => admin_url('admin-ajax.php')
	]);

	error_log('is_single: ' . (is_single() ? 'true' : 'false'));
	error_log('is_category: ' . (is_category() ? 'true' : 'false'));

	// Скрипты для single и category
	$conditional_js = [
		'tgx-sidebar-toggle' => '/js/sidebar-toggle.js',
		'tgx-contents-menu-toggle' => '/js/contents-menu.js',
		'tgx-page-header-toggle' => '/js/page-header-toggle.js',
		'tgx-sidebar-search' => '/js/sidebar-search.js',
		'tgx-page-header-search' => '/js/page-header-search.js',
	];
	foreach ($conditional_js as $handle => $path) {
		$file_path = get_template_directory() . $path;
		if (file_exists($file_path)) {
			wp_enqueue_script(
				$handle,
				get_template_directory_uri() . $path,
				['jquery'],
				filemtime($file_path),
				true
			);
		}
	}
}
add_action('wp_enqueue_scripts', 'tgx_theme_enqueue_scripts');