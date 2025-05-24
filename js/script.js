document.addEventListener('DOMContentLoaded', () => {
	const menuContainer = document.querySelector('.header-menu__container')
	const menuIcon = document.querySelector('.menu-icon')
	const closeIcon = document.querySelector('.close-icon')
	const dropdown = document.querySelector('.header-menu__dropdown')
	const overlay = document.querySelector('.header-menu__overlay')
	const body = document.body
	let scrollPosition = 0

	// Переключение меню только на мобильных
	menuContainer.addEventListener('click', e => {
		if (window.innerWidth <= 500) {
			e.stopPropagation()
			const isActive = menuContainer.classList.toggle('active')
			if (isActive) {
				scrollPosition = window.scrollY
				body.style.overflow = 'hidden'
			} else {
				body.style.overflow = ''
				window.scrollTo(0, scrollPosition)
			}
		}
	})

	// Предотвращаем закрытие при клике внутри dropdown
	dropdown.addEventListener('click', e => {
		e.stopPropagation()
	})

	// Закрытие по клику вне области или на overlay
	document.addEventListener('click', e => {
		if (
			!menuContainer.contains(e.target) &&
			menuContainer.classList.contains('active')
		) {
			menuContainer.classList.remove('active')
			body.style.overflow = ''
			window.scrollTo(0, scrollPosition)
		}
	})

	// Закрытие по Esc
	document.addEventListener('keydown', e => {
		if (e.key === 'Escape' && menuContainer.classList.contains('active')) {
			menuContainer.classList.remove('active')
			body.style.overflow = ''
			window.scrollTo(0, scrollPosition)
		}
	})
})

document.addEventListener('DOMContentLoaded', () => {
	const input = document.querySelector('.page-header__input')
	const inputContainer = document.querySelector('.page-header__input-container')

	if (!input || !inputContainer) {
		console.error('Required elements not found')
		return
	}

	input.addEventListener('focus', () => {
		inputContainer.classList.add('expanded')
	})

	input.addEventListener('blur', () => {
		inputContainer.classList.remove('expanded')
	})
})
