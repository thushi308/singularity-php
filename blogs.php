<?php

include "location_prefixes.php";

$BLOGS_PER_PAGE = 6;
$blogs = json_decode(file_get_contents($listing_pages_location_prefix . "blogs.json"), true);

$page = max(1, (int)($_GET['page'] ?? 1));
$search = trim($_GET['search'] ?? '');
$selectedTags = $_GET['tags'] ?? [];
$selectedAuthor = $_GET['author'] ?? '';

$uniqueTags = [];
$uniqueAuthor = [];

/* =========================================================
   COLLECT UNIQUE TAGS & AUTHORS
   ========================================================= */

foreach ($blogs as $blog) {
    if (!empty($blog['tags'])) {
        foreach ($blog['tags'] as $tag) {
            $uniqueTags[$tag] = true;
        }
    }
    if (!empty($blog['authorName'])) {
        $uniqueAuthor[$blog['authorName']] = true;
    }
}

$uniqueTags = array_keys($uniqueTags);
$uniqueAuthor = array_keys($uniqueAuthor);

sort($uniqueTags);
sort($uniqueAuthor);

/* =========================================================
   FILTER BY SEARCH
   ========================================================= */

if ($search !== '') {
    $blogs = array_filter($blogs, function ($blog) use ($search) {
        return stripos($blog['title'], $search) !== false
            || stripos($blog['description'], $search) !== false;
    });
}

/* =========================================================
   FILTER BY TAGS
   ========================================================= */

if (!empty($selectedTags)) {
    $blogs = array_filter($blogs, function ($blog) use ($selectedTags) {
        return !empty($blog['tags']) &&
               array_intersect($selectedTags, $blog['tags']);
    });
}

/* =========================================================
   FILTER BY AUTHOR
   ========================================================= */

if (!empty($selectedAuthor)) {
    $blogs = array_filter($blogs, function ($blog) use ($selectedAuthor) {
        return $blog['authorName'] === $selectedAuthor;
    });
}

/* =========================================================
   PAGINATION
   ========================================================= */

$totalBlogs = count($blogs);
$totalPages = max(1, ceil($totalBlogs / $BLOGS_PER_PAGE));

if ($page > $totalPages) {
    $page = 1;
}

$offset = ($page - 1) * $BLOGS_PER_PAGE;
$blogsToShow = array_slice($blogs, $offset, $BLOGS_PER_PAGE);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Singularity | Blogs</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat&family=Montserrat&family=Zalando+Sans&family=Lexend&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?php echo $static_css_location_prefix ?>global.css">
<link rel="stylesheet" href="<?php echo $static_css_location_prefix ?>blogs.css">

<?php include $includes_location_prefix . "stylesheet.html"; ?>

<style>
:root {
    --blogs-body-font: "Lexend", "Montserrat", sans-serif;
    --blogs-heading-fonts: "Lexend", "Zalando Sans", sans-serif;
    --blogs-main-background: #25282a;
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
    font-family: var(--blogs-body-font);
    background-color: var(--blogs-main-background);
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

.blogs-container {
    width: 85vw;
    max-width: 1400px;
    padding: 2rem 0;
}

/* Header */
.blog-element {
    width: 100%;
    margin: 2rem auto 3rem auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: var(--blogs-heading-fonts);
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
    font-family: var(--blogs-body-font);
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
    font-family: var(--blogs-body-font);
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

/* Author Select */
.author-select {
    padding: 12px 18px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-primary);
    border: 2px solid rgba(255, 255, 255, 0.1);
    min-width: 240px;
    font-size: 1rem;
    font-family: var(--blogs-body-font);
    cursor: pointer;
    transition: all 0.3s ease;
    margin-right: 1rem;
}

.author-select:hover {
    border-color: var(--primary-purple);
    background: rgba(255, 255, 255, 0.08);
}

.author-select:focus {
    outline: none;
    border-color: var(--primary-purple);
    box-shadow: 0 0 0 3px rgba(106, 61, 245, 0.1);
}

.author-select option {
    background: var(--blogs-main-background);
    color: var(--text-primary);
}

/* Blogs List (Replaces Grid) */
.blogs-grid {
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
    margin: 3rem 0;
}

.blog-card {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 1px solid rgba(255, 255, 255, 0.05);
    display: flex; /* Changed from column to flex row */
    flex-direction: row; 
    width: 100%;
    min-height: 250px;
}

.blog-card:hover {
    transform: translateX(10px); /* Slide right instead of lifting */
    box-shadow: -10px 0px 30px rgba(106, 61, 245, 0.2);
    border-color: rgba(106, 61, 245, 0.3);
}

.blog-image-container {
    width: 35%; /* Fixed width for the list view image */
    height: auto;
    min-width: 300px;
    overflow: hidden;
    position: relative;
}

.blog-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.blog-content {
    padding: 2rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: center; /* Center content vertically */
}

/* Mobile Responsiveness for List */
@media (max-width: 850px) {
    .blog-card {
        flex-direction: column; /* Stack back to column on small screens */
    }
    .blog-image-container {
        width: 100%;
        height: 200px;
        min-width: unset;
    }
}

.blog-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: var(--text-primary);
    transition: color 0.3s ease;
}

