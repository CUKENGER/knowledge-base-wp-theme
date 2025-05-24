<?php
/**
 * The template for displaying category archive pages.
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
						if (have_posts()):
							while (have_posts()):
								the_post();
								?>
								<div class="category-page__post-item">
									<a href="<?php the_permalink(); ?>" class="category-page__post-link"><?php the_title(); ?></a>
									<svg class="card-btn__icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
										<use href="#chevron-icon"></use>
									</svg>
								</div>
								<?php
							endwhile;
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