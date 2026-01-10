<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
    <link rel="icon" type="image/x-icon" href="admin/images/favicon.ico">
    <link rel="stylesheet" href="admin/styles_home.css">
    </head>
<div class="header">
    <img src="admin/images/Logo.png" style="width:247.217px;height:150px;">
</div>
<div class="nav">
    <li><a href="singular.php">Home</a></li>
    <li><a href="gallery.php">Gallery</a></li>
    <li><a href="research.php" onmouseover="this.style.backgroundColor='#692dc9b6'" onmouseout="this.style.backgroundColor='#3a196e8a'" style="background-color:#3a196e8a;">Blogs</a></li>
    <li><a href="activities.php">Activities</a></li>
    <li><a href="#contact" style="float:right">Contact Us</a></li>
    
    
</div>
<div class="body">
    <div class="main">
        <section id="about">
            <h2>Blogs</h2>
            <p>Welcome to the Singularity Blog Posts!</p>
            <p>Explore the cosmos through the eyes of passionate enthusiasts with our collection of blogs, all written by members of Singularity. Delve into this treasure trove of knowledge and discoveries made by dedicated amateur astronomers. Whether you're just beginning your journey into astronomy or you're a seasoned stargazer, we hope you'll find valuable insights and inspiration from our community's contributions.</p>
            </section>
            <section id="research">
            <div class="research-papers">
                <?php
                    $papers=file("admin/research.txt");
                    $length=count($papers);
                    $count=$length-4;
                    $sub_paper="";
                    $sub_title="";
                    $sub_author="";
                    $sub_abstract="";
                    $flag=0;
                    while($count>=0)
                    {   
                        $flag=1;
                        $sub_paper=trim($papers[$count]);
                        $sub_paper="'admin/uploads_papers/".$sub_paper."'";
                        $count=$count+1;
                        $sub_title=trim($papers[$count]);
                        $count=$count+1;
                        $sub_author="Author: ".trim($papers[$count]);
                        $count=$count+1;
                        $sub_abstract="Abstract: ".trim($papers[$count]);
                        $count=$count+1;
                        echo "<article><h3>".$sub_title."</h3><p>".$sub_author."</p><p>".$sub_abstract."</p><a href=".$sub_paper.">Read Full Paper</a></article>";
                        $count=$count-8;
                    }
                    if ($flag==0)
                    {
                        echo "<p>No papers have been uploaded yet.</p>";
                    }
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