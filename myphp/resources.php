<?php
session_start();

$server="localhost";
$db_user="root";
$db_pass="";
$db_name="utilisateurs";
$conn="";
$conn=mysqli_connect($server,$db_user,$db_pass,$db_name);

$username=$_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
     <style>
                    body {
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #ffffff;
            color: #333;
        }
        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, #7494ec, #1a252f);
            color: white;
            height: 100vh;
            padding: 20px;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            background-color: rgb(222, 230, 255);
            padding: 8px;
            border-radius: 8px;
        }

        .sidebar .logo img{
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .sidebar .logo span {
            color: #000000;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 8px 0;
        }

        .sidebar ul li a {
            color: #ecf0f1;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar ul li a i {
            margin-right: 12px;
            font-size: 20px;
        }

        .sidebar ul li a:hover {
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .sidebar ul li a.active {
            background: #3498db;
            color: white;
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }
        .main-content {
            flex: 1;
            padding: 30px 40px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .main-content h1 {
            color: #7494ec;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            font-size: 28px;
        }

        .main-content h1::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e0e0e0;
            margin-left: 20px;
        }

        .main-content p {
            color: #7f8c8d;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .upload-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin: 20px 0 30px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .file-input-wrapper {
            position: relative;
            flex: 1;
        }

        .file-input-wrapper::before {
            content: "Choose file...";
            position: absolute;
            left: 0;
            top: 0;
            display: inline-block;
            background: #f5f7fa;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            padding: 10px 15px;
            pointer-events: none;
            width: calc(100% - 34px);
            color: #7f8c8d;
        }

        .file-input-wrapper input[type="file"] {
            opacity: 0;
            width: 100%;
            height: 42px;
            cursor: pointer;
        }

        .upload-section button {
            padding: 12px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .upload-section button:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .upload-section button:active {
            transform: translateY(0);
        }

        /* File List */
        .files-container {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .files-container h2 {
            color: #2c3e50;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ecf0f1;
        }

        ul#fileList {
            list-style: none;
            padding: 0;
        }

        ul#fileList li {
            background: #f8f9fa;
            margin: 10px 0;
            padding: 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
            border-left: 4px solid #3498db;
        }

        ul#fileList li:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        ul#fileList li .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        ul#fileList li .file-icon {
            color: #3498db;
            font-size: 20px;
        }

        ul#fileList li button {
            background: none;
            border: none;
            color: #e74c3c;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.2s ease;
            padding: 5px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        ul#fileList li button:hover {
            background: rgba(231, 76, 60, 0.1);
            transform: scale(1.1);
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #95a5a6;
            display: none;
        }

        .empty-state i {
            font-size: 50px;
            margin-bottom: 15px;
            opacity: 0.3;
        }

        
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                padding: 10px;
            }
            .main-content {
                padding: 20px;
            }
            .upload-section {
                flex-direction: column;
                align-items: stretch;
            }
            .file-input-wrapper {
                width: 100%;
            }
        }

        /* Improved Upload Section Styles */
        .upload-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin: 20px 0 30px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
        }

        .upload-section:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .file-input-wrapper {
            position: relative;
            flex: 1;
            height: 80px;
        }

        .file-input-wrapper input[type="file"] {
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }

        .file-input-label {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border: 2px dashed #b8c2cc;
            border-radius: 8px;
            color: #7f8c8d;
            transition: all 0.3s ease;
        }

        .file-input-wrapper:hover .file-input-label {
            background: #eef2f7;
            border-color: #7494ec;
            color: #7494ec;
        }

        .file-input-label i {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .upload-section button {
            padding: 12px 24px;
            background: #7494ec;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            height: 50px;
        }

        .upload-section button:hover {
            background: #5a76c7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .upload-section button:active {
            transform: translateY(0);
        }

        /* Drag and drop highlight */
        .file-input-wrapper.dragover .file-input-label {
            background: #e6f7ff;
            border-color: #3498db;
        }

        @media (max-width: 768px) {
            .upload-section {
                flex-direction: column;
                align-items: stretch;
            }
            
            .file-input-wrapper {
                width: 100%;
                margin-bottom: 15px;
            }
        }
        .group-link {
    display: inline-block;
    color: #007bff;  /* Blue color */
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
    padding: 8px 12px;
    margin: 5px;
    border-radius: 5px;
    background-color: #f0f8ff;  /* Light blue background */
    transition: all 0.3s ease;
}
        .group-link:hover {
    background-color: #007bff;
    color: white;
    text-decoration: none;
    transform: scale(1.05);
}
.add-project-form {
    display: flex;
    margin-bottom: 20px;
}

#project-name {
    padding: 8px 12px;
    margin-right: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
    flex-grow: 1;
}
     </style>
     <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img >
            <span>Plateforme</span>
        </div>
        <ul>
            <li><h3>bievenue <?php echo $username;  ?></h3><li>
        <li><a href="amis.php"><i class='bx bxs-user'></i> Membres</a></li>
           
            <li><a href="projects.php" ><i class='bx bxs-briefcase'></i> Projects</a></li>
            <li><a href="tasks.php"><i class='bx bx-check-square'></i> Tasks</a></li>
            <li><a href="messages.php"><i class='bx bxs-message-dots'></i> Messages</a></li>
            <li><a href="resources.php"><i class='bx bxs-file-doc'></i> Resources</a></li>
            <li><a href="admin.php"><i class='bx bxs-cog'></i> Admin Panel</a></li>
            <li><a href="logout.php"><i class='bx bxs-log-out'></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>📁 Resources</h1>
        <p>Upload and manage project files.</p>

        <!-- Improved File Upload Section -->
        <div class="upload-section">
        <form id="uploadForm" action="upload.php" method="POST" enctype="multipart/form-data">

        <div class="groups-list">
                <h2>Groupes</h2>
                <?php
               
                            
                                
                                 $SQL8="SELECT groupes.group_id,groupes.group_name FROM groupes INNER JOIN membres_groupes ON groupes.group_id=membres_groupes.group_id WHERE createur=? or role='admin'";
                                 
                                 $stmt=$conn->prepare($SQL8);
                        $stmt->bind_param("s",$username);
                        $stmt->execute();
                        $result=$stmt->get_result();
                        while($ROW=$result->fetch_assoc()){
                           
                            echo "<div class='group-link'>";
                            echo "<a class='group-link' href='resources.php?group_id=" . htmlspecialchars($ROW['group_id']) . "'>" . htmlspecialchars($ROW['group_name']) . "</a><br>";
                            echo "</div>";
                        }
                        
                   
                        

                        
                ?>
                
            </div>
            <select id="project-select" name="project-select"  required>
                    
                    <?php
                    if(isset($_GET['group_id'])){ 
                    $group_id=$_GET['group_id'];
                        $SQL2="SELECT projects.project_id,projects.project_name FROM
                        projects INNER JOIN groupes ON projects.group_id=groupes.group_id WHERE projects.group_id=?";
                        $stmt1=$conn->prepare($SQL2);
                        $stmt1->bind_param("i",$group_id);
                        $stmt1->execute();
                        $result1=$stmt1->get_result();
                        while($ROw=$result1->fetch_assoc()){
                            echo "<option value='". htmlspecialchars($ROw['project_id']) . "'>" . htmlspecialchars($ROw['project_name']) . "</option>";
                        }
                    }
                    ?>
                   
                </select>  
                <input  type="text" name="title"/>
        <div class="file-input-wrapper">
        
                <input type="file" name="file" id="fileInput" multiple>
                
                <div class="file-input-label">
                    <i class="bx bx-upload"></i>
                    <span id="file-name">Choose files or drag & drop here</span>
                </div>
                
            </div>
            <input type="submit" name="submit" />
             
                
    </form>
        </div>

        <!-- File List -->
        <h2>Uploaded Files</h2>

        <ul id="fileList">
        <?php
