<?php
// Подключение модулей
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/misc.php';

// Поддержка миниатюр (оставляем здесь, так как это базовая настройка темы)
add_theme_support('post-thumbnails');

// Шорткод для copy-block
function copy_block_shortcode($atts, $content = null)
{
	if (empty($content)) {
		return '';
	}
	// Уникальный ID для каждого блока, чтобы избежать конфликтов в JavaScript
	$block_id = uniqid('copy-block-');
	$content = wp_strip_all_tags($content); // Удаляем HTML-теги для безопасного копирования
	$output = '<div class="copy-block" id="' . esc_attr($block_id) . '" onclick="copyBlockText(\'' . esc_attr($block_id) . '\', \'' . esc_js($content) . '\')">';
	$output .= '<div class="copy-block__content">' . esc_html($content) . '</div>';
	$output .= '<button class="copy-button" aria-label="Копировать текст">';
	$output .= '<svg width="20" height="28" viewBox="0 0 20 28" fill="none" xmlns="http://www.w3.org/2000/svg">';
	$output .= '<path d="M16.1543 4.00391C17.7394 4.08421 19 5.39489 19 7V17C19 18.6569 17.6569 20 16 20H15V21C15 22.6569 13.6569 24 12 24H4C2.34315 24 1 22.6569 1 21V11C1 9.34315 2.34315 8 4 8H5V7C5 5.34315 6.34315 4 8 4H16L16.1543 4.00391ZM4 10C3.44772 10 3 10.4477 3 11V21C3 21.5523 3.44772 22 4 22H12C12.5523 22 13 21.5523 13 21V11C13 10.4823 12.6067 10.0562 12.1025 10.0049L12 10H4ZM8 6C7.44772 6 7 6.44772 7 7V8H12L12.1543 8.00391C13.7394 8.08421 15 9.39489 15 11V18H16C16.5523 18 17 17.5523 17 17V7C17 6.48232 16.6067 6.05621 16.1025 6.00488L16 6H8Z" fill="currentColor"/>';
	$output .= '</svg></button></div>';
	return $output;
}
add_shortcode('copy_block', 'copy_block_shortcode');

// Шорткод для note-block (без изменений)
function note_block_shortcode($atts, $content = null)
{
	if (empty($content)) {
		return '';
	}
	return '<div class="note-block">' . do_shortcode($content) . '</div>';
}
add_shortcode('note_block', 'note_block_shortcode');

// Подключаем JavaScript для копирования
function enqueue_copy_block_script()
{
	wp_enqueue_script(
		'copy-block-script',
		get_template_directory_uri() . '/js/copy-block.js',
		array(),
		filemtime(get_template_directory() . '/js/copy-block.js'),
		true
	);
}
add_action('wp_enqueue_scripts', 'enqueue_copy_block_script');
?>