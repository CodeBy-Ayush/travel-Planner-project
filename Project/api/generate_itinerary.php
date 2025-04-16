<?php


// --- Configuration ---

// Comment out ALL the previous API key loading logic for now:
// $apiKey = getenv('GEMINI_API_KEY');
/*
if (!$apiKey && file_exists(__DIR__ . '/../.env')) {
    // ... all the phpdotenv loading code ...
}
*/

// !!! TEMPORARY HARDCODING FOR DEBUGGING ONLY !!!
// !!! REMEMBER TO REMOVE THIS AFTER TESTING !!!
$apiKey = 'yourapikey';

// Keep the check, but it should always pass now if the key above is correct
if (empty($apiKey)) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'API key is not configured or could not be loaded. Check server environment variables or .env file.']);
    exit;
}

$geminiApiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key=" . $apiKey;
// --- Request Handling ---
header('Content-Type: application/json'); // We will always return JSON

// ... (Rest of the script remains the same) ...

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method. Only POST is accepted.']);
    exit;
}

// --- Input Validation & Sanitization ---
$destination = isset($_POST['destination']) ? htmlspecialchars(trim($_POST['destination']), ENT_QUOTES, 'UTF-8') : null;
$duration = isset($_POST['duration']) ? filter_var($_POST['duration'], FILTER_VALIDATE_INT) : null;
$budget = isset($_POST['budget']) ? htmlspecialchars(trim($_POST['budget']), ENT_QUOTES, 'UTF-8') : null;
$season = isset($_POST['season']) ? htmlspecialchars(trim($_POST['season']), ENT_QUOTES, 'UTF-8') : null;
$interests = isset($_POST['interests']) && is_array($_POST['interests']) ? $_POST['interests'] : [];

// Basic validation checks
$errors = [];
if (empty($destination)) $errors[] = 'Destination is required.';
if (empty($duration) || $duration <= 0) $errors[] = 'Valid number of days is required.';
if (empty($budget)) $errors[] = 'Budget level is required.';
if (empty($season)) $errors[] = 'Season is required.';
// Add more specific validation if needed (e.g., check budget/season against allowed values)

if (!empty($errors)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => implode(' ', $errors)]);
    exit;
}

// Sanitize interests array
$sanitizedInterests = [];
// Define allowed values strictly
$allowedInterests = ['Adventure', 'Culture', 'Nature', 'Food', 'Relaxation', 'Nightlife', 'Shopping', 'Art'];
foreach ($interests as $interest) {
    $cleanInterest = htmlspecialchars(trim($interest), ENT_QUOTES, 'UTF-8');
    // Only include allowed interests to prevent injection via checkbox values
    if (in_array($cleanInterest, $allowedInterests)) {
        $sanitizedInterests[] = $cleanInterest;
    }
}
$interestsList = !empty($sanitizedInterests) ? implode(', ', $sanitizedInterests) : 'general sightseeing and activities'; // More descriptive default


// --- Prompt Generation ---
// Clearer prompt structure
$prompt = <<<PROMPT
Generate a detailed, day-by-day travel itinerary suitable for a traveler visiting {$destination} for {$duration} days during the {$season} season.

Traveler Profile:
- Budget Level: {$budget}
- Key Interests: {$interestsList}

Itinerary Requirements:
- Provide a clear breakdown for each day (Day 1, Day 2, etc.).
- For each day, suggest:
    - **Places to Visit:** Specific landmarks, attractions, neighborhoods.
    - **Activities:** Concrete things to do (tours, experiences, walks, etc.).
    - **Food Suggestions:** Local dishes, types of eateries, or specific restaurant recommendations appropriate for the budget.
    - **Travel Tips:** Practical advice for that day or the trip (e.g., transportation, booking in advance, packing essentials for the season).
    - **Estimated Daily Cost:** A simple relative indicator (e.g., $, $$, $$$) reflecting the budget level.
- Format the output using Markdown:
    - Use headings (e.g., `## Day 1: Arrival and Exploration`) for each day.
    - Use bold text (e.g., `**Places to Visit:**`) for subheadings within each day.
    - Use bullet points (`* ` or `- `) for lists under subheadings.
- Start the itinerary directly with Day 1. Be engaging and practical.
PROMPT;


// --- Gemini API Call ---
$data = [
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ],
    // Optional: Add safety settings or generation config if needed
    // 'safetySettings' => [ ... ],
    // 'generationConfig' => [ 'temperature' => 0.7, ... ]
];

$jsonData = json_encode($data);

if ($jsonData === false) {
     http_response_code(500);
     echo json_encode(['error' => 'Failed to encode data for API request.']);
     exit;
}

