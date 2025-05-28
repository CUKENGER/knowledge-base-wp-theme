function copyBlockText(blockId, textToCopy) {
	if (!textToCopy) {
		const block = document.getElementById(blockId)
		if (!block) return
		textToCopy = block.querySelector('.copy-block__content').textContent.trim()
	}

	navigator.clipboard
		.writeText(textToCopy)
		.catch(err => {
			console.error('Ошибка копирования:', err)
		})
}
