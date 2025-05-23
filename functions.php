<?php
// Подключение модулей
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/misc.php';

// Поддержка миниатюр (оставляем здесь, так как это базовая настройка темы)
add_theme_support('post-thumbnails');