.blog-card:hover .blog-title {
    color: var(--primary-purple);
}

.blog-description {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 1rem;
    line-height: 1.6;
    flex-grow: 1;
}

.blog-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.author-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-grow: 1;
}

.author-img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--primary-purple);
}

.author-details {
    display: flex;
    flex-direction: column;
}

.author-name {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--primary-purple);
}

.author-role {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
}

.blog-date {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.5);
    white-space: nowrap;
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

.no-blogs {
    text-align: center;
    padding: 4rem 2rem;
    font-size: 1.2rem;
    color: rgba(255, 255, 255, 0.6);
}

/* Responsive */
@media (max-width: 1024px) {
    .blogs-container {
        width: 90vw;
    }
}

@media (max-width: 768px) {
    .blogs-container {
        width: 92vw;
    }

    .blogs-grid {
        grid-template-columns: 1fr;
    }
    
    .blog-element {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }

    .search-filter-section input[type="text"],
    .author-select {
        max-width: 100%;
        width: 100%;
        margin-right: 0;
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 480px) {
    .blogs-container {
        width: 95vw;
        padding: 1rem 0;
    }

    .blog-element {
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
<main class="blogs-container">

<div class="blog-element">
    <h2 class="top-left">Blogs</h2>
    <h2 class="top-right">Singularity</h2>
</div>

<!-- SEARCH & FILTERS -->
<div class="search-filter-section">
    <!-- SEARCH -->
    <form method="GET">
        <input
            type="text"
            name="search"
            placeholder="Search blogs..."
            value="<?= htmlspecialchars($search) ?>"
        >

        <?php foreach ($selectedTags as $tag): ?>
            <input type="hidden" name="tags[]" value="<?= htmlspecialchars($tag) ?>">
        <?php endforeach; ?>

        <?php if ($selectedAuthor !== ''): ?>
            <input type="hidden" name="author" value="<?= htmlspecialchars($selectedAuthor) ?>">
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

        <?php if ($selectedAuthor !== ''): ?>
            <input type="hidden" name="author" value="<?= htmlspecialchars($selectedAuthor) ?>">
        <?php endif; ?>

        <button type="submit" style="margin-top: 1rem;">Apply Tags</button>
    </form>
    <?php endif; ?>

    <!-- AUTHOR FILTER -->
    <?php if (!empty($uniqueAuthor)): ?>
    <form method="GET">
        <select name="author" class="author-select">
            <option value="">All Authors</option>
            <?php foreach ($uniqueAuthor as $author): ?>
                <option value="<?= htmlspecialchars($author) ?>" <?= $author === $selectedAuthor ? 'selected' : '' ?>>
                    <?= htmlspecialchars($author) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <?php if ($search !== ''): ?>
            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
        <?php endif; ?>

        <?php foreach ($selectedTags as $tag): ?>
            <input type="hidden" name="tags[]" value="<?= htmlspecialchars($tag) ?>">
        <?php endforeach; ?>

        <button type="submit">Apply Author</button>
    </form>
    <?php endif; ?>
</div>

<!-- BLOGS GRID -->
<?php if ($blogsToShow): ?>
<div class="blogs-grid">
    <?php foreach ($blogsToShow as $blog): ?>
    <div class="blog-card" onclick="window.location.href='<?= htmlspecialchars($blog['link']) ?>'">
        
        <div class="blog-content">
            <h3 class="blog-title"><?= htmlspecialchars($blog['title']) ?></h3>
            <p class="blog-description"><?= htmlspecialchars($blog['description']) ?></p>
            
            <div class="blog-meta">
                <div class="author-info">
                    <?php if (!empty($blog['authorImg'])): ?>
                    <img src="<?= htmlspecialchars($blog['authorImg']) ?>" alt="<?= htmlspecialchars($blog['authorName']) ?>" class="author-img">
                    <?php endif; ?>
                    <div class="author-details">
                        <span class="author-name"><?= htmlspecialchars($blog['authorName']) ?></span>
                        <?php if (!empty($blog['authorRole'])): ?>
                        <span class="author-role"><?= htmlspecialchars($blog['authorRole']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (!empty($blog['date'])): ?>
                <span class="blog-date"><?= htmlspecialchars($blog['date']) ?></span>
                <?php endif; ?>
            </div>
            
            <a href="<?= htmlspecialchars($blog['link']) ?>" class="read-more" onclick="event.stopPropagation()">Read More</a>
        </div>

        <?php if (!empty($blog['image'])): ?>
        <div class="blog-image-container">
            <img src="<?= htmlspecialchars($blog['image']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>" class="blog-image">
        </div>
        <?php endif; ?>
        

    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="no-blogs">
    <p>No blogs found matching your criteria.</p>
</div>
<?php endif; ?>

<!-- PAGINATION -->
<?php if ($totalPages > 1): ?>
<div class="pagination">
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
<a
  href="?page=<?= $i ?>
  <?= $search !== '' ? '&search='.urlencode($search) : '' ?>
  <?= $selectedAuthor !== '' ? '&author='.urlencode($selectedAuthor) : '' ?>
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