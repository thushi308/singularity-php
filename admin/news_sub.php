<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscriptions</title>
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
    <?php
        clearstatcache();
        $size=filesize("news.txt");
        if($size==0){
            echo "No subscribers yet";
        }
        else{
            $file=fopen("news.txt",'r');
            echo fread($file,$size);
            fclose($file);
        }
       
    ?>
</body>