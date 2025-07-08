<?php
/**
 * Template: Category Page
 */
get_header();
?>
<main>
	<div class="overlay" aria-hidden="true"></div>
	<?php get_template_part('template-parts/page-header'); ?>
	<div class="container">
		<div class="category-page__container">
			<div class="category-page__content">
				<?php get_template_part('template-parts/breadcrumbs'); ?>
				<section class="category-page__post-section">
					<h1 class="category-page__title"><?php single_cat_title(); ?></h1>
					<?php
					$category_description = category_description();
					if (!empty($category_description)):
						?>
						<p class="category-page__description"><?php echo $category_description; ?></p>
					<?php endif; ?>
					<div class="category-page__post-list">
						<?php
						$cat_id = get_queried_object_id();
						error_log('Category ID: ' . $cat_id); // Отладка ID категории
						$args = array(
							'cat' => $cat_id,
							'posts_per_page' => 10,
							'post_type' => 'post',
							'post_parent' => 0,
							'post_status' => 'publish',
						);
						$query = new WP_Query($args);
						error_log('Query post count: ' . $query->post_count); // Отладка количества постов
						if ($query->have_posts()):
							while ($query->have_posts()):
								$query->the_post();
								error_log('Post found: ID: ' . get_the_ID() . ', Title: ' . get_the_title()); // Отладка постов
								?>
								<a href="<?php the_permalink(); ?>" class="category-page__post-item">
									<div class="category-page__post-link"><?php the_title(); ?></div>
									<svg class="card-btn__icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
										<use href="#chevron-icon"></use>
									</svg>
								</a>
								<?php
							endwhile;
							wp_reset_postdata();
						else:
							?>
							<div class="category-page__post-item">Записей в этой категории не найдено.</div>
						<?php endif; ?>
					</div>
				</section>
			</div>
			<div class="category-page__promo-card--tablet">
				<?php get_template_part('template-parts/promo-card'); ?>
			</div>
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