// Use file_get_contents with stream context
$contextOptions = [
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/json\r\nAccept: application/json\r\n",
        'content' => $jsonData,
        'ignore_errors' => true, // VERY IMPORTANT: Read body even on HTTP errors (4xx, 5xx)
        'timeout' => 60 // Add a timeout (in seconds)
    ]
];
$context = stream_context_create($contextOptions);
$response = @file_get_contents($geminiApiUrl, false, $context); // Use @ to suppress warnings on failure

// --- Response Handling ---
if ($response === false) {
    // This could be a DNS issue, firewall, timeout, or API URL problem
    http_response_code(500);
    $errorInfo = error_get_last(); // Get more details if possible
    $connectError = $errorInfo ? $errorInfo['message'] : 'Reason unknown.';
    echo json_encode(['error' => 'Failed to connect to the Gemini API endpoint. ' . $connectError]);
    exit;
}

// Get HTTP status code from the special $http_response_header variable
$statusCode = 0;
if (isset($http_response_header[0])) {
    preg_match('{HTTP/\d\.\d\s(\d{3})}', $http_response_header[0], $match);
    if (isset($match[1])) {
        $statusCode = (int)$match[1];
    }
}

// Decode the JSON response from Gemini
$responseData = json_decode($response, true);

// Check for HTTP errors or JSON decoding errors
if ($statusCode === 0) { // Should have been set if connection was successful
    http_response_code(500);
    echo json_encode(['error' => 'Could not determine API response status code.']);
    exit;
}

if ($statusCode < 200 || $statusCode >= 300 || $responseData === null) {
    http_response_code($statusCode ?: 500); // Use API status code if available, else 500
    $apiError = 'Unknown API error or invalid JSON response.';
    if ($responseData !== null && isset($responseData['error']['message'])) {
        // Use the error message provided by the Gemini API if available
        $apiError = $responseData['error']['message'];
        // Check for common specific statuses
        if (isset($responseData['error']['status'])) {
            if ($responseData['error']['status'] == 'RESOURCE_EXHAUSTED') {
                $apiError = 'API rate limit exceeded or quota finished. Please try again later.';
            } elseif ($responseData['error']['status'] == 'INVALID_ARGUMENT') {
                 $apiError = 'Invalid request sent to API (check prompt or parameters). Details: ' . $apiError;
            } elseif ($responseData['error']['status'] == 'PERMISSION_DENIED'){
                 $apiError = 'API Key is invalid or lacks permissions for the Gemini model.';
            }
        }
    } elseif ($response !== null && json_last_error() !== JSON_ERROR_NONE) {
        // JSON decoding failed, response was not valid JSON
        $apiError = 'Invalid JSON received from API. Error: ' . json_last_error_msg();
    } elseif ($response === "") {
        $apiError = "Empty response received from API.";
    }

    echo json_encode(['error' => "Gemini API Error (Status: {$statusCode}): " . $apiError]);
    exit;
}


// --- Extract Content and Format Output ---
$generatedText = '';
if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
    $generatedText = $responseData['candidates'][0]['content']['parts'][0]['text'];
} else {
    // Handle cases where the structure might be different or content is missing
    $finishReason = $responseData['candidates'][0]['finishReason'] ?? 'UNKNOWN';
    if ($finishReason !== 'STOP') {
        // Content might be blocked due to safety or other reasons
        http_response_code(500);
        echo json_encode(['error' => 'Gemini API response generation stopped prematurely. Reason: ' . $finishReason]);
        exit;
    }
    // If finishReason is STOP but text is missing, it's an unexpected structure
    http_response_code(500);
    echo json_encode(['error' => 'Could not extract itinerary text from the Gemini API response structure.']);
    exit;
}

// --- Basic Markdown to HTML Conversion & Card Styling ---
// Consider using a dedicated library like Parsedown for more robust conversion
// composer require erusev/parsedown
// $parsedown = new Parsedown();
// $htmlContent = $parsedown->text($generatedText);
// Then you might need to inject Tailwind classes into the Parsedown output or style the base tags.

// Simple parser (as before):
$htmlOutput = "";
$lines = explode("\n", $generatedText);
$currentDayContent = "";
$in_list = false; // Track if we are inside a <ul>

