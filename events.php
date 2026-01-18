<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "location_prefixes.php";

$EVENTS_PER_PAGE = 6;
$events = json_decode(file_get_contents($listing_pages_location_prefix . "events.json"), true);

$page = max(1, (int)($_GET['page'] ?? 1));
$search = trim($_GET['search'] ?? '');
$selectedTags = $_GET['tags'] ?? [];
$selectedLocation = $_GET['location'] ?? '';

$uniqueTags = [];
$uniqueLocations = [];

/* =========================================================
   COLLECT UNIQUE TAGS & LOCATIONS
   ========================================================= */

foreach ($events as $event) {
    if (!empty($event['tags'])) {
        foreach ($event['tags'] as $tag) {
            $uniqueTags[$tag] = true;
        }
    }
    if (!empty($event['location'])) {
        $uniqueLocations[$event['location']] = true;
    }
}

$uniqueTags = array_keys($uniqueTags);
$uniqueLocations = array_keys($uniqueLocations);

sort($uniqueTags);
sort($uniqueLocations);

/* =========================================================
   FILTER BY SEARCH
   ========================================================= */

if ($search !== '') {
    $events = array_filter($events, function ($event) use ($search) {
        return stripos($event['title'], $search) !== false
            || stripos($event['description'], $search) !== false
            || stripos($event['location'], $search) !== false;
    });
}

/* =========================================================
   FILTER BY TAGS
   ========================================================= */

if (!empty($selectedTags)) {
    $events = array_filter($events, function ($event) use ($selectedTags) {
        return !empty($event['tags']) &&
               array_intersect($selectedTags, $event['tags']);
    });
}

/* =========================================================
   FILTER BY LOCATION
   ========================================================= */

if (!empty($selectedLocation)) {
    $events = array_filter($events, function ($event) use ($selectedLocation) {
        return $event['location'] === $selectedLocation;
    });
}

/* =========================================================
   PAGINATION
   ========================================================= */

$totalEvents = count($events);
$totalPages = max(1, ceil($totalEvents / $EVENTS_PER_PAGE));

if ($page > $totalPages) {
    $page = 1;
}

$offset = ($page - 1) * $EVENTS_PER_PAGE;
$eventsToShow = array_slice($events, $offset, $EVENTS_PER_PAGE);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Singularity | Events</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat&family=Montserrat&family=Zalando+Sans&family=Lexend&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?php echo $static_css_location_prefix ?>global.css">
<link rel="stylesheet" href="<?php echo $static_css_location_prefix ?>events.css">

<?php include $includes_location_prefix . "stylesheet.html"; ?>

<style>
:root {
    --events-body-font: "Lexend", "Montserrat", sans-serif;
    --events-heading-fonts: "Lexend", "Zalando Sans", sans-serif;
    --events-main-background: #25282a;
    --primary-purple: #6a3df5;
    --secondary-purple: #9f7aea;
    --hover-red: #ff6666;
    --card-background: #404447;
    --text-primary: #ffffff;
    --text-secondary: #eee;
    --tag-bg: #222;
    --tag-hover: #444;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    font-family: var(--events-body-font);
    background-color: var(--events-main-background);
    color: var(--text-primary);
    line-height: 1.6;
    min-height: 100vh;
}

.page-wrapper {
    display: flex;
    justify-content: center;
    width: 100%;
    min-height: 100vh;
}

.events-container {
    width: 85vw;
    max-width: 1400px;
    padding: 2rem 0;
}

/* Header */
.event-element {
    width: 100%;
    margin: 2rem auto 3rem auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: var(--events-heading-fonts);
    padding-bottom: 1rem;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
}

