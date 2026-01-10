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

    <link rel="stylesheet" href="gallary-page.css" />
    <link rel="stylesheet" href="gallary-global.css" />

    <?php include "classes/stylesheet.html"; ?>
  </head>

  <body>
    <!-- // navbar from thushiyanth code  -->

    <!-- IMPORTANT NOTE: I have added a class .main-navbar for the header navbar because it was clashing with the gallery nav elements. -->
     
    <header>
      <nav class="main-navbar">
        <div>
          <img src="assets/Logo.png" alt="Logo" class="logo" />
        </div>
        <input type="checkbox" id="sidebar-toggle" />
        <label for="sidebar-toggle" class="open-sidebar-button">
          <!-- <span class="material-symbols-outlined">menu</span> -->
          <svg
            preserveAspectRatio="none"
            xmlns="http://www.w3.org/2000/svg"
            x="0px"
            y="0px"
            width="40"
            height="30"
            viewBox="0 0 24 24"
          >
            <path
              d="M5,7h2h12c1.1,0,2-0.9,2-2s-0.9-2-2-2H7H5C3.9,3,3,3.9,3,5S3.9,7,5,7z"
            ></path>
            <path
              d="M19,10h-3H5c-1.1,0-2,0.9-2,2s0.9,2,2,2h11h3c1.1,0,2-0.9,2-2S20.1,10,19,10z"
            ></path>
            <path
              d="M19,17h-6H5c-1.1,0-2,0.9-2,2s0.9,2,2,2h8h6c1.1,0,2-0.9,2-2S20.1,17,19,17z"
            ></path>
          </svg>
        </label>
        <div class="sidebar">
          <div>
            <label for="sidebar-toggle" class="close-sidebar-button">
              <!-- <span class="material-symbols-outlined">close</span> -->
              <svg
                xmlns="http://www.w3.org/2000/svg"
                x="0px"
                y="0px"
                width="35"
                height="35"
                viewBox="0 0 72 72"
              >
                <path
                  d="M 19 15 C 17.977 15 16.951875 15.390875 16.171875 16.171875 C 14.609875 17.733875 14.609875 20.266125 16.171875 21.828125 L 30.34375 36 L 16.171875 50.171875 C 14.609875 51.733875 14.609875 54.266125 16.171875 55.828125 C 16.951875 56.608125 17.977 57 19 57 C 20.023 57 21.048125 56.609125 21.828125 55.828125 L 36 41.65625 L 50.171875 55.828125 C 51.731875 57.390125 54.267125 57.390125 55.828125 55.828125 C 57.391125 54.265125 57.391125 51.734875 55.828125 50.171875 L 41.65625 36 L 55.828125 21.828125 C 57.390125 20.266125 57.390125 17.733875 55.828125 16.171875 C 54.268125 14.610875 51.731875 14.609875 50.171875 16.171875 L 36 30.34375 L 21.828125 16.171875 C 21.048125 15.391875 20.023 15 19 15 z"
                ></path>
              </svg>
            </label>
            <a href="#">Home</a>
            <div>
              <div>
                Our Activities<span
                  ><svg
                    width="20px"
                    height="20px"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill="white"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L6.29289 9.70711C5.90237 9.31658 5.90237 8.68342 6.29289 8.29289C6.68342 7.90237 7.31658 7.90237 7.70711 8.29289L12 12.5858L16.2929 8.29289C16.6834 7.90237 17.3166 7.90237 17.7071 8.29289C18.0976 8.68342 18.0976 9.31658 17.7071 9.70711L12.7071 14.7071Z"
                      fill="#000000"
                    /></svg
                ></span>
              </div>
              <div class="dropdown">
                <div>
                  <a href="#">Events</a>
                  <a href="#">Wrokshops</a>
                  <a href="#">Talks</a>
                  <a href="#">Talking 2D Stars</a>
                </div>
              </div>
            </div>
            <div>
              <div>
                Projects<span
                  ><svg
                    width="20px"
                    height="20px"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill="white"
                      fill-rule="evenodd"
                      clip-rule="evenodd"
                      d="M12.7071 14.7071C12.3166 15.0976 11.6834 15.0976 11.2929 14.7071L6.29289 9.70711C5.90237 9.31658 5.90237 8.68342 6.29289 8.29289C6.68342 7.90237 7.31658 7.90237 7.70711 8.29289L12 12.5858L16.2929 8.29289C16.6834 7.90237 17.3166 7.90237 17.7071 8.29289C18.0976 8.68342 18.0976 9.31658 17.7071 9.70711L12.7071 14.7071Z"
                      fill="#000000"
                    /></svg
                ></span>
              </div>
              <div class="dropdown">
                <div>
                  <a href="#">Radio Antenna</a>
                </div>
              </div>
            </div>
            <a href="#">ISAAC</a>
            <a href="#">Equipments</a>
            <a href="#">Blogs</a>
            <a href="#">Gallery</a>
            <a href="#">Team</a>
            <a href="#">About Us</a>
          </div>
        </div>
      </nav>
    </header>

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

    <script type="module" src="gallary-page.js"></script>
    <script src="global-js.js"></script>
  </body>
</html>
