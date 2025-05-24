<?php
function tgx_theme_enqueue_scripts()
{
	// Стили
	wp_enqueue_style('tgx-reset-styles', get_template_directory_uri() . '/css/reset.css', [], filemtime(get_template_directory() . '/css/reset.css'));
	wp_enqueue_style('tgx-custom-styles', get_template_directory_uri() . '/css/styles.css', ['tgx-reset-styles'], filemtime(get_template_directory() . '/css/styles.css'));
	wp_enqueue_style('tgx-header-styles', get_template_directory_uri() . '/css/header.css', ['tgx-custom-styles'], filemtime(get_template_directory() . '/css/header.css'));
	wp_enqueue_style('tgx-index-styles', get_template_directory_uri() . '/css/index.css', ['tgx-custom-styles'], filemtime(get_template_directory() . '/css/index.css'));
	
	if (is_category() && file_exists(get_template_directory() . '/css/category.css')) {
		wp_enqueue_style('tgx-category-styles', get_template_directory_uri() . '/css/category.css', ['tgx-custom-styles'], filemtime(get_template_directory() . '/css/category.css'));
	}

	if (file_exists(get_template_directory() . '/css/footer.css')) {
		wp_enqueue_style('tgx-footer-styles', get_template_directory_uri() . '/css/footer.css', ['tgx-custom-styles'], filemtime(get_template_directory() . '/css/footer.css'));
	}
	// Скрипты
	wp_enqueue_script('tgx-search-modal', get_template_directory_uri() . '/js/search-modal.js', ['jquery'], filemtime(get_template_directory() . '/js/search-modal.js'), true);
	wp_localize_script('tgx-search-modal', 'tgxSettings', [
		'ajaxUrl' => admin_url('admin-ajax.php')
	]);

	// Дополнительные скрипты
	if (file_exists(get_template_directory() . '/js/script.js')) {
		wp_enqueue_script('tgx-main-js', get_template_directory_uri() . '/js/script.js', [], filemtime(get_template_directory() . '/js/script.js'), true);
	}
	if (file_exists(get_template_directory() . '/js/categories-scroll.js')) {
		wp_enqueue_script('tgx-categories-scroll', get_template_directory_uri() . '/js/categories-scroll.js', [], '1.0', true);
	}
}
add_action('wp_enqueue_scripts', 'tgx_theme_enqueue_scripts');

