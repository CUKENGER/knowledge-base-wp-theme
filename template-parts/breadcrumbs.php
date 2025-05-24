<?php
/**
 * Template Part: Breadcrumbs
 * 
 * Displays breadcrumbs for category and single post pages.
 */
?>
<section class='common-breadcrumbs'>
	<a href="<?php echo esc_url(home_url('/')); ?>" class="common-breadcrumbs-link">База знаний</a>
	<span class="common-breadcrumbs-divider">/</span>
	<?php if (is_category()): ?>
		<?php
		$category = get_queried_object();
		?>
		<span><?php echo esc_html($category->name); ?></span>
	<?php elseif (is_single()): ?>
		<?php
		$categories = get_the_category();
		$main_category = !empty($categories) ? $categories[0] : null; // Берём первую категорию
		if ($main_category):
			?>
			<a href="<?php echo esc_url(get_category_link($main_category->term_id)); ?>"
				class="common-breadcrumbs-link"><?php echo esc_html($main_category->name); ?></a>
			<span class="common-breadcrumbs-divider">/</span>
		<?php endif; ?>
		<span><?php echo esc_html(get_the_title()); ?></span>
	<?php endif; ?>
</section>