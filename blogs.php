<?php

include "location_prefixes.php";

$BLOGS_PER_PAGE = 2;

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
    $uniqueAuthor[$blog['authorName']] = true;
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
   PAGINATION (BUG FIXED)
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
<link href="https://fonts.googleapis.com/css2?family=Caveat&family=Montserrat&family=Zalando+Sans&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?php echo $static_css_location_prefix ?>global.css">
<link rel="stylesheet" href="<?php echo $static_css_location_prefix ?>blogs.css">

<?php include $includes_location_prefix . "stylesheet.html"; ?>

<style>
.page-wrapper {
    display: flex;
    justify-content: center;
    width: 100%;
}

.tag {
    padding: 6px 16px;
    border-radius: 999px;
    background: #222;
    color: #eee;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.tag:hover { background: #444; }
.tag input { display: none; }

.tag:has(input:checked) {
    background: linear-gradient(135deg, #6a3df5, #9f7aea);
}

.author-select {
    padding: 10px 14px;
    border-radius: 8px;
    background: #222;
    color: white;
    border: none;
    min-width: 220px;
}

.author-name {
    font-weight: 600;
    color: #6a3df5;
}

.pagination a {
    padding: 8px 14px;
    border-radius: 6px;
    background: #333;
    color: white;
    text-decoration: none;
}

.pagination a.active {
    background: #6a3df5;
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

<!-- SEARCH -->
<form method="GET" style="margin:2rem 0;">
    <input
        type="text"
        name="search"
        placeholder="Search blogs..."
        value="<?= htmlspecialchars($search) ?>"
        style="width:100%;max-width:400px;padding:10px;border-radius:8px;border:none;"
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
<form method="GET" style="margin-bottom:2rem;">
    <div style="display:flex;flex-wrap:wrap;gap:10px;">
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

    <button type="submit" style="margin-top:1rem;">Apply Tags</button>
</form>

<!-- AUTHOR FILTER -->
<form method="GET" style="margin-bottom:2rem;">
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

<!-- BLOG LIST -->
<?php if ($blogsToShow): ?>
<?php foreach ($blogsToShow as $blog): ?>
<section class="blog-card">
    <div class="horizontal-line"></div>

    <div class="blog-left">
        <h2 class="blog-title"><?= htmlspecialchars($blog['title']) ?></h2>
        <p class="blog-description"><?= htmlspecialchars($blog['description']) ?></p>
        <a href="<?= htmlspecialchars($blog['link']) ?>" class="read-more">Read more →</a>
    </div>

    <div class="blog-right">
        <div class="image-box">
            <img src="<?= htmlspecialchars($blog['image']) ?>" alt="blog image">
        </div>

        <div class="author-box">
            <img src="<?= htmlspecialchars($blog['authorImg']) ?>" class="author-img">
            <div>
                <p class="author-name"><?= htmlspecialchars($blog['authorName']) ?></p>
                <p class="author-role"><?= htmlspecialchars($blog['authorRole']) ?></p>
                <p class="date"><?= htmlspecialchars($blog['date']) ?></p>
            </div>
        </div>
    </div>
</section>
<?php endforeach; ?>
<?php else: ?>
<p>No blogs found.</p>
<?php endif; ?>

<!-- PAGINATION -->
<?php if ($totalPages > 1): ?>
<div class="pagination" style="display:flex;justify-content:center;gap:10px;margin:3rem 0;">
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
