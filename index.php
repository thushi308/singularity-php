<?php include "location_prefixes.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SINGULARITY</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <?php include "classes/stylesheet.html"; ?> 
    <!--<link rel="stylesheet" href="style.css">-->
    <script src="static/js/script.js"></script>
</head>
<body>
    <?php include "classes/header.html"; ?>

    <main>
        <div class="hero-section-bg" style="background-image: url('<?php echo $static_images_location_prefix; ?>hero_image.jpg')"></div>
        <section class="section hero-section">
            <div>
                <h1 class="">SINGULARITY<br>The Astronomy Club of IISER-K</h1>
                <p class="">Born from a shared dream in 2023, Singularity has grown to IISER Kolkata's vibrant hub for exploring cosmos beyond classrooms!</p>
                <a href="#upcoming-events" class="call-to-action">Upcoming Events</a>
                <div class="credits">
                    <span style="font-style: normal; font-size: inherit;">Photo Credits:</span> Shantonu Dutta (IISER-K)
                </div>
            </div>
        </section>
        <section class="section about-section">
            
            <div class="card-img-container">
                <img src="<?php echo $static_images_location_prefix; ?>aboutus.jpg" alt="something">
            </div>
            <div class="section-header">
                <h2 class="">Who are We?</h2>
                <div class="">
                    <span style="display: none; font-style: italic;">Singularity</span>Singularity is the official Astronomy club of the Indian Institute of Science Education and Research (IISER) Kolkata. Founded in 2023 by a group of passionate astrophiles, we have consistently been one of the most active clubs in our institute, with a group of equally active and passionate members.<br><br>
                    We aim to foster passion and genuine interest in astronomy amongst the college community. We hold<br>
                    <ul>
                        <li>regular stargazing sessions that provide demonstrations in handling telescopes along with practical astronomy.</li>
                        <li>seminars where students talk about their theses/ internship projects - or anything they like to teach!</li>
                        <li>interviews with renowned scientists, faculties, and postdoc researchers.</li>
                        <li>workshops on astronomical data analysis and image processing techniques.</li>
                        <li>Science projects, where students learn to explore beyond textbooks</li>
                        <li>Quizzes, Discussion Sessions and more!</li>
                    </ul><br>
                </div>
                <a href="aboutus.php" class="call-to-action">Learn more</a>
            </div>
        
        </section>

        <section class="section projects-section">
            <div class="section-header">
                <h2 class="">Our Projects</h2>
                <p class="">Building, Observing, and Analyzing our way through the Universe. One step at a time ; )</p>
            </div>
            <div class="projects-container">
                <div class="project">
                    <div class="project-image-container">
                        <img src="pro1.jpeg" alt="someiamge">
                    </div>
                    <div class="project-details">
                        <h3 class="project-title">Radio Antenna Project</h3>
                        <p class="project-description">Astronomy is science that will challenge your imagination. How many stars in a galaxy? How many galaxies in the known universe? How many strange worlds are out there on other planets, orbiting other stars, and what are they like? Is there life on planets besides Earth? The distances are mind-boggling; the numbers are immense. </p>
                        <a class="card-page-link project-page-link" href="projects.php?loc=radio.json">Know more</a>
                    </div>
                </div>
            </div>
            <div>
                <a href="" class="call-to-action" style="display: none;">View All</a>
            </div>
        </section>
        <section class="section featured-events-section">
            <div class="section-header">
                <h2 class="">Featured Events</h2>
                <p class="">Seminars, Workshops, Quizzes, Stargazing, Outreach, and many more!</p>
            </div>
            <div class="cards-container">
                <!-- swiper js for featured events cards-->
                <div class="card">
                    <div class="card-img-container">
                        <img src="<?php echo $static_images_location_prefix; ?>featured_event_1.jpg" alt="someimage">
                    </div>
                    <div class="card-details-container">
                        <!-- event details -->
                        <div class="heading">Detecting Dark Matter</div>
                        <div class="description">Workshop: by Susnata Chattopadhyay</div>
                        <a href="" class="card-page-link">Know More</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img-container">
                        <img src="<?php echo $static_images_location_prefix; ?>featured_event_2.jpg" alt="someimage">
                    </div>
                    <div class="card-details-container">
                        <!-- event details -->
                        <div class="heading">A QnA session</div>
                        <div class="description">Talk: by Rohan Kumar</div>
                        <a href="" class="card-page-link">Know More</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img-container">
                        <img src="<?php echo $static_images_location_prefix; ?>featured_event_3.jpg" alt="someimage">
                    </div>
                    <div class="card-details-container">
                        <!-- event details -->
                        <div class="heading">Observing Total Lunar Eclipse 2025</div>
                        <div class="description">Date: 7<sup><span style="font-size: 0.7rem;">th</span></sup> September, 2025</div>
                        <a href="" class="card-page-link">Know More</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img-container">
                        <img src="<?php echo $static_images_location_prefix; ?>featured_event_4.jpg" alt="someimage">
                    </div>
                    <div class="card-details-container">
                        <!-- event details -->
                        <div class="heading">Astrophotography</div>
                        <div class="description">Workshop: by Shantonu Dutta</div>
                        <a href="" class="card-page-link">Know More</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img-container">
                        <img src="<?php echo $static_images_location_prefix; ?>featured_event_5.jpg" alt="someimage">
                    </div>
                    <div class="card-details-container">
                        <!-- event details -->
                        <div class="heading">What makes the Sun a Magnet?</div>
                        <div class="description">Talk: by Chitradeep Saha</div>
                        <a href="" class="card-page-link">Know More</a>
                    </div>
                </div>
            </div>
            <div>
                <a href="#blog" class="call-to-action">View All</a>
            </div>
        </section>
        <section id="upcoming-events" class="section calendar-section">
            <div class="section-header">
                <h2 class="">Upcoming Events</h2>
                <p class="">Discover what's next in our radar!</p>
            </div>
            <div class="cards-container">
                <!-- swiper js for calendar cards or normal scrolling-->
                <div class="card">
                    <div class="card-img-container">
                        <img src="up1.jpg" alt="someimage">
                        <div class="card-title-date-container">
                            <!-- heading and date of event -->
                            <div>
                                <span>01</span>
                                <span>NOV</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-details-container">
                        <!-- event details -->
                        <div class="heading">Water Rocketry Workshop</div>
                        <div class="data-time">Date and Time</div>
                        <div class="speaker">Speaker</div>
                        <div class="description">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Numquam, reiciendis vero.</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img-container">
                        <img src="up2.jpg" alt="someimage">
                        <div class="card-title-date-container">
                            <!-- heading and date of event -->
                            <div>
                                <span>05</span>
                                <span>NOV</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-details-container">
                        <!-- event details -->
                        <div class="heading">Student Talk</div>
                        <div class="data-time">Date and Time</div>
                        <div class="speaker">Speaker</div>
                        <div class="description">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Numquam, reiciendis vero.</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img-container">
                        <img src="up3.jpg" alt="someimage">
                        <div class="card-title-date-container">
                            <!-- heading and date of event -->
                            <div>
                                <span>07</span>
                                <span>NOV</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-details-container">
                        <!-- event details -->
                        <div class="heading">Celestial Quest</div>
                        <div class="data-time">Date and Time</div>
                        <div class="speaker">Speaker</div>
                        <div class="description">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Numquam, reiciendis vero.</div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section youtube-section">
            <div class="section-header">
                <h2 class="">Our YouTube Channel</h2>
                <p class="" style="display: none;"></p>
            </div>
            <div class="youtube-card">
                <!-- youtube cards -->
                <iframe width="" height="400px" src="https://www.youtube-nocookie.com/embed/hbA5q-j5hNg?si=cNFx2-jgXQ_GDAey" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>
        </section>
        <section class="section blog-section" style="display: none;">
            <div class="section-header">
                <h2 class="">Latest Blog</h2>
                <p class="">Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia, nam.</p>
            </div>
            <div class="cards-container">
                <!-- blog cards -->
                <div class="card">
                    <div class="card-img-container">
                        <img src="someimage.png" alt="someimage">
                    </div>
                    <div class="card-details-container">
                        <!-- event details -->
                        <div class="heading">Heading 1</div>
                        <div class="description">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Numquam, reiciendis vero.</div>
                        <a href="" class="card-page-link">Know More</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-img-container">
                        <img src="someimage.png" alt="someimage">
                    </div>
                    <div class="card-details-container">
                        <!-- event details -->
                        <div class="heading">Heading 1</div>
                        <div class="description">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Numquam, reiciendis vero.</div>
                        <a href="" class="card-page-link">Know More</a>
                    </div>
                </div>
            </div>
            <div>
                <a href="#blog" class="call-to-action">View All</a>
            </div>
        </section>
        <section class="gallery-section">
            <div class="section-header">
                <h2 class="">Gallery</h2>
                <p class="">Moments and Memories </p>
            </div>
            <div class="images-container">
                <div>
                    <div class="image"><img src="gal1.jpeg" alt="someimage"></div>
                    <div class="image"><img src="gal2.jpeg" alt="someimage"></div>
                    <div class="image"><img src="gal3.jpeg" alt="someimage"></div>
                </div>
                <div>
                    <div class="image"><img src="gal4.jpeg" alt="someimage"></div>
                    <div class="image"><img src="gal5.jpeg" alt="someimage"></div>
                    <div class="image"><img src="gal6.jpeg" alt="someimage"></div>
                </div>
            </div>
            <div>
                <a href="#blog" class="call-to-action">View All</a>
            </div>
        </section>
        <?php include "classes/footer.html"; ?>
    </main>    
</body>
</html>
