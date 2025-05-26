/**
 * Search modal functionality for hero input.
 */
document.addEventListener('DOMContentLoaded', () => {
	const inputs = document.querySelectorAll('.hero-input')
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
						link.addEventListener('click', () => {
							input.blur()
							resultsContainer.classList.remove('active')
							resultsContainer.innerHTML = ''
							overlay.classList.remove('active')
							body.classList.remove('overlay-active')
							body.style.overflow = ''
							window.scrollTo(0, scrollPosition)
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

	function setZIndex(isFocused, wrapper, container) {
		if (wrapper.classList.contains('hero-input__wrapper')) {
			wrapper.style.zIndex = isFocused ? '30' : '19'
			container.style.zIndex = isFocused ? '30' : '19'
		}
	}

	inputs.forEach(input => {
		const wrapper = input.closest('.hero-input__wrapper')
		const container = input.closest('.hero-input__container')
		const clearButton = wrapper?.querySelector('.hero-input-clear')
		const resultsContainer = container?.querySelector('.search-results')

		if (!input || !resultsContainer || !container) {
			console.error('Отсутствуют элементы для инпута:', {
				input,
				resultsContainer,
				container,
			})
			return
		}

		input.addEventListener('focus', () => {
			if (body.style.overflow !== 'hidden') {
				scrollPosition = window.scrollY
				overlay.classList.add('active')
				body.classList.add('overlay-active')
				body.style.overflow = 'hidden'
			}
			toggleClearButton(input, clearButton)
			setZIndex(true, wrapper, container)
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
				setZIndex(false, wrapper, container)
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
					const wrapper = input.closest('.hero-input__wrapper')
					const clearButton = wrapper?.querySelector('.hero-input-clear')
					const container = input.closest('.hero-input__container')
					const resultsContainer = container?.querySelector('.search-results')
					input.value = ''
					if (resultsContainer) resultsContainer.classList.remove('active')
					if (resultsContainer) resultsContainer.innerHTML = ''
					toggleClearButton(input, clearButton)
					if (wrapper && container) {
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

	document.addEventListener('keydown', e => {
		if (e.key === 'Escape' && overlay.classList.contains('active')) {
			overlay.classList.remove('active')
			body.classList.remove('overlay-active')
			inputs.forEach(input => {
				const wrapper = input.closest('.hero-input__wrapper')
				const clearButton = wrapper?.querySelector('.hero-input-clear')
				const container = input.closest('.hero-input__container')
				const resultsContainer = container?.querySelector('.search-results')
				input.value = ''
				if (resultsContainer) resultsContainer.classList.remove('active')
				if (resultsContainer) resultsContainer.innerHTML = ''
				toggleClearButton(input, clearButton)
				if (wrapper && container) {
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
