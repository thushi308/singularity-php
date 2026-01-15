<?php

include "location_prefixes.php";

// Get the filename from GET parameter
$filename = isset($_GET['id']) ? $_GET['id'] : 'blog_page';

// Sanitize filename to prevent directory traversal
$filename = basename($filename);
//$filepath = __DIR__ . '/data/' . $filename . "/blog-page-" . $filename . '.json';
$filepath = __DIR__ . "/" . $blogs_location_prefix . $filename . "/blog-page-" . $filename . ".json";

// Check if file exists
if (!file_exists($filepath)) {
    die("Error: Blog post not found.\n" . $filepath);
}

// Read and decode JSON file
$json_content = file_get_contents($filepath);
$blog_data = json_decode($json_content, true);

// Check if JSON is valid
if ($blog_data === null) {
    die("Error: Invalid JSON format.");
}

// Set the base path for images (relative to this file)
$image_base_path = "data/" . $filename . "/";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($blog_data['title']); ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $static_css_location_prefix; ?>blog.css">
  <?php include $includes_location_prefix . "stylesheet.html" ?>
</head>

<body>
  
  <?php include $includes_location_prefix . "header.html"; ?>

  <!-- HERO BANNER SECTION with Blurred Background Image -->
  <div class="hero">
    <div class="hero-content">
      <span class="meta"
        style="color: var(--accent-light); font-weight: 600; text-transform: uppercase; font-size: 0.9rem;">
        <?php echo htmlspecialchars($blog_data['institute']); ?>
      </span>
      <h1 id="blog-title">
        <?php echo htmlspecialchars($blog_data['title']); ?>
      </h1>
      <p class="meta">
        <?php echo htmlspecialchars($blog_data['author']['name']); ?>
      </p>
      <?php if (!empty($blog_data['date'])): ?>
      <p class="meta" style="font-size: 0.9rem; margin-top: 0.5rem;">
        <?php echo htmlspecialchars($blog_data['date']); ?>
      </p>
      <?php endif; ?>
    </div>
  </div>

  <div class="container">

    <!-- MAIN ARTICLE CONTENT SECTION (The single blog post) -->
    <div class="main">

      <!-- Introduction Paragraph -->
      <?php if (!empty($blog_data['desc'])): ?>
      <p style="font-weight: 600; font-size: 1.2rem; color: var(--accent-light);">
        <?php echo nl2br(htmlspecialchars($blog_data['desc'])); ?>
      </p>
      <?php endif; ?>

      <?php if (!empty($blog_data['image'])): ?>
      <img src="<?php echo htmlspecialchars($blog_data['image']); ?>" class="inline-image"
        alt="<?php echo htmlspecialchars($blog_data['title']); ?>"
        onerror="this.src='https://placehold.co/800x400/282828/ffffff?text=Image+Load+Failed'">
      <?php endif; ?>

      <!-- Dynamic Content Sections -->
      <?php foreach ($blog_data['contents'] as $section): ?>
        <?php if (!empty($section['heading'])): ?>
        <h2><?php echo htmlspecialchars($section['heading']); ?></h2>
        <?php endif; ?>
        
        <?php if (!empty($section['content'])): ?>
        <p>
          <?php echo nl2br(htmlspecialchars($section['content'])); ?>
        </p>
        <?php endif; ?>

        <?php if (!empty($section['image'])): ?>
        <img src="<?php echo htmlspecialchars($section['image']); ?>" class="inline-image"
          alt="<?php echo htmlspecialchars($section['heading']); ?>"
          onerror="this.src='https://placehold.co/800x400/282828/ffffff?text=Image+Load+Failed'">
        <?php endif; ?>
      <?php endforeach; ?>

      <!-- Call to Action/Author Bio Area -->
      <div
        style="margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); display: flex; align-items: center; gap: 1.5rem;">
        <?php if (!empty($blog_data['author']['image'])): ?>
        <img src="<?php echo htmlspecialchars($blog_data['author']['image']); ?>" 
          style="border-radius: 50%; width: 60px; height: 60px; object-fit: cover;" 
          alt="Author <?php echo htmlspecialchars($blog_data['author']['name']); ?>"
          onerror="this.src='https://placehold.co/60x60/4f46e5/ffffff?text=<?php echo substr($blog_data['author']['name'], 0, 1); ?>'">
        <?php endif; ?>
        <div>
          <p style="font-size: 0.9rem; margin-bottom: 0.25rem; color: var(--accent-light); font-weight: 600;">
            <?php echo htmlspecialchars($blog_data['author']['name']); ?>
          </p>
          <p style="font-size: 0.9rem; color: var(--subtle-text); line-height: 1.4;">
            <?php echo htmlspecialchars($blog_data['author']['desc']); ?>
          </p>
        </div>
      </div>

    </div>

    <!-- RIGHT SIDEBAR (FIXED CONTENT: Related, Newsletter) -->
    <div class="right">
      <div class="right-sidebar">
        <h3>Related Articles</h3>
        <ul>
          <li><a href="#">Link 1</a></li>
          <li><a href="#">Link 2</a></li>
          <li><a href="#">Link 3</a></li>
          <li><a href="#">Link 4</a></li>
        </ul>
      </div>
    </div>
  </div>

  <?php include $includes_location_prefix . "footer.html" ?>

</body>

</html>