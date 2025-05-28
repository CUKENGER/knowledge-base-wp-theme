/**
 * Page header search functionality.
 * Handles search input, clear button, and results display in the page header.
 */
document.addEventListener('DOMContentLoaded', () => {
	const input = document.querySelector('.page-header__input')
	const clearButton = document.querySelector('.page-header__input-clear')
	const resultsContainer = document.querySelector(
		'.page-header__search-results'
	)
	const body = document.body
	const overlay = document.querySelector('.overlay')
	const wrapper = document.querySelector('.page-header__input-container')
	const pageHeader = document.querySelector('.page-header--tablet')
	let scrollPosition = 0

	if (
		!input ||
		!clearButton ||
		!resultsContainer ||
		!overlay ||
		!wrapper ||
		!pageHeader
	) {
		console.warn('Page header search elements not found:', {
			input,
			clearButton,
			resultsContainer,
			overlay,
			wrapper,
			pageHeader,
		})
		return
	}

	// Toggle clear button visibility
	function toggleClearButton() {
		clearButton.style.display = input.value.trim() ? 'flex' : 'none'
	}

	// Perform AJAX search
	function performSearch(query) {
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
						link.className = 'page-header-search-result-item show'
						link.innerHTML = `
							<span>${item.title}</span>
							<svg class="page-header__post-icon" width="8" height="16" viewBox="0 0 8 16" aria-hidden="true">
								<use href="#chevron-icon"></use>
							</svg>
						`
						link.addEventListener('click', () => {
							input.value = ''
							resultsContainer.classList.remove('active')
							resultsContainer.innerHTML = ''
							toggleClearButton()
							overlay.classList.remove('active')
							body.classList.remove('overlay-active')
							body.style.overflow = ''
							wrapper.classList.remove('expanded')
							pageHeader.style.zIndex = '10'
						})
						resultsContainer.appendChild(link)
					})
					resultsContainer.classList.add('active')
				} else {
					resultsContainer.innerHTML =
						'<p class="page-header__no-posts">Ничего не найдено</p>'
					resultsContainer.classList.add('active')
				}
			})
			.catch(error => {
				console.error('Ошибка поиска:', error)
				resultsContainer.innerHTML =
					'<p class="page-header__no-posts">Ошибка поиска</p>'
				resultsContainer.classList.add('active')
			})
	}

	// Handle input focus
	input.addEventListener('focus', () => {
		scrollPosition = window.scrollY
		overlay.classList.add('active')
		body.classList.add('overlay-active')
		body.style.overflow = 'hidden'
		toggleClearButton()
		wrapper.classList.add('expanded')
		pageHeader.style.zIndex = '30'
	})

	// Handle input blur
	input.addEventListener('blur', e => {
		const isResultsClicked = resultsContainer.contains(e.relatedTarget)
		const isClearButtonClicked = clearButton.contains(e.relatedTarget)
		if (!isResultsClicked && !isClearButtonClicked) {
			resultsContainer.classList.remove('active')
			resultsContainer.innerHTML = ''
			overlay.classList.remove('active')
			body.classList.remove('overlay-active')
			body.style.overflow = ''
			window.scrollTo(0, scrollPosition)
			wrapper.classList.remove('expanded')
			pageHeader.style.zIndex = '10'
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
		// Убрано wrapper.classList.remove('expanded') чтобы инпут не сворачивался
		pageHeader.style.zIndex = '30'
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
			overlay.classList.remove('active')
			body.classList.remove('overlay-active')
			body.style.overflow = ''
			wrapper.classList.remove('expanded')
			pageHeader.style.zIndex = '10'
			window.scrollTo(0, scrollPosition)
			input.blur()
		}
	})

	// Handle container click to focus input
	wrapper.addEventListener('click', e => {
		if (
			e.target !== input &&
			!e.target.closest('.page-header-search-result-item') &&
			!e.target.closest('.page-header__input-clear')
		) {
			input.focus()
		}
	})
})
