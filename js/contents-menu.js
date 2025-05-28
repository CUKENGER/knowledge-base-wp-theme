// contents-menu.js
document.addEventListener('DOMContentLoaded', () => {
	const contentsBtn = document.querySelector('.page-header__contents-btn')
	const contentsMenu = document.querySelector('.page-header__contents-menu')
	const overlay = document.querySelector('.overlay')
	const body = document.body
	let scrollPosition = 0

	if (!contentsBtn || !contentsMenu || !overlay) {
		console.error('Отсутствуют элементы для меню:', {
			contentsBtn,
			contentsMenu,
			overlay,
		})
		return
	}

	// Открытие/закрытие меню
	contentsBtn.addEventListener('click', e => {
		e.stopPropagation()
		const isExpanded = contentsBtn.getAttribute('aria-expanded') === 'true'
		contentsBtn.setAttribute('aria-expanded', !isExpanded)
		contentsMenu.classList.toggle('active')

		if (!isExpanded) {
			scrollPosition = window.scrollY
			overlay.style.zIndex = '100' // Увеличиваем z-index для overlay
			contentsMenu.style.zIndex = '101' // Меню выше overlay
			overlay.classList.add('active')
			body.classList.add('overlay-active')
			body.style.overflow = 'hidden'
			const firstItem = contentsMenu.querySelector('.contents-menu__item')
			if (firstItem) firstItem.focus() // Фокус на первый пункт
		} else {
			overlay.style.zIndex = '' // Сбрасываем z-index
			contentsMenu.style.zIndex = ''
			overlay.classList.remove('active')
			body.classList.remove('overlay-active')
			body.style.overflow = ''
			window.scrollTo(0, scrollPosition)
		}
	})

	// Закрытие меню кнопкой "Закрыть"
	const closeBtn = document.querySelector('.contents-menu__btn')
	if (closeBtn) {
		closeBtn.addEventListener('click', e => {
			e.stopPropagation()
			if (contentsMenu.classList.contains('active')) {
				contentsMenu.classList.remove('active')
				contentsBtn.setAttribute('aria-expanded', 'false')
				overlay.style.zIndex = ''
				contentsMenu.style.zIndex = ''
				overlay.classList.remove('active')
				body.classList.remove('overlay-active')
				body.style.overflow = ''
				window.scrollTo(0, scrollPosition)
			}
		})
	}

	// Закрытие меню при клике вне его
	document.addEventListener('click', e => {
		if (
			!contentsMenu.contains(e.target) &&
			!contentsBtn.contains(e.target) &&
			contentsMenu.classList.contains('active')
		) {
			contentsMenu.classList.remove('active')
			contentsBtn.setAttribute('aria-expanded', 'false')
			overlay.style.zIndex = ''
			contentsMenu.style.zIndex = ''
			overlay.classList.remove('active')
			body.classList.remove('overlay-active')
			body.style.overflow = ''
			window.scrollTo(0, scrollPosition)
		}
	})

	// Закрытие меню по Esc
	document.addEventListener('keydown', e => {
		if (e.key === 'Escape' && contentsMenu.classList.contains('active')) {
			contentsMenu.classList.remove('active')
			contentsBtn.setAttribute('aria-expanded', 'false')
			overlay.style.zIndex = ''
			contentsMenu.style.zIndex = ''
			overlay.classList.remove('active')
			body.classList.remove('overlay-active')
			body.style.overflow = ''
			window.scrollTo(0, scrollPosition)
		}
	})

	// Закрытие меню при клике на overlay
	overlay.addEventListener('click', e => {
		if (e.target === overlay && contentsMenu.classList.contains('active')) {
			contentsMenu.classList.remove('active')
			contentsBtn.setAttribute('aria-expanded', 'false')
			overlay.style.zIndex = ''
			contentsMenu.style.zIndex = ''
			overlay.classList.remove('active')
			body.classList.remove('overlay-active')
			body.style.overflow = ''
			window.scrollTo(0, scrollPosition)
		}
	})
})
