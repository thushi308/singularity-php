<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["unid"])){
        $un_id=trim($_POST["unid"]);
        clearstatcache();
        $unsize=filesize("admin/news.txt");
        if (!empty($un_id)){
            $unflag=0;
            if($unsize>0){
                $unfile=fopen("admin/news.txt", "r");
                $unarr=explode(", ",fread($unfile,$unsize));
                fclose($unfile);
                $unfile = fopen("admin/news.txt", "w");
                $start=0;
                foreach($unarr as $x){
                    if(trim($x)==$un_id){$unflag=1;continue;}
                    else{
                        if($start==0){fwrite($unfile,trim($x));$start=1;}
                        else{fwrite($unfile,", ".trim($x));}
                    }
                }
            }
            if (!filter_var($un_id, FILTER_VALIDATE_EMAIL)) {
                $_SESSION["unmessage"]="Invalid email ID.";
            }
            elseif($unflag==0){
                $_SESSION["unmessage"]="Your mail ID was not found";
            }
            elseif($unfile){
                if($unflag==1){
                    $_SESSION["unmessage"]="We're sad to see you go! You’ve successfully unsubscribed from Singularity’s newsletter. While you won’t receive regular updates anymore, the wonders of the universe will always be within reach. If you ever wish to rejoin our community of star enthusiasts, we’d be thrilled to welcome you back. Clear skies and happy stargazing!";
                }
                
            }
            else{
                $_SESSION["unmessage"]="There was an unexpected problem. Please try again.";
            }
            if($unsize>0){
                fclose($unfile);
            }
        }
        else{
            $_SESSION["unmessage"]="Please fill up all fields.";
        }
        
    }
    $unmessage = "";
    if (isset($_SESSION["unmessage"])) {
        $unmessage = $_SESSION["unmessage"];
        unset($_SESSION["unmessage"]); 
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsub</title>
    <link rel="icon" type="image/x-icon" href="admin/images/favicon.ico">
    <link rel="stylesheet" href="admin/styles2.css">
    </head>
<div class="header">
    <img src="admin/images/Logo.png" style="width:247.217px;height:150px;">
</div>
<div class="body">
    <div id="starfield"></div>
    <div class="main">
        </section>
            <section id="newsletter">
                <link rel="stylesheet" href="admin/newslet.css">
                <h2>Newsletter Unsubscription</h2>
                <div class="newsletter">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <div class="form-group">
                            <label for="unid">Enter your email</label><br><br>
                            <input type="email" id="unid" name="unid" required><br><br>
                        </div>
                        <?php
                            if (isset($unmessage) && !empty($unmessage)) {
                                echo "<script>document.getElementById('newsletter').scrollIntoView();</script>";
                                echo "<p style='color: yellow;'>".$unmessage."</p>";
                            }
                        ?>
                        <button id="submit" type="submit" onmouseover="this.style.backgroundColor='rgba(255, 255, 0, 0.788)'" onmouseout="this.style.backgroundColor='yellow'" style="background-color:yellow;">Unsubscribe</button>
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