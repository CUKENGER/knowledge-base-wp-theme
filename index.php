<?php get_header(); ?>
<main>
  <div class="overlay" aria-hidden="true"></div>
  <div class="container">
    <section class='hero'>
      <h1 class='hero-title'>
        База знаний по работе с сервисом Телеграфикс
      </h1>
      <p class="hero_subtitle">Напишите ваш вопрос, и мы найдем нужные статьи.</p>
      <div class='hero-input__container'>
        <div class='hero-input__wrapper'>
          <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/search-icon.svg'); ?>" alt="Поиск">
          <input type="text" class='hero-input' placeholder='Какой у вас вопрос?'>
          <svg class="hero-input-clear" width="24" height="24" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" fill="currentColor" />
            <path d="M9 15L15 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" />
            <path d="M15 15L9 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" />
          </svg>
        </div>
        <div class="search-results"></div>
      </div>
      <div class='suggestions'>
        <button class="suggestions-btn">
          Настройка доступа
        </button>
        <button class="suggestions-btn">
          Аналитика
        </button>
        <button class="suggestions-btn">
          Подключение бота
        </button>
        <button class="suggestions-btn">
          Умная кнопка
        </button>
      </div>
      <section class='category-container'>
        <?php
        // Получаем категории
        $categories = get_categories([
          'hide_empty' => true, // Только категории с постами
          'orderby' => 'name',
          'order' => 'ASC',
        ]);

        foreach ($categories as $category):
          // Запрос постов для текущей категории
          $posts_query = new WP_Query([
            'cat' => $category->term_id,
            'posts_per_page' => 4, // Ограничим 4 постами, как в шаблоне
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
                $posts_query->the_post(); ?>
                <a href="<?php the_permalink(); ?>" class='card-btn'>
                  <span>
                    <?php the_title(); ?>
                  </span>
                  <svg class="card-btn__icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
                    <use href="#chevron-icon"></use>
                  </svg>
                </a>
              <?php endwhile; ?>
            </div>
            <?php
          endif;
          wp_reset_postdata(); // Сбрасываем запрос
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
<?php get_footer(); ?>