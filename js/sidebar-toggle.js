document.addEventListener('DOMContentLoaded', function () {
	const buttons = document.querySelectorAll('.category-widget__title')

	buttons.forEach(button => {
		button.addEventListener('click', function () {
			const categoryId = this.getAttribute('data-category-id')
			const postList = document.querySelector(
				`.category-widget__post-list[data-category-id="${categoryId}"]`
			)

			if (postList) {
				const isActive = this.classList.contains('is-active')

				// Закрываем все списки
				document.querySelectorAll('.category-widget__title').forEach(btn => {
					btn.classList.remove('is-active')
				})
				document
					.querySelectorAll('.category-widget__post-list')
					.forEach(list => {
						list.classList.remove('active')
						list.style.display = 'none'
					})

				// Открываем/закрываем текущий список
				if (!isActive) {
					this.classList.add('is-active')
					postList.classList.add('active')
					postList.style.display = 'flex'
				}
			}
		})
	})
})
