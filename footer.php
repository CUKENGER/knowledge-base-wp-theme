<?php
/**
 * Footer template
 */
?>
<footer class='footer'>
	<div class='container'>
		<div class='footer-blocks'>
			<div class='footer-block footer-block--support'>
				<div class='footer-block__content'>
					<div class='footer-block__text-container'>
						<p class='footer-block__title'>Служба поддержки</p>
						<p class='footer-block__description'>Если вы не нашли ответ на свой вопрос, то свяжитесь с нами. Мы вам
							поможем.
						</p>
					</div>
					<button class='footer-block__btn'>Задать вопрос</button>
				</div>
				<div class="footer-block__image">
					<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/support-image.svg'); ?>"
						alt="Служба поддержки">
				</div>
			</div>
			<div class='footer-block footer-block--links'>
				<div class='footer-block__title-container'>
					<p class='footer-block__title'>Мы в Telegram</p>
					<div class='footer-block-links-list'>
						<a class="footer-block__link" href='#' blank='_'>
							Официальный канал
							<svg class="footer-block__link-icon" width="14" height="14" viewBox="0 0 14 14" aria-hidden="true">
								<use href="#external-link-icon"></use>
							</svg>
						</a>
						<a class="footer-block__link" href='#' blank='_'>
							Канал про продвижение
							<svg class="footer-block__link-icon" width="14" height="14" viewBox="0 0 14 14" aria-hidden="true">
								<use href="#external-link-icon"></use>
							</svg>
						</a>
						<a class="footer-block__link" href='#' blank='_'>
							Чат поддержки
							<svg class="footer-block__link-icon" width="14" height="14" viewBox="0 0 14 14" aria-hidden="true">
								<use href="#external-link-icon"></use>
							</svg>
						</a>
					</div>
				</div>
				<div class="footer-block__image">
					<img src="<?php echo esc_url(get_template_directory_uri() . '/assets/contacts-image.svg'); ?>" alt="Контакты">
				</div>
			</div>
		</div>
	</div>
	<div class='footer-wrapper'>
		<div class="container">
			<div class='footer-container'>
				<p>База знаний TeleGraphyx © 2025</p>
				<p class='footer-container__link'>Политика конфиденциальности</p>
			</div>
		</div>
	</div>
	<svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;">
		<defs>
			<symbol id="external-link-icon" viewBox="0 0 14 14">
				<path d="M12 7V10C12 11.1046 11.1046 12 10 12H4C2.89543 12 2 11.1046 2 10V4C2 2.89543 2.89543 2 4 2H7"
					stroke="currentColor" stroke-width="1.4" fill="none" />
				<path d="M12 2L7 7" stroke="currentColor" stroke-width="1.4" />
				<path
					d="M9.20804 1H12.5009C12.777 1 13.0009 1.22386 13.0009 1.5V4.79289C13.0009 5.23835 12.4623 5.46143 12.1473 5.14645L8.85448 1.85355C8.5395 1.53857 8.76258 1 9.20804 1Z"
					fill="currentColor" />
			</symbol>
		</defs>
	</svg>
</footer>
<?php wp_footer(); ?>
</body>

</html>