.top-left {
    font-size: clamp(1.5rem, 4vw, 2rem);
    font-weight: 600;
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.top-right {
    font-size: clamp(1.5rem, 4vw, 2rem);
    font-weight: 700;
    opacity: 0.8;
}

/* Search & Filter Section */
.search-filter-section {
    margin: 2rem 0;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.search-filter-section form {
    margin-bottom: 1.5rem;
}

.search-filter-section form:last-child {
    margin-bottom: 0;
}

.search-filter-section input[type="text"] {
    width: 100%;
    max-width: 500px;
    padding: 12px 16px;
    border-radius: 10px;
    border: 2px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    font-size: 1rem;
    font-family: var(--events-body-font);
    transition: all 0.3s ease;
    margin-right: 1rem;
}

.search-filter-section input[type="text"]:focus {
    outline: none;
    border-color: var(--primary-purple);
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 0 3px rgba(106, 61, 245, 0.1);
}

.search-filter-section input[type="text"]::placeholder {
    color: rgba(255, 255, 255, 0.4);
}

.search-filter-section button {
    padding: 12px 28px;
    border: none;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
    font-family: var(--events-body-font);
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(106, 61, 245, 0.3);
}

.search-filter-section button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(106, 61, 245, 0.4);
}

/* Tags */
.tag-filter {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 1rem;
}

.tag {
    padding: 8px 18px;
    border-radius: 999px;
    background: var(--tag-bg);
    color: var(--text-secondary);
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid transparent;
    user-select: none;
}

.tag:hover {
    background: var(--tag-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.tag input {
    display: none;
}

.tag:has(input:checked) {
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 12px rgba(106, 61, 245, 0.4);
    transform: translateY(-2px);
}

/* Location Select */
.location-select {
    padding: 12px 18px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    border: 2px solid rgba(255, 255, 255, 0.1);
    min-width: 240px;
    font-size: 1rem;
    font-family: var(--events-body-font);
    cursor: pointer;
    transition: all 0.3s ease;
    margin-right: 1rem;
}

.location-select:hover {
    border-color: var(--primary-purple);
    background: rgba(255, 255, 255, 0.08);
}

.location-select:focus {
    outline: none;
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 3px rgba(106, 61, 245, 0.1);
}

.location-select option {
    background: var(--events-main-background);
    color: var(--text-primary);
}

/* Events Grid */
.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
}

.event-card {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid rgba(255, 255, 255, 0.05);
    display: flex;
    flex-direction: column;
}

.event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(106, 61, 245, 0.3);
    border-color: rgba(106, 61, 245, 0.3);
}

.event-image-container {
    width: 100%;
    height: 220px;
    overflow: hidden;
    position: relative;
}

.event-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.event-card:hover .event-image {
    transform: scale(1.05);
}

.event-content {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.event-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: var(--text-primary);
    transition: color 0.3s ease;
}

.event-card:hover .event-title {
    color: var(--primary-purple);
}

.event-description {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 1rem;
    line-height: 1.6;
    flex-grow: 1;
}

.event-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 1rem;
}

.event-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.event-meta-icon {
    color: var(--primary-purple);
    font-weight: 600;
}

.read-more {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    font-size: 0.95rem;
    align-self: flex-start;
}

.read-more::after {
    content: '→';
    margin-left: 8px;
    transition: transform 0.3s ease;
}

.read-more:hover {
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(106, 61, 245, 0.4);
}

.read-more:hover::after {
    transform: translateX(4px);
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12px;
    margin: 4rem 0 2rem 0;
    flex-wrap: wrap;
}

.pagination a {
    padding: 10px 18px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid rgba(255, 255, 255, 0.1);
    min-width: 44px;
    text-align: center;
}

.pagination a:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--primary-purple);
    transform: translateY(-2px);
}

.pagination a.active {
    background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
    border-color: transparent;
    box-shadow: 0 4px 12px rgba(106, 61, 245, 0.4);
}

.no-events {
    text-align: center;
    padding: 4rem 2rem;
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.6);
}

/* Responsive */
@media (max-width: 1024px) {
    .events-container {
        width: 90vw;
    }
}

