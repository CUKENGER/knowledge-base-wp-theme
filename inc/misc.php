<?php
// SVG
function tgx_mime_types($mimes)
{
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter('upload_mimes', 'tgx_mime_types');

// Excerpt
function tgx_custom_excerpt($excerpt)
{
	$excerpt = wp_strip_all_tags($excerpt);
	if (mb_strlen($excerpt) > 142) {
		$excerpt = mb_substr($excerpt, 0, 142) . '...';
	}
	return $excerpt;
}
add_filter('get_the_excerpt', 'tgx_custom_excerpt', 10, 1);

// Категория "Прочее"
add_action('init', function () {
	$category = get_term(1, 'category');
	if ($category && !is_wp_error($category)) {
		wp_update_term(1, 'category', [
			'name' => 'Прочее',
			'slug' => 'prochee'
		]);
	}
});

// AJAX-поиск
function tgx_search_posts()
{
	$query = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';
	if (empty($query)) {
		wp_send_json_error(['message' => 'Пустой запрос']);
		return;
	}
	add_filter('posts_where', function ($where, $wp_query) use ($query) {
		global $wpdb;
		$search_term = '%' . $wpdb->esc_like($query) . '%';
		$where .= " AND {$wpdb->posts}.post_title LIKE '$search_term'";
		return $where;
	}, 10, 2);
	$args = [
		'post_type' => 'post',
		'posts_per_page' => 5,
		'post_status' => 'publish',
		'suppress_filters' => false,
	];
	$search_query = new WP_Query($args);
	$results = [];
	if ($search_query->have_posts()) {
		while ($search_query->have_posts()) {
			$search_query->the_post();
			$results[] = [
				'title' => get_the_title(),
				'link' => get_permalink(),
			];
		}
	}
	wp_reset_postdata();
	remove_filter('posts_where', function () {}, 10, 2);
	wp_send_json_success($results);
}
add_action('wp_ajax_tgx_search_posts', 'tgx_search_posts');
add_action('wp_ajax_nopriv_tgx_search_posts', 'tgx_search_posts');

// Изображения
add_action('after_setup_theme', function () {
	add_image_size('post-cover', 360, 216, true);
	add_image_size('post-cover-2x', 720, 432, true);
	add_image_size('post-cover-mobile', 768, 432, true);
});
add_filter('max_srcset_image_width', function () {
	return 1920;
});
add_filter('jpeg_quality', function () {
	return 90;
});

function tgx_disable_category_pagination($query)
{
	if ($query->is_category() && $query->is_main_query()) {
		$query->set('posts_per_page', -1);
	}
}
add_action('pre_get_posts', 'tgx_disable_category_pagination');

function banner_shortcode($atts)
{
	$atts = shortcode_atts(array(
		'id' => '1',
		'date' => date(format: 'Y-m-d'),
		'idstate' => '',
	), $atts, 'banner_pulse');

	// Проверка: если idstate пустой, не отображаем баннер
	if (empty($atts['idstate'])) {
		return '';
	}

	$dimensions = [
		'1' => ['width' => 760, 'height' => 500],
		'2' => ['width' => 760, 'height' => 400]
	];

	$banner_id = esc_attr($atts['id']);
	$date = esc_attr($atts['date']);
	$idstate = esc_attr($atts['idstate']);
	$width = $dimensions[$banner_id]['width'] ?? 760;
	$height = $dimensions[$banner_id]['height'] ?? 500;

	// Пути к изображениям из медиабиблиотеки
	$base_path = get_template_directory_uri() . '/assets/';

	$utm_params = "utm_source=blog&utm_medium=entry&utm_campaign=banner&utm_content=$date&utm_term=$idstate";
	$url = "https://tgryx.ru/1002428166824?$utm_params";

	$html = '
    <div class="banner banner-' . $banner_id . '" role="banner">
        <picture>
            <source media="(max-width: 500px)" srcset="' . $base_path . 'banner-' . $banner_id . '_320.webp" sizes="100vw"/>
            <source media="(max-width: 600px)" srcset="' . $base_path . 'banner-' . $banner_id . '_620.webp" sizes="100vw"/>
            <source media="(min-width: 600px)" srcset="' . $base_path . 'banner-' . $banner_id . '.webp" sizes="800px"/>
            <img src="' . $base_path . 'banner-' . $banner_id . '.webp" alt="Узнай, как получить новых подписчиков в Telegram-канал" class="banner-image-' . $banner_id . '" width="' . $width . '" height="' . $height . '"/>
        </picture>
        <div class="banner-content">
            <p class="banner-title">Узнай, как получить новых подписчиков в Telegram-канал — с нуля за 7 дней.</p>
            <a href="' . esc_url($url) . '" class="banner-button" aria-label="Перейти в Telegram-канал TGX">Перейти в канал TGX</a>
        </div>
    </div>';

	return $html;
}
add_shortcode('banner_pulse', 'banner_shortcode');

// functions.php
function tgx_register_suggestions_settings() {
    for ($i = 1; $i <= 5; $i++) {
        register_setting('tgx_suggestions_group', "tgx_suggestion_post_$i", array(
            'sanitize_callback' => 'absint',
            'default' => 0,
        ));
    }

    add_settings_section(
        'tgx_suggestions_section',
        'Релевантные статьи для главной',
        '__return_empty_string',
        'tgx-suggestions'
    );

    for ($i = 1; $i <= 5; $i++) {
        add_settings_field(
            "tgx_suggestion_post_$i",
            "Статья $i",
            'tgx_suggestion_post_field_callback',
            'tgx-suggestions',
            'tgx_suggestions_section',
            array('index' => $i)
        );
    }
}
add_action('admin_init', 'tgx_register_suggestions_settings');

function tgx_suggestion_post_field_callback($args) {
    $index = $args['index'];
    $value = get_option("tgx_suggestion_post_$index", 0);
    $posts = get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ));
    ?>
    <select name="tgx_suggestion_post_<?php echo esc_attr($index); ?>">
        <option value="0">Выберите пост</option>
        <?php foreach ($posts as $post): ?>
            <option value="<?php echo esc_attr($post->ID); ?>" <?php selected($value, $post->ID); ?>>
                <?php echo esc_html($post->post_title); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

function tgx_add_suggestions_menu() {
    add_menu_page(
        'Релевантные статьи',
        'Релевантные статьи',
        'manage_options',
        'tgx-suggestions',
        'tgx_suggestions_page_callback',
        'dashicons-admin-links',
        80
    );
}
add_action('admin_menu', 'tgx_add_suggestions_menu');

function tgx_suggestions_page_callback() {
    ?>
    <div class="wrap">
        <h1>Релевантные статьи</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('tgx_suggestions_group');
            do_settings_sections('tgx-suggestions');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}


function tgx_remove_emoji($string) {
    // Удаляем эмодзи (Unicode-символы)
    $string = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $string); // Эмоции
    $string = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $string); // Символы и пиктограммы
    $string = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $string); // Транспорт и карты
    $string = preg_replace('/[\x{1F1E0}-\x{1F1FF}]/u', '', $string); // Флаги
    $string = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $string);   // Разные символы
    $string = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $string);   // Дингбаты
    $string = preg_replace('/[\x{1F900}-\x{1F9FF}]/u', '', $string); // Дополнения
    // Удаляем лишние пробелы
    return trim($string);
}