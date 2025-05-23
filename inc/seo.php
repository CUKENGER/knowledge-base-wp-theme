<?php
// SEO для постов
function tgx_add_seo_meta_boxes()
{
	add_meta_box('tgx_seo_meta', 'SEO настройки', 'tgx_seo_meta_box_callback', 'post', 'normal', 'high');
}
add_action('add_meta_boxes', 'tgx_add_seo_meta_boxes');
function tgx_seo_meta_box_callback($post)
{
	wp_nonce_field('tgx_seo_meta_nonce', 'tgx_seo_meta_nonce');
	$custom_title = get_post_meta($post->ID, '_tgx_seo_title', true);
	$custom_description = get_post_meta($post->ID, '_tgx_seo_description', true);
	$custom_keywords = get_post_meta($post->ID, '_tgx_seo_keywords', true);
	echo '<p><label for="tgx_seo_title">Кастомный заголовок (title, 60–70 символов):</label><br>';
	echo '<input type="text" id="tgx_seo_title" name="tgx_seo_title" value="' . esc_attr($custom_title) . '" style="width:100%;" placeholder="Монетизация Telegram | Название блога"></p>';
	echo '<p><label for="tgx_seo_description">Мета-описание (description, 150–160 символов):</label><br>';
	echo '<textarea id="tgx_seo_description" name="tgx_seo_description" style="width:100%;height:80px;" placeholder="Как заработать на Telegram: реклама, партнерки, донаты...">' . esc_textarea($custom_description) . '</textarea></p>';
	echo '<p><label for="tgx_seo_keywords">Ключевые слова (keywords, через запятую):</label><br>';
	echo '<input type="text" id="tgx_seo_keywords" name="tgx_seo_keywords" value="' . esc_attr($custom_keywords) . '" style="width:100%;" placeholder="telegram, монетизация, заработок"></p>';
}
function tgx_save_seo_meta($post_id)
{
	if (!isset($_POST['tgx_seo_meta_nonce']) || !wp_verify_nonce($_POST['tgx_seo_meta_nonce'], 'tgx_seo_meta_nonce'))
		return;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	if (!current_user_can('edit_post', $post_id))
		return;
	if (isset($_POST['tgx_seo_title'])) {
		update_post_meta($post_id, '_tgx_seo_title', sanitize_text_field($_POST['tgx_seo_title']));
	}
	if (isset($_POST['tgx_seo_description'])) {
		update_post_meta($post_id, '_tgx_seo_description', sanitize_textarea_field($_POST['tgx_seo_description']));
	}
	if (isset($_POST['tgx_seo_keywords'])) {
		update_post_meta($post_id, '_tgx_seo_keywords', sanitize_text_field($_POST['tgx_seo_keywords']));
	}
}
add_action('save_post', 'tgx_save_seo_meta');

// SEO для категорий
function tgx_add_category_seo_fields($taxonomy)
{
	$category_description = get_term_meta($taxonomy->term_id, '_tgx_category_description', true);
	$category_keywords = get_term_meta($taxonomy->term_id, '_tgx_category_keywords', true);
	?>
	<tr class="form-field">
		<th scope="row"><label for="tgx_category_description">Мета-описание (description)</label></th>
		<td>
			<textarea name="tgx_category_description" id="tgx_category_description" rows="5" cols="50"
				class="large-text"><?php echo esc_textarea($category_description); ?></textarea>
			<p class="description">Описание для SEO (150–160 символов).</p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="tgx_category_keywords">Ключевые слова (keywords)</label></th>
		<td>
			<input type="text" name="tgx_category_keywords" id="tgx_category_keywords"
				value="<?php echo esc_attr($category_keywords); ?>" class="large-text" />
			<p class="description">Ключевые слова через запятую (например, telegram, монетизация).</p>
		</td>
	</tr>
	<?php
}
add_action('category_edit_form_fields', 'tgx_add_category_seo_fields', 10, 2);

function tgx_save_category_seo_fields($term_id)
{
	if (isset($_POST['tgx_category_description'])) {
		update_term_meta($term_id, '_tgx_category_description', sanitize_textarea_field($_POST['tgx_category_description']));
	}
	if (isset($_POST['tgx_category_keywords'])) {
		update_term_meta($term_id, '_tgx_category_keywords', sanitize_text_field($_POST['tgx_category_keywords']));
	}
}
add_action('edited_category', 'tgx_save_category_seo_fields', 10, 2);