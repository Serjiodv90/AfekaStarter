<?php

include_once "sessionManager.php";
include_once "PostImagesTable.php";
include_once "PostsTable.php";
include_once "sessionManager.php";


$postImagesFolder = "/pics/postImages/";

$isPrivate;
if ($_POST["privacyCheckbox"] == 0)
    $isPrivate = 0;
else
    $isPrivate = 1;

$postContent = $_POST["postContent"];
$postTableConn = new PostsTable();

// echo ("in tempStoreImage\n");
// echo ("post content: " . $_POST["postContent"] . "\nis private: $isPrivate\n");
// echo ("current dir: " . __DIR__);

$imagesTableConn = new PostImagesTable();
if (isset($_FILES["postImage"])) {

    if (is_array($_FILES["postImage"]["name"])) {

        $numOfImages = empty($_FILES["postImage"]["name"][0]) ? 0 : count($_FILES["postImage"]["name"]);
        $postId = $postTableConn->inserPost(getCurrentUserId(), $postContent, $numOfImages, $isPrivate);
        // echo ("num of images = $numOfImages");

        if (!empty($_FILES["postImage"]["name"][0])) {

            // print_r($_FILES["postImage"]);
            foreach ($_FILES["postImage"]["name"] as $indx => $value) {
                // echo ("in foreach: \nindex: $indx \nvalue: " . empty($value));

                $allowedEx = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
                $fileName = $_FILES["postImage"]["name"][$indx];   //get the full name of the file including the extenssion
                $fileType = $_FILES["postImage"]["type"][$indx];
                $fileSize = $_FILES["postImage"]["size"][$indx];

                // echo "File Name: " . $_FILES["postImage"]["name"][$indx] . "<br>";
                // echo "File Type: " . $_FILES["postImage"]["type"][$indx] . "<br>";
                // echo "File Size: " . ($_FILES["postImage"]["size"][$indx] / 1024) . " KB<br>";
                // echo "Stored in: " . $_FILES["postImage"]["tmp_name"][$indx];

                //verify extenssion correct
                $ext = pathinfo($fileName, PATHINFO_EXTENSION); //extract the file extension
                if (!array_key_exists($ext, $allowedEx))
                    die("Wrong image format");

                //maximize the file size to 5Mb
                $maxSize = 5 * 1024 * 1024;
                if ($fileSize > $maxSize)
                    die("The file is too big");

                //verify MIME type of the file
                if (in_array($fileType, $allowedEx)) {
                    $fileName = getCurrentUserId() . $postId . '_' . $fileName;
                    echo ("new file name: $fileName");
                    //check if the file exists before uploading it
                    if (file_exists($postImagesFolder . $fileName))
                        echo ("Image exists already");
                    else {
                        $imagesTableConn->saveImageForPost($postId, getCurrentUserId(), $postImagesFolder . $fileName);
                        $targetFileName = __DIR__ . '/..' . $postImagesFolder . $fileName;
                        move_uploaded_file($_FILES["postImage"]["tmp_name"][$indx], $targetFileName);
                        echo ("file was uploaded: $postImagesFolder $fileName");
                    }
                } else
                    echo ("MIME is not good");
            }
        }
    }
} else {
    $postTableConn->inserPost(getCurrentUserId(), $postContent, 0, $isPrivate);
}
