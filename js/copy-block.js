function copyBlockText(blockId, text) {
	navigator.clipboard
		.writeText(text)
		.then(() => {
			const block = document.getElementById(blockId)
			block.classList.add('copied')
		})
		.catch(err => {
			console.error('Ошибка копирования:', err)
		})
}
