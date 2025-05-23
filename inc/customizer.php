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

	// Хедер
	$wp_customize->add_section('tgx_header_section', [
		'title' => __('Настройки хедера', 'tgx-theme'),
		'priority' => 30,
	]);
	$wp_customize->add_setting('tgx_logo', [
		'default' => get_template_directory_uri() . '/assets/logo.svg',
		'sanitize_callback' => 'esc_url_raw',
	]);
	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'tgx_logo', [
		'label' => __('Логотип (SVG или изображение)', 'tgx-theme'),
		'section' => 'tgx_header_section',
		'settings' => 'tgx_logo',
		'extensions' => ['svg', 'png', 'jpg'],
	]));
	$wp_customize->add_setting('tgx_search_icon', [
		'default' => get_template_directory_uri() . '/assets/search.svg',
		'sanitize_callback' => 'esc_url_raw',
	]);
	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'tgx_search_icon', [
		'label' => __('Иконка поиска (SVG)', 'tgx-theme'),
		'section' => 'tgx_header_section',
		'settings' => 'tgx_search_icon',
		'extensions' => ['svg'],
	]));
	$wp_customize->add_setting('tgx_telegram_icon', [
		'default' => get_template_directory_uri() . '/assets/tg-button.svg',
		'sanitize_callback' => 'esc_url_raw',
	]);
	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'tgx_telegram_icon', [
		'label' => __('Иконка Telegram (SVG)', 'tgx-theme'),
		'section' => 'tgx_header_section',
		'settings' => 'tgx_telegram_icon',
		'extensions' => ['svg'],
	]));

	// Футер
	$wp_customize->add_section('tgx_footer_section', [
		'title' => __('Настройки футера', 'tgx-theme'),
		'priority' => 31,
	]);
	$wp_customize->add_setting('tgx_footer_copyright', [
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('tgx_footer_copyright', [
		'label' => __('Текст копирайта', 'tgx-theme'),
		'section' => 'tgx_footer_section',
		'type' => 'text',
	]);
	$wp_customize->add_setting('tgx_privacy_policy', [
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('tgx_privacy_policy', [
		'label' => __('Текст политики конфиденциальности', 'tgx-theme'),
		'section' => 'tgx_footer_section',
		'type' => 'text',
	]);
	$wp_customize->add_setting('tgx_privacy_policy_link', [
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
	]);
	$wp_customize->add_control('tgx_privacy_policy_link', [
		'label' => __('Ссылка для политики конфиденциальности', 'tgx-theme'),
		'section' => 'tgx_footer_section',
		'type' => 'url',
	]);
	$wp_customize->add_setting('tgx_public_offer', [
		'default' => '',
		'sanitize_callback' => 'sanitize_text_field',
	]);
	$wp_customize->add_control('tgx_public_offer', [
		'label' => __('Текст публичной оферты', 'tgx-theme'),
		'section' => 'tgx_footer_section',
		'type' => 'text',
	]);
	$wp_customize->add_setting('tgx_public_offer_link', [
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
	]);
	$wp_customize->add_control('tgx_public_offer_link', [
		'label' => __('Ссылка для публичной оферты', 'tgx-theme'),
		'section' => 'tgx_footer_section',
		'type' => 'url',
	]);
}
add_action('customize_register', 'tgx_theme_customizer');