document.addEventListener('DOMContentLoaded', () => {
	const searchIcon = document.querySelector('.search-icon-container')
	const searchOverlay = document.querySelector('.search-overlay')
	const searchOverlayClose = document.querySelector('.search-overlay-close')
	const searchInput = document.querySelector('.header-search')
	const overlayInput = document.querySelector('.search-overlay-input')
	const searchClear = document.querySelector('.header-search-clear')
	const overlayClear = document.querySelector('.search-overlay-clear')
	const headerSearchResults = document.querySelector('.header-search-results')
	const overlaySearchResults = document.querySelector('.search-overlay-results')
	let scrollPosition = 0

	// Функция для управления видимостью крестика
	function toggleClearButton(input, clearButton) {
		if (clearButton) {
			clearButton.style.display = input.value.trim() ? 'flex' : 'none'
		}
	}

	// Инициализация состояния крестиков
	function initializeClearButtons() {
		if (searchInput && searchClear) {
			toggleClearButton(searchInput, searchClear)
		}
		if (overlayInput && overlayClear) {
			toggleClearButton(overlayInput, overlayClear)
		}
	}

	// Вызываем инициализацию
	initializeClearButtons()

	// Функция поиска
	function performSearch(query, resultsContainer) {
		if (!query) {
			resultsContainer.style.display = 'none'
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
					resultsContainer.style.display = 'flex'
				} else {
					resultsContainer.innerHTML = '<p>Ничего не найдено</p>'
					resultsContainer.style.display = 'flex'
				}
			})
			.catch(error => {
				console.error('Search error:', error)
				resultsContainer.innerHTML = '<p>Ошибка поиска</p>'
				resultsContainer.style.display = 'flex'
			})
	}

	// Обработчики для десктопного поиска
	if (searchInput && searchClear) {
		searchInput.addEventListener('input', () => {
			const query = searchInput.value.trim()
			toggleClearButton(searchInput, searchClear)
			performSearch(query, headerSearchResults)
		})

		searchClear.addEventListener('click', e => {
			e.preventDefault()
			searchInput.value = ''
			headerSearchResults.style.display = 'none'
			headerSearchResults.innerHTML = ''
			toggleClearButton(searchInput, searchClear)
			searchInput.focus()
		})
	}

	// Обработчики для модалки
	function openSearchOverlay() {
		scrollPosition = window.scrollY
		document.body.style.overflow = 'hidden'
		searchOverlay.classList.add('open')
		if (overlayInput) {
			overlayInput.focus()
			toggleClearButton(overlayInput, overlayClear)
		}
	}

	if (searchIcon) {
		searchIcon.removeEventListener('click', openSearchOverlay)
		if (window.innerWidth <= 550) {
			searchIcon.addEventListener('click', openSearchOverlay)
		}
		window.addEventListener('resize', () => {
			searchIcon.removeEventListener('click', openSearchOverlay)
			if (window.innerWidth <= 550) {
				searchIcon.addEventListener('click', openSearchOverlay)
			}
		})
	}

	if (searchOverlayClose && searchOverlay) {
		searchOverlayClose.addEventListener('click', () => {
			document.body.style.overflow = ''
			window.scrollTo(0, scrollPosition)
			searchOverlay.classList.remove('open')
			if (overlayInput) overlayInput.value = ''
			if (overlaySearchResults) overlaySearchResults.style.display = 'none'
			if (overlayClear) toggleClearButton(overlayInput, overlayClear)
		})
	}

	if (overlayInput && overlayClear) {
		overlayInput.addEventListener('input', () => {
			const query = overlayInput.value.trim()
			toggleClearButton(overlayInput, overlayClear)
			performSearch(query, overlaySearchResults)
		})

		overlayClear.addEventListener('click', e => {
			e.preventDefault()
			overlayInput.value = ''
			overlaySearchResults.style.display = 'none'
			overlaySearchResults.innerHTML = ''
			toggleClearButton(overlayInput, overlayClear)
			overlayInput.focus()
		})
	}

	// Закрытие модалки по клику вне области
	if (searchOverlay) {
		searchOverlay.addEventListener('click', e => {
			if (e.target === searchOverlay) {
				document.body.style.overflow = ''
				window.scrollTo(0, scrollPosition)
				searchOverlay.classList.remove('open')
				if (overlayInput) overlayInput.value = ''
				if (overlaySearchResults) overlaySearchResults.style.display = 'none'
				if (overlayClear) toggleClearButton(overlayInput, overlayClear)
			}
		})
	}

	// Закрытие модалки по Esc
	document.addEventListener('keydown', e => {
		if (e.key === 'Escape' && searchOverlay.classList.contains('open')) {
			document.body.style.overflow = ''
			window.scrollTo(0, scrollPosition)
			searchOverlay.classList.remove('open')
			if (overlayInput) overlayInput.value = ''
			if (overlaySearchResults) overlaySearchResults.style.display = 'none'
			if (overlayClear) toggleClearButton(overlayInput, overlayClear)
		}
	})
})
