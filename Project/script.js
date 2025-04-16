const form = document.getElementById('travel-form');
const resultsDiv = document.getElementById('results');
const loadingDiv = document.getElementById('loading');
const submitBtn = document.getElementById('submit-btn');
const errorMessageDiv = document.getElementById('error-message');

form.addEventListener('submit', async(event) => {
    event.preventDefault(); // Prevent default form submission

    // Clear previous results and errors
    resultsDiv.innerHTML = '';
    errorMessageDiv.classList.add('hidden');
    errorMessageDiv.textContent = '';

    // Show loading indicator and disable button
    loadingDiv.style.display = 'block';
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

    // Get form data
    const formData = new FormData(form);

    // No need to manually add interests here, PHP handles the array

    try {
        // CRITICAL: Ensure this relative path 'api/generate_itinerary.php'
        // correctly points to your PHP script from the location of index.html
        const response = await fetch('api/generate_itinerary.php', {
            method: 'POST',
            body: formData // Send form data directly
        });

        // --- Error Handling ---
        if (!response.ok) {
            // Read the body ONCE as text, regardless of content type
            const errorBodyText = await response.text();
            let errorText = `HTTP error! Status: ${response.status}`; // Base error

            try {
                // Try to parse the text we already read as JSON (sent by our PHP script on error)
                const errorData = JSON.parse(errorBodyText);
                if (errorData && errorData.error) {
                    // Use the specific error message from our PHP JSON response
                    errorText = errorData.error;
                } else if (errorBodyText) {
                    // If JSON parsing worked but no 'error' field, or fallback
                    errorText = `${errorText}: ${errorBodyText.substring(0, 200)}...`; // Show snippet
                }
            } catch (parseError) {
                // JSON parsing failed: Likely a server error page (HTML) or plain text
                if (errorBodyText) {
                    // Show snippet of the raw non-JSON error response
                    errorText = `${errorText}: ${errorBodyText.substring(0, 200)}...`;
                }
                // If errorBodyText is empty, the default errorText remains
            }
            // Throw the constructed error message to be caught below
            throw new Error(errorText);
        }

        // --- Success Handling ---
        // If response.ok was true, the body stream is still available
        const data = await response.json(); // Read the expected JSON response

        // Check for application-level errors returned in the JSON payload
        if (data.error) {
            throw new Error(data.error);
        }

        // Check if the expected HTML content exists
        if (data.itinerary_html) {
            // Display the formatted HTML itinerary from PHP
            resultsDiv.innerHTML = data.itinerary_html;
            applyCardStyles(); // Apply transition effect after content is added
        } else {
            // The JSON was valid, but didn't contain the expected data
            throw new Error("Received invalid response structure from server (missing itinerary_html).");
        }

    } catch (error) {
        // Catch errors from fetch() itself (network errors) or errors thrown above
        console.error('Error fetching itinerary:', error);
        errorMessageDiv.textContent = `Failed to generate itinerary: ${error.message}`;
        errorMessageDiv.classList.remove('hidden');
    } finally {
        // This block always runs, whether try succeeded or failed
        // Hide loading indicator and re-enable button
        loadingDiv.style.display = 'none';
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
});

// Function to apply card transition effect slightly after they are added to DOM
function applyCardStyles() {
    // Select cards that have the initial state class
    const cards = resultsDiv.querySelectorAll('.day-card.day-card-initial');
    // Use setTimeout to allow browser to render elements first before starting transition
    setTimeout(() => {
        cards.forEach(card => {
            // Add classes to trigger the transition
            card.classList.add('opacity-100', 'transform', 'scale-100');
            // Remove the initial state class now that transition is triggered
            card.classList.remove('day-card-initial', 'opacity-0', 'scale-95');
        });
    }, 50); // Small delay (50ms)
}