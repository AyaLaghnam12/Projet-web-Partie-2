<?php
session_start();

$localhost = "localhost"; 
$dbusername = "root"; 
$dbpassword = "";  
$dbname = "utilisateurs";  

// Connection string
$conn = mysqli_connect($localhost, $dbusername, $dbpassword, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_SESSION['username'];   

if (isset($_POST["submit"])) {   
    $project_id = mysqli_real_escape_string($conn, $_POST['project-select']);

    // Fetch user ID based on the session username
    $SQL = "SELECT user_id FROM users WHERE username='$username'";
    $rq = mysqli_query($conn, $SQL);
    if ($row = mysqli_fetch_assoc($rq)) {
        $user_id = $row['user_id'];

        // Get the group ID for the user
        $SQL1 = "SELECT group_id FROM projects WHERE user_id='$user_id'";
        $rR = mysqli_query($conn, $SQL1);
        if ($Row = mysqli_fetch_assoc($rR)) {
            $group_id = $Row['group_id'];

            // Get the title and file
            $title = mysqli_real_escape_string($conn, $_POST["title"]);
            $pname = rand(1000, 10000) . "-" . $_FILES["file"]["name"];
            $tname = $_FILES["file"]["tmp_name"];

            // Define upload directory (change this to a valid directory path on your server)
            $uploads_dir = 'C:\Users\hp\Documents';  // Ensure this path is writable

            // Check for file upload errors
            if ($_FILES["file"]["error"] === UPLOAD_ERR_OK) {
                // Move the uploaded file
                if (move_uploaded_file($tname, $uploads_dir . '/' . $pname)) {
                    // Insert file data into the database
                    $sql = "INSERT INTO ressources (group_id, user_id, project_id, file_name, file_path, upload_date) 
                            VALUES ('$group_id', '$user_id', '$project_id', '$title', '$pname', CURRENT_TIMESTAMP)";

                    if (mysqli_query($conn, $sql)) {
                        echo "File successfully uploaded.";
                    } else {
                        echo "Error inserting record into the database: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error uploading file.";
                }
            } else {
                echo "File upload error: " . $_FILES["file"]["error"];
            }
        } else {
            echo "Error fetching group ID.";
        }
    } else {
        echo "Error fetching user ID.";
    }
}

?>
