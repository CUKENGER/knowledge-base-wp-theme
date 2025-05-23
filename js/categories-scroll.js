document.addEventListener('DOMContentLoaded', () => {
	const categories = document.querySelector('.categories')
	if (!categories) return

	let isDragging = false
	let startX
	let scrollLeft

	// Начало перетаскивания
	categories.addEventListener('mousedown', e => {
		// Игнорируем, если клик по ссылке
		if (e.target.closest('.category-container')) return

		isDragging = true
		categories.classList.add('dragging')
		startX = e.pageX - categories.offsetLeft
		scrollLeft = categories.scrollLeft
		e.preventDefault() // Отключаем выделение текста
	})

	// Перетаскивание
	categories.addEventListener('mousemove', e => {
		if (!isDragging) return
		const x = e.pageX - categories.offsetLeft
		const walk = (x - startX) * 2 // Ускоряем скролл
		categories.scrollLeft = scrollLeft - walk
	})

	// Конец перетаскивания
	categories.addEventListener('mouseup', () => {
		isDragging = false
		categories.classList.remove('dragging')
	})

	categories.addEventListener('mouseleave', () => {
		isDragging = false
		categories.classList.remove('dragging')
	})

	// Отключаем drag-to-scroll на мобильных (ширина < 550px)
	window.addEventListener('resize', () => {
		if (window.innerWidth <= 550) {
			categories.style.cursor = 'auto'
		} else {
			categories.style.cursor = 'grab'
		}
	})

	// Инициализация курсора
	if (window.innerWidth <= 550) {
		categories.style.cursor = 'auto'
	}
})
