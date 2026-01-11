<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon.ico" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Syne+Mono&family=Turret+Road:wght@200;300;400;500;700;800&display=swap"
      rel="stylesheet"
    />

    <title>Gallery | Singularity</title>

    <link rel="stylesheet" href="static/css/gallery-page.css" />
    <link rel="stylesheet" href="static/css/gallery-global.css" />
  </head>

  <body>
    <!-- // navbar from thushiyanth code  -->

    <!-- IMPORTANT NOTE: I have added a class .main-navbar for the header navbar because it was clashing with the gallery nav elements. -->
     
    <?php include "classes/header.html"; ?>

    <!-- // navbar from thushiyanth code   -->

    <main>
      <h1>Gallery | Singularity</h1>

      <section class="intro">
        <div class="intro-box">
          <h2>Welcome to the Singularity Gallery</h2>
          <p>
            Explore moments from Astrophotography, Events, and Behind The
            Scenes.
          </p>
        </div>
      </section>

      <nav class="category-nav">
        <ul>
          <li data-category="astrophotography" class="active">
            Astrophotography
            <select class="year-dropdown">
              <option value="all">All Time</option>
            </select>
          </li>
          <li data-category="events">
            Events
            <select class="year-dropdown">
              <option value="all">All Time</option>
            </select>
          </li>
          <li data-category="behind-the-scenes">
            Behind The Scenes
            <select class="year-dropdown">
              <option value="all">All Time</option>
            </select>
          </li>
          <li data-category="posters">
            Posters
            <select class="year-dropdown">
              <option value="all">All Time</option>
            </select>
          </li>
        </ul>
      </nav>

      <section class="gallery-section">
        <div class="gallery-cards" id="galleryContainer"></div>
      </section>
    </main>

    <div class="image-modal" id="imageModal">
      <span class="close-btn" id="closeModal">&times;</span>
      <span class="nav-btn prev" id="prevBtn">&#10094;</span>
      <img class="modal-content" id="modalImage" />
      <span class="nav-btn next" id="nextBtn">&#10095;</span>
      <div class="modal-caption" id="modalCaption"></div>
    </div>

    <!-- footer by thushiyanth  -->

    <?php include "classes/footer.html"; ?>

    <script type="module" src="static/js/gallery-page.js"></script>
    <script src="static/js/global-js.js"></script>
  </body>
</html>
