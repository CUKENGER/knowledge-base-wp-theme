<?php
/**
 * Template Part: Promo Card
 * 
 * Displays the promotional card for Telegram X workshop.
 */
$title = get_option('tgx_promo_title', 'Пройди Telegram X');
$description = get_option('tgx_promo_description', 'Это воркшоп, где ты запустишь канал и получишь подписчиков без слива бюджета!');
$link = get_option('tgx_promo_link', 'https://example.com/workshop');
?>
<div class='promo-card'>
	<div class='promo-card__content'>
		<p class='promo-card__title'><?php echo esc_html($title); ?></p>
		<p class='promo-card__description'><?php echo esc_html($description); ?></p>
	</div>
	<a href="<?php echo esc_url($link); ?>" target='_blank' class='promo-card__btn'>Подробнее</a>
</div>