document.addEventListener('DOMContentLoaded', () => {
	const containers = document.querySelectorAll(
		'.suggestions, .common-breadcrumbs'
	)
	if (!containers.length) return

	containers.forEach(container => {
		let isDragging = false
		let startX
		let scrollLeft

		// Проверка переполнения
		function checkOverflow() {
			if (container.classList.contains('suggestions')) {
				const buttons = container.querySelectorAll('.suggestions-btn')
				let totalWidth = 0
				buttons.forEach(button => {
					totalWidth += button.getBoundingClientRect().width
				})
				const gap = buttons.length > 1 ? 8 * (buttons.length - 1) : 0
				totalWidth += gap

				// Проверка на переполнение с допуском 10px
				if (totalWidth > container.clientWidth + 10) {
					container.classList.add('overflow')
				} else {
					container.classList.remove('overflow')
				}
			} else {
				const hasOverflow = container.scrollWidth > container.clientWidth + 10
				if (hasOverflow) {
					container.classList.add('overflow')
				} else {
					container.classList.remove('overflow')
				}
			}
		}

		// Начало перетаскивания
		container.addEventListener('mousedown', e => {
			if (e.target.closest('.suggestions-btn, .common-breadcrumbs-link')) return
			isDragging = true
			container.classList.add('dragging')
			startX = e.pageX - container.offsetLeft
			scrollLeft = container.scrollLeft
			e.preventDefault()
		})

		// Перетаскивание
		container.addEventListener('mousemove', e => {
			if (!isDragging) return
			const x = e.pageX - container.offsetLeft
			const walk = (x - startX) * 2 // Ускоряем скролл
			container.scrollLeft = scrollLeft - walk
		})

		// Конец перетаскивания
		container.addEventListener('mouseup', () => {
			isDragging = false
			container.classList.remove('dragging')
		})

		// Выход мыши
		container.addEventListener('mouseleave', () => {
			isDragging = false
			container.classList.remove('dragging')
		})

		// Управление курсором в зависимости от ширины экрана
		function updateCursor() {
			if (window.innerWidth <= 550) {
				container.style.cursor = 'auto'
			} else {
				container.style.cursor = isDragging ? 'grabbing' : 'grab'
			}
		}

		// Инициализация и обновление при ресайзе
		checkOverflow() // Проверяем переполнение при загрузке
		updateCursor()
		window.addEventListener('resize', () => {
			updateCursor()
			checkOverflow() // Проверяем переполнение при ресайзе
		})
	})
})
