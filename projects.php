<?php
$loc = $_GET['loc'];
#$loc = "temp.json";
$file = file_get_contents($loc);
$obj = json_decode($file);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SINGULARITY</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Symbols+Outlined">
    <?php include "classes/stylesheet.html"; ?>
    <script src="script.js"></script>
<style>
        .resource-item:hover {
            transform: scale(1.03); /* Makes the item slightly larger */
            filter: brightness(1.2); /* Makes it slightly brighter */
            transition: transform 0.2s ease-out, filter 0.2s ease-out; /* Smooth transition */
            cursor: pointer; /* Changes mouse to a pointer */
        }

        /* This makes the page background black, creating the 'border' color */
        body {
            background-color: #000;
        }
    </style>
</head>
<body>
    <?php include "classes/header.html"; ?>
    <main>
        <section class="section" style="text-align: center; padding-bottom: 0;">
            <div>
            <h1 class=""><?php echo $obj->title; ?></h1>
            </div>
        </section>

        <section class="section about-section" style="align-items: flex-start; padding-top: 1.5rem;">
            
            <div class="card-img-container" style="flex: 0 0 30%; max-width: 30%;">
            <img src="<?php echo $obj->cover; ?>" alt="<?php echo $obj->cover; ?>" style="width: 100%; height: auto; border-radius: 5px;">
            </div>

            <div style="flex: 1 1 65%; text-align: left; padding-left: 2rem;">
                <h2>About the Project</h2>
                <p>
                    <?php echo $obj->about; ?>
                </p>
            </div>

        </section>

        <section class="section" style="padding-top: 1.5rem;">
            <div class="section-header">
                <h2>What We Are Doing</h2>
            </div>
            <p style="padding-top: 1rem;">
                <?php echo $obj->what; ?> 
            </p>
        </section>

        <section class="section">
            <div class="section-header">
                <h2>Team</h2>
            </div>

            <div class="about-section" style="align-items: flex-start; padding-top: 1.5rem;">
                
                <div style="flex: 0 0 40%; max-width: 40%;">
                    <h3>Members</h3>

                    <?php
                        for ($i=0; $i<count($obj->team); $i++) {
                            if ($obj->team[$i]->type == "member") {
                                echo "<div style='display: flex; align-items: center; margin-bottom: 15px;'><img src='" . $obj->team[$i]->photo . "' alt='" . $obj->team[$i]->photo . "' style='width: 60px; height: 60px; border-radius: 50%; margin-right: 15px; object-fit: cover;'><span>" . $obj->team[$i]->name . "</span></div>";
                            }
                        }
                    ?>
                </div>

                <div style="flex: 1 1 55%; text-align: left; padding-left: 2rem;">
                    <h3>Profs:</h3>
                    <?php
                        for ($i=0; $i < count($obj->team); $i++) {
                            if ($obj->team[$i]->type == "supervisor") {
                                echo "<div style='display: flex; align-items: center; margin-bottom: 15px;'><img src='" . $obj->team[$i]->photo . "' alt='" . $obj->team[$i]->photo . "' style='width: 60px; height: 60px; border-radius: 50%; margin-right: 15px; object-fit: cover;'><span>" . $obj->team[$i]->name . "</span></div>";
                            }
                        }
                    ?>
                </div>

            </div>
        </section>

        <section class="section">
            <div class="section-header">
                <h2>Next Steps</h2>
            </div>

            <p style="padding-top: 1.5rem; text-align: left; max-width: 800px; margin: 0 auto;">
            <?php echo $obj->next; ?>
            </p>
        </section>

        <section class="section">
            <div class="section-header">
                <h2>Photos</h2>
            </div>

            <div class="photo-gallery-preview" style="display: flex; gap: 1rem; padding-top: 1.5rem;">

                <?php
                    for ($i=0; $i<count($obj->photos); $i++) {
                        echo "<div style='flex: 1 1 0; min-width: 250px;'><img src='" . $obj->photos[$i] . "' alt='" . $obj->photos[$i] . "' style='width: 100%; height: 400px; object-fit: cover; border-radius: 5px;'></div>";
                    }
                ?>
            </div>

            <div style="text-align: center; padding: 2rem 0 1rem 0;">
                <a href="gallery.html" class="call-to-action">View All</a>
            </div>
        </section>

        <section class="section">
            <div class="section-header">
                <h2>Resources</h2>
            </div>

            <div class="resource-grid" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem; padding-top: 1.5rem;">

                <?php
                    for ($i=0; $i<count($obj->resources); $i++) {
                        echo "<div class='resource-item' style='flex: 1 1 45%; min-width: 300px; background-color: #1a1a1a; padding: 1.5rem; border-radius: 5px;'><h3>" . $obj->resources[$i]->title . "</h3><p>" . $obj->resources[$i]->description . "</p><a href='" . $obj->resources[$i]->link . "' class='card-page-link' style='padding-top: 0.5rem; display: inline-block;'>Learn More</a></div>";
                    }
                ?>
            </div>
        </section>
        
        <section class="section" style="display: none;">
            <div class="section-header">
                <h2>Findings</h2>
            </div>

            <div class="about-section" style="align-items: center; padding-top: 1.5rem; gap: 0;">
                <div style="flex: 1 1 70%; padding-right: 2rem;">
                    <h3>Finding 1</h3>
                    <p>
                        Description of the first finding. This text block takes up 70% of the width, allowing for detailed explanation.
                    </p>
                </div>
                <div style="flex: 0 0 30%; max-width: 30%;">
                    <img src="fea3.jpg" alt="Image for finding 1" style="width: 100%; height: auto; border-radius: 5px;">
                </div>
            </div>

            <div class="about-section" style="align-items: center; padding-top: 1.5rem; gap: 0;">
                <div style="flex: 0 0 30%; max-width: 30%;">
                    <img src="fea2.jpg" alt="Image for finding 2" style="width: 100%; height: auto; border-radius: 5px;">
                </div>
                <div style="flex: 1 1 70%; text-align: left; padding-left: 2rem;">
                    <h3>Finding 2</h3>
                    <p>
                        Description of the second finding. This text block takes up 70% of the width, with the image on the left.
                    </p>
                </div>
            </div>

            <div class="about-section" style="align-items: center; padding-top: 1.5rem; gap: 0;">
                <div style="flex: 1 1 70%; padding-right: 2rem;">
                    <h3>Finding 3</h3>
                    <p>
                        Description of the third finding. This layout is flipped again, matching the first finding with 70% text on the left.
                    </p>
                </div>
                <div style="flex: 0 0 30%; max-width: 30%;">
                    <img src="fea1.jpg" alt="Image for finding 3" style="width: 100%; height: auto; border-radius: 5px;">
                </div>
            </div>
        </section>

        <?php include "classes/footer.html"; ?>
    </main>
</body>
</html>
