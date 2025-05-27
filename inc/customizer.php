<?php
function tgx_theme_customizer($wp_customize)
{
	// Секция SEO
	$wp_customize->add_section('tgx_seo_section', [
		'title' => __('SEO настройки', 'tgx-theme'),
		'priority' => 29,
	]);
	$wp_customize->add_setting('tgx_site_description', [
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('tgx_site_description', [
		'label' => __('Описание сайта (meta description)', 'tgx-theme'),
		'section' => 'tgx_seo_section',
		'type' => 'textarea',
	]);
	$wp_customize->add_setting('tgx_site_keywords', [
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('tgx_site_keywords', [
		'label' => __('Ключевые слова (meta keywords)', 'tgx-theme'),
		'section' => 'tgx_seo_section',
		'type' => 'text',
	]);



	// Добавляем секцию
	$wp_customize->add_section('tgx_header_links', array(
		'title' => __('Ссылки в шапке', 'tgx'),
		'priority' => 120,
	));

	// Настройка для ссылки кнопки "Поддержка"
	$wp_customize->add_setting('tgx_support_link', array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw', // Очищаем ссылку
	));

	$wp_customize->add_control('tgx_support_link', array(
		'label' => __('Ссылка для кнопки "Поддержка"', 'tgx'),
		'section' => 'tgx_header_links',
		'type' => 'url',
	));

	// Настройка для ссылки кнопки "Сайт сервиса"
	$wp_customize->add_setting('tgx_site_link', array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
	));

	$wp_customize->add_control('tgx_site_link', array(
		'label' => __('Ссылка для кнопки "Сайт сервиса"', 'tgx'),
		'section' => 'tgx_header_links',
		'type' => 'url',
	));
}
add_action('customize_register', 'tgx_theme_customizer');





// Регистрируем настройки
function tgx_register_promo_settings() {
    // Регистрируем опции
    register_setting('tgx_promo_group', 'tgx_promo_title', array(
        'sanitize_callback' => 'sanitize_text_field',
        'default' => 'Пройди Telegram X',
    ));
    register_setting('tgx_promo_group', 'tgx_promo_description', array(
        'sanitize_callback' => 'sanitize_textarea_field',
        'default' => 'Это воркшоп, где ты запустишь канал и получишь подписчиков без слива бюджета!',
    ));
    register_setting('tgx_promo_group', 'tgx_promo_link', array(
        'sanitize_callback' => 'esc_url_raw',
        'default' => 'https://example.com/workshop',
    ));

    // Добавляем секцию
    add_settings_section(
        'tgx_promo_section',
        'Настройки промо-карточки',
        '__return_empty_string',
        'tgx-promo-settings'
    );

    // Поле для заголовка
    add_settings_field(
        'tgx_promo_title',
        'Заголовок',
        'tgx_promo_title_field_callback',
        'tgx-promo-settings',
        'tgx_promo_section'
    );

    // Поле для описания
    add_settings_field(
        'tgx_promo_description',
        'Описание',
        'tgx_promo_description_field_callback',
        'tgx-promo-settings',
        'tgx_promo_section'
    );

    // Поле для ссылки
    add_settings_field(
        'tgx_promo_link',
        'Ссылка',
        'tgx_promo_link_field_callback',
        'tgx-promo-settings',
        'tgx_promo_section'
    );
}
add_action('admin_init', 'tgx_register_promo_settings');

// Callback для поля заголовка
function tgx_promo_title_field_callback() {
    $value = get_option('tgx_promo_title', 'Пройди Telegram X');
    ?>
    <input type="text" name="tgx_promo_title" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <?php
}

// Callback для поля описания
function tgx_promo_description_field_callback() {
    $value = get_option('tgx_promo_description', 'Это воркшоп, где ты запустишь канал и получишь подписчиков без слива бюджета!');
    ?>
    <textarea name="tgx_promo_description" rows="5" class="large-text"><?php echo esc_textarea($value); ?></textarea>
    <?php
}

// Callback для поля ссылки
function tgx_promo_link_field_callback() {
    $value = get_option('tgx_promo_link', 'https://example.com/workshop');
    ?>
    <input type="url" name="tgx_promo_link" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <?php
}

// Добавляем страницу настроек в меню
function tgx_add_promo_settings_menu() {
    add_menu_page(
        'Настройки промо-карточки',
        'Промо-карточка',
        'manage_options',
        'tgx-promo-settings',
        'tgx_promo_settings_page_callback',
        'dashicons-megaphone',
        81
    );
}
add_action('admin_menu', 'tgx_add_promo_settings_menu');

// Callback для страницы настроек
function tgx_promo_settings_page_callback() {
    ?>
    <div class="wrap">
        <h1>Настройки промо-карточки</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('tgx_promo_group');
            do_settings_sections('tgx-promo-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}






// Регистрируем настройки
function tgx_register_footer_settings() {
    // Ссылки Telegram
    $telegram_links = [
        'official_channel' => 'Официальный канал',
        'promotion_channel' => 'Канал про продвижение',
        'support_chat' => 'Чат поддержки',
    ];

    foreach ($telegram_links as $key => $label) {
        register_setting('tgx_footer_group', "tgx_footer_link_$key", [
            'sanitize_callback' => 'esc_url_raw',
            'default' => '#',
        ]);
    }

    // Политика конфиденциальности
    register_setting('tgx_footer_group', 'tgx_footer_privacy_text', [
        'sanitize_callback' => 'sanitize_text_field',
        'default' => 'Политика конфиденциальности',
    ]);
    register_setting('tgx_footer_group', 'tgx_footer_privacy_link', [
        'sanitize_callback' => 'esc_url_raw',
        'default' => '#',
    ]);
    register_setting('tgx_footer_group', 'tgx_footer_privacy_enabled', [
        'sanitize_callback' => 'absint',
        'default' => 1,
    ]);

    // Ссылка для кнопки "Задать вопрос"
    register_setting('tgx_footer_group', 'tgx_footer_support_link', [
        'sanitize_callback' => 'esc_url_raw',
        'default' => '#',
    ]);

    // Секция для ссылок Telegram
    add_settings_section(
        'tgx_footer_links_section',
        'Ссылки Telegram',
        '__return_empty_string',
        'tgx-footer-settings'
    );

    // Секция для политики конфиденциальности
    add_settings_section(
        'tgx_footer_privacy_section',
        'Политика конфиденциальности',
        '__return_empty_string',
        'tgx-footer-settings'
    );

    // Новая секция для кнопки поддержки
    add_settings_section(
        'tgx_footer_support_section',
        'Кнопка поддержки',
        '__return_empty_string',
        'tgx-footer-settings'
    );

    // Поля для ссылок Telegram
    foreach ($telegram_links as $key => $label) {
        add_settings_field(
            "tgx_footer_link_$key",
            $label,
            'tgx_footer_link_field_callback',
            'tgx-footer-settings',
            'tgx_footer_links_section',
            ['key' => $key]
        );
    }

    // Поля для политики конфиденциальности
    add_settings_field(
        'tgx_footer_privacy_enabled',
        'Показывать политику',
        'tgx_footer_privacy_enabled_field_callback',
        'tgx-footer-settings',
        'tgx_footer_privacy_section'
    );
    add_settings_field(
        'tgx_footer_privacy_text',
        'Текст ссылки',
        'tgx_footer_privacy_text_field_callback',
        'tgx-footer-settings',
        'tgx_footer_privacy_section'
    );
    add_settings_field(
        'tgx_footer_privacy_link',
        'Ссылка',
        'tgx_footer_privacy_link_field_callback',
        'tgx-footer-settings',
        'tgx_footer_privacy_section'
    );

    // Поле для ссылки кнопки "Задать вопрос"
    add_settings_field(
        'tgx_footer_support_link',
        'Ссылка для кнопки "Задать вопрос"',
        'tgx_footer_support_link_field_callback',
        'tgx-footer-settings',
        'tgx_footer_support_section'
    );
}
add_action('admin_init', 'tgx_register_footer_settings');

// Callback для полей ссылок Telegram
function tgx_footer_link_field_callback($args) {
    $key = $args['key'];
    $value = get_option("tgx_footer_link_$key", '#');
    ?>
    <input type="url" name="tgx_footer_link_<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <?php
}

// Callback для включения/отключения политики
function tgx_footer_privacy_enabled_field_callback() {
    $value = get_option('tgx_footer_privacy_enabled', 1);
    ?>
    <input type="checkbox" name="tgx_footer_privacy_enabled" value="1" <?php checked($value, 1); ?>>
    <label>Показывать ссылку на политику конфиденциальности</label>
    <?php
}

// Callback для текста политики
function tgx_footer_privacy_text_field_callback() {
    $value = get_option('tgx_footer_privacy_text', 'Политика конфиденциальности');
    ?>
    <input type="text" name="tgx_footer_privacy_text" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <?php
}

// Callback для ссылки политики
function tgx_footer_privacy_link_field_callback() {
    $value = get_option('tgx_footer_privacy_link', '#');
    ?>
    <input type="url" name="tgx_footer_privacy_link" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <?php
}

// Callback для ссылки кнопки "Задать вопрос"
function tgx_footer_support_link_field_callback() {
    $value = get_option('tgx_footer_support_link', '#');
    ?>
    <input type="url" name="tgx_footer_support_link" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <?php
}

// Добавляем страницу настроек в меню
function tgx_add_footer_settings_menu() {
    add_menu_page(
        'Настройки футера',
        'Футер',
        'manage_options',
        'tgx-footer-settings',
        'tgx_footer_settings_page_callback',
        'dashicons-admin-generic',
        82
    );
}
add_action('admin_menu', 'tgx_add_footer_settings_menu');

// Callback для страницы настроек
function tgx_footer_settings_page_callback() {
    ?>
    <div class="wrap">
        <h1>Настройки футера</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('tgx_footer_group');
            do_settings_sections('tgx-footer-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}