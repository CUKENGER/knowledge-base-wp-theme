<?php
/**
 * Template: Index
 */
get_header();
?>
<main>
  <div class="overlay" aria-hidden="true"></div>
  <div class="container">
    <section class='hero'>
      <h1 class='hero-title'>База знаний по работе с сервисом Телеграфикс</h1>
      <p class="hero_subtitle">Напишите ваш вопрос, и мы найдем нужные статьи.</p>
      <div class='hero-input__container'>
        <div class='hero-input__wrapper'>
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/search-icon.svg'); ?>" alt="Поиск">
          <input type="text" class='hero-input' placeholder="Какой у вас вопрос?">
          <svg class="hero-input-clear" width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" fill="currentColor" />
            <path d="M9 15L15 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M15 15L9 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>
        <div class="search-results"></div>
      </div>
      <div class='suggestions'>
        <?php
        $has_posts = false;
        for ($i = 1; $i <= 5; $i++):
          $post_id = get_option("tgx_suggestion_post_$i", 0);
          if ($post_id):
            $has_posts = true;
            $link = esc_url(get_permalink($post_id));
            $raw_title = get_the_title($post_id);
            $title = esc_html(tgx_remove_emoji($raw_title)); // Очищаем эмодзи и экранируем
            $class = "suggestions-btn suggestions-btn--$i";
            ?>
            <a href="<?php echo $link; ?>" class="<?php echo esc_attr($class); ?>">
              <?php echo $title; ?>
            </a>
          <?php endif; ?>
        <?php endfor; ?>
      </div>
      <section class='category-container'>
        <?php
        $categories = get_transient('tgx_categories');
        if (false === $categories) {
          $categories = get_categories([
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC',
          ]);
          set_transient('tgx_categories', $categories, HOUR_IN_SECONDS);
        }

        foreach ($categories as $category):
          $posts_query = new WP_Query([
            'cat' => $category->term_id,
            'posts_per_page' => 4,
            'post_status' => 'publish',
          ]);
          if ($posts_query->have_posts()):
            ?>
            <div class='category-widget'>
              <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class='category-widget__title'>
                <p class='category-widget__content--title'>
                  <?php echo esc_html($category->name); ?>
                  <span class='category-widget__count--title'><?php echo esc_html($category->count); ?></span>
                </p>
                <svg width="8" height="16" viewBox="0 0 8 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M2 3L6 8L2 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                </svg>
              </a>
              <?php while ($posts_query->have_posts()):
                $posts_query->the_post();
                // Дебаг
                error_log('Index Post: ID ' . get_the_ID() . ', Title: ' . get_the_title() . ', URL: ' . get_permalink());
                ?>
                <a href="<?php the_permalink(); ?>" class='card-btn'>
                  <span><?php the_title(); ?></span>
                  <svg class="card-btn__icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
                    <use href="#chevron-icon"></use>
                  </svg>
                </a>
              <?php endwhile; ?>
            </div>
            <?php
          endif;
          wp_reset_postdata();
        endforeach;
        ?>
        <?php get_template_part('template-parts/promo-card'); ?>
      </section>
    </section>
    <svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;">
      <defs>
        <symbol id="chevron-icon" viewBox="0 0 8 16">
          <path d="M2 3L6 8L2 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            fill="none" />
        </symbol>
      </defs>
    </svg>
  </div>
</main>
<?php get_footer();