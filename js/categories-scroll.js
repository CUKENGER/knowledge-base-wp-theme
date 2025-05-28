document.addEventListener('DOMContentLoaded', () => {
	const containers = document.querySelectorAll(
		'.suggestions, .common-breadcrumbs'
	)
	if (!containers.length) return

	containers.forEach(container => {
		let isDragging = false
		let startX
		let scrollLeft

		// Проверка переполнения для .suggestions
		// Проверка переполнения
		function checkOverflow() {
			// Для .suggestions проверяем ширину внутренних элементов
			if (container.classList.contains('suggestions')) {
				const buttons = container.querySelectorAll('.suggestions-btn')
				let totalWidth = 0
				buttons.forEach(button => {
					const style = window.getComputedStyle(button)
					const width =
						button.offsetWidth +
						parseFloat(style.marginLeft) +
						parseFloat(style.marginRight)
					totalWidth += width
				})
				// Добавляем gap (8px из CSS) между кнопками
				const gap = 8 * (buttons.length - 1)
				totalWidth += gap

				// Сравниваем с clientWidth контейнера, учитываем допуск
				const hasOverflow = totalWidth > container.clientWidth + 2
				if (hasOverflow) {
					container.classList.add('overflow')
				} else {
					container.classList.remove('overflow')
				}
			} else {
				// Для .common-breadcrumbs используем старую логику
				const hasOverflow = container.scrollWidth > container.clientWidth + 2
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
