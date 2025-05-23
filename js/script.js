

document.addEventListener('DOMContentLoaded', () => {
	const input = document.querySelector('.hero-input')
	const overlay = document.querySelector('.overlay')

	overlay.addEventListener('click', () => {
		input.blur()
	})

	input.addEventListener('focus', () => {
		overlay.classList.add('active')
	})

	input.addEventListener('blur', () => {
		overlay.classList.remove('active')
	})
})

