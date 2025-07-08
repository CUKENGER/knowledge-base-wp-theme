<?php
// Функция для удаления эмодзи из строки
function remove_emoji($string)
{
  $pattern = '/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F1E0}-\x{1F1FF}]/u';
  return preg_replace($pattern, '', $string);
}
?>

<?php get_header(); ?>
<main>
  <div class="overlay" aria-hidden="true"></div>

  <?php
  $current_post_id = get_queried_object_id();
  $post_categories = wp_get_post_categories($current_post_id, ['fields' => 'ids']);
  $active_category_id = !empty($post_categories) ? $post_categories[0] : 0;
  ?>
  <div class="overlay" aria-hidden="true"></div>

  <?php get_template_part('template-parts/page-header'); ?>

  <div class="container">
    <div class="category-page__container">
      <div class="category-page__content">
        <!-- Вставка breadcrumbs.php -->
        <section class='common-breadcrumbs'>
          <a href="<?php echo esc_url(home_url('/')); ?>" class="common-breadcrumbs-link">База знаний</a>
          <span class="common-breadcrumbs-divider">/</span>
          <?php
          $categories = get_the_category($current_post_id);
          $main_category = !empty($categories) ? $categories[0] : null;
          if ($main_category) {
            echo '<a href="' . esc_url(get_category_link($main_category->term_id)) . '" class="common-breadcrumbs-link">' . esc_html($main_category->name) . '</a>';
            echo '<span class="common-breadcrumbs-divider">/</span>';
          } else {
            echo '<span>Без категории</span>';
            echo '<span class="common-breadcrumbs-divider">/</span>';
          }
          $current_post = get_post($current_post_id);
          $parent_id = $current_post ? $current_post->post_parent : 0;

          if ($parent_id) {
            $parent_post = get_post($parent_id);
            if ($parent_post) {
              if (WP_DEBUG) {
                error_log(sprintf('Breadcrumbs: Parent Post Found, ID=%d, Title=%s', $parent_id, $parent_post->post_title));
              }
              $clean_parent_title = remove_emoji($parent_post->post_title);
              echo '<a href="' . esc_url(get_permalink($parent_id)) . '" class="common-breadcrumbs-link">' . esc_html($clean_parent_title) . '</a>';
              echo '<span class="common-breadcrumbs-divider">/</span>';
            } else {
              if (WP_DEBUG) {
                error_log('Breadcrumbs: Parent Post Not Found for Parent ID=' . $parent_id);
              }
            }
          }
          $clean_title = remove_emoji(get_the_title($current_post_id));
          echo '<span>' . esc_html($clean_title) . '</span>';
          ?>
        </section>
        <!-- Конец breadcrumbs.php -->

        <article class="single-post">
          <?php
          $post = get_post($current_post_id);
          if ($post):
            ?>
            <h1 class="single-post-title"><?php echo esc_html(get_the_title($current_post_id)); ?></h1>
            <div class="post-content">
              <?php echo apply_filters('the_content', get_post_field('post_content', $current_post_id)); ?>
              <div class="post-updated">
                Обновлено: <?php echo get_the_modified_date('d.m.Y'); ?>
              </div>
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
      <?php get_template_part('template-parts/sidebar'); ?>
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