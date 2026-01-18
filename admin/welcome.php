<?php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Pragma: no-cache");

// Member Registration Section
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
    unset($_SESSION["message"]);
}

// Events Section
// ==================== EVENTS SECTION - WITH HERO IMAGE ====================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["events"]) ) {
    $event_choice = $_POST["events"];
    include "../location_prefixes.php";

    // Define paths
    $event_data_json = "../".$listing_pages_location_prefix."events.json";
    $event_page_base_dir = "../".$listing_pages_location_prefix."events/";
    
    // Create base directory if it doesn't exist
    if (!file_exists($event_page_base_dir)) {
        mkdir($event_page_base_dir, 0755, true);
    }
    
    // Load existing event-data.json
    $eventDataArray = [];
    if (file_exists($event_data_json)) {
        $content = file_get_contents($event_data_json);
        $eventDataArray = json_decode($content, true);
        if ($eventDataArray === null) {
            $eventDataArray = [];
        }
    }
    
    // ========== NEW EVENT ==========
    if ($event_choice == "new" && isset($_POST["event_id"]) && isset($_POST["title"]) && 
        isset($_POST["short_description"]) && isset($_POST["date"]) && 
        isset($_POST["time"]) && isset($_POST["location"])) {
        
        $event_id = trim($_POST["event_id"]);
        $title = trim($_POST["title"]);
        $short_description = trim($_POST["short_description"]);
        $date = trim($_POST["date"]);
        $time = trim($_POST["time"]);
        $location = trim($_POST["location"]);
        $what_happened = isset($_POST["what_happened"]) ? trim($_POST["what_happened"]) : "";
        $author_bio = isset($_POST["author_bio"]) ? trim($_POST["author_bio"]) : "";
        
        // Check if event_id already exists
        $exists = false;
        foreach ($eventDataArray as $event) {
            if (isset($event['link']) && strpos($event['link'], $event_id) !== false) {
                $exists = true;
                break;
            }
        }
        
        if ($exists) {
            $_SESSION["event_message"] = "Event ID already exists. Please use a unique ID.";
        } else {
            // Create event-specific folder: events/{event_id}/
            $event_folder = $event_page_base_dir . $event_id . "/";
            if (!file_exists($event_folder)) {
                mkdir($event_folder, 0755, true);
            }
            
            // Handle tags
            $tags = [];
            if (isset($_POST["tags"]) && !empty($_POST["tags"])) {
                $tags_input = trim($_POST["tags"]);
                $tags = array_map('trim', explode(',', $tags_input));
            }
            
            // Handle card/preview image - stored in event folder
            $card_image = "";
            if (!empty($_POST["card_image_url"])) {
                $card_image = trim($_POST["card_image_url"]);
            } elseif (!empty($_FILES["card_image"]["name"])) {
                $imageFileType = strtolower(pathinfo($_FILES["card_image"]["name"], PATHINFO_EXTENSION));
                $card_image_name = "card." . $imageFileType;
                $target_file = $event_folder . $card_image_name;
                
                if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif", "webp"])) {
                    if (move_uploaded_file($_FILES["card_image"]["tmp_name"], $target_file)) {
                        $card_image = $listing_pages_location_prefix."events/" . $event_id . "/" . $card_image_name;
                    }
                }
            }
            
            // Handle poster image (hero image for event page) - stored in event folder
            $poster_image = "";
            if (!empty($_POST["poster_image_url"])) {
                $poster_image = trim($_POST["poster_image_url"]);
            } elseif (!empty($_FILES["poster_image"]["name"])) {
                $imageFileType = strtolower(pathinfo($_FILES["poster_image"]["name"], PATHINFO_EXTENSION));
                $poster_image_name = "poster." . $imageFileType;
                $target_file = $event_folder . $poster_image_name;
                
                if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif", "webp"])) {
                    if (move_uploaded_file($_FILES["poster_image"]["tmp_name"], $target_file)) {
                        $poster_image = $listing_pages_location_prefix."events/" . $event_id . "/" . $poster_image_name;
                    }
                }
            }
            
            // Handle author image - stored in event folder
            $author_img = "";
            if (!empty($_POST["author_image_url"])) {
                $author_img = trim($_POST["author_image_url"]);
            } elseif (!empty($_FILES["author_image"]["name"])) {
                $imageFileType = strtolower(pathinfo($_FILES["author_image"]["name"], PATHINFO_EXTENSION));
                $author_image_name = "author." . $imageFileType;
                $target_file = $event_folder . $author_image_name;
                
                if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif", "webp"])) {
                    if (move_uploaded_file($_FILES["author_image"]["tmp_name"], $target_file)) {
                        $author_img = $listing_pages_location_prefix."events/" . $event_id . "/" . $author_image_name;
                    }
                }
            }
            
            // Handle gallery photos
            $gallery_photos = [];
            if (!empty($_FILES["gallery_images"]["name"][0])) {
                for ($i = 0; $i < count($_FILES["gallery_images"]["name"]); $i++) {
                    if (!empty($_FILES["gallery_images"]["name"][$i])) {
                        $imageFileType = strtolower(pathinfo($_FILES["gallery_images"]["name"][$i], PATHINFO_EXTENSION));
                        $gallery_image_name = "gallery_" . $i . "." . $imageFileType;
                        $target_file = $event_folder . $gallery_image_name;
                        
                        if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif", "webp"])) {
                            if (move_uploaded_file($_FILES["gallery_images"]["tmp_name"][$i], $target_file)) {
                                $gallery_photos[] = $listing_pages_location_prefix."events/" . $event_id . "/" . $gallery_image_name;
                            }
                        }
                    }
                }
            }
            
            // Handle resources
            $resources = [];
            if (isset($_POST["resource_name"]) && is_array($_POST["resource_name"])) {
                for ($i = 0; $i < count($_POST["resource_name"]); $i++) {
                    if (!empty($_POST["resource_name"][$i]) && !empty($_POST["resource_link"][$i])) {
                        $resources[] = [
                            "name" => trim($_POST["resource_name"][$i]),
                            "link" => trim($_POST["resource_link"][$i])
                        ];
                    }
                }
            }
            
            // Create entry for events.json (preview card) - NOW WITH HERO IMAGE
            $eventDataEntry = [
                "title" => $title,
                "description" => $short_description,
                "image" => $card_image,
                "heroImage" => $poster_image, // Add hero image to listing
                "date" => $date,
                "time" => $time,
                "location" => $location,
                "link" => "event.php?id=" . $event_id,
                "tags" => $tags
            ];
            
            // Add to events.json array
            $eventDataArray[] = $eventDataEntry;
            file_put_contents($event_data_json, json_encode($eventDataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            // Create individual event page JSON
            $eventPageData = [
                "title" => $title,
                "date" => $date,
                "time" => $time,
                "location" => $location,
                "details" => $short_description,
                "poster" => $poster_image,
                "whatHappened" => $what_happened,
                "author" => [
                    "bio" => $author_bio,
                    "image" => $author_img
                ],
                "resources" => $resources,
                "gallery" => $gallery_photos
            ];
            
            // Save individual event page JSON in the event folder
            $event_page_json = $event_folder . "event-page-" . $event_id . ".json";
            file_put_contents($event_page_json, json_encode($eventPageData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            $_SESSION["event_message"] = "New event created successfully! Event ID: " . $event_id . " | Folder: events/" . $event_id . "/";
        }
    }
    
    // ========== EDIT EVENT ==========
    elseif ($event_choice == "change" && isset($_POST["event_id"])) {
        $event_id = trim($_POST["event_id"]);
        $found = false;
        $event_index = -1;
        
        // Find the event in events.json
        foreach ($eventDataArray as $key => $event) {
            if (isset($event['link']) && strpos($event['link'], $event_id) !== false) {
                $found = true;
                $event_index = $key;
                break;
            }
        }
        
        if ($found) {
            $event_folder = $event_page_base_dir . $event_id . "/";
            
            // Update events.json entry
            if (isset($_POST["title"]) && !empty($_POST["title"])) {
                $eventDataArray[$event_index]['title'] = trim($_POST["title"]);
            }
            if (isset($_POST["short_description"]) && !empty($_POST["short_description"])) {
                $eventDataArray[$event_index]['description'] = trim($_POST["short_description"]);
            }
            if (isset($_POST["date"]) && !empty($_POST["date"])) {
                $eventDataArray[$event_index]['date'] = trim($_POST["date"]);
            }
            if (isset($_POST["time"]) && !empty($_POST["time"])) {
                $eventDataArray[$event_index]['time'] = trim($_POST["time"]);
            }
            if (isset($_POST["location"]) && !empty($_POST["location"])) {
                $eventDataArray[$event_index]['location'] = trim($_POST["location"]);
            }
            
            // Update tags
            if (isset($_POST["tags"])) {
                $tags_input = trim($_POST["tags"]);
                $eventDataArray[$event_index]['tags'] = array_map('trim', explode(',', $tags_input));
            }
            
            // Update card image
            if (!empty($_POST["card_image_url"])) {
                $eventDataArray[$event_index]['image'] = trim($_POST["card_image_url"]);
            } elseif (!empty($_FILES["card_image"]["name"])) {
                $imageFileType = strtolower(pathinfo($_FILES["card_image"]["name"], PATHINFO_EXTENSION));
                $card_image_name = "card." . $imageFileType;
                $target_file = $event_folder . $card_image_name;
                
                if (move_uploaded_file($_FILES["card_image"]["tmp_name"], $target_file)) {
                    $eventDataArray[$event_index]['image'] = $listing_pages_location_prefix."events/" . $event_id . "/" . $card_image_name;
                }
            }
            
            // Update hero/poster image in events.json
            if (!empty($_POST["poster_image_url"])) {
                $eventDataArray[$event_index]['heroImage'] = trim($_POST["poster_image_url"]);
            } elseif (!empty($_FILES["poster_image"]["name"])) {
                $imageFileType = strtolower(pathinfo($_FILES["poster_image"]["name"], PATHINFO_EXTENSION));
                $poster_image_name = "poster." . $imageFileType;
                $target_file = $event_folder . $poster_image_name;
                
                if (move_uploaded_file($_FILES["poster_image"]["tmp_name"], $target_file)) {
                    $eventDataArray[$event_index]['heroImage'] = $listing_pages_location_prefix."events/" . $event_id . "/" . $poster_image_name;
                }
            }
            
            // Save updated events.json
            file_put_contents($event_data_json, json_encode($eventDataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            // Update individual event page JSON
            $event_page_json = $event_folder . "event-page-" . $event_id . ".json";
            
            if (file_exists($event_page_json)) {
                $eventPageData = json_decode(file_get_contents($event_page_json), true);
                
                if (isset($_POST["title"]) && !empty($_POST["title"])) {
                    $eventPageData['title'] = trim($_POST["title"]);
                }
                if (isset($_POST["short_description"]) && !empty($_POST["short_description"])) {
                    $eventPageData['details'] = trim($_POST["short_description"]);
                }
                if (isset($_POST["date"]) && !empty($_POST["date"])) {
                    $eventPageData['date'] = trim($_POST["date"]);
                }
                if (isset($_POST["time"]) && !empty($_POST["time"])) {
                    $eventPageData['time'] = trim($_POST["time"]);
                }
                if (isset($_POST["location"]) && !empty($_POST["location"])) {
                    $eventPageData['location'] = trim($_POST["location"]);
                }
                if (isset($_POST["what_happened"]) && !empty($_POST["what_happened"])) {
                    $eventPageData['whatHappened'] = trim($_POST["what_happened"]);
                }
                if (isset($_POST["author_bio"]) && !empty($_POST["author_bio"])) {
                    $eventPageData['author']['bio'] = trim($_POST["author_bio"]);
                }
                
                // Update poster image
                if (!empty($_POST["poster_image_url"])) {
                    $eventPageData['poster'] = trim($_POST["poster_image_url"]);
                } elseif (!empty($_FILES["poster_image"]["name"])) {
                    $imageFileType = strtolower(pathinfo($_FILES["poster_image"]["name"], PATHINFO_EXTENSION));
                    $poster_image_name = "poster." . $imageFileType;
                    $target_file = $event_folder . $poster_image_name;
                    
                    if (move_uploaded_file($_FILES["poster_image"]["tmp_name"], $target_file)) {
                        $eventPageData['poster'] = $listing_pages_location_prefix."events/" . $event_id . "/" . $poster_image_name;
                    }
                }
                
                // Update author image
                if (!empty($_POST["author_image_url"])) {
                    $eventPageData['author']['image'] = trim($_POST["author_image_url"]);
                } elseif (!empty($_FILES["author_image"]["name"])) {
                    $imageFileType = strtolower(pathinfo($_FILES["author_image"]["name"], PATHINFO_EXTENSION));
                    $author_image_name = "author." . $imageFileType;
                    $target_file = $event_folder . $author_image_name;
                    
                    if (move_uploaded_file($_FILES["author_image"]["tmp_name"], $target_file)) {
                        $eventPageData['author']['image'] = $listing_pages_location_prefix."events/" . $event_id . "/" . $author_image_name;
                    }
                }
                
                // Save updated event page JSON
                file_put_contents($event_page_json, json_encode($eventPageData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
            
            $_SESSION["event_message"] = "Event updated successfully!";
        } else {
            $_SESSION["event_message"] = "Event ID not found.";
        }
    }
    
    // ========== DELETE EVENT ==========
    elseif ($event_choice == "delete_specific" && isset($_POST["event_id"])) {
        $event_id = trim($_POST["event_id"]);
        $found = false;
        $event_index = -1;
        
        foreach ($eventDataArray as $key => $event) {
            if (isset($event['link']) && strpos($event['link'], $event_id) !== false) {
                $found = true;
                $event_index = $key;
                break;
            }
        }
        
        if ($found) {
            // Delete entire event folder with all images and JSON
            $event_folder = $event_page_base_dir . $event_id . "/";
            if (file_exists($event_folder)) {
                // Delete all files in the folder
                $files = glob($event_folder . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                // Remove the folder
                rmdir($event_folder);
            }
            
            // Remove from event-data array
            unset($eventDataArray[$event_index]);
            $eventDataArray = array_values($eventDataArray);
            
            // Save updated events.json
            file_put_contents($event_data_json, json_encode($eventDataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            $_SESSION["event_message"] = "Event and all associated files deleted successfully!";
        } else {
            $_SESSION["event_message"] = "Event ID not found.";
        }
    }
    
    // ========== DELETE ALL EVENTS ==========
    elseif ($event_choice == "delete_all") {
        // Delete all event folders
        if (file_exists($event_page_base_dir)) {
            $folders = glob($event_page_base_dir . '*', GLOB_ONLYDIR);
            foreach ($folders as $folder) {
                $files = glob($folder . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                rmdir($folder);
            }
        }
        
        // Clear events.json
        file_put_contents($event_data_json, json_encode([], JSON_PRETTY_PRINT));
        
        $_SESSION["event_message"] = "All events have been deleted successfully!";
    }
}

$event_message = "";
if (isset($_SESSION["event_message"])) {
    $event_message = $_SESSION["event_message"];
    unset($_SESSION["event_message"]);
}
$event_message = "";
if (isset($_SESSION["event_message"])) {
    $event_message = $_SESSION["event_message"];
    unset($_SESSION["event_message"]);
}

// Gallery Section
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
    unset($_SESSION["gallery_message"]);
}

// ==================== BLOG SECTION - REFACTORED FOR CENTRALIZED IMAGE STORAGE ====================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["blog"]) ) {
    $blog_choice = $_POST["blog"];
    include "../location_prefixes.php";

    // Define paths
    $blog_data_json = "../".$listing_pages_location_prefix."blogs.json";
    $blog_page_base_dir = "../".$listing_pages_location_prefix."blogs/";
    
    // Create base directory if it doesn't exist
    if (!file_exists($blog_page_base_dir)) {
        mkdir($blog_page_base_dir, 0755, true);
    }
    
    // Load existing blog-data.json
    $blogDataArray = [];
    if (file_exists($blog_data_json)) {
        $content = file_get_contents($blog_data_json);
        $blogDataArray = json_decode($content, true);
        if ($blogDataArray === null) {
            $blogDataArray = [];
        }
    }
    
    // ========== NEW BLOG POST ==========
    if ($blog_choice == "new" && isset($_POST["blog_id"]) && isset($_POST["title"]) && 
        isset($_POST["short_description"]) && isset($_POST["author_name"]) && 
        isset($_POST["author_role"]) && isset($_POST["date"])) {
        
        $blog_id = trim($_POST["blog_id"]);
        $title = trim($_POST["title"]);
        $short_description = trim($_POST["short_description"]);
        $author_name = trim($_POST["author_name"]);
        $author_role = trim($_POST["author_role"]);
        $date = trim($_POST["date"]);
        $institute = isset($_POST["institute"]) ? trim($_POST["institute"]) : "";
        $intro_desc = isset($_POST["intro_desc"]) ? trim($_POST["intro_desc"]) : "";
        $author_desc = isset($_POST["author_desc"]) ? trim($_POST["author_desc"]) : "A very Good author";
        
        // Check if blog_id already exists
        $exists = false;
        foreach ($blogDataArray as $blog) {
            if (isset($blog['link']) && strpos($blog['link'], $blog_id) !== false) {
                $exists = true;
                break;
            }
        }
        
        if ($exists) {
            $_SESSION["blog_message"] = "Blog ID already exists. Please use a unique ID.";
        } else {
            // Create blog-specific folder: data/{blog_id}/
            $blog_folder = $blog_page_base_dir . $blog_id . "/";
            echo $blog_id;
            if (!file_exists($blog_folder)) {
                mkdir($blog_folder, 0755, true);
            }
            
            // Handle tags
            $tags = [];
            if (isset($_POST["tags"]) && !empty($_POST["tags"])) {
                $tags_input = trim($_POST["tags"]);
                $tags = array_map('trim', explode(',', $tags_input));
            }
            
            // Handle card/preview image - stored in blog folder
            $card_image = "";
            if (!empty($_POST["card_image_url"])) {
                $card_image = trim($_POST["card_image_url"]);
            } elseif (!empty($_FILES["card_image"]["name"])) {
                $imageFileType = strtolower(pathinfo($_FILES["card_image"]["name"], PATHINFO_EXTENSION));
                $card_image_name = "card." . $imageFileType;
                $target_file = $blog_folder . $card_image_name;
                
                if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif", "webp"])) {
                    if (move_uploaded_file($_FILES["card_image"]["tmp_name"], $target_file)) {
                        // Path relative to blog-data.json location
                        $card_image = $listing_pages_location_prefix."blogs/" . $blog_id . "/" . $card_image_name;
                    }
                }
            }
            
            // Handle author image - stored in blog folder
            $author_img = "";
            if (!empty($_POST["author_image_url"])) {
                $author_img = trim($_POST["author_image_url"]);
            } elseif (!empty($_FILES["author_image"]["name"])) {
                $imageFileType = strtolower(pathinfo($_FILES["author_image"]["name"], PATHINFO_EXTENSION));
                $author_image_name = "author." . $imageFileType;
                $target_file = $blog_folder . $author_image_name;
                
                if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif", "webp"])) {
                    if (move_uploaded_file($_FILES["author_image"]["tmp_name"], $target_file)) {
                        // Path relative to blog-data.json location
                        $author_img = $listing_pages_location_prefix."blogs/" . $blog_id . "/" . $author_image_name;
                    }
                }
            }
            
            // Handle hero/banner image - stored in blog folder
            $hero_image = "";
            if (!empty($_POST["hero_image_url"])) {
                $hero_image = trim($_POST["hero_image_url"]);
            } elseif (!empty($_FILES["hero_image"]["name"])) {
                $imageFileType = strtolower(pathinfo($_FILES["hero_image"]["name"], PATHINFO_EXTENSION));
                $hero_image_name = "hero." . $imageFileType;
                $target_file = $blog_folder . $hero_image_name;
                
                if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif", "webp"])) {
                    if (move_uploaded_file($_FILES["hero_image"]["tmp_name"], $target_file)) {
                        // Path relative to index.php location (BlogPage/index.php)
                        // $hero_image = "data/" . $blog_id . "/" . $hero_image_name;
                        $hero_image = $listing_pages_location_prefix."blogs/".$blog_id."/".$hero_image_name;
                    }
                }
            }
            
            // Handle content sections - images stored in blog folder
            $contents = [];
            if (isset($_POST["content_heading"]) && is_array($_POST["content_heading"])) {
                for ($i = 0; $i < count($_POST["content_heading"]); $i++) {
                    $section_image = "";
                    
                    if (!empty($_POST["content_image_url"][$i])) {
                        $section_image = trim($_POST["content_image_url"][$i]);
                    } elseif (!empty($_FILES["content_image"]["name"][$i])) {
                        $imageFileType = strtolower(pathinfo($_FILES["content_image"]["name"][$i], PATHINFO_EXTENSION));
                        $section_image_name = "section_" . $i . "." . $imageFileType;
                        $target_file = $blog_folder . $section_image_name;
                        
                        if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif", "webp"])) {
                            if (move_uploaded_file($_FILES["content_image"]["tmp_name"][$i], $target_file)) {
                                // Path relative to index.php location
                                $section_image = $listing_pages_location_prefix."blogs/". $blog_id . "/" . $section_image_name;
                            }
                        }
                    }
                    
                    $contents[] = [
                        "heading" => trim($_POST["content_heading"][$i]),
                        "content" => trim($_POST["content_text"][$i]),
                        "image" => $section_image
                    ];
                }
            }
            
            // Create entry for blog-data.json (preview card)
            $blogDataEntry = [
                "title" => $title,
                "description" => $short_description,
                "image" => $card_image,
                "authorImg" => $author_img,
                "authorName" => $author_name,
                "authorRole" => $author_role,
                "date" => $date,
                "link" => "blog.php?id=" . $blog_id,
                "tags" => $tags
            ];
            
            // Add to blog-data.json array
            $blogDataArray[] = $blogDataEntry;
            file_put_contents($blog_data_json, json_encode($blogDataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            // Create individual blog page JSON
            $blogPageData = [
                "title" => $title,
                "institute" => $institute,
                "desc" => $intro_desc,
                "author" => [
                    "name" => $author_name,
                    "image" => !empty($author_img) ? $listing_pages_location_prefix."blogs/" . $blog_id . "/" . basename($author_img) : "",
                    "desc" => $author_desc
                ],
                "date" => $date,
                "image" => $hero_image,
                "contents" => $contents
            ];
            
            // Save individual blog page JSON in the blog folder
            $blog_page_json = $blog_folder . "blog-page-" . $blog_id . ".json";
            file_put_contents($blog_page_json, json_encode($blogPageData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            $_SESSION["blog_message"] = "New blog post created successfully! Blog ID: " . $blog_id . " | Folder: data/" . $blog_id . "/";
        }
    }
    
    // ========== EDIT BLOG POST ==========
    elseif ($blog_choice == "edit" && isset($_POST["blog_id"])) {
        $blog_id = trim($_POST["blog_id"]);
        $found = false;
        $blog_index = -1;
        
        // Find the blog in blog-data.json
        foreach ($blogDataArray as $key => $blog) {
            if (isset($blog['link']) && strpos($blog['link'], $blog_id) !== false) {
                $found = true;
                $blog_index = $key;
                break;
            }
        }
        
        if ($found) {
            $blog_folder = $blog_page_base_dir . $blog_id . "/";
            
            // Update blog-data.json entry
            if (isset($_POST["title"]) && !empty($_POST["title"])) {
                $blogDataArray[$blog_index]['title'] = trim($_POST["title"]);
            }
            if (isset($_POST["short_description"]) && !empty($_POST["short_description"])) {
                $blogDataArray[$blog_index]['description'] = trim($_POST["short_description"]);
            }
            if (isset($_POST["author_name"]) && !empty($_POST["author_name"])) {
                $blogDataArray[$blog_index]['authorName'] = trim($_POST["author_name"]);
            }
            if (isset($_POST["author_role"]) && !empty($_POST["author_role"])) {
                $blogDataArray[$blog_index]['authorRole'] = trim($_POST["author_role"]);
            }
            if (isset($_POST["date"]) && !empty($_POST["date"])) {
                $blogDataArray[$blog_index]['date'] = trim($_POST["date"]);
            }
            
            // Update tags
            if (isset($_POST["tags"])) {
                $tags_input = trim($_POST["tags"]);
                $blogDataArray[$blog_index]['tags'] = array_map('trim', explode(',', $tags_input));
            }
            
            // Update card image
            if (!empty($_POST["card_image_url"])) {
                $blogDataArray[$blog_index]['image'] = trim($_POST["card_image_url"]);
            } elseif (!empty($_FILES["card_image"]["name"])) {
                $imageFileType = strtolower(pathinfo($_FILES["card_image"]["name"], PATHINFO_EXTENSION));
                $card_image_name = "card." . $imageFileType;
                $target_file = $blog_folder . $card_image_name;
                
                if (move_uploaded_file($_FILES["card_image"]["tmp_name"], $target_file)) {
                    $blogDataArray[$blog_index]['image'] = "BlogPage/data/" . $blog_id . "/" . $card_image_name;
                }
            }
            
            // Update author image
            if (!empty($_POST["author_image_url"])) {
                $blogDataArray[$blog_index]['authorImg'] = trim($_POST["author_image_url"]);
            } elseif (!empty($_FILES["author_image"]["name"])) {
                $imageFileType = strtolower(pathinfo($_FILES["author_image"]["name"], PATHINFO_EXTENSION));
                $author_image_name = "author." . $imageFileType;
                $target_file = $blog_folder . $author_image_name;
                
                if (move_uploaded_file($_FILES["author_image"]["tmp_name"], $target_file)) {
                    $blogDataArray[$blog_index]['authorImg'] = "BlogPage/data/" . $blog_id . "/" . $author_image_name;
                }
            }
            
            // Save updated blog-data.json
            file_put_contents($blog_data_json, json_encode($blogDataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            // Update individual blog page JSON
            $blog_page_json = $blog_folder . "blog-page-" . $blog_id . ".json";
            
            if (file_exists($blog_page_json)) {
                $blogPageData = json_decode(file_get_contents($blog_page_json), true);
                
                if (isset($_POST["title"]) && !empty($_POST["title"])) {
                    $blogPageData['title'] = trim($_POST["title"]);
                }
                if (isset($_POST["institute"]) && !empty($_POST["institute"])) {
                    $blogPageData['institute'] = trim($_POST["institute"]);
                }
                if (isset($_POST["intro_desc"]) && !empty($_POST["intro_desc"])) {
                    $blogPageData['desc'] = trim($_POST["intro_desc"]);
                }
                if (isset($_POST["date"]) && !empty($_POST["date"])) {
                    $blogPageData['date'] = trim($_POST["date"]);
                }
                if (isset($_POST["author_name"]) && !empty($_POST["author_name"])) {
                    $blogPageData['author']['name'] = trim($_POST["author_name"]);
                }
                if (isset($_POST["author_desc"]) && !empty($_POST["author_desc"])) {
                    $blogPageData['author']['desc'] = trim($_POST["author_desc"]);
                }
                
                // Update hero image
                if (!empty($_POST["hero_image_url"])) {
                    $blogPageData['image'] = trim($_POST["hero_image_url"]);
                } elseif (!empty($_FILES["hero_image"]["name"])) {
                    $imageFileType = strtolower(pathinfo($_FILES["hero_image"]["name"], PATHINFO_EXTENSION));
                    $hero_image_name = "hero." . $imageFileType;
                    $target_file = $blog_folder . $hero_image_name;
                    
                    if (move_uploaded_file($_FILES["hero_image"]["tmp_name"], $target_file)) {
                        $blogPageData['image'] = "data/" . $blog_id . "/" . $hero_image_name;
                    }
                }
                
                // Update author image in blog page data
                if (!empty($_POST["author_image_url"])) {
                    $blogPageData['author']['image'] = trim($_POST["author_image_url"]);
                } elseif (!empty($_FILES["author_image"]["name"])) {
                    $imageFileType = strtolower(pathinfo($_FILES["author_image"]["name"], PATHINFO_EXTENSION));
                    $author_image_name = "author." . $imageFileType;
                    $blogPageData['author']['image'] = "data/" . $blog_id . "/" . $author_image_name;
                }
                
                // Save updated blog page JSON
                file_put_contents($blog_page_json, json_encode($blogPageData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
            
            $_SESSION["blog_message"] = "Blog post updated successfully!";
        } else {
            $_SESSION["blog_message"] = "Blog ID not found.";
        }
    }
    
    // ========== DELETE BLOG POST ==========
    elseif ($blog_choice == "delete" && isset($_POST["blog_id"])) {
        $blog_id = trim($_POST["blog_id"]);
        $found = false;
        $blog_index = -1;
        
        foreach ($blogDataArray as $key => $blog) {
            if (isset($blog['link']) && strpos($blog['link'], $blog_id) !== false) {
                $found = true;
                $blog_index = $key;
                break;
            }
        }
        
        if ($found) {
            // Delete entire blog folder with all images and JSON
            $blog_folder = $blog_page_base_dir . $blog_id . "/";
            if (file_exists($blog_folder)) {
                // Delete all files in the folder
                $files = glob($blog_folder . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                // Remove the folder
                rmdir($blog_folder);
            }
            
            // Remove from blog-data array
            unset($blogDataArray[$blog_index]);
            $blogDataArray = array_values($blogDataArray);
            
            // Save updated blog-data.json
            file_put_contents($blog_data_json, json_encode($blogDataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            $_SESSION["blog_message"] = "Blog post and all associated files deleted successfully!";
        } else {
            $_SESSION["blog_message"] = "Blog ID not found.";
        }
    }
}

$blog_message = "";
if (isset($_SESSION["blog_message"])) {
    $blog_message = $_SESSION["blog_message"];
    unset($_SESSION["blog_message"]);
}

// Activity Section
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
        $allowedExtensions = array("pdf", "doc", "docx", "txt", "ppt", "pptx", "xls", "xlsx","zip","json");
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
            "application/json"
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

// Deregister Section
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
                <a href="#update_blog-section">Blogs</a>
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
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        <p>What do you want to do?</p>
        <input type="radio" id="new_event" name="events" value="new" checked>
        <label for="new_event">New Event</label>
        <input type="radio" id="change_event" name="events" value="change">
        <label for="change_event">Edit Event</label>
        <input type="radio" id="delete_specific_event" name="events" value="delete_specific">
        <label for="delete_specific_event">Delete Event</label>
        <input type="radio" id="delete_all_events" name="events" value="delete_all">
        <label for="delete_all_events">Delete All Events</label>
        
        <br><br>
        <p><strong>Instructions:</strong></p>
        <ul style="text-align: left; margin-left: 20px;">
            <li>For <strong>New Event</strong>: Fill in all required fields</li>
            <li>For <strong>Edit Event</strong>: Enter Event ID and only the fields you want to update</li>
            <li>For <strong>Delete Event</strong>: Only Event ID is required</li>
            <li>For <strong>Delete All</strong>: No input required</li>
            <li>For images: You can either upload a file OR provide a URL (URL takes priority)</li>
            <li><strong>Tags:</strong> Enter comma-separated tags (e.g., "workshop, astronomy, public")</li>
            <li><strong>All images will be stored in:</strong> events/{event-id}/ folder</li>
        </ul>
        
        <hr style="margin: 20px 0;">
        
        <h4>Basic Information (for events.json)</h4>
        <div class="form-group">
            <label for="event_id">Event ID (Unique identifier, e.g., "event-1", "stargazing-2024") *</label>
            <input type="text" id="event_id" name="event_id" placeholder="e.g., event-1, workshop-2024">
            <small>This will be used as the folder name for storing all event files</small>
        </div>
        
        <div class="form-group">
            <label for="title">Event Title *</label>
            <input type="text" id="title" name="title" placeholder="Enter event title">
        </div>
        
        <div class="form-group">
            <label for="short_description">Short Description/Details (for event card preview) *</label>
            <textarea id="short_description" name="short_description" rows="3" placeholder="Brief description shown on event listing page"></textarea>
        </div>
        
        <div class="form-group">
            <label for="date">Date *</label>
            <input type="text" id="date" name="date" placeholder="e.g., November 15, 2025">
        </div>
        
        <div class="form-group">
            <label for="time">Time *</label>
            <input type="text" id="time" name="time" placeholder="e.g., 7:00 PM - 9:00 PM">
        </div>
        
        <div class="form-group">
            <label for="location">Location *</label>
            <input type="text" id="location" name="location" placeholder="e.g., Main Auditorium, Building A">
        </div>
        
        <div class="form-group">
            <label for="tags">Tags (comma-separated)</label>
            <input type="text" id="tags" name="tags" placeholder="e.g., workshop, astronomy, public, students">
            <small>Enter tags separated by commas</small>
        </div>
        
        <hr style="margin: 20px 0;">
        
        <h4>Card/Preview Image (shown on event listing page)</h4>
        <div class="form-group">
            <label for="card_image_url">Card Image URL (Optional)</label>
            <input type="text" id="card_image_url" name="card_image_url" placeholder="https://example.com/card-image.jpg">
        </div>
        
        <div class="form-group">
            <label for="card_image">OR Upload Card Image:</label>
            <input type="file" accept="image/*" name="card_image" id="card_image">
            <small>Will be saved as: events/{event-id}/card.{ext}</small>
        </div>
        
        <hr style="margin: 20px 0;">
        
        <h4>Event Page Content (for event-page-{id}.json)</h4>
        
        <h4>Event Poster (shown at top of event page)</h4>
        <div class="form-group">
            <label for="poster_image_url">Poster Image URL (Optional)</label>
            <input type="text" id="poster_image_url" name="poster_image_url" placeholder="https://example.com/poster.jpg">
        </div>
        
        <div class="form-group">
            <label for="poster_image">OR Upload Poster Image:</label>
            <input type="file" accept="image/*" name="poster_image" id="poster_image">
            <small>Will be saved as: events/{event-id}/poster.{ext}</small>
        </div>
        
        <div class="form-group">
            <label for="what_happened">What Happened in Event?</label>
            <textarea id="what_happened" name="what_happened" rows="6" placeholder="Describe what happened during the event"></textarea>
        </div>
        
        <hr style="margin: 20px 0;">
        
        <h4>Author/Organizer Information</h4>
        <div class="form-group">
            <label for="author_bio">Author/Organizer Bio</label>
            <textarea id="author_bio" name="author_bio" rows="3" placeholder="Enter author/organizer bio"></textarea>
        </div>
        
        <div class="form-group">
            <label for="author_image_url">Author Image URL (Optional)</label>
            <input type="text" id="author_image_url" name="author_image_url" placeholder="https://example.com/author.jpg">
        </div>
        
        <div class="form-group">
            <label for="author_image">OR Upload Author Image:</label>
            <input type="file" accept="image/*" name="author_image" id="author_image">
            <small>Will be saved as: events/{event-id}/author.{ext}</small>
        </div>
        
        <hr style="margin: 20px 0;">
        
        <h4>Resources Section</h4>
        <p><small>Add links to resources related to the event (PDFs, presentations, etc.)</small></p>
        
        <div id="resources-sections">
            <div class="resource-section" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px;">
                <h5>Resource 1</h5>
                <div class="form-group">
                    <label>Resource Name</label>
                    <input type="text" name="resource_name[]" placeholder="e.g., Event Presentation">
                </div>
                <div class="form-group">
                    <label>Resource Link</label>
                    <input type="text" name="resource_link[]" placeholder="https://example.com/resource.pdf">
                </div>
            </div>
        </div>
        
        <button type="button" onclick="addResourceSection()" style="background-color: #4CAF50; margin-bottom: 20px;">
            + Add Another Resource
        </button>
        
        <hr style="margin: 20px 0;">
        
        <h4>Gallery Photos</h4>
        <div class="form-group">
            <label for="gallery_images">Upload Multiple Gallery Photos:</label>
            <input type="file" accept="image/*" name="gallery_images[]" id="gallery_images" multiple>
            <small>Hold Ctrl/Cmd to select multiple images. Will be saved as: events/{event-id}/gallery_0.{ext}, gallery_1.{ext}, etc.</small>
        </div>
        
        <hr style="margin: 20px 0;">
        
        <button type="submit">Submit Event</button>
    </form>
</section>

<script>
    let resourceCount = 1;

    function addResourceSection() {
        resourceCount++;
        const resourcesContainer = document.getElementById('resources-sections');
        const newResource = document.createElement('div');
        newResource.className = 'resource-section';
        newResource.style.cssText = 'border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px;';
        newResource.innerHTML = `
            <h5>Resource ${resourceCount}</h5>
            <div class="form-group">
                <label>Resource Name</label>
                <input type="text" name="resource_name[]" placeholder="e.g., Event Presentation">
            </div>
            <div class="form-group">
                <label>Resource Link</label>
                <input type="text" name="resource_link[]" placeholder="https://example.com/resource.pdf">
            </div>
            <button type="button" onclick="this.parentElement.remove(); updateResourceNumbers();" style="background-color: #f44336;">
                Remove Resource
            </button>
        `;
        resourcesContainer.appendChild(newResource);
    }

    function updateResourceNumbers() {
        const resources = document.querySelectorAll('.resource-section');
        resourceCount = resources.length;
        resources.forEach((resource, index) => {
            resource.querySelector('h5').textContent = `Resource ${index + 1}`;
        });
    }
</script>

        <!-- UPDATED BLOG SECTION -->
        <section class="update_blog-section" id="update_blog-section">
            <h3>Update Blog Posts</h3>
            <?php
                if (isset($blog_message) && !empty($blog_message)) {
                    echo "<script>document.getElementById('update_blog-section').scrollIntoView();</script>";
                    echo "<p style='color: green;'>".$blog_message."</p>";
                }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
                <p>What do you want to do?</p>
                <input type="radio" id="new_blog" name="blog" value="new" checked>
                <label for="new_blog">New Blog Post</label>
                <input type="radio" id="edit_blog" name="blog" value="edit">
                <label for="edit_blog">Edit Blog Post</label>
                <input type="radio" id="delete_blog" name="blog" value="delete">
                <label for="delete_blog">Delete Blog Post</label>
                
                <br><br>
                <p><strong>Instructions:</strong></p>
                <ul style="text-align: left; margin-left: 20px;">
                    <li>For <strong>New Blog</strong>: Fill in all required fields</li>
                    <li>For <strong>Edit Blog</strong>: Enter Blog ID and only the fields you want to update</li>
                    <li>For <strong>Delete Blog</strong>: Only Blog ID is required</li>
                    <li>For images: You can either upload a file OR provide a URL (URL takes priority)</li>
                    <li><strong>Tags:</strong> Enter comma-separated tags (e.g., "technology, AI, research")</li>
                    <li><strong>All images will be stored in:</strong> BlogPage/data/{blog-id}/ folder</li>
                </ul>
                
                <hr style="margin: 20px 0;">
                
                <h4>Basic Information (for blog-data.json)</h4>
                <div class="form-group">
                    <label for="blog_id">Blog ID (Unique identifier, e.g., "blog-1", "ai-research-2024") *</label>
                    <input type="text" id="blog_id" name="blog_id" placeholder="e.g., blog-1, tech-article-2024">
                    <small>This will be used as the folder name for storing all blog files</small>
                </div>
                
                <div class="form-group">
                    <label for="title">Blog Title *</label>
                    <input type="text" id="title" name="title" placeholder="Enter blog title">
                </div>
                
                <div class="form-group">
                    <label for="short_description">Short Description (for blog card preview) *</label>
                    <textarea id="short_description" name="short_description" rows="3" placeholder="Brief description shown on blog listing page (150-200 chars)"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="date">Publication Date *</label>
                    <input type="text" id="date" name="date" placeholder="e.g., November 15, 2025">
                </div>
                
                <div class="form-group">
                    <label for="tags">Tags (comma-separated)</label>
                    <input type="text" id="tags" name="tags" placeholder="e.g., technology, AI, research, science">
                    <small>Enter tags separated by commas</small>
                </div>
                
                <hr style="margin: 20px 0;">
                
                <h4>Card/Preview Image (shown on blog listing page)</h4>
                <div class="form-group">
                    <label for="card_image_url">Card Image URL (Optional)</label>
                    <input type="text" id="card_image_url" name="card_image_url" placeholder="https://example.com/card-image.jpg">
                </div>
                
                <div class="form-group">
                    <label for="card_image">OR Upload Card Image:</label>
                    <input type="file" accept="image/*" name="card_image" id="card_image">
                    <small>Will be saved as: data/{blog-id}/card.{ext}</small>
                </div>
                
                <hr style="margin: 20px 0;">
                
                <h4>Author Information</h4>
                <div class="form-group">
                    <label for="author_name">Author Name *</label>
                    <input type="text" id="author_name" name="author_name" placeholder="Enter author name">
                </div>
                
                <div class="form-group">
                    <label for="author_role">Author Role *</label>
                    <input type="text" id="author_role" name="author_role" placeholder="e.g., Co-Founder, Singularity">
                </div>
                
                <div class="form-group">
                    <label for="author_desc">Author Description/Bio (for blog page)</label>
                    <textarea id="author_desc" name="author_desc" rows="2" placeholder="Enter author bio or description"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="author_image_url">Author Image URL (Optional)</label>
                    <input type="text" id="author_image_url" name="author_image_url" placeholder="https://example.com/author.jpg">
                </div>
                
                <div class="form-group">
                    <label for="author_image">OR Upload Author Image:</label>
                    <input type="file" accept="image/*" name="author_image" id="author_image">
                    <small>Will be saved as: data/{blog-id}/author.{ext}</small>
                </div>
                
                <hr style="margin: 20px 0;">
                
                <h4>Blog Page Content (for blog-page-{id}.json)</h4>
                
                <div class="form-group">
                    <label for="institute">Institute/Category</label>
                    <input type="text" id="institute" name="institute" placeholder="e.g., Institute, Technology, Science">
                </div>
                
                <div class="form-group">
                    <label for="intro_desc">Introduction/Description with Quotes</label>
                    <textarea id="intro_desc" name="intro_desc" rows="4" placeholder="Enter introduction text with quotes or description"></textarea>
                </div>
                
                <h4>Hero/Banner Image (shown at top of blog page)</h4>
                <div class="form-group">
                    <label for="hero_image_url">Hero Image URL (Optional)</label>
                    <input type="text" id="hero_image_url" name="hero_image_url" placeholder="https://example.com/hero-image.jpg">
                </div>
                
                <div class="form-group">
                    <label for="hero_image">OR Upload Hero Image:</label>
                    <input type="file" accept="image/*" name="hero_image" id="hero_image">
                    <small>Will be saved as: data/{blog-id}/hero.{ext}</small>
                </div>
                
                <hr style="margin: 20px 0;">
                
                <h4>Content Sections</h4>
                <p><small>You can add multiple content sections. Each section can have a heading, content, and an optional image.</small></p>
                
                <div id="content-sections">
                    <div class="content-section" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px;">
                        <h5>Section 1</h5>
                        <div class="form-group">
                            <label>Heading</label>
                            <input type="text" name="content_heading[]" placeholder="Enter section heading">
                        </div>
                        <div class="form-group">
                            <label>Content</label>
                            <textarea name="content_text[]" rows="5" placeholder="Enter section content"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Image URL (Optional)</label>
                            <input type="text" name="content_image_url[]" placeholder="https://example.com/section-image.jpg">
                        </div>
                        <div class="form-group">
                            <label>OR Upload Image:</label>
                            <input type="file" accept="image/*" name="content_image[]">
                            <small>Will be saved as: data/{blog-id}/section_0.{ext}</small>
                        </div>
                    </div>
                </div>
                
                <button type="button" onclick="addContentSection()" style="background-color: #4CAF50; margin-bottom: 20px;">
                    + Add Another Section
                </button>
                
                <hr style="margin: 20px 0;">
                
                <button type="submit">Submit Blog Post</button>
            </form>
        </section>
        
        <script>
            let sectionCount = 1;

            function addContentSection() {
                sectionCount++;
                const sectionsContainer = document.getElementById('content-sections');
                const newSection = document.createElement('div');
                newSection.className = 'content-section';
                newSection.style.cssText = 'border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px;';
                newSection.innerHTML = `
                    <h5>Section ${sectionCount}</h5>
                    <div class="form-group">
                        <label>Heading</label>
                        <input type="text" name="content_heading[]" placeholder="Enter section heading">
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="content_text[]" rows="5" placeholder="Enter section content"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Image URL (Optional)</label>
                        <input type="text" name="content_image_url[]" placeholder="https://example.com/section-image.jpg">
                    </div>
                    <div class="form-group">
                        <label>OR Upload Image:</label>
                        <input type="file" accept="image/*" name="content_image[]">
                        <small>Will be saved as: data/{blog-id}/section_${sectionCount - 1}.{ext}</small>
                    </div>
                    <button type="button" onclick="this.parentElement.remove(); updateSectionNumbers();" style="background-color: #f44336;">
                        Remove Section
                    </button>
                `;
                sectionsContainer.appendChild(newSection);
            }

            function updateSectionNumbers() {
                const sections = document.querySelectorAll('.content-section');
                sectionCount = sections.length;
                sections.forEach((section, index) => {
                    section.querySelector('h5').textContent = `Section ${index + 1}`;
                });
            }
        </script>
        
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