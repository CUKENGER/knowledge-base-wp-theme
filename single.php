<?php
/**
 * The template for displaying single posts.
 */
get_header(); ?>
<main>
  <div class="overlay" aria-hidden="true"></div>
  <?php get_template_part('template-parts/page-header'); ?>
  <div class="container">
    <div class='single-container'>
      <?php get_template_part('template-parts/breadcrumbs'); ?>
      <div class='single-container__wrapper'>
        <?php
        if (have_posts()):
          while (have_posts()):
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
              <h1 class="entry-title"><?php the_title(); ?></h1>
              <div class="entry-content">
                <?php the_content(); ?>
              </div>
            </article>
            <?php
          endwhile;
        else:
          echo '<p>Записей не найдено.</p>';
        endif;
        ?>
        <?php get_template_part('template-parts/sidebar'); ?>
      </div>
      <div class='single__promo-card--tablet'>
        <?php get_template_part('template-parts/promo-card'); ?>
      </div>
    </div>
  </div>
</main>
<?php get_footer(); ?>