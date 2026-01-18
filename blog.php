<?php

include "location_prefixes.php";

// Get the blog ID from URL parameter
$blog_id = isset($_GET['id']) ? $_GET['id'] : '';

// Sanitize to prevent directory traversal
$blog_id = basename($blog_id);

if (empty($blog_id)) {
    die("Error: No blog ID specified. Please provide a valid blog ID in the URL.<br>Example: index.php?id=blog-3");
}

// CRITICAL: Construct path using location_prefixes.php variable
// Path structure: assets/blogs/{blog_id}/blog-page-{blog_id}.json
$folder = __DIR__ ."/" . $listing_pages_location_prefix . "blogs/" . $blog_id ;
$json_filepath = $folder. "/blog-page-" . $blog_id . ".json";

// Check if JSON file exists
if (!file_exists($json_filepath)) {
    die("Error: Blog post not found.<br>" . 
        "Looking for: " . htmlspecialchars($json_filepath) . "<br>" .
        "Blog ID: " . htmlspecialchars($blog_id) . "<br>" .
        "Expected location: assets/blogs/{$blog_id}/blog-page-{$blog_id}.json");
}

// Read and decode JSON
$json_content = file_get_contents($json_filepath);
$blog_data = json_decode($json_content, true);

if ($blog_data === null) {
    die("Error: Invalid JSON format in file: " . htmlspecialchars($json_filepath) . "<br>JSON Error: " . json_last_error_msg());
}

if (!empty($blog_data['image'])) {
    $blog_data['image'] = $blog_data['image'];
}

if (!empty($blog_data['author']['image'])) {
    $blog_data['author']['image'] = $blog_data['author']['image'];
}

