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
	// Настраиваемые параметры
	$atts = shortcode_atts([
		'type' => 'info', // Тип блока: info, warning, error
	], $atts, 'copy-block');

	// Проверяем, есть ли содержимое
	if (empty($content)) {
		return '';
	}

	// Уникальный ID для блока
	$block_id = uniqid('copy-block-');

	// Экранируем содержимое для отображения
	$display_content = wp_kses($content, [
		'b' => [],
		'strong' => [],
		'i' => [],
		'em' => [],
	]);

	// Подготавливаем текст для копирования (без HTML)
	$copy_content = wp_strip_all_tags($content);

	// Формируем HTML
	$output = '<div class="copy-block copy-block--' . esc_attr($atts['type']) . '" id="' . esc_attr($block_id) . '">';
	$output .= '<div class="copy-block__content">' . $display_content . '</div>';
	$output .= '<button class="copy-button" aria-label="Копировать текст" onclick="copyBlockText(\'' . esc_attr($block_id) . '\', \'' . esc_js($copy_content) . '\')">';
	$output .= '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">';
	$output .= '<path d="M13.75 2.5H6.25C4.86929 2.5 3.75 3.61929 3.75 5V15C3.75 16.3807 4.86929 17.5 6.25 17.5H13.75C15.1307 17.5 16.25 16.3807 16.25 15V5C16.25 3.61929 15.1307 2.5 13.75 2.5ZM6.25 3.75H13.75C14.4404 3.75 15 4.30964 15 5V15C15 15.6904 14.4404 16.25 13.75 16.25H6.25C5.55964 16.25 5 15.6904 5 15V5C5 4.30964 5.55964 3.75 6.25 3.75Z" fill="currentColor"/>';
	$output .= '<path d="M7.5 6.25H12.5C12.9142 6.25 13.25 6.58579 13.25 7V13.75C13.25 14.1642 12.9142 14.5 12.5 14.5H7.5C7.08579 14.5 6.75 14.1642 6.75 13.75V7C6.75 6.58579 7.08579 6.25 7.5 6.25Z" fill="currentColor"/>';
	$output .= '</svg></button>';
	$output .= '<span class="copy-block__notification" aria-live="polite">Текст скопирован</span>';
	$output .= '</div>';

	return $output;
}
add_shortcode('copy-block', 'copy_block_shortcode');

// Подключаем JavaScript для копирования
function enqueue_copy_block_script()
{
	wp_enqueue_script(
		'copy-block-script',
		get_template_directory_uri() . '/js/copy-block.js',
		[], // Зависимости
		filemtime(get_template_directory() . '/js/copy-block.js') ?: '1.0', // Версия
		true // В футере
	);
}
add_action('wp_enqueue_scripts', 'enqueue_copy_block_script');

// Шорткод для note-block (без изменений)
function tgx_note_block_shortcode($atts, $content = null)
{
	// Настраиваемые параметры
	$atts = shortcode_atts([
		'type' => 'info', // Тип заметки: info, warning, error
		'link' => '', // URL ссылки
		'link_text' => '', // Текст ссылки
		'link_word' => '', // Слово в содержимом, которое станет ссылкой
	], $atts, 'note-block');

	// Проверяем, есть ли содержимое
	if (empty($content)) {
		return '';
	}

	// Разрешаем безопасные HTML-теги в содержимом (исключая <a>)
	$allowed_tags = [
		'b' => [],
		'strong' => [],
		'i' => [],
		'em' => [],
	];

	// Экранируем содержимое
	$content = wp_kses($content, $allowed_tags);

	// Формируем ссылку, если указаны link и link_text
	$link_html = '';
	if (!empty($atts['link']) && !empty($atts['link_text'])) {
		// Проверяем валидность URL
		if (!filter_var($atts['link'], FILTER_VALIDATE_URL)) {
			return '<div class="note-block note-block--error">Ошибка: Неверный URL в шорткоде.</div>';
		}
		$link_url = esc_url($atts['link']); // Экранируем URL
		$link_text = esc_html($atts['link_text']); // Экранируем текст ссылки
		$link_html = sprintf(
			'<a href="%s" target="_blank" rel="noopener">%s</a>',
			$link_url,
			$link_text
		);
	}

	// Заменяем указанное слово (link_word) на ссылку, если задано
	if ($link_html && !empty($atts['link_word'])) {
		$link_word = preg_quote(esc_attr($atts['link_word']), '/'); // Экранируем для regex
		$content = preg_replace("/\b$link_word\b/", $link_html, $content, 1);
	} elseif ($link_html) {
		// Если link_word не указано, заменяем "ссылка" или "ссылке"
		$content = preg_replace('/\b(ссылке|ссылка)\b/i', $link_html, $content, 1);
	}

	// Экранируем тип заметки
	$type = esc_attr($atts['type']);

	// Возвращаем HTML
	return '<div class="note-block note-block--' . $type . '">' . $content . '</div>';
}
add_shortcode('note-block', 'tgx_note_block_shortcode');

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