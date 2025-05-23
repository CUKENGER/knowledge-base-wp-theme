<section class="categories">
  <?php
  $categories = get_categories([
    'hide_empty' => true,
    'orderby' => 'term_order',
    'order' => 'ASC',
  ]);
  $current_cat = get_queried_object();
  $category_icons = tgh_get_category_icons();

  // Дебаг
  error_log('Categories: ' . count($categories) . ', Slugs: ' . implode(', ', wp_list_pluck($categories, 'slug')));
  echo '<!-- Categories: ' . count($categories) . ', Slugs: ' . esc_html(implode(', ', wp_list_pluck($categories, 'slug'))) . ' -->';

  foreach ($categories as $category_obj) {
    $args = [
      'posts_per_page' => 1,
      'category_name' => $category_obj->slug,
      'post_status' => 'publish',
    ];
    $check_query = new WP_Query($args);
    $post_count = $check_query->post_count;
    wp_reset_postdata();

    if ($post_count === 0) {
      error_log('Skipped category: ' . $category_obj->name . ', Slug: ' . $category_obj->slug);
      echo '<!-- Skipped category: ' . esc_html($category_obj->name) . ', Slug: ' . esc_html($category_obj->slug) . ' -->';
      continue;
    }

    $icon_url = isset($category_icons[$category_obj->slug]) ? $category_icons[$category_obj->slug] : $category_icons['prochee'];
    $icon_path = str_replace(get_site_url(), ABSPATH, $icon_url);
    $is_active = ($current_cat && $current_cat->term_id == $category_obj->term_id) ? ' active' : '';
    $svg_content = ''; // По умолчанию пусто
  
    error_log('Category: ' . $category_obj->name . ', Slug: ' . $category_obj->slug . ', Icon: ' . $icon_url);
    echo '<!-- Category: ' . esc_html($category_obj->name) . ', Slug: ' . esc_html($category_obj->slug) . ', Icon: ' . esc_url($icon_url) . ' -->';

    if (file_exists($icon_path)) {
      $svg_content = file_get_contents($icon_path);
      if ($svg_content && stripos($svg_content, '<svg') !== false) {
        error_log('SVG for ' . $category_obj->name . ': loaded');
      } else {
        error_log('Invalid SVG for category: ' . $category_obj->name);
        $svg_content = ''; // Не показываем иконку
      }
    } else {
      error_log('SVG not found: ' . $icon_path);
      $svg_content = ''; // Не показываем иконку
    }
    ?>
    <a href="<?php echo esc_url(get_category_link($category_obj->term_id)); ?>"
      class="category-container<?php echo $is_active; ?>">
      <?php if ($svg_content): ?>
        <?php echo $svg_content; ?>
      <?php endif; ?>
      <div class="category-name"><?php echo esc_html($category_obj->name); ?></div>
    </a>
    <?php
  }
  ?>
</section>