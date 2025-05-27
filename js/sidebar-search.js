/**
 * Sidebar search functionality.
 * Handles search input, clear button, results display, and overlay in the sidebar.
 */
document.addEventListener('DOMContentLoaded', () => {
	const input = document.querySelector('.sidebar__search-input')
	const clearButton = document.querySelector('.sidebar__search-clear')
	const resultsContainer = document.querySelector('.sidebar__search-results')
	const overlay = document.querySelector('.overlay')
	const body = document.body
	let scrollPosition = 0

	if (!input || !clearButton || !resultsContainer || !overlay) {
		console.warn('Sidebar search elements or overlay not found:', {
			input,
			clearButton,
			resultsContainer,
			overlay,
		})
		return
	}

	// Toggle clear button visibility
	function toggleClearButton() {
		clearButton.style.display = input.value.trim() ? 'block' : 'none'
	}

	// Perform AJAX search
	function performSearch(query) {
		if (!query) {
			resultsContainer.classList.remove('active')
			resultsContainer.innerHTML = ''
			overlay.classList.remove('active')
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
						link.className = 'sidebar-search-result-item'
						link.innerHTML = `
                            <span>${item.title}</span>
                            <svg class="sidebar__post-icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
                                <use href="#chevron-icon"></use>
                            </svg>
                        `
						link.addEventListener('click', () => {
							input.value = ''
							resultsContainer.classList.remove('active')
							resultsContainer.innerHTML = ''
							toggleClearButton()
							body.style.overflow = ''
							overlay.classList.remove('active')
							window.scrollTo(0, scrollPosition)
						})
						resultsContainer.appendChild(link)
					})
					resultsContainer.classList.add('active')
					overlay.classList.add('active')
				} else {
					resultsContainer.innerHTML =
						'<p class="sidebar__no-posts">Ничего не найдено</p>'
					resultsContainer.classList.add('active')
					overlay.classList.add('active')
				}
			})
			.catch(error => {
				console.error('Ошибка поиска:', error)
				resultsContainer.innerHTML =
					'<p class="sidebar__no-posts">Ошибка поиска</p>'
				resultsContainer.classList.add('active')
				overlay.classList.add('active')
			})
	}

	// Handle input focus
	input.addEventListener('focus', () => {
		scrollPosition = window.scrollY
		body.style.overflow = 'hidden'
		overlay.classList.add('active')
		toggleClearButton()
	})

	// Handle input blur
	input.addEventListener('blur', e => {
		const isResultsClicked = resultsContainer.contains(e.relatedTarget)
		if (!isResultsClicked && input !== document.activeElement) {
			resultsContainer.classList.remove('active')
			resultsContainer.innerHTML = ''
			body.style.overflow = ''
			overlay.classList.remove('active')
			window.scrollTo(0, scrollPosition)
		}
	})

	// Handle input changes
	input.addEventListener('input', () => {
		const query = input.value.trim()
		toggleClearButton()
		performSearch(query)
	})

	// Handle clear button click
	clearButton.addEventListener('click', e => {
		e.preventDefault()
		e.stopPropagation()
		input.value = ''
		resultsContainer.classList.remove('active')
		resultsContainer.innerHTML = ''
		toggleClearButton()
		// overlay.classList.remove('active')
		input.focus()
	})

	// Prevent default mousedown behavior on clear button
	clearButton.addEventListener('mousedown', e => {
		e.preventDefault()
	})

	// Handle Escape key
	document.addEventListener('keydown', e => {
		if (e.key === 'Escape' && input === document.activeElement) {
			input.value = ''
			resultsContainer.classList.remove('active')
			resultsContainer.innerHTML = ''
			toggleClearButton()
			body.style.overflow = ''
			overlay.classList.remove('active')
			window.scrollTo(0, scrollPosition)
			input.blur()
		}
	})
})
