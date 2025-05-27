<?php
// Функция для удаления эмодзи из строки
function remove_emoji($string)
{
  // Регулярное выражение для удаления эмодзи (Unicode диапазоны)
  $pattern = '/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F1E0}-\x{1F1FF}]/u';
  return preg_replace($pattern, '', $string);
}
?>

<?php get_header(); ?>
<main>
  <div class="overlay" aria-hidden="true"></div>

  <!-- Вставка page-header.php -->
  <?php
  $current_post_id = get_queried_object_id();
  $current_category_id = is_category() ? get_queried_object_id() : 0;
  if (is_single()) {
    $post_categories = wp_get_post_categories($current_post_id, ['fields' => 'ids']);
    $active_category_id = !empty($post_categories) ? $post_categories[0] : 0;
  } else {
    $active_category_id = $current_category_id;
  }
  if (WP_DEBUG) {
    error_log('Page Header: Queried ID ' . $current_post_id . ', Title: ' . get_the_title($current_post_id));
  }
  ?>
  <div class="overlay" aria-hidden="true"></div>
  <div class='page-header__contents-menu' id="contents-menu">
    <div class='page-header__contents-list'>
      <?php
      $categories = get_transient('tgx_categories');
      if (false === $categories) {
        $categories = get_categories(['hide_empty' => true]);
        set_transient('tgx_categories', $categories, HOUR_IN_SECONDS);
      }
      global $post;
      $tmp_post = $post;
      foreach ($categories as $category):
        $post_count = $category->count;
        $is_active = ($category->term_id == $active_category_id) ? ' is-active' : '';
        $display = ($category->term_id == $active_category_id) ? 'flex' : 'none';
        ?>
        <button class="page-header__category-title<?php echo esc_attr($is_active); ?>"
          data-category-id="<?php echo esc_attr($category->term_id); ?>">
          <p class="page-header__category-content">
            <?php echo esc_html($category->name); ?>
            <span class="page-header__category-count"><?php echo esc_html($post_count); ?></span>
          </p>
          <svg class="page-header__category-arrow" width="16" height="8" viewBox="0 0 16 8" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path d="M3 2L8 6L13 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
        </button>
        <div class="page-header__post-list" data-category-id="<?php echo esc_attr($category->term_id); ?>"
          style="display: <?php echo esc_attr($display); ?>;">
          <?php
          $posts = get_posts([
            'category' => $category->term_id,
            'numberposts' => 10,
          ]);
          foreach ($posts as $p):
            setup_postdata($p);
            $is_post_active = ($p->ID == $current_post_id) ? ' active' : '';
            if (WP_DEBUG) {
              error_log('Page Header Post: ID ' . $p->ID . ', Title: ' . get_the_title($p->ID) . ', URL: ' . get_permalink($p->ID));
            }
            ?>
            <a href="<?php echo esc_url(get_permalink($p->ID)); ?>"
              class="page-header__post-item<?php echo esc_attr($is_post_active); ?>">
              <?php echo esc_html(get_the_title($p->ID)); ?>
              <svg class="page-header__post-icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
                <use href="#chevron-icon"></use>
              </svg>
            </a>
          <?php endforeach; ?>
          <?php wp_reset_postdata(); ?>
        </div>
      <?php endforeach; ?>
      <?php setup_postdata($tmp_post); ?>
    </div>
    <div class='contents-menu__btn-container'>
      <button class="contents-menu__btn">Закрыть</button>
    </div>
  </div>
  <section class='page-header--tablet sidebar__page-header'>
    <div class='page-header__wrapper'>
      <button class='page-header__contents-btn' aria-expanded="false" aria-controls="contents-menu">Содержание</button>
      <div class='page-header__input-container'>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M10.5 3C14.6421 3 18 6.35786 18 10.5C18 12.2105 17.4259 13.7861 16.4619 15.0479L20.707 19.293L20.7754 19.3691C21.0957 19.7619 21.0731 20.3409 20.707 20.707C20.3409 21.0731 19.7619 21.0957 19.3691 20.7754L19.293 20.707L15.0479 16.4619C13.7861 17.4259 12.2105 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3ZM10.5 5C7.46243 5 5 7.46243 5 10.5C5 13.5376 7.46243 16 10.5 16C13.5376 16 16 13.5376 16 10.5C16 7.46243 13.5376 5 10.5 5Z"
            fill="#ABB0BA" />
        </svg>
        <input class='page-header__input' type="text" placeholder='Какой у вас вопрос?'>
        <button class='page-header__input-clear' style='display: none;'>
          <svg class="page-header__input-clear__icon" width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle cx="12" cy="12" r="10" fill="currentColor" />
            <path d="M9 15L15 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M15 15L9 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
        <div class='page-header__search-results'></div>
      </div>
    </div>
  </section>
  <svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;">
    <defs>
      <symbol id="chevron-icon" viewBox="0 0 8 16">
        <path d="M2 3L6 8L2 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          fill="none" />
      </symbol>
    </defs>
  </svg>
  <!-- Конец page-header.php -->

  <div class="container">
    <div class="category-page__container">
      <div class="category-page__content">
        <!-- Вставка breadcrumbs.php -->
        <section class='common-breadcrumbs'>
          <a href="<?php echo esc_url(home_url('/')); ?>" class="common-breadcrumbs-link">База знаний</a>
          <span class="common-breadcrumbs-divider">/</span>
          <?php
          if (WP_DEBUG) {
            error_log('Breadcrumbs: is_category=' . is_category() . ', is_single=' . is_single() . ', is_page=' . is_page() . ', Post ID=' . $current_post_id . ', Categories=' . print_r(get_the_category($current_post_id), true));
          }
          if (is_category()) {
            $category = get_queried_object();
            if ($category) {
              echo '<span>' . esc_html($category->name) . '</span>';
            }
          } elseif (is_single()) {
            $categories = get_the_category($current_post_id);
            $main_category = !empty($categories) ? $categories[0] : null;
            if ($main_category) {
              echo '<a href="' . esc_url(get_category_link($main_category->term_id)) . '" class="common-breadcrumbs-link">' . esc_html($main_category->name) . '</a>';
              echo '<span class="common-breadcrumbs-divider">/</span>';
            } else {
              echo '<span>Без категории</span>';
              echo '<span class="common-breadcrumbs-divider">/</span>';
            }
            // Очищаем заголовок от эмодзи перед выводом
            $clean_title = remove_emoji(get_the_title($current_post_id));
            echo '<span>' . esc_html($clean_title) . '</span>';
          } else {
            $clean_title = remove_emoji(get_the_title($current_post_id));
            echo '<span>' . esc_html($clean_title) . '</span>';
          }
          ?>
        </section>
        <!-- Конец breadcrumbs.php -->

        <article class="single-post">
          <?php
          if (WP_DEBUG) {
            error_log('Single Post Queried: ID ' . $current_post_id . ', Title: ' . get_the_title($current_post_id));
          }
          $post = get_post($current_post_id);
          if ($post):
            if (WP_DEBUG) {
              error_log('Single Post: ID ' . $post->ID . ', Title: ' . get_the_title($post->ID));
            }
            ?>
            <h1 class="single-post-title"><?php echo esc_html(get_the_title($current_post_id)); ?></h1>
            <div class="post-content">
              <?php echo apply_filters('the_content', get_post_field('post_content', $current_post_id)); ?>
              <?php
              if (WP_DEBUG) {
                error_log('After Content: ID ' . $current_post_id . ', Title: ' . get_the_title($current_post_id));
              }
              // echo do_shortcode('[banner_pulse]');
              ?>
            </div>
            <?php if (get_field('external_source', $current_post_id)): ?>
              <div class="external-links">
                <p>Источник: <a href="<?php echo esc_url(get_field('external_source', $current_post_id)); ?>"
                    target="_blank" class="source-link">Перейти</a></p>
              </div>
            <?php endif; ?>
          <?php else: ?>
            <p>Пост не найден.</p>
          <?php endif; ?>
          <?php wp_reset_postdata(); ?>
        </article>
        <div class="single__promo-card--tablet">
          <?php get_template_part('template-parts/promo-card'); ?>
        </div>
      </div>

      <!-- Вставка sidebar -->
      <?php
      if (is_single() || is_category()):
        $current_post_id = get_queried_object_id();
        $active_category_id = is_category() ? $current_post_id : (wp_get_post_categories($current_post_id, ['fields' => 'ids'])[0] ?? 0);
        ?>
        <div class='single__sidebar-container'>
          <section class="sidebar single__sidebar">
            <div class="sidebar__search-container">
              <div class="sidebar__search-wrapper">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/search-icon.svg'); ?>" alt="Поиск"
                  aria-hidden="true" />
                <input type="text" class="sidebar__search-input" placeholder="Какой у вас вопрос?">
                <svg class="sidebar__search-clear" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <circle cx="12" cy="12" r="10" fill="currentColor" />
                  <path d="M9 15L15 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M15 15L9 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </div>
              <div class="sidebar__search-results"></div>
            </div>
            <div class="sidebar__categories">
              <?php
              $categories = get_transient('tgx_categories') ?: get_categories(['hide_empty' => true]);
              if ($categories && !is_wp_error($categories)):
                set_transient('tgx_categories', $categories, HOUR_IN_SECONDS);
                global $post;
                $tmp_post = $post;
                foreach ($categories as $category):
                  $is_active = $category->term_id === $active_category_id ? ' is-active' : '';
                  $is_post_list_active = $category->term_id === $active_category_id ? ' active' : '';
                  ?>
                  <button class="sidebar__category-title<?php echo esc_attr($is_active); ?>"
                    data-category-id="<?php echo esc_attr($category->term_id); ?>" type="button"
                    aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>">
                    <span class="sidebar__category-content">
                      <?php echo esc_html($category->name); ?>
                      <span class="sidebar__category-count"><?php echo esc_html($category->count); ?></span>
                    </span>
                    <svg class="sidebar__category-arrow" width="16" height="8" viewBox="0 0 16 8" fill="none"
                      xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                      <path d="M3 2L8 6L13 2" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    </svg>
                  </button>
                  <div class="sidebar__post-list<?php echo esc_attr($is_post_list_active); ?>"
                    data-category-id="<?php echo esc_attr($category->term_id); ?>">
                    <?php
                    $posts = get_posts([
                      'category' => $category->term_id,
                      'numberposts' => 10,
                      'post_status' => 'publish',
                    ]);
                    if (empty($posts)):
                      echo '<p class="sidebar__no-posts">Нет постов в этой категории.</p>';
                    else:
                      foreach ($posts as $p):
                        setup_postdata($p);
                        $is_post_active = is_single() && $p->ID === $current_post_id ? ' active' : '';
                        if (WP_DEBUG) {
                          error_log(sprintf('Sidebar Post: ID %d, Title: %s, URL: %s', $p->ID, get_the_title($p->ID), get_permalink($p->ID)));
                        }
                        ?>
                        <a href="<?php echo esc_url(get_permalink($p->ID)); ?>"
                          class="sidebar__post-item<?php echo esc_attr($is_post_active); ?>">
                          <?php echo esc_html(get_the_title($p->ID)); ?>
                          <svg class="sidebar__post-icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
                            <use href="#chevron-icon"></use>
                          </svg>
                        </a>
                      <?php endforeach; ?>
                      <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
                <?php setup_postdata($tmp_post); ?>
              <?php endif; ?>
              <svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;">
                <defs>
                  <symbol id="chevron-icon" viewBox="0 0 8 16">
                    <path d="M2 3L6 8L2 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round" fill="none" />
                  </symbol>
                </defs>
              </svg>
          </section>
          <?php get_template_part('template-parts/promo-card'); ?>
        </div>
      <?php endif; ?>
      <!-- Конец sidebar -->
    </div>
  </div>
  <svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;">
    <defs>
      <symbol id="chevron-icon" viewBox="0 0 8 16">
        <path d="M2 3L6 8L2 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          fill="none" />
      </symbol>
    </defs>
  </svg>
</main>
<?php get_footer(); ?>