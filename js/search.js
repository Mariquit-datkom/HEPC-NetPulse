document.addEventListener('DOMContentLoaded', () => {
    const searchBar = document.getElementById('search-bar');
    const items = document.querySelectorAll('.shelf-item');
    const noResultsMsg = document.querySelectorAll('.no-results');

    if (searchBar) {
        searchBar.addEventListener('keyup', (e) => {
            const searchString = e.target.value.toLowerCase();
            let visibleCount = 0;

            items.forEach((item) => {
                // Get IP from data-ip attribute defined in your PHP files
                const ipAddress = item.getAttribute('data-ip').toLowerCase();

                if (ipAddress.includes(searchString)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            noResultsMsg.forEach((msg) => {
                // Show message only if visibleCount is 0 AND user has typed something
                if (visibleCount === 0 && searchString !== "") {
                    msg.style.display = 'block';
                } else {
                    msg.style.display = 'none';
                }
            });
        });
    }
});