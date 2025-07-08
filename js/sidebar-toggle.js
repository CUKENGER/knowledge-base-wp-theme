document.addEventListener('DOMContentLoaded', () => {
	const buttons = document.querySelectorAll('.sidebar__category-title')
	const postButtons = document.querySelectorAll('.sidebar__post-item')

	if (!buttons.length) {
		console.warn('Список кнопок категорий пуст.')
		return
	}

	buttons.forEach(button => {
		button.addEventListener('click', () => {
			const categoryId = button.dataset.categoryId
			const postList = document.querySelector(
				`.sidebar__post-list[data-category-id="${categoryId}"]`
			)
			const isActive = button.classList.contains('is-active')

			if (!postList) {
				console.error(`Список постов для категории ${categoryId} не найден.`)
				return
			}

			document.querySelectorAll('.sidebar__category-title').forEach(btn => {
				btn.classList.remove('is-active')
				btn.setAttribute('aria-expanded', 'false')
			})
			document.querySelectorAll('.sidebar__post-list').forEach(list => {
				list.classList.remove('active')
			})

			if (!isActive) {
				button.classList.add('is-active')
				button.setAttribute('aria-expanded', 'true')
				postList.classList.add('active')
			}
		})
	})

	postButtons.forEach(button => {
		button.addEventListener('click', e => {
			e.preventDefault()
			const link = button.querySelector('.sidebar__post-link')
			const href = link.getAttribute('href')
			const postId = button.dataset.postId
			const childList = document.querySelector(
				`.sidebar__child-list[data-post-id="${postId}"]`
			)

			console.log('Клик по посту:', {
				postId,
				hasChildList: !!childList,
				buttonIsActive: button.classList.contains('is-active'),
				childListIsActive: childList
					? childList.classList.contains('active')
					: null,
			})

			// Если есть дочерние посты и текущая страница — это родительский пост
			if (childList && postId === String(window.currentPostId)) {
				const isActive = childList.classList.contains('active')

				document.querySelectorAll('.sidebar__post-item').forEach(btn => {
					btn.classList.remove('is-active')
					btn.setAttribute('aria-expanded', 'false')
				})
				document.querySelectorAll('.sidebar__child-list').forEach(list => {
					list.classList.remove('active')
				})

				if (!isActive) {
					button.classList.add('is-active')
					button.setAttribute('aria-expanded', 'true')
					childList.classList.add('active')
					console.log('Список дочерних открыт:', {
						postId,
						ariaExpanded: button.getAttribute('aria-expanded'),
					})
				} else {
					button.classList.remove('is-active')
					button.setAttribute('aria-expanded', 'false')
					childList.classList.remove('active')
					console.log('Список дочерних закрыт:', {
						postId,
						ariaExpanded: button.getAttribute('aria-expanded'),
					})
				}
				return
			}

			// Переход на страницу поста
			if (href !== window.location.href) {
				const parentId = button.dataset.postId
				window.location.href = href + '?open_parent=' + parentId
			}
		})
	})

	const childButtons = document.querySelectorAll('.sidebar__child-item')
	childButtons.forEach(button => {
		button.addEventListener('click', e => {
			e.preventDefault()
			const href = button.getAttribute('href')
			const postId = href.split('/').filter(Boolean).pop()

			if (
				postId === String(window.currentPostId) ||
				href === window.location.href
			) {
				return
			}

			window.location.href = href
		})
	})

	const urlParams = new URLSearchParams(window.location.search)
	const openParentId = urlParams.get('open_parent')
	if (openParentId) {
		const parentButton = document.querySelector(
			`.sidebar__post-item[data-post-id="${openParentId}"]`
		)
		const childList = document.querySelector(
			`.sidebar__child-list[data-post-id="${openParentId}"]`
		)
		if (parentButton && childList) {
			parentButton.classList.add('is-active')
			parentButton.setAttribute('aria-expanded', 'true')
			childList.classList.add('active')
		}
	}
})
