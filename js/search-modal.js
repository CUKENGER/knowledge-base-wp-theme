document.addEventListener('DOMContentLoaded', () => {
	const inputs = document.querySelectorAll(
		'.hero-input, .sidebar-input, .page-header__input'
	)
	const overlay = document.querySelector('.overlay')
	const body = document.body
	let scrollPosition = 0
	let isFocusing = false
	let isProcessingClick = false
	let lastClickTime = 0

	function toggleClearButton(input, clearButton) {
		if (clearButton) {
			clearButton.style.display = input.value.trim() ? 'flex' : 'none'
		}
	}

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
						link.className = 'search-result-item show'
						link.innerHTML = `
                            <span>${item.title}</span>
                            <svg class="card-btn__icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
                                <use href="#chevron-icon"></use>
                            </svg>
                        `
						link.addEventListener('click', e => {
							// e.preventDefault()
							e.stopPropagation()
							input.blur()
							resultsContainer.classList.remove('active')
							resultsContainer.innerHTML = ''
							overlay.classList.remove('active')
							body.classList.remove('overlay-active')
							body.style.overflow = ''
							window.scrollTo(0, scrollPosition)
							setZIndex(false)
							window.location.href = item.link
						})
						resultsContainer.appendChild(link)
					})
					resultsContainer.classList.add('active')
				} else {
					resultsContainer.innerHTML = '<p>Ничего не найдено</p>'
					resultsContainer.classList.add('active')
				}
			})
			.catch(error => {
				console.error('Ошибка поиска:', error)
				resultsContainer.innerHTML = '<p>Ошибка поиска</p>'
				resultsContainer.classList.add('active')
			})
	}

	function setZIndex(isFocused, wrapper, container, pageHeader) {
		if (
			wrapper.classList.contains('hero-input__wrapper') ||
			wrapper.classList.contains('page-header__wrapper')
		) {
			wrapper.style.zIndex = isFocused ? '30' : '19'
			container.style.zIndex = isFocused ? '30' : '19'
			if (pageHeader) {
				pageHeader.style.zIndex = isFocused ? '30' : '10'
			}
			if (container.classList.contains('page-header__input-container')) {
				container.classList.toggle('expanded', isFocused)
			}
		}
	}

	inputs.forEach(input => {
		const wrapper = input.closest(
			'.hero-input__wrapper, .sidebar-input__wrapper, .page-header__wrapper'
		)
		const container = input.closest(
			'.hero-input__container, .sidebar-input__container, .page-header__input-container'
		)
		const clearButton = wrapper?.querySelector(
			'.hero-input-clear, .sidebar-input-clear, .page-header__input-clear'
		)
		const resultsContainer = container?.querySelector(
			'.search-results, .sidebar-input__search-results, .page-header__search-results'
		)
		const pageHeader = container?.closest('.page-header--tablet')

		if (!input || !resultsContainer || !container) {
			console.error('Отсутствуют элементы для инпута:', {
				input,
				resultsContainer,
				container,
			})
			return
		}

		// Обработчик клика только для page-header__input-container
		if (container.classList.contains('page-header__input-container')) {
			container.addEventListener('click', e => {
				const now = Date.now()
				if (
					!e.target.closest('.search-result-item') &&
					!e.target.closest('.page-header__input-clear') &&
					document.activeElement !== input &&
					e.target !== input &&
					!isProcessingClick &&
					now - lastClickTime > 300
				) {
					e.preventDefault()
					isProcessingClick = true
					lastClickTime = now
					isFocusing = true
					setZIndex(true, wrapper, container, pageHeader)
					input.focus()
					isFocusing = false
					isProcessingClick = false
				}
			})
		}

		input.addEventListener('focus', () => {
			if (body.style.overflow !== 'hidden') {
				scrollPosition = window.scrollY
				overlay.classList.add('active')
				body.classList.add('overlay-active')
				body.style.overflow = 'hidden'
			}
			toggleClearButton(input, clearButton)
			setZIndex(true, wrapper, container, pageHeader)
		})

		input.addEventListener('blur', e => {
			const now = Date.now()
			if (isFocusing || now - lastClickTime < 300) {
				return
			}
			const isResultsClicked = resultsContainer.contains(e.relatedTarget)
			const isInputFocused = input === document.activeElement
			const isWrapperClicked = wrapper.contains(e.relatedTarget)
			if (!isResultsClicked && !isInputFocused && !isWrapperClicked) {
				overlay.classList.remove('active')
				body.classList.remove('overlay-active')
				resultsContainer.classList.remove('active')
				body.style.overflow = ''
				window.scrollTo(0, scrollPosition)
				setZIndex(false, wrapper, container, pageHeader)
			}
		})

		input.addEventListener('input', () => {
			const query = input.value.trim()
			toggleClearButton(input, clearButton)
			performSearch(query, resultsContainer)
		})

		if (clearButton) {
			const clearHandler = e => {
				e.preventDefault()
				e.stopPropagation()
				input.value = ''
				resultsContainer.classList.remove('active')
				resultsContainer.innerHTML = ''
				toggleClearButton(input, clearButton)
				input.focus()
			}
			clearButton.addEventListener('click', clearHandler)
			clearButton.addEventListener('mousedown', e => {
				e.preventDefault()
				clearHandler(e)
			})
		}
	})

	if (overlay) {
		overlay.addEventListener('click', e => {
			if (e.target === overlay) {
				overlay.classList.remove('active')
				body.classList.remove('overlay-active')
				inputs.forEach(input => {
					const wrapper = input.closest(
						'.hero-input__wrapper, .sidebar-input__wrapper, .page-header__wrapper'
					)
					const clearButton = wrapper?.querySelector(
						'.hero-input-clear, .sidebar-input-clear, .page-header__input-clear'
					)
					const container = input.closest(
						'.hero-input__container, .sidebar-input__container, .page-header__input-container'
					)
					const resultsContainer = container?.querySelector(
						'.search-results, .sidebar-input__search-results, .page-header__search-results'
					)
					const pageHeader = container?.closest('.page-header--tablet')
					input.value = ''
					if (resultsContainer) resultsContainer.classList.remove('active')
					if (resultsContainer) resultsContainer.innerHTML = ''
					toggleClearButton(input, clearButton)
					if (
						wrapper &&
						container &&
						(wrapper.classList.contains('hero-input__wrapper') ||
							wrapper.classList.contains('page-header__wrapper'))
					) {
						wrapper.style.zIndex = '19'
						container.style.zIndex = '19'
						if (container.classList.contains('page-header__input-container')) {
							container.classList.remove('expanded')
						}
						if (pageHeader) {
							pageHeader.style.zIndex = '10'
						}
					}
				})
				body.style.overflow = ''
				window.scrollTo(0, scrollPosition)
				inputs.forEach(input => input.blur())
			}
		})
	}

	document.addEventListener('keydown', e => {
		if (e.key === 'Escape' && overlay.classList.contains('active')) {
			overlay.classList.remove('active')
			body.classList.remove('overlay-active')
			inputs.forEach(input => {
				const wrapper = input.closest(
					'.hero-input__wrapper, .sidebar-input__wrapper, .page-header__wrapper'
				)
				const clearButton = wrapper?.querySelector(
					'.hero-input-clear, .sidebar-input-clear, .page-header__input-clear'
				)
				const container = input.closest(
					'.hero-input__container, .sidebar-input__container, .page-header__input-container'
				)
				const resultsContainer = container?.querySelector(
					'.search-results, .sidebar-input__search-results, .page-header__search-results'
				)
				const pageHeader = container?.closest('.page-header--tablet')
				input.value = ''
				if (resultsContainer) resultsContainer.classList.remove('active')
				if (resultsContainer) resultsContainer.innerHTML = ''
				toggleClearButton(input, clearButton)
				if (
					wrapper &&
					container &&
					(wrapper.classList.contains('hero-input__wrapper') ||
						wrapper.classList.contains('page-header__wrapper'))
				) {
					wrapper.style.zIndex = '19'
					container.style.zIndex = '19'
					if (container.classList.contains('page-header__input-container')) {
						container.classList.remove('expanded')
					}
					if (pageHeader) {
						pageHeader.style.zIndex = '10'
					}
				}
				input.blur()
			})
			body.style.overflow = ''
			window.scrollTo(0, scrollPosition)
		}
	})
})
