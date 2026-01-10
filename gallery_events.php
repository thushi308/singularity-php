<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="icon" type="image/x-icon" href="admin/images/favicon.ico">
    <link rel="stylesheet" href="admin/styles2.css">
    </head>
<div class="header">
    <img src="admin/images/Logo.png" style="width:247.217px;height:150px;">
</div>
<div class="nav">
    <li><a href="singular.php">Home</a></li>
    <li><a href="gallery.php" onmouseover="this.style.backgroundColor='#692dc9b6'" onmouseout="this.style.backgroundColor='#3a196e8a'" style="background-color:#3a196e8a;">Gallery</a></li>
    <li><a href="research.php" >Blogs</a></li>
    <li><a href="activities.php">Activities</a></li>
    <li><a href="#contact" style="float:right">Contact Us</a></li>
    
    
</div>

<div class="body">
    <div class="main">
        <div class="dropdown">
            <button class="dropbtn">Events/Activities &nbsp;<i class="arrow down"></i></button>
            <div class="dropdown-content">
                <a href="gallery.php">Astronomy</a>
            </div>
        </div>
        <section id="about">
            <h2>Gallery</h2>
            <p>Welcome to our Events Gallery! Here, we proudly showcase moments from our various gatherings, star parties, workshops, and educational sessions. These images capture the enthusiasm and camaraderie of our members as we explore the night sky, share knowledge, and enjoy the wonders of astronomy together. Whether you're seeing the joy of stargazing for the first time or the excitement of discovering new celestial phenomena, each photo tells a story of our shared passion for the cosmos. Enjoy browsing through our cherished memories and get a glimpse of what it's like to be part of our astronomy community.</p>
        </section>
        <section id="gallery">
            
            
            <div class="gallery">
                <?php
                    $images=file("admin/gallery/gallery.txt");
                    $length=count($images);
                    $count=$length-3;
                    $sub_img="";
                    $sub_caption="";
                    $flag=0;
                    while($count>=0)
                    {
                        $sub_img=trim($images[$count]);
                        $sub_img="'admin/uploads/".$sub_img."'";
                        $count=$count+1;
                        if(trim($images[$count])=="eve")
                        {
                            $flag=1;
                            $count=$count+1;
                            $sub_caption=trim($images[$count]);
                            $count=$count+1;
                            echo "<figure><img src=".$sub_img." ><figcption style= 'margin-top: 10px; font-style: italic' >".$sub_caption."</figcaption></figure>";
                        }
                        else{$count=$count+2;}
                        $count=$count-6;
                    }
                    if($flag==0){echo "<p> No images have been uploaded yet. <p>";}
                
                ?>
            </div>
        </section>
    </div>
   
</div>
<div class="body">
    <div id="starfield"></div>
    <script src="admin/script.js"></script>
    <div class="footer">
    <section id="contact">
        <h2>Contact Us</h2>
        <p><b class="pr-2"><i class="fa-solid fa-envelope"></i> Email: </b>singularity@iiserkol.ac.in</p>
        <p><b class="pr-2"><i class="fa-brands fa-github"></i> Github:</b> <a class="text-yellow-400" target="_blank" href="https://github.com/Singularity-Astro-Club-of-IISER-K">Singularity-Astro-Club-of-IISER-K</a></p>
        <p><b class="pr-2"><i class="fa-brands fa-linkedin"></i> LinkedIn:</b> <a class="text-yellow-400" target="_blank" href="https://www.linkedin.com/in/singularity-astro-club-of-iiser-kolkata-7538382a2/">Singularity</a></p>
        <p><b class="pr-2"><i class="fa-brands fa-twitter"></i> Twitter:</b> <a class="text-yellow-400"  target="_blank" href="https://twitter.com/singularity_ik">singularity_ik</a></p>
        <p><b class="pr-2"><i class="fa-brands fa-instagram"></i> Instagram:</b> <a class="text-yellow-400" target="_blank" href="https://www.instagram.com/singularity_iiserk/">singularity_iiserk</a></p>
    </section>
    <p  style="color:white">&copy; 2024<span id="demo"></span> Singularity. All rights reserved.</p>
</div>
</div>
</html>
<script>
    const d = new Date();
    if(d.getFullYear()>2024){
        document.getElementById("demo").innerHTML =" - "+ d.getFullYear();
    }
</script>