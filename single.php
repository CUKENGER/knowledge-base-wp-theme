<?php
/**
 * Template: Single Post
 */
get_header();
?>
<main>
  <div class="overlay" aria-hidden="true"></div>
  <div class="container">
    <div class="single-container">
      <div class="single-container__wrapper">
        <article class="single-post">
          <?php if (have_posts()):
            while (have_posts()):
              the_post();
              // Дебаг
              $post_id = get_the_ID();
              $title = get_the_title() ?: 'No title';
              $slug = get_post_field('post_name', $post_id) ?: 'No slug';
              $permalink = get_permalink() ?: 'No URL';
              $request_uri = esc_url_raw($_SERVER['REQUEST_URI']);
              error_log("Single Post: ID=$post_id, Title=$title, Slug=$slug, URL=$permalink, Requested URL=$request_uri");
              ?>
              <header class="post-header">
                <div class="post-tags">
                  <span><?php echo get_the_date('j F'); ?></span>
                  <svg width="4" height="4" viewBox="0 0 4 4" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle opacity="0.5" cx="2" cy="2" r="2" fill="currentColor" />
                  </svg>
                  <a href="<?php
                  $categories = get_the_category();
                  $category = !empty($categories) ? $categories[0] : null;
                  echo $category ? esc_url(get_category_link($category->term_id)) : esc_url(home_url('/category/prochee/'));
                  ?>" class="post-tags-icon" aria-label="Перейти к категории <?php
                  $category_name = $category ? esc_html($category->name) : 'Прочее';
                  echo esc_attr($category_name);
                  ?>">
                    <span class="post-category-name"><?php echo esc_html($category_name); ?></span>
                  </a>
                </div>
                <h1 class="single-post-title"><?php echo esc_html($title); ?></h1>
                <div class="post-author">
                  <span
                    class="post-author-avatar"><?php echo get_avatar(get_the_author_meta('ID'), 30, 'mystery', 'Аватар пользователя'); ?></span>
                  <span class="post-author-name"><?php the_author(); ?></span>
                </div>
              </header>
              <div class="post-cover">
                <?php if (has_post_thumbnail()): ?>
                  <?php the_post_thumbnail('large', [
                    'alt' => esc_attr($title),
                    'class' => 'post-cover-image',
                    'loading' => 'lazy'
                  ]); ?>
                <?php else: ?>
                  <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/post.png'); ?>"
                    alt="<?php echo esc_attr($title); ?>" class="post-cover-image" loading="lazy">
                <?php endif; ?>
              </div>
              <div class="post-content">
                <?php
                the_content();
                if (shortcode_exists('banner_pulse')) {
                  echo do_shortcode('[banner_pulse]');
                } else {
                  error_log("Shortcode [banner_pulse] not found for post ID $post_id");
                }
                ?>
              </div>
              <?php if (get_field('external_source')): ?>
                <div class="external-links">
                  <p>Источник: <a href="<?php echo esc_url(get_field('external_source')); ?>" target="_blank"
                      class="source-link">Перейти</a></p>
                </div>
              <?php endif; ?>
              <!-- Секция похожих постов -->
              <?php
              $related_posts = get_field('related_posts');
              $related_titles = [];
              $posts_needed = 2;
              $args = [];

              // Шаг 1: ACF related_posts
              if ($related_posts && is_array($related_posts) && count($related_posts) > 0) {
                $args = [
                  'post_type' => 'post',
                  'post__in' => array_slice($related_posts, 0, $posts_needed),
                  'post_status' => 'publish',
                  'ignore_sticky_posts' => true,
                  'orderby' => 'post__in',
                ];
              } else {
                // Шаг 2: По категориям
                $categories = get_the_category();
                $category_ids = !empty($categories) ? wp_list_pluck($categories, 'term_id') : [];
                if (!empty($category_ids)) {
                  $args = [
                    'post_type' => 'post',
                    'posts_per_page' => $posts_needed,
                    'post__not_in' => [$post_id],
                    'category__in' => $category_ids,
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => true,
                  ];
                } else {
                  // Шаг 3: Любые посты
                  $args = [
                    'post_type' => 'post',
                    'posts_per_page' => $posts_needed,
                    'post__not_in' => [$post_id],
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => true,
                  ];
                }
              }

              $related_query = new WP_Query($args);
              error_log("Related posts query for post ID $post_id: " . print_r($args, true));

              if ($related_query->have_posts()): ?>
                <div class='related-post-wrapper'>
                  <section class="related-posts" aria-labelledby="related-posts-title">
                    <div class="related-posts__container">
                      <h2 id="related-posts-title" class='category-title'>Похожие статьи</h2>
                      <div class="posts">
                        <?php
                        while ($related_query->have_posts()):
                          $related_query->the_post();
                          $related_titles[] = get_the_title();
                          get_template_part('template-parts/post');
                        endwhile;
                        error_log("Related posts rendered for post ID $post_id: " . implode(', ', $related_titles));
                        ?>
                      </div>
                    </div>
                </div>
              <?php endif; ?>
              <?php wp_reset_postdata(); ?>
            <?php endwhile; ?>
          <?php else: ?>
            <p>Пост не найден.</p>
          <?php endif; ?>
        </article>
        <?php get_template_part('template-parts/sidebar'); ?>
      </div>
      <?php get_template_part('template-parts/breadcrumbs'); ?>
      <?php get_template_part('template-parts/page-header'); ?>
      <div class="single__promo-card--tablet">
        <?php get_template_part('template-parts/promo-card'); ?>
      </div>
    </div>
  </div>
</main>
<?php get_footer();