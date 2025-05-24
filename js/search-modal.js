document.addEventListener('DOMContentLoaded', () => {
	const inputs = document.querySelectorAll('.hero-input, .sidebar-input')
	const overlay = document.querySelector('.overlay')
	const body = document.body
	let scrollPosition = 0
	let isFocusing = false // Глобальный флаг для предотвращения blur
	let isProcessingClick = false // Флаг для блокировки новых кликов
	let lastClickTime = 0 // Время последнего клика

	console.log('Found inputs:', inputs.length)

	// Функция для управления видимостью крестика
	function toggleClearButton(input, clearButton) {
		if (clearButton) {
			clearButton.style.display = input.value.trim() ? 'flex' : 'none'
		}
	}

	// Функция для анимации появления элементов
	function animateResults(items) {
		items.forEach((item, index) => {
			setTimeout(() => {
				item.classList.add('show')
			}, index * 100)
		})
	}

	// Функция поиска
	function performSearch(query, resultsContainer) {
		if (!query) {
			resultsContainer.classList.remove('active')
			resultsContainer.innerHTML = ''
			return
		}
		const data = new FormData()
		data.append('action', 'tgx_search_posts')
		data.append('query', query)
		fetch(tgxSettings.ajaxUrl, {
			method: 'POST',
			body: data,
		})
			.then(response => response.json())
			.then(data => {
				resultsContainer.innerHTML = ''
				if (data.success && data.data.length > 0) {
					data.data.forEach(item => {
						const link = document.createElement('a')
						link.href = item.link
						link.className = 'search-result-item'
						link.textContent = item.title
						resultsContainer.appendChild(link)
					})
					resultsContainer.classList.add('active')
					const items = resultsContainer.querySelectorAll('.search-result-item')
					animateResults(items)
				} else {
					resultsContainer.innerHTML = '<p>Ничего не найдено</p>'
					resultsContainer.classList.add('active')
				}
			})
			.catch(error => {
				console.error('Search error:', error)
				resultsContainer.innerHTML = '<p>Ошибка поиска</p>'
				resultsContainer.classList.add('active')
			})
	}

	// Функция для надёжного возврата фокуса
	function ensureFocus(input) {
		if (document.activeElement === input || isFocusing) {
			console.log('Focus already set or in progress on', input)
			return
		}
		let focusTimeout
		const tryFocus = () => {
			clearTimeout(focusTimeout)
			focusTimeout = setTimeout(() => {
				input.focus()
				if (document.activeElement === input) {
					console.log('Focus set successfully on', input)
				} else {
					console.error('Failed to set focus')
				}
			}, 150)
		}
		tryFocus()
	}

	// Обработка каждого инпута
	inputs.forEach(input => {
		const wrapper = input.closest(
			'.hero-input__wrapper, .sidebar-input__wrapper'
		)
		const clearButton = wrapper
			? wrapper.querySelector('.hero-input-clear, .sidebar-input-clear')
			: null
		const container = input.closest(
			'.hero-input__container, .sidebar-input__container'
		)
		const resultsContainer = container?.querySelector(
			'.search-results, .sidebar-input__search-results'
		)

		if (!input || !resultsContainer || !container) {
			console.error('Missing elements for input:', {
				input,
				resultsContainer,
				container,
			})
			return
		}

		// Установка/сброс z-index
		const setZIndex = isFocused => {
			if (wrapper.classList.contains('hero-input__wrapper')) {
				wrapper.style.zIndex = isFocused ? '30' : '19'
				container.style.zIndex = isFocused ? '30' : '19'
				console.log(
					`Set z-index for hero-input__wrapper: ${wrapper.style.zIndex}`
				)
			}
		}

		// Сохраняем фокус при клике на обёртку
		if (wrapper) {
			let clickTimeout
			wrapper.addEventListener('click', e => {
				const now = Date.now()
				if (
					!e.target.closest('.hero-input-clear, .sidebar-input-clear') &&
					document.activeElement !== input &&
					e.target !== input &&
					!isProcessingClick &&
					now - lastClickTime > 300 // Минимальный интервал между кликами
				) {
					e.preventDefault()
					clearTimeout(clickTimeout)
					isProcessingClick = true
					lastClickTime = now
					clickTimeout = setTimeout(() => {
						isFocusing = true
						console.log('Setting focus via wrapper click:', wrapper.className)
						setZIndex(true)
						input.focus()
						console.log('Overflow after click:', body.style.overflow)
						console.log(
							'Border after click:',
							getComputedStyle(wrapper).borderColor
						)
						setTimeout(() => {
							isFocusing = false
							isProcessingClick = false
						}, 400) // Продлеваем флаг
					}, 300) // Увеличено
				}
			})
		}

		// Фокус: показываем overlay, блокируем скролл
		input.addEventListener('focus', () => {
			console.log('Input focused:', input.className)
			if (body.style.overflow !== 'hidden') {
				scrollPosition = window.scrollY
				overlay.classList.add('active')
				body.classList.add('overlay-active')
				body.style.overflow = 'hidden'
			}
			toggleClearButton(input, clearButton)
			setZIndex(true)
			console.log('Overflow on focus:', body.style.overflow)
			console.log('Border on focus:', getComputedStyle(wrapper).borderColor)
		})

		// Потеря фокуса: скрываем overlay и результаты
		input.addEventListener('blur', e => {
			console.log('Input blur:', input.className)
			setTimeout(() => {
				const now = Date.now()
				if (isFocusing || now - lastClickTime < 400) {
					console.log('Blur ignored due to ongoing focus or recent click')
					return
				}
				const isResultsClicked = resultsContainer.contains(
					document.activeElement
				)
				const isInputFocused = input === document.activeElement
				const isWrapperClicked = wrapper.contains(e.relatedTarget)
				if (!isResultsClicked && !isInputFocused && !isWrapperClicked) {
					overlay.classList.remove('active')
					body.classList.remove('overlay-active')
					resultsContainer.classList.remove('active')
					body.style.overflow = ''
					window.scrollTo(0, scrollPosition)
					setZIndex(false)
					console.log('Overflow on blur:', body.style.overflow)
					console.log('Border on blur:', getComputedStyle(wrapper).borderColor)
				}
			}, 400) // Увеличено
		})

		// Ввод текста: поиск и показ крестика
		input.addEventListener('input', () => {
			const query = input.value.trim()
			toggleClearButton(input, clearButton)
			performSearch(query, resultsContainer)
		})

		// Кнопка очистки
		if (clearButton) {
			const clearHandler = e => {
				e.preventDefault()
				e.stopPropagation()
				console.log('Clear button clicked:', clearButton.className)
				input.value = ''
				resultsContainer.classList.remove('active')
				resultsContainer.innerHTML = ''
				toggleClearButton(input, clearButton)
				isFocusing = true
				input.focus()
				ensureFocus(input)
				setTimeout(() => {
					isFocusing = false
				}, 400)
			}
			clearButton.addEventListener('click', clearHandler)
			clearButton.addEventListener('mousedown', e => {
				e.preventDefault()
				clearHandler(e)
			})
		}
	})

	// Закрытие overlay по клику вне области
	if (overlay) {
		overlay.addEventListener('click', e => {
			if (e.target === overlay) {
				overlay.classList.remove('active')
				body.classList.remove('overlay-active')
				inputs.forEach(input => {
					const wrapper = input.closest(
						'.hero-input__wrapper, .sidebar-input__wrapper'
					)
					const clearButton = wrapper?.querySelector(
						'.hero-input-clear, .sidebar-input-clear'
					)
					const container = input.closest(
						'.hero-input__container, .sidebar-input__container'
					)
					const resultsContainer = container?.querySelector(
						'.search-results, .sidebar-input__search-results'
					)
					input.value = ''
					if (resultsContainer) resultsContainer.classList.remove('active')
					if (resultsContainer) resultsContainer.innerHTML = ''
					toggleClearButton(input, clearButton)
					if (
						wrapper &&
						container &&
						wrapper.classList.contains('hero-input__wrapper')
					) {
						wrapper.style.zIndex = '19'
						container.style.zIndex = '19'
					}
				})
				body.style.overflow = ''
				window.scrollTo(0, scrollPosition)
				inputs.forEach(input => input.blur())
			}
		})
	}

	// Закрытие overlay по Esc
	document.addEventListener('keydown', e => {
		if (e.key === 'Escape' && overlay.classList.contains('active')) {
			overlay.classList.remove('active')
			body.classList.remove('overlay-active')
			inputs.forEach(input => {
				const wrapper = input.closest(
					'.hero-input__wrapper, .sidebar-input__wrapper'
				)
				const clearButton = wrapper?.querySelector(
					'.hero-input-clear, .sidebar-input-clear'
				)
				const container = input.closest(
					'.hero-input__container, .sidebar-input__container'
				)
				const resultsContainer = container?.querySelector(
					'.search-results, .sidebar-input__search-results'
				)
				input.value = ''
				if (resultsContainer) resultsContainer.classList.remove('active')
				if (resultsContainer) resultsContainer.innerHTML = ''
				toggleClearButton(input, clearButton)
				if (
					wrapper &&
					container &&
					wrapper.classList.contains('hero-input__wrapper')
				) {
					wrapper.style.zIndex = '19'
					container.style.zIndex = '19'
				}
				input.blur()
			})
			body.style.overflow = ''
			window.scrollTo(0, scrollPosition)
		}
	})
})