@media (max-width: 768px) {
    .events-container {
        width: 92vw;
    }

    .events-grid {
        grid-template-columns: 1fr;
    }
    
    .event-element {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }

    .search-filter-section input[type="text"],
    .location-select {
        max-width: 100%;
        width: 100%;
        margin-right: 0;
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 480px) {
    .events-container {
        width: 95vw;
        padding: 1rem 0;
    }

    .event-element {
        margin: 1rem 0 1.5rem 0;
    }

    .search-filter-section {
        padding: 1.5rem 1rem;
    }
}

html {
    scroll-behavior: smooth;
}

*:focus-visible {
    outline: 2px solid var(--primary-purple);
    outline-offset: 2px;
}
</style>
</head>

<body>

<?php include $includes_location_prefix . "header.html"; ?>

<div class="page-wrapper">
<main class="events-container">

<div class="event-element">
    <h2 class="top-left">Events</h2>
    <h2 class="top-right">Singularity</h2>
</div>

<!-- SEARCH & FILTERS -->
<div class="search-filter-section">
    <!-- SEARCH -->
    <form method="GET">
        <input
            type="text"
            name="search"
            placeholder="Search events..."
            value="<?= htmlspecialchars($search) ?>"
        >

        <?php foreach ($selectedTags as $tag): ?>
            <input type="hidden" name="tags[]" value="<?= htmlspecialchars($tag) ?>">
        <?php endforeach; ?>

        <?php if ($selectedLocation !== ''): ?>
            <input type="hidden" name="location" value="<?= htmlspecialchars($selectedLocation) ?>">
        <?php endif; ?>

        <button type="submit">Search</button>
    </form>

    <!-- TAG FILTER -->
    <?php if (!empty($uniqueTags)): ?>
    <form method="GET">
        <div class="tag-filter">
            <?php foreach ($uniqueTags as $tag): ?>
                <label class="tag">
                    <input
                        type="checkbox"
                        name="tags[]"
                        value="<?= htmlspecialchars($tag) ?>"
                        <?= in_array($tag, $selectedTags) ? 'checked' : '' ?>
                    >
                    <?= htmlspecialchars($tag) ?>
                </label>
            <?php endforeach; ?>
        </div>

        <?php if ($search !== ''): ?>
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
        <?php endif; ?>

        <?php if ($selectedLocation !== ''): ?>
            <input type="hidden" name="location" value="<?= htmlspecialchars($selectedLocation) ?>">
        <?php endif; ?>

        <button type="submit" style="margin-top: 1rem;">Apply Tags</button>
    </form>
    <?php endif; ?>

    <!-- LOCATION FILTER -->
    <?php if (!empty($uniqueLocations)): ?>
    <form method="GET">
        <select name="location" class="location-select">
            <option value="">All Locations</option>
            <?php foreach ($uniqueLocations as $location): ?>
                <option value="<?= htmlspecialchars($location) ?>" <?= $location === $selectedLocation ? 'selected' : '' ?>>
                    <?= htmlspecialchars($location) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if ($search !== ''): ?>
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
        <?php endif; ?>

        <?php foreach ($selectedTags as $tag): ?>
            <input type="hidden" name="tags[]" value="<?= htmlspecialchars($tag) ?>">
        <?php endforeach; ?>

        <button type="submit">Apply Location</button>
    </form>
    <?php endif; ?>
</div>

<!-- EVENTS GRID -->
<?php if ($eventsToShow): ?>
<div class="events-grid">
    <?php foreach ($eventsToShow as $event): ?>
    <div class="event-card" onclick="window.location.href='<?= htmlspecialchars($event['link']) ?>'">
        <?php 
        // Use heroImage if available, otherwise fall back to regular image
        $displayImage = !empty($event['heroImage']) ? $event['heroImage'] : (!empty($event['image']) ? $event['image'] : '');
        if (!empty($displayImage)): 
        ?>
        <div class="event-image-container">
            <img src="<?= htmlspecialchars($displayImage) ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="event-image">
        </div>
        <?php endif; ?>
        
        <div class="event-content">
            <h3 class="event-title"><?= htmlspecialchars($event['title']) ?></h3>
            <p class="event-description"><?= htmlspecialchars($event['description']) ?></p>
            
            <div class="event-meta">
                <div class="event-meta-item">
                    <span class="event-meta-icon">📅</span>
                    <span><?= htmlspecialchars($event['date']) ?></span>
                </div>
                <div class="event-meta-item">
                    <span class="event-meta-icon">🕒</span>
                    <span><?= htmlspecialchars($event['time']) ?></span>
                </div>
                <div class="event-meta-item">
                    <span class="event-meta-icon">📍</span>
                    <span><?= htmlspecialchars($event['location']) ?></span>
                </div>
            </div>
            
            <a href="<?= htmlspecialchars($event['link']) ?>" class="read-more" onclick="event.stopPropagation()">View Details</a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="no-events">
    <p>No events found matching your criteria.</p>
</div>
<?php endif; ?>

<!-- PAGINATION -->
<?php if ($totalPages > 1): ?>
<div class="pagination">
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
<a
  href="?page=<?= $i ?>
  <?= $search !== '' ? '&search='.urlencode($search) : '' ?>
  <?= $selectedLocation !== '' ? '&location='.urlencode($selectedLocation) : '' ?>
  <?php foreach ($selectedTags as $tag) echo '&tags[]='.urlencode($tag); ?>"
  class="<?= $i === $page ? 'active' : '' ?>"
>
<?= $i ?>
</a>
<?php endfor; ?>
</div>
<?php endif; ?>

</main>
</div>

<?php include $includes_location_prefix . "footer.html"; ?>

</body>
</html>