foreach ($lines as $line) {
    $trimmedLine = trim($line);
    if (empty($trimmedLine)) continue;

    // Detect Day headings (Handles ## Day X, **Day X**, Day X:)
    if (preg_match('/^(?:(?:##|\*\*)\s*Day\s*(\d+)\b.*?|Day\s*(\d+)\s*:)/i', $trimmedLine, $matches)) {
        $dayNumber = !empty($matches[1]) ? $matches[1] : $matches[2];

        // Close previous day's card and list if open
        if ($in_list) {
            $currentDayContent .= "</ul>\n";
            $in_list = false;
        }
        if (!empty($currentDayContent)) {
            $initialClasses = 'day-card-initial opacity-0 scale-95'; // Class for JS to target
            $htmlOutput .= "<div class='day-card bg-white rounded-2xl shadow-xl p-6 transition duration-500 ease-out transform hover:scale-[1.03] {$initialClasses}'>\n";
            $htmlOutput .= $currentDayContent;
            $htmlOutput .= "</div>\n";
        }
        $currentDayContent = ""; // Reset

        // Start new day heading - extract full heading text
        $fullHeading = trim(preg_replace('/^(?:##|\*\*|Day\s*\d+\s*:)\s*/i', '', $trimmedLine)); // Remove markdown/prefix
        $currentDayContent .= "<h2 class='text-2xl font-semibold text-blue-700 mb-4'>Day " . htmlspecialchars($dayNumber) . (!empty($fullHeading) ? ': ' . htmlspecialchars($fullHeading) : '') . "</h2>\n";

    }
    // Detect Bold subheadings (like **Places to Visit:**)
    elseif (preg_match('/^\*\*(.*?)\*\*:/', $trimmedLine, $matches)) {
        if ($in_list) { // Close previous list before starting a new subheading
            $currentDayContent .= "</ul>\n";
            $in_list = false;
        }
        $currentDayContent .= "<h3 class='text-lg font-semibold text-gray-800 mt-4 mb-2'>" . htmlspecialchars(trim($matches[1])) . "</h3>\n";
        // Add rest of the line if any, as a paragraph - though usually followed by list
        $restOfLine = trim(substr($trimmedLine, strlen($matches[0])));
        if (!empty($restOfLine)) {
            $currentDayContent .= "<p class='text-gray-600 mb-2'>" . htmlspecialchars($restOfLine) . "</p>\n";
        }
    }
    // Detect bullet points (* or -)
    elseif (preg_match('/^[\*\-]\s+(.*)/', $trimmedLine, $matches)) {
        if (!$in_list) { // Start list if not already in one
            $currentDayContent .= "<ul class='list-disc list-inside text-gray-700 space-y-1 mb-3'>\n";
            $in_list = true;
        }
        // Sanitize list item content
        $listItemContent = htmlspecialchars(trim($matches[1]));
        // Basic bold/italic handling within list items (optional)
        $listItemContent = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $listItemContent);
        $listItemContent = preg_replace('/_(.*?)_/', '<em>$1</em>', $listItemContent); // Or use * for italics if Gemini uses that

        $currentDayContent .= "<li>" . $listItemContent . "</li>\n";
    }
    // Handle plain paragraphs
    else {
        if ($in_list) { // Close list if we encounter a paragraph after list items
            $currentDayContent .= "</ul>\n";
            $in_list = false;
        }
        $currentDayContent .= "<p class='text-gray-600 mb-2'>" . htmlspecialchars($trimmedLine) . "</p>\n";
    }
}

// Add the last day's content
if (!empty($currentDayContent)) {
    if ($in_list) { // Close any potentially open list at the end
        $currentDayContent .= "</ul>\n";
    }
    $initialClasses = 'day-card-initial opacity-0 scale-95'; // Class for JS to target
    $htmlOutput .= "<div class='day-card bg-white rounded-2xl shadow-xl p-6 transition duration-500 ease-out transform hover:scale-[1.03] {$initialClasses}'>\n";
    $htmlOutput .= $currentDayContent;
    $htmlOutput .= "</div>\n";
}

// If $htmlOutput is empty after processing, it means no days were found
if (empty(trim($htmlOutput))) {
     // This might happen if Gemini response wasn't structured as expected
     // You could return the raw text or a specific error
     $htmlOutput = "<div class='day-card bg-white rounded-2xl shadow-xl p-6'><p class='text-red-600'>Could not parse the itinerary structure from the AI response. Raw response:</p><pre class='mt-2 text-sm bg-gray-100 p-2 rounded overflow-auto'>".htmlspecialchars($generatedText)."</pre></div>";
     // Or send an error status back to JS:
     // http_response_code(500);
     // echo json_encode(['error' => 'Failed to parse itinerary structure from Gemini response.']);
     // exit;
}


// --- Send Success Response ---
http_response_code(200); // Explicitly set OK status
echo json_encode(['itinerary_html' => $htmlOutput]);
exit;

?>
