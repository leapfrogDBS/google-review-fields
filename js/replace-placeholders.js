document.addEventListener('DOMContentLoaded', () => {
    // Ensure acfData is available from PHP.
    if (typeof acfData !== 'undefined') {
        // Fetch the values passed from PHP.
        const reviewCount = acfData.reviewCount || 'N/A';
        const averageRating = acfData.averageRating || 'N/A';

        // Replace placeholders in the DOM.
        document.body.innerHTML = document.body.innerHTML
            .replace(/{{review-count}}/g, reviewCount)
            .replace(/{{average-rating}}/g, averageRating);
    }
});
