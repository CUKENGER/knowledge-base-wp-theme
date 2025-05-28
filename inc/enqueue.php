<?php
function tgx_theme_enqueue_scripts()
{
	error_log('Enqueue scripts started. Post ID: ' . get_queried_object_id());
	wp_enqueue_script('jquery', includes_url('js/jquery/jquery.min.js'), [], '3.6.0', true);
	$css_files = [
		'tgx-reset-styles' => ['path' => '/css/reset.css', 'deps' => []],
		'tgx-custom-styles' => ['path' => '/css/styles.css', 'deps' => ['tgx-reset-styles']],
		'tgx-header-styles' => ['path' => '/css/header.css', 'deps' => ['tgx-custom-styles']],
		'tgx-index-styles' => ['path' => '/css/index.css', 'deps' => ['tgx-custom-styles']],
		'tgx-footer-styles' => ['path' => '/css/footer.css', 'deps' => ['tgx-custom-styles']],
		'tgx-page-header-styles' => ['path' => '/css/page-header.css', 'deps' => ['tgx-custom-styles']],
		'tgx-sidebar-styles' => ['path' => '/css/sidebar.css', 'deps' => ['tgx-custom-styles']],
		'tgx-breadcrumbs-styles' => ['path' => '/css/breadcrumbs.css', 'deps' => ['tgx-custom-styles']],
		'tgx-single-styles' => ['path' => '/css/single.css', 'deps' => ['tgx-custom-styles']],
		'tgx-category-styles' => ['path' => '/css/category.css', 'deps' => ['tgx-custom-styles']],
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
			error_log('Enqueued CSS: ' . $file_path);
		} else {
			error_log('CSS file not found: ' . $file_path);
		}
	}
	$js_files = [
		'tgx-search-modal' => ['path' => '/js/search-modal.js', 'deps' => ['jquery']],
		'tgx-main-js' => ['path' => '/js/script.js', 'deps' => ['jquery']],
		'tgx-categories-scroll' => ['path' => '/js/categories-scroll.js', 'deps' => []],
		'tgx-copy-block' => ['path' => '/js/copy-block.js', 'deps' => []],
		'tgx-sidebar-toggle' => ['path' => '/js/sidebar-toggle.js', 'deps' => ['jquery']],
		'tgx-contents-menu-toggle' => ['path' => '/js/contents-menu.js', 'deps' => ['jquery']],
		'tgx-page-header-toggle' => ['path' => '/js/page-header-toggle.js', 'deps' => ['jquery']],
		'tgx-sidebar-search' => ['path' => '/js/sidebar-search.js', 'deps' => ['jquery']],
		'tgx-page-header-search' => ['path' => '/js/page-header-search.js', 'deps' => ['jquery']],
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
			error_log('Enqueued JS: ' . $file_path);
		} else {
			error_log('JS file not found: ' . $file_path);
		}
	}
	wp_localize_script('tgx-search-modal', 'tgxSettings', [
		'ajaxUrl' => admin_url('admin-ajax.php')
	]);
}
add_action('wp_enqueue_scripts', 'tgx_theme_enqueue_scripts');