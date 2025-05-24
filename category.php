<?php get_header(); ?>
<main>
	<div class="overlay" aria-hidden="true"></div>
	<div class='container'>
		<div class='category-page__container'>
			<div class='category-page__content'>
				<?php get_template_part('template-parts/breadcrumbs'); ?>
				<section class='category-page__post-section'>
					<p class="category-page__title">Подключение (название категории)</p>
					<p class='category-page__description'>Возможно, стоит добавить какое-то короткое описание к каждому разделу.
					</p>
					<div class='category-page__post-list'>
						<div class='category-page__post-item'>
							📢 Подключение канала и группы
							<svg class="card-btn__icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
								<use href="#chevron-icon"></use>
							</svg>
						</div>
						<div class='category-page__post-item'>
							📢 Подключение канала и группы
							<svg class="card-btn__icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
								<use href="#chevron-icon"></use>
							</svg>
						</div>
						<div class='category-page__post-item'>
							📢 Подключение канала и группы
							<svg class="card-btn__icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
								<use href="#chevron-icon"></use>
							</svg>
						</div>
						<div class='category-page__post-item'>
							📢 Подключение канала и группы
							<svg class="card-btn__icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
								<use href="#chevron-icon"></use>
							</svg>
						</div>
						<div class='category-page__post-item'>
							📢 Подключение канала и группы
							<svg class="card-btn__icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
								<use href="#chevron-icon"></use>
							</svg>
						</div>
						<div class='category-page__post-item'>
							📢 Подключение канала и группы
							<svg class="card-btn__icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
								<use href="#chevron-icon"></use>
							</svg>
						</div>
					</div>
				</section>

				<div>

				</div>

			</div>
			<section class='category-page__sidebar'>

				<div class='sidebar-input__container'>
					<div class='sidebar-input__wrapper'>
						<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/search-icon.svg'); ?>" alt="Поиск">
						<input type="text" class='sidebar-input' placeholder='Какой у вас вопрос?'>
						<svg class="sidebar-input-clear" width="24" height="24" viewBox="0 0 24 24" fill="none"
							xmlns="http://www.w3.org/2000/svg">
							<circle cx="12" cy="12" r="10" fill="currentColor" />
							<path d="M9 15L15 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" />
							<path d="M15 15L9 9" stroke="#EAEAED" stroke-width="2" stroke-linecap="round" />
						</svg>
					</div>
					<div class="sidebar-input__search-results"></div>
				</div>

				<div class='category-page__sidebar-contents'>
					<button class='category-widget__title'>
						<p class='category-widget__content--title'>
							О Сервисе
							<span class='category-widget__count--title'>5</span>
						</p>
						<svg width="8" height="16" viewBox="0 0 8 16" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M2 3L6 8L2 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"
								stroke-linejoin="round" />
						</svg>
					</button>
				</div>
				<?php get_template_part('template-parts/promo-card'); ?>
			</section>
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