if (!empty($blog_data['contents'])) {
    foreach ($blog_data['contents'] as $key => $section) {
        if (!empty($section['image'])) {
            $blog_data['contents'][$key]['image'] = $section['image'];
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($blog_data['title']); ?> | Blog</title>
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
  
  <!-- Stylesheets -->
  <link rel="stylesheet" href="<?php echo $static_css_location_prefix; ?>blog.css">
  <?php include $includes_location_prefix . "stylesheet.html"; ?>
</head>

<body>
  
  <?php include $includes_location_prefix . "header.html"; ?>

  <!-- HERO BANNER SECTION -->
  <div class="hero" <?php if (!empty($blog_data['image'])): ?>
    style="background-image: url('<?php echo htmlspecialchars($blog_data['image']); ?>');"
  <?php endif; ?>>
    <div class="hero-content">
      <?php if (!empty($blog_data['institute'])): ?>
      <span class="meta" style="color: var(--accent-light); font-weight: 600; text-transform: uppercase; font-size: 0.9rem;">
        <?php echo htmlspecialchars($blog_data['institute']); ?>
      </span>
      <?php endif; ?>
      
      <h1 id="blog-title">
        <?php echo htmlspecialchars($blog_data['title']); ?>
      </h1>
      
      <?php if (!empty($blog_data['author']['name'])): ?>
      <p class="meta">
        By <?php echo htmlspecialchars($blog_data['author']['name']); ?>
      </p>
      <?php endif; ?>
      
      <?php if (!empty($blog_data['date'])): ?>
      <p class="meta" style="font-size: 0.9rem; margin-top: 0.5rem;">
        <?php echo htmlspecialchars($blog_data['date']); ?>
      </p>
      <?php endif; ?>
    </div>
  </div>

  <div class="container">

    <!-- MAIN ARTICLE CONTENT -->
    <div class="main">

      <!-- Introduction Paragraph -->
      <?php if (!empty($blog_data['desc'])): ?>
      <p style="font-weight: 600; font-size: 1.2rem; color: var(--accent-light); line-height: 1.6;">
        <?php echo nl2br(htmlspecialchars($blog_data['desc'])); ?>
      </p>
      <?php endif; ?>

      <!-- Hero Image (if not in background) -->
      <?php if (!empty($blog_data['image'])): ?>
      <img 
        src="<?php echo htmlspecialchars($blog_data['image']); ?>" 
        class="inline-image"
        alt="<?php echo htmlspecialchars($blog_data['title']); ?>"
        onerror="this.style.border='2px solid red'; this.alt='Image failed to load: <?php echo htmlspecialchars(basename($blog_data['image'])); ?>';">
      <?php endif; ?>

      <!-- Dynamic Content Sections -->
      <?php if (!empty($blog_data['contents']) && is_array($blog_data['contents'])): ?>
        <?php foreach ($blog_data['contents'] as $index => $section): ?>
        
          <?php if (!empty($section['heading'])): ?>
          <h2><?php echo htmlspecialchars($section['heading']); ?></h2>
          <?php endif; ?>
          
          <?php if (!empty($section['content'])): ?>
          <p style="line-height: 1.8; margin: 1rem 0;">
            <?php echo nl2br(htmlspecialchars($section['content'])); ?>
          </p>
          <?php endif; ?>

          <?php if (!empty($section['image'])): ?>
          <img 
            src="<?php echo htmlspecialchars($section['image']); ?>" 
            class="inline-image"
            alt="<?php echo !empty($section['heading']) ? htmlspecialchars($section['heading']) : 'Section image'; ?>"
            onerror="this.style.border='2px solid red'; this.alt='Image failed to load: <?php echo htmlspecialchars(basename($section['image'])); ?>';">
          <?php endif; ?>
          
        <?php endforeach; ?>
      <?php else: ?>
        <p style="color: #888;">No content sections available.</p>
      <?php endif; ?>

      <!-- Author Bio Section -->
      <?php if (!empty($blog_data['author'])): ?>
      <div style="margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); display: flex; align-items: center; gap: 1.5rem;">
        
        <?php if (!empty($blog_data['author']['image'])): ?>
        <img 
          src="<?php echo htmlspecialchars($blog_data['author']['image']); ?>" 
          style="border-radius: 50%; width: 60px; height: 60px; object-fit: cover; flex-shrink: 0;" 
          alt="Author <?php echo htmlspecialchars($blog_data['author']['name']); ?>"
          onerror="this.src='https://placehold.co/60x60/4f46e5/ffffff?text=<?php echo substr($blog_data['author']['name'], 0, 1); ?>';">
        <?php endif; ?>
        
        <div>
          <p style="font-size: 0.9rem; margin-bottom: 0.25rem; color: var(--accent-light); font-weight: 600;">
            <?php echo htmlspecialchars($blog_data['author']['name']); ?>
          </p>
          
          <?php if (!empty($blog_data['author']['desc'])): ?>
          <p style="font-size: 0.9rem; color: var(--subtle-text); line-height: 1.4;">
            <?php echo htmlspecialchars($blog_data['author']['desc']); ?>
          </p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

    </div>

    <!-- RIGHT SIDEBAR -->
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

  <?php include $includes_location_prefix . "footer.html"; ?>

  <!-- Debug Info (Remove in production) -->
  <?php if (isset($_GET['debug'])): ?>
  <div style="background: #1a1a1a; color: #0f0; padding: 20px; margin: 20px; font-family: monospace; font-size: 12px;">
    <h3 style="color: #0ff;">DEBUG INFO</h3>
    <p><strong>Blog ID:</strong> <?php echo htmlspecialchars($blog_id); ?></p>
    <p><strong>JSON File:</strong> <?php echo htmlspecialchars($json_filepath); ?></p>
    <p><strong>File Exists:</strong> <?php echo file_exists($json_filepath) ? 'YES' : 'NO'; ?></p>
    <p><strong>Hero Image Path:</strong> <?php echo htmlspecialchars($blog_data['image']); ?></p>
    <p><strong>Author Image Path:</strong> <?php echo htmlspecialchars($blog_data['author']['image']); ?></p>
    <hr>
    <pre><?php print_r($blog_data); ?></pre>
  </div>
  <?php endif; ?>

</body>

</html>