document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('.page-header__category-title');
    const postButtons = document.querySelectorAll('.page-header__post-item');
    const contentsButton = document.querySelector('.page-header__contents-btn');
    const contentsMenu = document.querySelector('.page-header__contents-menu');
    const closeButton = document.querySelector('.contents-menu__btn');

    if (!buttons.length) {
        console.warn('Список кнопок категорий пуст.');
        return;
    }

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            const categoryId = button.dataset.categoryId;
            const postList = document.querySelector(
                `.page-header__post-list[data-category-id="${categoryId}"]`
            );
            const isActive = button.classList.contains('is-active');

            if (!postList) {
                console.error(`Список постов для категории ${categoryId} не найден.`);
                return;
            }

            document.querySelectorAll('.page-header__category-title').forEach(btn => {
                btn.classList.remove('is-active');
                btn.setAttribute('aria-expanded', 'false');
            });
            document.querySelectorAll('.page-header__post-list').forEach(list => {
                list.classList.remove('active');
            });

            if (!isActive) {
                button.classList.add('is-active');
                button.setAttribute('aria-expanded', 'true');
                postList.classList.add('active');
            }
        });
    });

    postButtons.forEach(button => {
        button.addEventListener('click', e => {
            e.preventDefault();
            const link = button.querySelector('.page-header__post-link');
            const href = link.getAttribute('href');
            const postId = button.dataset.postId;
            const childList = document.querySelector(
                `.page-header__child-list[data-post-id="${postId}"]`
            );

            console.log('Клик по посту:', {
                postId,
                hasChildList: !!childList,
                buttonIsActive: button.classList.contains('is-active'),
                childListIsActive: childList ? childList.classList.contains('active') : null,
            });

            if (childList && postId === String(currentPostId)) {
                const isActive = childList.classList.contains('active');

                document.querySelectorAll('.page-header__post-item').forEach(btn => {
                    btn.classList.remove('is-active');
                    btn.setAttribute('aria-expanded', 'false');
                });
                document.querySelectorAll('.page-header__child-list').forEach(list => {
                    list.classList.remove('active');
                });

                if (!isActive) {
                    button.classList.add('is-active');
                    button.setAttribute('aria-expanded', 'true');
                    childList.classList.add('active');
                    console.log('Список дочерних открыт:', {
                        postId,
                        ariaExpanded: button.getAttribute('aria-expanded'),
                    });
                } else {
                    button.classList.remove('is-active');
                    button.setAttribute('aria-expanded', 'false');
                    childList.classList.remove('active');
                    console.log('Список дочерних закрыт:', {
                        postId,
                        ariaExpanded: button.getAttribute('aria-expanded'),
                    });
                }
                return;
            }

            if (href !== window.location.href) {
                const parentId = button.dataset.postId;
                window.location.href = href + '?open_parent=' + parentId;
            }
        });
    });

    const childButtons = document.querySelectorAll('.page-header__child-item');
    childButtons.forEach(button => {
        button.addEventListener('click', e => {
            e.preventDefault();
            const href = button.getAttribute('href');
            const postId = href.split('/').filter(Boolean).pop();

            if (
                postId === String(currentPostId) ||
                href === window.location.href
            ) {
                return;
            }

            window.location.href = href;
        });
    });

    const urlParams = new URLSearchParams(window.location.search);
    const openParentId = urlParams.get('open_parent');
    if (openParentId) {
        const parentButton = document.querySelector(
            `.page-header__post-item[data-post-id="${openParentId}"]`
        );
        const childList = document.querySelector(
            `.page-header__child-list[data-post-id="${openParentId}"]`
        );
        if (parentButton && childList) {
            parentButton.classList.add('is-active');
            parentButton.setAttribute('aria-expanded', 'true');
            childList.classList.add('active');
        }
    }
});