<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Singularity</title>
    <link rel="icon" type="image/x-icon" href="admin/images/favicon.ico">
    <link rel="stylesheet" href="admin/styles_home.css">
    
</head>
<div class="header">
    <img src="admin/images/Logo.png" style="width:247.217px;height:150px;">
   
</div>
<div class="nav">
    <li><a href="singular.php" >Home</a></li>
    <li><a href="gallery.php">Gallery</a></li>
    <li><a href="research.php">Blogs</a></li>
    <li><a href="activities.php"onmouseover="this.style.backgroundColor='#692dc9b6'" onmouseout="this.style.backgroundColor='#3a196e8a'" style="background-color:#3a196e8a;">Activities</a></li>
    <li><a href="#contact" style="float:right">Contact Us</a></li>
</div>
<div class="body">
    <div id="starfield"></div>
    <div class="main">
        
        <section id="activities">
                <h2>Activities</h2>
                <p>Explore the celestial journeys we've embarked on as a community of passionate stargazers. Here, you'll find highlights of our past events, each one a testament to our love for the universe and its wonders. From quizzes and educational workshops to captivating guest lectures, our activities page is your gateway to relive the magical moments we've shared under the night sky. Dive in and discover the highlights of our astronomical adventures, and get inspired for the exciting events yet to come!</p><br><br>
                <div class="activities">
                <?php
                        $papers=file("admin/activity.txt");
                        $length=count($papers);
                        $count=$length-2;
                        $sub_paper="";
                        $sub_title="";
                        $flag=0;
                        $s=1;
                        while($count>=0)
                        {   
                            $flag=1;
                            $sub_paper=trim($papers[$count]);
                            $sub_paper="'admin/uploads_activity/".$sub_paper."'";
                            $count=$count+1;
                            $sub_title=trim($papers[$count]);
                            $count=$count+1;
                            $count=$count-4;
                            if($s%2==1){echo "<article style='background-color: rgba(42, 42, 42, 0.9);'><p>".$sub_title."</p><a href=".$sub_paper.">Visit</a></article>";}
                            else{echo "<article><p>".$sub_title."</p><a href=".$sub_paper.">Visit</a></article>";}
                            $s=$s+1;
                        }
                        if ($flag==0)
                        {
                            echo "<p>No activities have been uploaded yet.</p>";
                        }
                    ?>
                    
                        
                    
                </div>
            </section>
        </div>
    

    

    </div>
    <script src="admin/script.js"></script>
</div>
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
</html>
<script>
    const d = new Date();
    if(d.getFullYear()>2024){
        document.getElementById("demo").innerHTML =" - "+ d.getFullYear();
    }
</script>