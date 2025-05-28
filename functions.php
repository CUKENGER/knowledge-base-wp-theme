<?php
// Подключение модулей
delete_transient('tgx_categories');
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/misc.php';

// Поддержка миниатюр
add_theme_support('post-thumbnails');

// Шорткод для copy-block
function copy_block_shortcode($atts, $content = null)
{
	$atts = shortcode_atts([
		'type' => 'info',
	], $atts, 'copy-block');

	if (empty($content)) {
		return '';
	}

	$block_id = uniqid('copy-block-');
	$display_content = wp_kses($content, [
		'b' => 'info',
		'strong' => [],
		'i' => [],
		'em' => [],
	]);
	$copy_content = wp_strip_all_tags($content);

	$valid_types = ['info', 'warning', 'error'];
	$type = in_array($atts['type'], $valid_types) ? $atts['type'] : 'info';

	$output = '<div class="copy-block copy-block--' . esc_attr($type) . '" id="' . esc_attr($block_id) . '" onclick="copyBlockText(\'' . esc_attr($block_id) . '\', \'' . esc_js($copy_content) . '\')">';
	$output .= '<div class="copy-block__content">' . $display_content . '</div>';
	$output .= '<button class="copy-button" aria-label="Копировать текст" onclick="copyBlockText(\'' . esc_attr($block_id) . '\', \'' . esc_js($copy_content) . '\')">';
	$output .= '<svg width="20" height="28" viewBox="0 0 20 28" fill="none" xmlns="http://www.w3.org/2000/svg">';
	$output .= '<path d="M16.1543 4.00391C17.7394 4.08421 19 5.39489 19 7V17C19 18.6569 17.6569 20 16 20H15V21C15 22.6569 13.6569 24 12 24H4C2.34315 24 1 22.6569 1 21V11C1 9.34315 2.34315 8 4 8H5V7C5 5.34315 6.34315 4 8 4H16L16.1543 4.00391ZM4 10C3.44772 10 3 10.4477 3 11V21C3 21.5523 3.44772 22 4 22H12C12.5523 22 13 21.5523 13 21V11C13 10.4823 12.6067 10.0562 12.1025 10.0049L12 10H4ZM8 6C7.44772 6 7 6.44772 7 7V8H12L12.1543 8.00391C13.7394 8.08421 15 9.39489 15 11V18H16C16.5523 18 17 17.5523 17 17V7C17 6.48232 16.6067 6.05621 16.1025 6.00488L16 6H8Z" fill="currentColor"/>';
	$output .= '</svg></button>';
	$output .= '</div>';

	return $output;
}
add_shortcode('copy-block', 'copy_block_shortcode');

// Шорткод для note-block
function tgx_note_block_shortcode($atts, $content = null)
{
	$atts = shortcode_atts([
		'type' => 'info',
		'link' => '',
		'link_text' => '',
	], $atts, 'note-block');

	if (empty($content)) {
		error_log('note-block: Пустой контент');
		return '';
	}

	$allowed_tags = [
		'b' => [],
		'strong' => [],
		'i' => [],
		'em' => [],
		'p' => [],
		'br' => [],
	];

	$content = wp_kses($content, $allowed_tags);
	error_log('note-block: Контент после wp_kses: ' . $content);

	if (!empty($atts['link']) && !empty($atts['link_text'])) {
		if (!filter_var($atts['link'], FILTER_VALIDATE_URL)) {
			error_log('note-block: Неверный URL: ' . $atts['link']);
			return '<div class="note-block note-block--error">Ошибка: Неверный URL.</div>';
		}
		$link_url = esc_url($atts['link']);
		$link_text = trim($atts['link_text']); // Убираем пробелы
		error_log('note-block: link_text: ' . $link_text);

		$link_html = sprintf('<a href="%s" target="_blank" rel="noopener">%s</a>', $link_url, esc_html($link_text));

		// Нормализуем контент и link_text
		$normalized_content = str_replace(["\r", "\n", "\t"], ' ', $content);
		$normalized_link_text = str_replace(["\r", "\n", "\t"], ' ', $link_text);

		// Пробуем замену с учетом @
		$pattern = '/\b' . preg_quote($normalized_link_text, '/') . '\b/i';
		$new_content = preg_replace($pattern, $link_html, $normalized_content, 1);

		if ($new_content === $normalized_content) {
			error_log("note-block: Не удалось заменить '$normalized_link_text' в контенте: '$normalized_content'");
			// Пробуем без @, если есть
			if (strpos($link_text, '@') === 0) {
				$link_text_no_at = ltrim($link_text, '@');
				$pattern_no_at = '/\b' . preg_quote($link_text_no_at, '/') . '\b/i';
				$link_html_no_at = sprintf('<a href="%s" target="_blank" rel="noopener">%s</a>', $link_url, esc_html($link_text));
				$new_content = preg_replace($pattern_no_at, $link_html_no_at, $normalized_content, 1);
				if ($new_content !== $normalized_content) {
					$content = $new_content;
					error_log("note-block: Успешно заменили '$link_text_no_at' на ссылку");
				} else {
					error_log("note-block: Не удалось заменить даже '$link_text_no_at' в контенте: '$normalized_content'");
				}
			}
		} else {
			$content = $new_content;
			error_log("note-block: Успешно заменили '$normalized_link_text' на ссылку");
		}
	} else {
		error_log('note-block: Отсутствует link или link_text');
	}

	$valid_types = ['info', 'warning', 'error'];
	$type = in_array($atts['type'], $valid_types) ? $atts['type'] : 'info';

	return '<div class="note-block note-block--' . esc_attr($type) . '">' . $content . '</div>';
}
add_shortcode('note-block', 'tgx_note_block_shortcode');

?>