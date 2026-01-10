<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Pragma: no-cache");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["new_username"]) && isset($_POST["new_password"])&& isset($_POST["name"])&& isset($_POST["confirm_password"])) {
    $new_username = $_POST["new_username"];
    $new_password = $_POST["new_password"];
    $new_name=$_POST["name"];
    $confirm=$_POST["confirm_password"];
    clearstatcache();
    $size=filesize("credentials.txt");
    if (!empty($new_username) && !empty($new_password)&& !empty($new_name)&& !empty($confirm) ){
        $file = fopen("credentials.txt", "a");
        if ($file && ($confirm==$new_password) && (strlen($new_password)>=8) ) {
            
            fwrite($file,$new_name."\n");
            
            fwrite($file, $new_username."\n");
            fwrite($file, $new_password."\n"); 
            fclose($file);
            $_SESSION["message"] = "New member registered successfully!";
        }else if ($confirm!=$new_password){
            $_SESSION["message"] ="Passwords do not match";
        }else if(strlen($new_password)<8){
            $_SESSION["message"] ="Password must be atleast 8 characters long";
        }else {
            $_SESSION["message"] ="Failed to register new member. Please try again.";
        }
    } else {
        $_SESSION["message"] = "Parameters cannot be empty.";
    }
}
$message = "";
if (isset($_SESSION["message"])) {
    $message = $_SESSION["message"];
    unset($_SESSION["message"]); // Clear the message after displaying it
}
if ($_SERVER["REQUEST_METHOD"] == "POST"  && isset($_POST["events"])) {
    $event_choice=$_POST["events"];
    $store=file("events.txt");
    $length=count($store);
    $count=0;
    if ($event_choice=="new" && isset($_POST["title"]) && isset($_POST["date"])&& isset($_POST["time"])&& isset($_POST["location"])&& isset($_POST["description"])){
        $event_name = trim( $_POST["title"]);
        $event_date ="Date: ". trim($_POST["date"]);
        $event_time="Time: ".trim($_POST["time"]);
        $event_location="Location: ".trim($_POST["location"]);
        $event_description="Description: ".trim($_POST["description"]);
        clearstatcache();
        $events_size=filesize("events.txt");
        $flag=0;
        if(!empty($event_name) && (strlen($event_date)>6)&& (strlen($event_time)>6)&& (strlen($event_location)>10) && (strlen($event_description)>13)){
            $events_file = fopen("events.txt", "a");
            if($events_file){
                while($count<$length)
                {
                    if($event_name==trim($store[$count])){
                        $flag=1;
                    }
                    $count=$count+5;
                }
                if($flag==1)
                {
                    $_SESSION["event_message"] = "Event data already exists. Please use some other name";
                }
                else{
                    fwrite($events_file,$event_name."\n");
                    fwrite($events_file,$event_date."\n");
                    fwrite($events_file,$event_time."\n");
                    fwrite($events_file,$event_location."\n");
                    fwrite($events_file,$event_description."\n");
                    $_SESSION["event_message"] = "Event section has been updated succesfully.";
                }
            }else {
                $_SESSION["event_message"] ="Failed to update. Please try again.";
            }
        } else {
            $_SESSION["event_message"] = "Parameters cannot be empty.";
        }
    }
    else if ($event_choice=="change" && isset($_POST["title"]) && isset($_POST["date"])&& isset($_POST["time"])&& isset($_POST["location"])&& isset($_POST["description"])){
        $event_name = trim( $_POST["title"]);
        $event_date ="Date: ". trim($_POST["date"]);
        $event_time="Time: ".trim($_POST["time"]);
        $event_location="Location: ".trim($_POST["location"]);
        $event_description="Description: ".trim($_POST["description"]);
        $flag=0;
        if(!empty($event_name) && (strlen($event_date)>6)&& (strlen($event_time)>6)&& (strlen($event_location)>10) && (strlen($event_description)>13)){
            $events_file = fopen("events.txt", "w");
            if($events_file){
                while($count<$length)
                {
                    if($event_name==trim($store[$count])){
                        fwrite($events_file,$event_name."\n");
                        fwrite($events_file,$event_date."\n");
                        fwrite($events_file,$event_time."\n");
                        fwrite($events_file,$event_location."\n");
                        fwrite($events_file,$event_description."\n");
                        $flag=1;
                        $count=$count+5;
                    }
                    else{
                        for ($x = 0; $x <5; $x++) {
                            fwrite($events_file,trim($store[$count])."\n");
                            $count=$count+1;
                        }
                    }
                }
                fclose($events_file);
                if($flag==1){$_SESSION["event_message"] = "Event section has been updated succesfully.";}
                else if ($flag==0){$_SESSION["event_message"] = "Event name was not found.";}
            }else {
                $_SESSION["event_message"] ="Failed to update. Please try again.";
            }
        }else {
            $_SESSION["event_message"] = "Parameters cannot be empty.";
        }
    }
    else if ($event_choice=="delete_specific" && isset($_POST["title"]))
    {
        $event_name = trim( $_POST["title"]);
        $flag=0;
        if(!empty($event_name))
        {
            $events_file = fopen("events.txt", "w");
            if($events_file){
                while($count<$length)
                {
                    if($event_name==trim($store[$count]))
                    {
                        $flag=1;
                        $count=$count+5;
                    }
                    else{
                        for ($x = 0; $x <5; $x++) {
                            fwrite($events_file,trim($store[$count])."\n");
                            $count=$count+1;
                        }
                    }
                }
                fclose($events_file);
                if($flag==1){$_SESSION["event_message"] = "Event section has been updated succesfully.";}
                else if ($flag==0){$_SESSION["event_message"] = "Event name was not found.";}
            }else {
                $_SESSION["event_message"] ="Failed to update. Please try again.";
            }
        }else {
            $_SESSION["event_message"] = "Parameter cannot be empty.";
        }
    }
    else if ($event_choice=="delete_all")
    {
        $events_file = fopen("events.txt", "w");
        if($events_file){
            fclose($events_file);
            $_SESSION["event_message"] = "All events have been deleted.";
        }else {
            $_SESSION["event_message"] ="Failed to update. Please try again.";
        }
    }
}
$event_message = "";
if (isset($_SESSION["event_message"])) {
    $event_message = $_SESSION["event_message"];
    unset($_SESSION["event_message"]); // Clear the message after displaying it
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["gallery"]) ) {
    $gallery_choice=$_POST["gallery"];
    $gallery_store=file("gallery/gallery.txt");
    $gallery_length=count($gallery_store);
    $gallery_count=0;
    if ($gallery_choice=="new" && isset($_POST["subject_name"]) && isset($_POST["topic"]) && isset($_POST["caption"])){
        $target_dir = "uploads/";
        $image_subject = trim( $_POST["subject_name"]);
        $image_topic = trim($_POST["topic"]);
        $image_caption =trim($_POST["caption"]);
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); 
        if(!empty($image_subject) && !empty($image_caption) && !empty($image_topic)){
            if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) {
                $uploadOk = 1;
                } else {
                $_SESSION["gallery_message"] ="File is not an image.";
                $uploadOk = 0;
                }
            }
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $_SESSION["gallery_message"]="Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            if ($_FILES["fileToUpload"]["size"] > 500000) {
                $_SESSION["gallery_message"]="Sorry, your file is too large.";
                $uploadOk = 0;
            }
            $image_name=$target_dir.$image_subject.".".$imageFileType;
            if (file_exists($image_name)) {
                $_SESSION["gallery_message"]="Sorry, file already exists.";
                $uploadOk = 0;
            }
            $gallery_file = fopen("gallery/gallery.txt", "a");
            if($gallery_file){
                if ($uploadOk == 0) {
                    $_SESSION["gallery_message"]=$_SESSION["gallery_message"]."Your file was not uploaded.";
                }
                else{
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        rename($target_file,$image_name);
                        $_SESSION["gallery_message"]="The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                        fwrite($gallery_file,$image_subject.".".$imageFileType."\n");
                        fwrite($gallery_file,$image_topic."\n");
                        fwrite($gallery_file,$image_caption."\n");
                      } else {
                        $_SESSION["gallery_message"]="Sorry, there was an error uploading your file.";
                      }
                }
            }else{$_SESSION["gallery_message"] ="Failed to update. Please try again.";}    
        }else {
            $_SESSION["gallery_message"] = "Parameters cannot be empty.";
        }
    }
    else if ($gallery_choice=="change" && isset($_POST["subject_name"]) && (isset($_POST["topic"]) || isset($_POST["caption"]))){
        $image_subject = trim( $_POST["subject_name"]);
        $flag1=0;
        if(!empty($image_subject)){
            $gallery_file = fopen("gallery/gallery.txt", "w");
            if($gallery_file){
                while($gallery_count<$gallery_length)
                {
                    if($image_subject==trim($gallery_store[$gallery_count])){  
                        $flag1=1;
                        fwrite($gallery_file,trim($gallery_store[$gallery_count])."\n");
                        $gallery_count=$gallery_count+1;
                        if(isset($_POST["topic"])){
                            $image_topic=trim($_POST["topic"]);
                            if(!empty($image_topic))
                            {
                                fwrite($gallery_file,$image_topic."\n");
                                $gallery_count=$gallery_count+1;
                            }
                            else{
                                fwrite($gallery_file,trim($gallery_store[$gallery_count])."\n");
                                $gallery_count=$gallery_count+1;
                                $_SESSION["gallery_message"] ="Parameter Topic was empty.";
                            }
                        } else{
                            fwrite($gallery_file,trim($gallery_store[$gallery_count])."\n");
                            $gallery_count=$gallery_count+1;
                        }
                        if(isset($_POST["caption"])){
                            $image_caption=trim($_POST["caption"]);
                            if(!empty($image_caption))
                            {
                                fwrite($gallery_file,$image_caption."\n");
                                $gallery_count=$gallery_count+1;
                            }
                            else{
                                fwrite($gallery_file,trim($gallery_store[$gallery_count])."\n");
                                $gallery_count=$gallery_count+1;
                                $_SESSION["gallery_message"] ="Parameter Caption was empty.";
                            }
                        } else{
                            fwrite($gallery_file,trim($gallery_store[$gallery_count])."\n");
                            $gallery_count=$gallery_count+1;
                        }
                    }else{
                            fwrite($gallery_file,trim($gallery_store[$gallery_count])."\n");
                            $gallery_count=$gallery_count+1;
                            fwrite($gallery_file,trim($gallery_store[$gallery_count])."\n");
                            $gallery_count=$gallery_count+1;
                            fwrite($gallery_file,trim($gallery_store[$gallery_count])."\n");
                            $gallery_count=$gallery_count+1;
                        } 
                }
                if($flag1==1)
                {
                    $_SESSION["gallery_message"] ="Image details have been updated.";
                }
                else{$_SESSION["gallery_message"] ="Image not found.";}
            } else{$_SESSION["gallery_message"] ="Failed to update. Please try again.";} 
        }else {
            $_SESSION["gallery_message"] = "Parameters cannot be empty.";
        }
    }
    else if ($gallery_choice=="delete" && isset($_POST["subject_name"])){
        $image_subject = trim( $_POST["subject_name"]);
        $flag1=0;
        if(!empty($image_subject)){
            $gallery_file = fopen("gallery/gallery.txt", "w");
            if($gallery_file){
                while($gallery_count<$gallery_length)
                {
                    if($image_subject==trim($gallery_store[$gallery_count])){  
                        $flag1=1;
                        $gallery_count=$gallery_count+3;
                        unlink("uploads/".$image_subject);
                    }
                    else{
                        fwrite($gallery_file,trim($gallery_store[$gallery_count])."\n");
                        $gallery_count=$gallery_count+1;
                        fwrite($gallery_file,trim($gallery_store[$gallery_count])."\n");
                        $gallery_count=$gallery_count+1;
                        fwrite($gallery_file,trim($gallery_store[$gallery_count])."\n");
                        $gallery_count=$gallery_count+1;
                    } 
                }
                if($flag1==1)
                {
                    $_SESSION["gallery_message"] ="Image has been deleted.";
                }
                else{$_SESSION["gallery_message"] ="Image not found.";}
            } else{$_SESSION["gallery_message"] ="Failed to update. Please try again.";} 
        }else {
            $_SESSION["gallery_message"] = "Parameters cannot be empty.";
        }
    }
}
$gallery_message = "";
if (isset($_SESSION["gallery_message"])) {
    $gallery_message = $_SESSION["gallery_message"];
    unset($_SESSION["gallery_message"]); // Clear the message after displaying it
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["research"]) ) {
    $research_choice=$_POST["research"];
    $research_store=file("research.txt");
    $research_length=count($research_store);
    $research_count=0;
    if ($research_choice=="new" && isset($_POST["paper_name"]) && isset($_POST["paper_title"]) && isset($_POST["author"])&& isset($_POST["abstract"])){
        function checkMimeType($filePath, $allowedTypes) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $filePath);
            finfo_close($finfo);
            return in_array($mimeType, $allowedTypes);
        }
        $allowedExtensions = array("pdf", "doc", "docx", "txt", "ppt", "pptx", "xls", "xlsx","zip","tar","gz");
        $allowedMimeTypes = array(
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "text/plain",
            "application/vnd.ms-powerpoint",
            "application/vnd.openxmlformats-officedocument.presentationml.presentation",
            "application/vnd.ms-excel",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "application/zip",

        );
        $target_dir = "uploads_papers/";
        $paper_name= trim( $_POST["paper_name"]);
        $title = trim($_POST["paper_title"]);
        $author=trim($_POST["author"]);
        $abstract=trim($_POST["abstract"]);
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if(!empty($paper_name) && !empty($title) && !empty($author) && !empty($abstract)){
            if (!in_array($fileType, $allowedExtensions)) {
                $_SESSION["research_message"]="Sorry, only PDF, DOC, DOCX, TXT, PPT, PPTX, XLS, ZIP and XLSX files are allowed.";
                $uploadOk = 0;
            }
            if (!checkMimeType($_FILES["fileToUpload"]["tmp_name"], $allowedMimeTypes)) {
                $_SESSION["research_message"]= "Sorry, the MIME type of your file is not allowed.";
                $uploadOk = 0;
            }
            $file_name=$target_dir.$paper_name.".".$fileType;
            if (file_exists($file_name)) {
                $_SESSION["research_message"]= "Sorry, file already exists.";
                $uploadOk = 0;
            }
            if ($_FILES["fileToUpload"]["size"] > 100000000) {
                $_SESSION["research_message"]= "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            $blacklist = array(".php", ".js", ".exe", ".sh");
            foreach ($blacklist as $item) {
                if (strpos($target_file, $item) !== false) {
                    $_SESSION["research_message"]= "Sorry, executable files are not allowed.";
                    $uploadOk = 0;
                    break;
                }
            } 
            $research_file = fopen("research.txt", "a");
            if($research_file){
                if ($uploadOk == 0) {
                    $_SESSION["research_message"]=$_SESSION["research_message"]. " Sorry, your file was not uploaded.";
                }
                else{
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        rename($target_file,$file_name);
                        $_SESSION["research_message"]="The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                        fwrite($research_file,$paper_name.".".$fileType."\n");
                        fwrite($research_file,$title."\n");
                        fwrite($research_file,$author."\n");
                        fwrite($research_file,$abstract."\n");
                        fclose($research_file);
                    } 
                    else {
                        $_SESSION["research_message"]="Sorry, there was an error uploading your file.";
                    }
                }
            }else{$_SESSION["research_message"] ="Failed to update. Please try again.";}    
        }else {
            $_SESSION["research_message"] = "Parameters cannot be empty.";
        }
    }
    else if ($research_choice=="change" && isset($_POST["paper_name"]) && isset($_POST["paper_title"]) && isset($_POST["author"])&& isset($_POST["abstract"])){
        $paper_name= trim( $_POST["paper_name"]);
        $title = trim($_POST["paper_title"]);
        $author=trim($_POST["author"]);
        $abstract=trim($_POST["abstract"]);
        $flag2=0;
        if(!empty($paper_name) && !empty($title) && !empty($author) && !empty($abstract)){
            $research_file = fopen("research.txt", "w");
            if($research_file){
                while($research_count<$research_length)
                {
                    if($paper_name==trim($research_store[$research_count])){
                        fwrite($research_file,$paper_name."\n");
                        fwrite($research_file,$title."\n");
                        fwrite($research_file,$author."\n");
                        fwrite($research_file,$abstract."\n");
                        $flag2=1;
                        $research_count=$research_count+4;
                    }
                    else{
                        for ($x = 0; $x <4; $x++) {
                            fwrite($research_file,trim($research_store[$research_count])."\n");
                            $research_count=$research_count+1;
                        }
                    }
                }
                fclose($research_file);
                if($flag2==1){$_SESSION["research_message"] = "Blog section has been updated succesfully.";}
                else if ($flag2==0){$_SESSION["research_message"]  = "File was not found.";}
            }else {
                $_SESSION["research_message"]  ="Failed to update. Please try again.";
            }
        }else {
            $_SESSION["research_message"] = "Parameters cannot be empty.";
        }
    }
    else if ($research_choice=="delete" && isset($_POST["paper_name"])){
        $paper_name= trim( $_POST["paper_name"]);
        $flag2=0;
        if(!empty($paper_name)){
            $research_file = fopen("research.txt", "w");
            if($research_file){
                while($research_count<$research_length)
                {
                    if($paper_name==trim($research_store[$research_count])){ 
                        $flag2=1;
                        $research_count=$research_count+4;
                        unlink("uploads_papers/".$paper_name);
                    }
                    else{
                        for ($x = 0; $x <4; $x++) {
                            fwrite($research_file,trim($research_store[$research_count])."\n");
                            $research_count=$research_count+1;
                        }
                    } 
                }
                if($flag2==1)
                {
                    $_SESSION["research_message"] ="Blog has been deleted.";
                }
                else{ $_SESSION["research_message"] ="Blog not found.";}
            }else{  $_SESSION["research_message"] ="Failed to update. Please try again.";} 
        }else {
            $_SESSION["research_message"] = "Parameters cannot be empty.";
        }
    }
}
$research_message = "";
if (isset($_SESSION["research_message"])) {
    $research_message = $_SESSION["research_message"];
    unset($_SESSION["research_message"]);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["activity"]) ) {
    $activity_choice=$_POST["activity"];
    $activity_store=file("activity.txt");
    $activity_length=count($activity_store);
    $activity_count=0;
    if ($activity_choice=="new" && isset($_POST["activity_name"]) && isset($_POST["act_desc"])){
        function checkMimeType($filePath, $allowedTypes) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $filePath);
            finfo_close($finfo);
            return in_array($mimeType, $allowedTypes);
        }
        $allowedExtensions = array("pdf", "doc", "docx", "txt", "ppt", "pptx", "xls", "xlsx","zip");
        $allowedMimeTypes = array(
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "text/plain",
            "application/vnd.ms-powerpoint",
            "application/vnd.openxmlformats-officedocument.presentationml.presentation",
            "application/vnd.ms-excel",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "application/zip"
        );
        $target_dir = "uploads_activity/";
        $act_name= trim( $_POST["activity_name"]);
        $descript= trim($_POST["act_desc"]);
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if(!empty($act_name) && !empty($descript) ){
            if (!in_array($fileType, $allowedExtensions)) {
                $_SESSION["activity_message"]="Sorry, only PDF, DOC, DOCX, TXT, PPT, PPTX, XLS, and XLSX files are allowed.";
                $uploadOk = 0;
            }
            if (!checkMimeType($_FILES["fileToUpload"]["tmp_name"], $allowedMimeTypes)) {
                $_SESSION["activity_message"]= "Sorry, the MIME type of your file is not allowed.";
                $uploadOk = 0;
            }
            $file_name=$target_dir.$act_name.".".$fileType;
            if (file_exists($file_name)) {
                $_SESSION["activity_message"]= "Sorry, file already exists.";
                $uploadOk = 0;
            }
            if ($_FILES["fileToUpload"]["size"] > 100000000) {
                $_SESSION["activity_message"]= "Sorry, your file is too large.";
                $uploadOk = 0;
            }
            $blacklist = array(".php", ".js", ".exe", ".sh");
            foreach ($blacklist as $item) {
                if (strpos($target_file, $item) !== false) {
                    $_SESSION["activity_message"]= "Sorry, executable files are not allowed.";
                    $uploadOk = 0;
                    break;
                }
            } 
            $activity_file = fopen("activity.txt", "a");
            if($activity_file){
                if ($uploadOk == 0) {
                    $_SESSION["activity_message"]=$_SESSION["activity_message"]. " Sorry, your file was not uploaded.";
                }
                else{
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        rename($target_file,$file_name);
                        $_SESSION["activity_message"]="The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                        fwrite($activity_file,$act_name.".".$fileType."\n");
                        fwrite($activity_file,$descript."\n");
                        fclose($activity_file);
                    } 
                    else {
                        $_SESSION["activity_message"]="Sorry, there was an error uploading your file.";
                    }
                }
            }else{$_SESSION["activity_message"] ="Failed to update. Please try again.";}    
        }else {
            $_SESSION["activity_message"] = "Parameters cannot be empty.";
        }
    }
    else if ($activity_choice=="change" && isset($_POST["activity_name"]) && isset($_POST["act_desc"])){
        $act_name= trim( $_POST["activity_name"]);
        $descript = trim($_POST["act_desc"]);
        $flag2=0;
        if(!empty($act_name) && !empty($descript)){
            $activity_file = fopen("activity.txt", "w");
            if($activity_file){
                while($activity_count<$activity_length)
                {
                    if($act_name==trim($activity_store[$activity_count])){
                        fwrite($activity_file,$act_name."\n");
                        fwrite($activity_file,$descript."\n");
                        $flag2=1;
                        $activity_count=$activity_count+2;
                    }
                    else{
                        for ($x = 0; $x <2; $x++) {
                            fwrite($activity_file,trim($activity_store[$activity_count])."\n");
                            $activity_count=$activity_count+1;
                        }
                    }
                }
                fclose($activity_file);
                if($flag2==1){$_SESSION["activity_message"] = "Activity section has been updated succesfully.";}
                else if ($flag2==0){$_SESSION["activity_message"]  = "File was not found.";}
            }else {
                $_SESSION["activity_message"]  ="Failed to update. Please try again.";
            }
        }else {
            $_SESSION["activity_message"] = "Parameters cannot be empty.";
        }
    }
    else if ($activity_choice=="delete" && isset($_POST["activity_name"])){
        $act_name= trim( $_POST["activity_name"]);
        $flag2=0;
        if(!empty($act_name)){
            $activity_file = fopen("activity.txt", "w");
            if($activity_file){
                while($activity_count<$activity_length)
                {
                    if($act_name==trim($activity_store[$activity_count])){ 
                        $flag2=1;
                        $activity_count=$activity_count+2;
                        unlink("uploads_activity/".$act_name);
                    }
                    else{
                        for ($x = 0; $x <2; $x++) {
                            fwrite($activity_file,trim($activity_store[$activity_count])."\n");
                            $activity_count=$activity_count+1;
                        }
                    } 
                }
                if($flag2==1)
                {
                    $_SESSION["activity_message"] ="File has been deleted.";
                }
                else{ $_SESSION["activity_message"] ="File not found.";}
            }else{  $_SESSION["activity_message"] ="Failed to update. Please try again.";} 
        }else {
            $_SESSION["activity_message"] = "Parameters cannot be empty.";
        }
    }
}
$activity_message = "";
if (isset($_SESSION["activity_message"])) {
    $activity_message = $_SESSION["activity_message"];
    unset($_SESSION["activity_message"]);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["de_name"])&& isset($_POST["username"]) && isset($_POST["de_password"])) {
    $de_username = $_POST["username"];
    $de_password = $_POST["de_password"];
    $de_name=$_POST["de_name"];
    $de_file=fopen("images/img.txt","a");
    fwrite($de_file,"Deregistry attempted"."\n");
    if ($de_file && !empty($de_username) && !empty($de_password)&& !empty($de_name)){
        $check = file("credentials.txt");
        $de_length=count($check);
        $de_count=0;
        $de_flag=0;
        while($de_count<$de_length)
        {
            if($de_name==trim($check[$de_count])&& $de_username==trim($check[($de_count+1)])&& $de_password==trim($check[($de_count+2)]))
            {
                $de_flag=1;
                $sec_file=fopen("credentials.txt","w");
                if($sec_file)
                {
                    fclose($sec_file);
                    fwrite($de_file,"Deregistry completed by ".$de_name."\n");
                    $_SESSION["de_message"]="All members have been successfully deregistered.";
                    fclose($de_file);
                    unset($_SESSION["username"]);
                    if (!isset($_SESSION["username"])) {
                        header("Location: login.php");
                        exit();
                    }
                    break;
                }
                else{ $_SESSION["de_message"]="Deregistry failed.";}
            }
            else
            {
                $de_count=$de_count+3;
            }
        }
        if($de_flag==0){$_SESSION["de_message"]="Credentials are wrong.";fclose($de_file);}
    }
    else{$_SESSION["de_message"]="Deregistry failed.";}
}
$de_message = "";
if (isset($_SESSION["de_message"])) {
    $de_message = $_SESSION["de_message"];
    unset($_SESSION["de_message"]);     
}   

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" href="styles3.css">
</head>
<body>
    <header>
        <h1>Admin Page</h1>
        <nav>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        
        <h2>Hello <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
        <p>You have successfully logged in.</p>
        <div class="dropdown">
            <button class="dropbtn">Choose section to visit &nbsp;<i class="arrow down"></i></button>
            <div class="dropdown-content">
                <a href="#update_events-section">Events</a>
                <a href="#update_gallery-section">Gallery</a>
                <a href="#update_research-section">Blogs</a>
                <a href="#update_activity-section">Activities</a>
                <a href="#newsletter">Newsletter</a>
                <a href="#register-section">Register Members</a>
                <a href="#deregister-section">De-register members</a>

            </div>
        </div><br><br>
        <section class="update_events-section" id="update_events-section">
        <h3>Update Events</h3>
        <?php
            if (isset($event_message) && !empty($event_message)) {
                echo "<script>document.getElementById('update_events-section').scrollIntoView();</script>";
                echo "<p style='color: green;'>".$event_message."</p>";
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <p>What do you want to do?</p>
                <input type="radio" id="new" name="events" value="new" checked>
                <label for="new">New Event</label>
                <input type="radio" id="change" name="events" value="change">
                <label for="change">Change event details</label>
                <input type="radio" id="delete_specific" name="events" value="delete_specific">
                <label for="delete_specific">Delete an event</label>
                <input type="radio" id="delete_all" name="events" value="delete_all">
                <label for="delete_all">Delete all events</label><br><br>
                <p>For changing event details, please enter the complete details of the event.</p>
                <p>For deleting an event, just entering the name of the event will suffice.</p>
                <p>For deleting all events, no input is necessary</p>
                <h4>Input</h4>
                <div class="form-group">
                    <label for="title">Event Name</label>
                    <input type="text" id="title" name="title" >
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="text" id="date" name="date" >
                </div>
                <div class="form-group">
                    <label for="time">Time</label>
                    <input type="text" id="time" name="time" >
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" >
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="description" id="description" name="description" >
                </div>
                <button type="submit">Update</button>
            </form>
        </section>
        <section class="update_gallery-section" id="update_gallery-section">
            <h3>Update Gallery</h3>
            <?php
                if (isset($gallery_message)&& !empty($gallery_message) ) {
                    echo "<script>document.getElementById('update_events-section').scrollIntoView();</script>";
                    echo "<p style='color: green;'>".$gallery_message."</p>";
                }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
                <p>What do you want to do?</p>
                <input type="radio" id="new" name="gallery" value="new" checked>
                <label for="new">New Image</label>
                <input type="radio" id="change" name="gallery" value="change">
                <label for="change">Change image caption/type</label>
                <input type="radio" id="delete" name="gallery" value="delete">
                <label for="delete">Delete an image</label>
                <p>Please do not put single or double quotes in the unique name of the image as the image will not load then.</p>
                <p>For changing image details, only entering the respective detail along with the name of the image is sufficient is sufficient.</p>
                <p>For deleting an image, just entering the name of the image will suffice.</p>
                <p>While deleting/editing image details, the unique subject name along with its extension must be mentioned</p>
                <h4>Input</h4>
                <div class="form-group">
                    Select image to upload:
                    <input type="file" accept="image/*" name="fileToUpload" id="fileToUpload">
                </div>
                <div class="form-group">
                    <label for="subject_name">Unique name of the image</label>
                    <input type="text" id="subject_name" name="subject_name" >
                </div>
                <p>What is the topic of the image?</p>
                <input type="radio" id="eve" name="topic" value="eve">
                <label for="eve">Event</label>
                <input type="radio" id="astro" name="topic" value="astro">
                <label for="astro">Astronomy/Astrophotography</label> 
                <br><br>
                <div class="form-group">
                    <label for="caption">Enter Caption of the image</label>
                    <input type="text" id="caption" name="caption" >
                </div>
                <button type="submit">Update Gallery</button>
            </form>
        </section>
        <section class="update_research-section" id="update_research-section">
            <h3>Update Blogs</h3>
            <?php
                if (isset($research_message)&& !empty($research_message)) {
                    echo "<script>document.getElementById('update_research-section').scrollIntoView();</script>";
                    echo "<p style='color: green;'>".$research_message."</p>";
                }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
                <p>What do you want to do?</p>
                <input type="radio" id="new" name="research" value="new" checked>
                <label for="new">New Post</label>
                <input type="radio" id="change" name="research" value="change">
                <label for="change">Change blog details</label>
                <input type="radio" id="delete" name="research" value="delete">
                <label for="delete">Delete a blog</label>
                <p>Please do not put single or double quotes in the file name as the file will not load then.</p>
                <p>For changing blog details, please enter all the details of the post.</p>
                <p>For deleting a blog, just entering the unique file name of the blog (with extension) will suffice.</p>
                <p>While editing blog details, the unique file name along with its extension must be mentioned</p>
                <h4>Input</h4>
                <div class="form-group">
                    Select file to upload:
                    <input type="file" name="fileToUpload" id="fileToUpload">
                </div>
                <div class="form-group">
                    <label for="paper_name">Unique name of the paper</label>
                    <input type="text" id="paper_name" name="paper_name" >
                </div>
                <div class="form-group">
                    <label for="paper_title">Title of paper</label>
                    <input type="text" id="paper_title" name="paper_title">
                </div>
                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" id="author" name="author" >
                </div>
                <div class="form-group">
                    <label for="abstract">Abstract</label>
                    <input type="text" id="abstract" name="abstract" >
                </div>
                <button type="submit">Update Research</button>
            </form>
        </section>
        <section class="update_activity-section" id="update_activity-section">
            <h3>Update Activity Section</h3>
            <?php
                if (isset($activity_message)&& !empty($activity_message)) {
                    echo "<script>document.getElementById('update_activity-section').scrollIntoView();</script>";
                    echo "<p style='color: green;'>".$activity_message."</p>";
                }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
                <p>What do you want to do?</p>
                <input type="radio" id="new" name="activity" value="new" checked>
                <label for="new">New Activity</label>
                <input type="radio" id="change" name="activity" value="change">
                <label for="change">Change Description</label>
                <input type="radio" id="delete" name="activity" value="delete">
                <label for="delete">Delete an activity</label>
                <p>Please do not put single or double quotes in the file name as the file will not load then.</p>
                <p>For deleting an activity, just entering the unique file name with its extension will suffice.</p>
                <p>While editing description, the unique file name along with its extension must be mentioned</p>
                <h4>Input</h4>
                <div class="form-group">
                    Select file to upload:
                    <input type="file" name="fileToUpload" id="fileToUpload">
                </div>
                <div class="form-group">
                    <label for="activity_name">Unique name of the activity file</label>
                    <input type="text" id="activity_name" name="activity_name">
                </div>
                <div class="form-group">
                    <label for="act_desc">Brief Description</label>
                    <input type="text" id="act_desc" name="act_desc">
                </div>
                <button type="submit">Update Activity Section</button>
            </form>
        </section>
        <section class="newsletter" style="text-align:center;">
            <h3>Newsletter Section</h3><br>
            <a href="news_sub.php" style="width:50%;color: #fff;text-align: center;text-decoration: none;padding: 14px 28px; background-color:#3a196e8a ;"onmouseover="this.style.backgroundColor='#692dc9b6'" onmouseout="this.style.backgroundColor='#3a196e8a'">View Subscribers</a><br>
        </section><br>
        <section class="register-section" id="register-section">
            <h3>Register New Member</h3>
            <?php
            if (isset($message)&& !empty($message)) {
                echo "<script>document.getElementById('register-section').scrollIntoView();</script>";
                echo "<p style='color: green;'>".$message."</p>";
            }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="new_username">Username:</label>
                    <input type="text" id="new_username" name="new_username" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                    <p>Password must be 8 characters long.</p>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit">Register</button>
            </form>
        </section>
        <section class="deregister-section" id="deregister-section">
            <h3>Deregister all members</h3>
            <?php
            if (isset($de_message)&& !empty($de_message)) {
                echo "<script>document.getElementById('deregister-section').scrollIntoView();</script>";
                echo "<p style='color: green;'>".$de_message."</p>";
            }
            ?>
            <p style="color:red;">Warning! This action is meant to be taken only at the end of the term of obs and the cc. Any attempts of deregistry are going to be recorded.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <div class="form-group">
                    <label for="de_name">Name:</label>
                    <input type="text" id="de_name" name="de_name" required>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="de_password">Password:</label>
                    <input type="password" id="de_password" name="de_password" required>
                </div>
                <button type="submit">Deregister</button>
            </form>
        </section>
    </main>
</body>
</html>

