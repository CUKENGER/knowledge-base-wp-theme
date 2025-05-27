<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  $site_name = get_bloginfo('name');
  $site_description = get_theme_mod('tgx_site_description', get_bloginfo('description'));
  $site_keywords = get_theme_mod('tgx_site_keywords');

  if (is_front_page() || is_home()) {
    $title = $site_name . ' | ' . $site_description;
    $description = $site_description;
    $keywords = $site_keywords;
  } elseif (is_single()) {
    $custom_title = get_post_meta(get_the_ID(), '_tgx_seo_title', true);
    $custom_description = get_post_meta(get_the_ID(), '_tgx_seo_description', true);
    $custom_keywords = get_post_meta(get_the_ID(), '_tgx_seo_keywords', true);
    $title = $custom_title ?: get_the_title() . ' | ' . $site_name;
    $description = $custom_description ?: wp_trim_words(get_the_excerpt(), 30, '...');
    $keywords = $custom_keywords ?: implode(', ', wp_list_pluck(get_the_category(), 'name'));
  } elseif (is_category()) {
    $category = get_queried_object();
    $category_description = get_term_meta($category->term_id, '_tgx_category_description', true);
    $category_keywords = get_term_meta($category->term_id, '_tgx_category_keywords', true);
    $title = $category->name . ' | ' . $site_name;
    $description = $category_description ?: $site_description;
    $keywords = $category_keywords ?: $site_keywords;
  } else {
    $title = wp_title('', false) . ' | ' . $site_name;
    $description = $site_description;
    $keywords = $site_keywords;
  }
  ?>
  <title><?php echo esc_html($title); ?></title>
  <meta name="description" content="<?php echo esc_attr($description); ?>">
  <meta name="keywords" content="<?php echo esc_attr($keywords); ?>">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <header>
    <div class="container">
      <div class="header-container">
        <div class='logo-container'>
          <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/logo.svg'); ?>" alt="Логотип сайта" />
          </a>
        </div>
        <div class='header-btn__container'>
          <?php
          $support_link = get_theme_mod('tgx_support_link', '');
          $site_link = get_theme_mod('tgx_site_link', '');
          ?>
          <a href="<?php echo esc_url($support_link); ?>" class="header-btn header-btn--support" <?php echo $support_link ? '' : 'disabled'; ?>>
            Поддержка
          </a>
          <a href="<?php echo esc_url($site_link); ?>" class="header-btn header-btn--site" <?php echo $site_link ? '' : 'disabled'; ?>>
            Сайт сервиса
          </a>
        </div>
        <div class="header-menu__container">
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/menu-button.svg'); ?>" alt="Меню"
            class="menu-icon">
          <svg class="close-icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path d="M6 18L18 6M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
          <div class="header-menu__dropdown">
            <a href="<?php echo esc_url($support_link); ?>" class="header-menu__link" <?php echo $support_link ? '' : 'disabled'; ?>>Поддержка</a>
            <a href="<?php echo esc_url($site_link); ?>" class="header-menu__link" <?php echo $site_link ? '' : 'disabled'; ?>>Сайт сервиса</a>
            <div class='header-menu__divide'></div>
            <?php
            $telegram_links = [
                'official_channel' => 'Официальный канал',
                'promotion_channel' => 'Канал про продвижение',
                'support_chat' => 'Чат поддержки',
            ];
            foreach ($telegram_links as $key => $label):
                $link = get_option("tgx_footer_link_$key", '#');
                ?>
                <a href="<?php echo esc_url($link); ?>" class="header-menu__link" <?php echo $link !== '#' ? '' : 'disabled'; ?>>
                    <?php echo esc_html($label); ?>
                </a>
            <?php endforeach; ?>
          </div>
          <div class="header-menu__overlay"></div>
        </div>
      </div>
    </div>
  </header>