// Assuming you've already connected to the database, as shown in the previous response.

$username = $_SESSION['username'];

// Fetch user ID based on the session username
$SQL = "SELECT user_id FROM users WHERE username='$username'";
$rq = mysqli_query($conn, $SQL);
if ($row = mysqli_fetch_assoc($rq)) {
    $user_id = $row['user_id'];

    // Fetch files associated with the user
    $SQL1 = "SELECT file_name, file_path,group_id FROM ressources WHERE user_id='$user_id'";
    $result = mysqli_query($conn, $SQL1);
    
    // Check if there are any files
    if (mysqli_num_rows($result) > 0) {
        echo '<ul id="fileList">';
        while ($row = mysqli_fetch_assoc($result)) {
            // Display each file in a list item
            
            echo '<li>';
            echo '<a href="C:\Users\hp\Documents' . $row['file_path'] . '" target="_blank">' . $row['file_name'] .'</a>'.$row['group_id'] ;
            echo '</li>';
        }
        echo '</ul>';
    }}
    ?>
        </ul>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <script>
   document.addEventListener("DOMContentLoaded", function() {
    let fileInput = document.getElementById("fileInput");
    let fileList = document.getElementById("fileList");
    let fileNameDisplay = document.getElementById("file-name");
    let dropArea = document.querySelector(".file-input-wrapper");

    function uploadFile() {
        if (fileInput.files.length > 0) {
            let formData = new FormData();

            for (let i = 0; i < fileInput.files.length; i++) {
                let file = fileInput.files[i];
                formData.append("fileInput[]", file); // Append multiple files

                // UI updates
                let listItem = document.createElement("li");
                let fileInfo = document.createElement("div");
                fileInfo.className = "file-info";

                let fileIcon = document.createElement("span");
                fileIcon.className = "file-icon";

                // File type detection
                if (file.type.startsWith("image/")) {
                    fileIcon.innerHTML = '<i class="bx bxs-file-image"></i>';
                } else if (file.type === "application/pdf") {
                    fileIcon.innerHTML = '<i class="bx bxs-file-pdf"></i>';
                } else if (file.type === "application/msword" || file.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                    fileIcon.innerHTML = '<i class="bx bxs-file-doc"></i>';
                } else {
                    fileIcon.innerHTML = '<i class="bx bxs-file"></i>';
                }

                fileInfo.appendChild(fileIcon);
                
                let fileName = document.createElement("span");
                fileName.textContent = file.name;
                fileInfo.appendChild(fileName);
                listItem.appendChild(fileInfo);

                let deleteBtn = document.createElement("button");
                deleteBtn.innerHTML = '<i class="bx bx-trash"></i>';
                deleteBtn.title = "Delete file";
                deleteBtn.onclick = function() {
                    listItem.remove();
                };
                dropArea.addEventListener("dragover", function () {
    dropArea.classList.add("dragover");
});

dropArea.addEventListener("dragleave", function () {
    dropArea.classList.remove("dragover");
});
document.getElementById("uploadForm").addEventListener("submit", function(event) {
    event.preventDefault();
    uploadFile();
});
                listItem.appendChild(deleteBtn);
                fileList.appendChild(listItem);
            }

            // Send file to the server
            fetch("resources.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert("File uploaded successfully");
                console.log(data);
            })
            .catch(error => {
                alert("Error uploading file");
                console.error(error);
            });

            // Clear file input
            fileInput.value = "";
            fileNameDisplay.textContent = "Choose files or drag & drop here";
        } else {
            alert("Please select a file!");
        }
    }

    // Update file name when selecting files
    fileInput.addEventListener("change", function() {
        if (this.files.length > 1) {
            fileNameDisplay.textContent = `${this.files.length} files selected`;
        } else if (this.files.length === 1) {
            fileNameDisplay.textContent = this.files[0].name;
        } else {
            fileNameDisplay.textContent = "Choose files or drag & drop here";
        }
    });

    // Drag & Drop
    ["dragenter", "dragover", "dragleave", "drop"].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ["dragenter", "dragover"].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.add("dragover"), false);
    });

    ["dragleave", "drop"].forEach(eventName => {
        dropArea.addEventListener(eventName, () => dropArea.classList.remove("dragover"), false);
    });
    document.getElementById("fileInput").addEventListener("change", function() {
    let fileName = this.files.length > 1 ? `${this.files.length} fichiers sélectionnés` : this.files[0].name;
    document.getElementById("file-name").textContent = fileName;
});

    dropArea.addEventListener("drop", function(e) {
        let dt = e.dataTransfer;
        let files = dt.files;
        fileInput.files = files;

        // Trigger change event to update file name display
        const event = new Event("change", { bubbles: true });
        fileInput.dispatchEvent(event);

        uploadFile();
    });
});

</script>




</body>
</html>