/**
 * Page header category toggle functionality.
 * Handles opening/closing of post lists in the page header with smooth animation.
 */
document.addEventListener('DOMContentLoaded', () => {
	const buttons = document.querySelectorAll('.page-header__category-title')

	if (!buttons.length) {
		console.warn('No page header category buttons found.')
		return
	}

	buttons.forEach(button => {
		button.addEventListener('click', () => {
			const categoryId = button.dataset.categoryId
			const postList = document.querySelector(
				`.page-header__post-list[data-category-id="${categoryId}"]`
			)
			const isActive = button.classList.contains('is-active')

			if (!postList) {
				console.error(`Post list for category ID ${categoryId} not found.`)
				return
			}

			// Close all lists and remove active states
			document.querySelectorAll('.page-header__category-title').forEach(btn => {
				btn.classList.remove('is-active')
				btn.setAttribute('aria-expanded', 'false')
			})
			document.querySelectorAll('.page-header__post-list').forEach(list => {
				list.style.display = 'none'
			})

			// Open current list if it was not active
			if (!isActive) {
				button.classList.add('is-active')
				button.setAttribute('aria-expanded', 'true')
				postList.style.display = 'flex'
			}
		})
	})
})
