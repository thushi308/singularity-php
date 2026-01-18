<?php

include "location_prefixes.php";

// Get event ID from URL
$event_id = $_GET['id'] ?? '';

if (empty($event_id)) {
    header("Location: events.php");
    exit();
}

// Load event data
$event_json = $listing_pages_location_prefix . "events/" . $event_id . "/event-page-" . $event_id . ".json";

if (!file_exists($event_json)) {
    header("Location: events.php");
    exit();
}

$event = json_decode(file_get_contents($event_json), true);

if ($event === null) {
    header("Location: events.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Atma:wght@300;400;500;600;700&family=Cherry+Cream+Soda&family=Momo+Trust+Display&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />

    <link rel="icon" href="assets/favicon.ico" />
    <title><?= htmlspecialchars($event['title']) ?> | Singularity</title>

    <link rel="stylesheet" href="<?php echo $static_css_location_prefix ?>global.css">
    <link rel="stylesheet" href="<?php echo $static_css_location_prefix ?>event-page.css">
    
    <?php include $includes_location_prefix . "stylesheet.html"; ?>
    
    <style>
    /* Gallery Modal Styles */
    .gallery-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.95);
        overflow: auto;
    }

    .gallery-modal.active {
        display: block;
    }

    .modal-content-wrapper {
        position: relative;
        max-width: 1400px;
        margin: 2rem auto;
        padding: 20px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #444;
    }

    .modal-header h2 {
        color: white;
        font-family: var(--event-general-heading-font);
        font-size: 2rem;
    }

    .close-modal {
        color: #fff;
        font-size: 3rem;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.3s ease;
        line-height: 1;
        padding: 0 15px;
    }

    .close-modal:hover,
    .close-modal:focus {
        color: #ff6666;
    }

    .modal-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        padding: 1rem 0;
    }

    .modal-gallery-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .modal-gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(255, 102, 102, 0.3);
    }

    .modal-gallery-item img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        display: block;
    }

    .modal-gallery-item .image-number {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* Lightbox for viewing full image */
    .lightbox {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.98);
        justify-content: center;
        align-items: center;
    }

    .lightbox.active {
        display: flex;
    }

    .lightbox-content {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }

    .lightbox-close {
        position: absolute;
        top: 20px;
        right: 40px;
        color: white;
        font-size: 3rem;
        font-weight: bold;
        cursor: pointer;
        z-index: 2001;
    }

    .lightbox-close:hover {
        color: #ff6666;
    }

    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 3rem;
        font-weight: bold;
        cursor: pointer;
        padding: 20px;
        user-select: none;
        z-index: 2001;
        transition: color 0.3s ease;
    }

    .lightbox-nav:hover {
        color: #ff6666;
    }

    .lightbox-prev {
        left: 20px;
    }

    .lightbox-next {
        right: 20px;
    }

    .event-meta-info {
        background: rgba(255, 255, 255, 0.05);
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .event-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .event-meta-item:last-child {
        margin-bottom: 0;
    }

    .event-meta-icon {
        min-width: 25px;
    }

    @media (max-width: 768px) {
        .modal-gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .modal-gallery-item img {
            height: 180px;
        }

        .modal-header h2 {
            font-size: 1.5rem;
        }

        .close-modal {
            font-size: 2rem;
        }

        .lightbox-nav {
            font-size: 2rem;
            padding: 10px;
        }

        .lightbox-close {
            top: 10px;
            right: 20px;
            font-size: 2rem;
        }
    }
    </style>
</head>

<body>
    <?php include $includes_location_prefix . "header.html"; ?>

    <h1 class="page-title">EVENTS | Singularity</h1>

    <main class="page-container">
        <section class="main-event-details">
            <div id="event-poster">
                <?php if (!empty($event['poster'])): ?>
                    <img src="<?= htmlspecialchars($event['poster']) ?>" alt="Event Poster" />
                <?php endif; ?>
            </div>
            <div class="event-details">
                <h3 class="event-title"><?= htmlspecialchars($event['title']) ?></h3>
                
                <div class="event-meta-info">
                    <div class="event-meta-item">
                        <span class="event-meta-icon">📅</span>
                        <span><strong>Date:</strong> <?= htmlspecialchars($event['date']) ?></span>
                    </div>
                    <div class="event-meta-item">
                        <span class="event-meta-icon">🕒</span>
                        <span><strong>Time:</strong> <?= htmlspecialchars($event['time']) ?></span>
                    </div>
                    <div class="event-meta-item">
                        <span class="event-meta-icon">📍</span>
                        <span><strong>Location:</strong> <?= htmlspecialchars($event['location']) ?></span>
                    </div>
                </div>
                
                <h4>Details & Abstract</h4>
                <p><?= nl2br(htmlspecialchars($event['details'])) ?></p>
            </div>
        </section>

        <?php if (!empty($event['whatHappened'])): ?>
        <section class="event-description">
            <h2>What Happened in Event ?</h2>
            <p><?= nl2br(htmlspecialchars($event['whatHappened'])) ?></p>
        </section>
        <?php endif; ?>

        <?php if (!empty($event['author']['bio']) || !empty($event['author']['image'])): ?>
        <section class="author-section">
            <div class="author-bio">
                <h2 id="author-heading">Author</h2>
                <p><?= nl2br(htmlspecialchars($event['author']['bio'])) ?></p>
            </div>
            <div class="author-photo">
                <?php if (!empty($event['author']['image'])): ?>
                    <img src="<?= htmlspecialchars($event['author']['image']) ?>" alt="Author Photo" />
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($event['resources'])): ?>
        <section class="resources-section">
            <h2>Resources</h2>
            <div class="resources-list">
                <ul>
                    <?php
                    $half = ceil(count($event['resources']) / 2);
                    for ($i = 0; $i < $half; $i++):
                        if (isset($event['resources'][$i])):
                    ?>
                        <li><a href="<?= htmlspecialchars($event['resources'][$i]['link']) ?>" target="_blank"><?= htmlspecialchars($event['resources'][$i]['name']) ?></a></li>
                    <?php
                        endif;
                    endfor;
                    ?>
                </ul>
                <ul>
                    <?php
                    for ($i = $half; $i < count($event['resources']); $i++):
                        if (isset($event['resources'][$i])):
                    ?>
                        <li><a href="<?= htmlspecialchars($event['resources'][$i]['link']) ?>" target="_blank"><?= htmlspecialchars($event['resources'][$i]['name']) ?></a></li>
                    <?php
                        endif;
                    endfor;
                    ?>
                </ul>
            </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($event['gallery'])): ?>
        <section class="photos-section">
            <h2>Photos</h2>
            <div class="gallery-container">
                <?php if (isset($event['gallery'][0])): ?>
                <div class="group-photo">
                    <img src="<?= htmlspecialchars($event['gallery'][0]) ?>" alt="Event Photo" onclick="openLightbox(0)" style="cursor: pointer;" />
                </div>
                <?php endif; ?>
                
                <?php if (count($event['gallery']) > 1): ?>
                <div class="other-photos">
                    <div class="small-photos-row">
                        <?php
                        $displayCount = min(2, count($event['gallery']) - 1);
                        for ($i = 1; $i <= $displayCount; $i++):
                        ?>
                        <div class="few-photo">
                            <img src="<?= htmlspecialchars($event['gallery'][$i]) ?>" alt="Event Photo" onclick="openLightbox(<?= $i ?>)" style="cursor: pointer;" />
                        </div>
                        <?php endfor; ?>
                    </div>
                    
                    <button class="view-all-btn" onclick="openGalleryModal()">View All</button>
                </div>
                <?php endif; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <?php if (!empty($event['gallery'])): ?>
    <!-- Gallery Modal -->
    <div id="galleryModal" class="gallery-modal">
        <div class="modal-content-wrapper">
            <div class="modal-header">
                <h2>All Photos (<?= count($event['gallery']) ?>)</h2>
                <span class="close-modal" onclick="closeGalleryModal()">&times;</span>
            </div>
            <div class="modal-gallery-grid">
                <?php foreach ($event['gallery'] as $index => $photo): ?>
                <div class="modal-gallery-item" onclick="openLightbox(<?= $index ?>)">
                    <img src="<?= htmlspecialchars($photo) ?>" alt="Event Photo <?= $index + 1 ?>" />
                    <div class="image-number">#<?= $index + 1 ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Lightbox for Full Image View -->
    <div id="lightbox" class="lightbox">
        <span class="lightbox-close" onclick="closeLightbox()">&times;</span>
        <span class="lightbox-nav lightbox-prev" onclick="changeImage(-1)">&#10094;</span>
        <img id="lightboxImage" class="lightbox-content" src="" alt="Full size image" />
        <span class="lightbox-nav lightbox-next" onclick="changeImage(1)">&#10095;</span>
    </div>

    <script>
        const galleryImages = <?= json_encode($event['gallery']) ?>;
        let currentImageIndex = 0;

        function openGalleryModal() {
            document.getElementById('galleryModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeGalleryModal() {
            document.getElementById('galleryModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function openLightbox(index) {
            currentImageIndex = index;
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightboxImage');
            
            lightboxImage.src = galleryImages[currentImageIndex];
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function changeImage(direction) {
            currentImageIndex += direction;
            
            if (currentImageIndex >= galleryImages.length) {
                currentImageIndex = 0;
            } else if (currentImageIndex < 0) {
                currentImageIndex = galleryImages.length - 1;
            }
            
            document.getElementById('lightboxImage').src = galleryImages[currentImageIndex];
        }

        // Close modal when clicking outside
        document.getElementById('galleryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeGalleryModal();
            }
        });

        // Close lightbox when clicking outside image
        document.getElementById('lightbox').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const lightbox = document.getElementById('lightbox');
            const modal = document.getElementById('galleryModal');
            
            if (lightbox.classList.contains('active')) {
                if (e.key === 'Escape') {
                    closeLightbox();
                } else if (e.key === 'ArrowLeft') {
                    changeImage(-1);
                } else if (e.key === 'ArrowRight') {
                    changeImage(1);
                }
            } else if (modal.classList.contains('active')) {
                if (e.key === 'Escape') {
                    closeGalleryModal();
                }
            }
        });
    </script>
    <?php endif; ?>

    <?php include $includes_location_prefix . "footer.html"; ?>
</body>
</html>