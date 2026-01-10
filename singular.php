<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])){
        $new_id=trim($_POST["id"]);
        clearstatcache();
        $size=filesize("admin/news.txt");
        if (!empty($new_id)){
            $flag=0;
            if($size>0){
                $file=fopen("admin/news.txt", "r");
                if(!$file){$_SESSION["message"]="There was an unexpected problem. Please try again.";}
                $arr=explode(", ",fread($file,$size));
                fclose($file);
                foreach($arr as $x){
                    if(trim($x)==$new_id){$flag=1;}
                }
            }
            $file = fopen("admin/news.txt", "a");
            if (!filter_var($new_id, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["message"]="Invalid email ID.";
            }
            elseif($flag==1){
                $_SESSION["message"]="You've already subscribed. :)";
            }
            elseif($file){
                if($size==0){
                    fwrite($file,$new_id);
                }
                else{
                    fwrite($file,", ".$new_id);
                }
                $_SESSION["message"]="You've subscribed successfully.";
            }
            else{
                $_SESSION["message"]="There was an unexpected problem. Please try again.";
            }
            fclose($file);
        }
        else{
            $_SESSION["message"]="Please fill up all fields.";
        }
        
    }
    $message = "";
    if (isset($_SESSION["message"])) {
        $message = $_SESSION["message"];
        unset($_SESSION["message"]); 
    }
    
    
?>
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
    <li><a href="singular.php" onmouseover="this.style.backgroundColor='#692dc9b6'" onmouseout="this.style.backgroundColor='#3a196e8a'" style="background-color:#3a196e8a;">Home</a></li>
    <li><a href="gallery.php">Gallery</a></li>
    <li><a href="research.php">Blogs</a></li>
    <li><a href="activities.php">Activities</a></li>
    <li><a href="#contact" style="float:right">Contact Us</a></li>
</div>
<div class="body">
    <div id="starfield"></div>
    <script src="admin/script.js"></script>

    <div id="events" class="aside">
        <h2>Upcoming Events</h2>
        <ul>
        <?php
            $myfile = fopen("admin/events.txt", "r") or die("Unable to open file!");
            clearstatcache();
            if(filesize("admin/events.txt")==0){
                echo "<p>No events have been scheduled.<p>";
            }
            else{
                $count=1;
                while(!feof($myfile)) {
                    if($count%5==1){echo "<h3>".fgets($myfile) . "<br></h3>";}
                    else{echo "<p>".fgets($myfile) . "<br></p>";}
                    $count=$count+1;
                    if($count%5==1){echo"<br>";}
                }
                fclose($myfile);
            }
        ?>
        </ul>
    </div>

    <div class="main">
        <section id="about">
            <h2>About Us</h2>
            <p>Welcome to the Astronomy Club! We are a group of enthusiasts who love to explore the wonders of the universe. Join us for stargazing sessions, astrophotography workshops, and engaging discussions about the cosmos.</p>
        </section>

        <section id="gallery">
            <h2>Gallery</h2>
            <div class="gallery">
                <?php
                    $images=file("admin/gallery/gallery.txt");
                    $length=count($images);
                    if($length<=9){$count=0;}
                    else{$count=$length-9;}
                    $sub_img="";
                    $sub_caption="";
                    while($count<$length)
                    {
                        $sub_img=trim($images[$count]);
                        $sub_img="'admin/uploads/".$sub_img."'";
                        $count=$count+2;
                        $sub_caption=trim($images[$count]);
                        $count=$count+1;
                        echo "<figure><img src=".$sub_img." ><figcption style= 'margin-top: 10px; font-style: italic' >".$sub_caption."</figcaption></figure>";
                    }
                
                ?>
            </div>
            <p>To continue exploring the rest of our catalogue, be sure to visit our <a href="gallery.php" onmouseover="this.style.color='yellow'" onmouseout="this.style.color='yellow'" style="color:yellow;"> gallery. </a></p>
        </section>

       

        <section id="research">
            <h2>Blogs</h2>
            <div class="research-papers">
                <?php
                    $papers=file("admin/research.txt");
                    $length=count($papers);
                    if($length<=8){$count=0;}
                    else{$count=$length-8;}
                    $sub_paper="";
                    $sub_title="";
                    $sub_author="";
                    $sub_abstract="";
                    $flag=0;
                    while($count<$length)
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
                    }
                    if ($flag==0)
                    {
                        echo "<p>No papers have been uploaded yet.</p>";
                    }
                ?>
            </div>
        </section>
        <section id="activities">
            <h2>Activities</h2>
            <div class="activities">
            <?php
                    $papers=file("admin/activity.txt");
                    $length=count($papers);
                    if($length<=6){$count=0;}
                    else{$count=$length-6;}
                    $sub_paper="";
                    $sub_title="";
                    $flag=0;
                    $s=1;
                    while($count<$length)
                    {   
                        $flag=1;
                        $sub_paper=trim($papers[$count]);
                        $sub_paper="'admin/uploads_activity/".$sub_paper."'";
                        $count=$count+1;
                        $sub_title=trim($papers[$count]);
                        $count=$count+1;
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
        <section id="newsletter">
            <link rel="stylesheet" href="admin/newslet.css">
            <h2>Newsletter</h2>
            <p>Are you fascinated by the stars, planets, and the mysteries of the universe? Our newsletter is your gateway to the cosmos, offering a front-row seat to the latest astronomical discoveries, celestial events, and club activities.</p>
            <p>By subscribing, you'll receive regular updates on stargazing opportunities, expert insights, and tips on how to observe the night sky. Whether you’re a seasoned astronomer or just starting out, our newsletter has something for everyone.</p>
            <p>Don’t miss out on the wonders of the universe—subscribe today and join our community of star enthusiasts!</p><br>
            <div class="newsletter">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <div class="form-group">
                        <label for="id">Enter your email</label><br><br>
                        <input type="email" id="id" name="id" required><br><br>
                    </div>
                    <?php
                        if (isset($message) && !empty($message)) {
                            echo "<script>document.getElementById('newsletter').scrollIntoView();</script>";
                            echo "<p style='color: yellow;'>".$message."</p>";
                        }
                    ?>
                    <button id="submit" type="submit" onmouseover="this.style.backgroundColor='rgba(255, 255, 0, 0.788)'" onmouseout="this.style.backgroundColor='yellow'" style="background-color:yellow;">Subscribe</button>
            </form>
        </div>
    </section>
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