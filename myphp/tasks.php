<?php
session_start();
$server="localhost";
$db_user="root";
$db_pass="";
$db_name="utilisateurs";
$conn="";
$conn=mysqli_connect($server,$db_user,$db_pass,$db_name);
$username=$_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['taskTitle']) && isset($_POST['taskStatus']) && isset($_POST['taskDueDate']) && isset($_POST['taskProject'])) {
    $usernam=$_SESSION['username'];
    $group_id=$_GET['group_id'];
    $titre=$_POST['taskTitle'];
    $project_id=$_POST['taskProject'];
    $etat=$_POST['taskStatus'];
    $date = date("Y-m-d", strtotime($_POST['taskDueDate']));
    $SQl="SELECT user_id FROM users WHERE username=?";
    $stmt = $conn->prepare($SQl);
    $stmt->bind_param("s", $usernam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $user_id = $row['user_id'];
    $SQL3="INSERT INTO taches(tach_nom,tach_etat,group_id,project_id,user_id,datelim) VALUES(?,?,?,?,?,?)";
    $stmt2=$conn->prepare($SQL3);
    $stmt2->bind_param("ssiiis",$titre,$etat,$group_id,$project_id,$user_id,$date);
   $stmt2->execute();
   echo json_encode(["success" => true, "message" => "Tâche ajoutée avec succès!"]);
   exit();
    }
}

function getTasksByStatus($conn, $group_id, $proj_id, $status) {
    $sql = "SELECT * FROM taches WHERE group_id = ? AND project_id = ? AND tach_etat = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $group_id, $proj_id, $status);
    $stmt->execute();
    return $stmt->get_result();
}

// Handle status update (from URL)
if (isset($_GET['group_ID']) && isset($_GET['task_ID']) && isset($_GET['data-role']) && isset($_GET['project_id'])) {
    $task_id = $_GET['task_ID'];
    $gr = $_GET['group_ID'];
    $new_stat = $_GET['data-role'];
    $project_id = $_GET['project_id'];

    // Validate and sanitize inputs
    $task_id = intval($task_id);
    $gr = intval($gr);
    $new_stat = htmlspecialchars($new_stat);
    $project_id = intval($project_id);

    // Update task status in the database
    $sql_update = "UPDATE taches SET tach_etat = ? WHERE group_id = ? AND tach_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sii", $new_stat, $gr, $task_id);
    $stmt_update->execute();

    // Redirect to the same page after update
    header("Location: tasks.php?group_id=$gr&project_id=$project_id");
    exit();
}

// Fetch task counts for each status
if (isset($_GET['group_id']) && isset($_GET['project_id'])) {
    $group_id = $_GET['group_id'];
    $proj_id = $_GET['project_id'];

    // Task counts for each status
    $todo_count = getTasksByStatus($conn, $group_id, $proj_id, 'À faire')->num_rows;
    $doing_count = getTasksByStatus($conn, $group_id, $proj_id, 'En cours')->num_rows;
    $done_count = getTasksByStatus($conn, $group_id, $proj_id, 'Terminé')->num_rows;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Tâches</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, #7494ec, #1a252f);
            color: white;
            height: 100vh;
            padding: 20px;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            z-index: 10;
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            background-color: rgb(222, 230, 255);
            padding: 8px;
            border-radius: 8px;
        }

        .sidebar .logo img {
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
            margin-left: 270px;
            padding: 30px;
            flex: 1;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .add-task-button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .add-task-button i {
            margin-right: 8px;
        }

        .add-task-button:hover {
            background: #3e8e41;
        }

        .board {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding-bottom: 20px;
        }

        .column {
            background: #ebecf0;
            border-radius: 8px;
            min-width: 280px;
            max-width: 280px;
            height: fit-content;
        }

        .column-header {
            padding: 12px 16px;
            font-weight: 600;
            color: #172b4d;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .column-header .task-count {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 12px;
        }

        .task-list {
            padding: 0 8px 8px 8px;
            min-height: 20px;
        }

        .task-card {
            background: white;
            border-radius: 3px;
            box-shadow: 0 1px 0 rgba(9, 30, 66, 0.25);
            cursor: pointer;
            margin-bottom: 8px;
            padding: 10px;
            position: relative;
        }

        .task-card:hover {
            background: #f4f5f7;
        }

        .task-labels {
            display: flex;
            gap: 5px;
            margin-bottom: 5px;
        }

        .task-label {
            height: 8px;
            width: 40px;
            border-radius: 4px;
        }

        .label-red { background: #eb5a46; }
        .label-green { background: #61bd4f; }
        .label-blue { background: #0079bf; }
        .label-yellow { background: #f2d600; }

        .task-title {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .task-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            margin-top: 8px;
            color: #6b778c;
        }

        .deadline {
            display: flex;
            align-items: center;
        }

        .deadline i {
            margin-right: 3px;
        }

        .task-modal {
            display: none;
            position: fixed;
            z-index: 20;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            max-width: 500px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .task-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-weight: 500;
        }

        .btn-primary {
            background: #0079bf;
            color: white;
        }

        .btn-cancel {
            background: #ebecf0;
            color: #172b4d;
        }

        /* Drag and drop styles */
        .task-card.dragging {
            opacity: 0.5;
        }

        .column.drag-over {
            background-color: #dfe1e6;
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
.delete-group-container {
    margin-top: 30px;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .group-selection {
    margin: 15px 0;
  }
  
  #group-select {
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ced4da;
    width: 250px;
  }
    </style>
</head>
<body>

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

    <div class="main-content">
        <form id="taskForm" method="post" action="tasks.php?group_id=<?php echo $group_id; ?>">
            <div class="page-header">
                <button class="add-task-button" type="button" id="openTaskModal" onclick="showTaskModal()"><i class='bx bx-plus'></i> Ajouter une tâche</button>
            </div>
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
                            echo "<a class='group-link' href='tasks.php?group_id=" . htmlspecialchars($ROW['group_id']) . "'>" . htmlspecialchars($ROW['group_name']) . "</a><br>";
                            echo "</div>";
                        }
                        
                   
                        

                        
                ?>
                
            </div>
            <div class="form-group">
                            <label for="project-select">Projet</label>
                            <div class="groups-list">
                                <?php
                                if (isset($_GET['group_id'])) {
                                    $group_id=$_GET['group_id'];
                                    $SQL2 = "SELECT * FROM projects WHERE group_id=?";
                                    $stmt1 = $conn->prepare($SQL2);
                                    $stmt1->bind_param("i", $group_id);
                                    $stmt1->execute();
                                    $result1 = $stmt1->get_result();
                                    while ($ROw = $result1->fetch_assoc()) {
                                        echo "<div class='group-link'>";
                                        echo "<a class='group-link' href='tasks.php?group_id=" . htmlspecialchars($ROw['group_id']) . "&project_id=" . htmlspecialchars($ROw['project_id']) . "'>" . htmlspecialchars($ROw['project_name']) . "</a><br>";
                                        echo "</div>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
            <div class="task-modal" id="addTaskModal">
                <div class="modal-content">
                    <span class="close" onclick="closeTaskModal()">&times;</span>
                    <h2>Ajouter une tâche</h2>
                   
                        <div class="form-group">
                            <label for="titre">Titre de la tâche</label>
                            <input type="text" id="taskTitle" name="taskTitle" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="project-select">Projet</label>
                            <select id="taskProject" name="taskProject" class="form-control" required onchange="this.form.submit()">
                                <?php
                                if (isset($_GET['group_id'])) {
                                    $group_id=$_GET['group_id'];
                                    $SQL2 = "SELECT project_id, project_name FROM projects WHERE group_id=?";
                                    $stmt1 = $conn->prepare($SQL2);
                                    $stmt1->bind_param("i", $group_id);
                                    $stmt1->execute();
                                    $result1 = $stmt1->get_result();
                                    while ($ROw = $result1->fetch_assoc()) {
                                        echo "<option value='" . htmlspecialchars($ROw['project_id']) . "'>" . htmlspecialchars($ROw['project_name']) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Date d'échéance</label>
                            <input type="date" id="taskDueDate" name="taskDueDate" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="etat">État</label>
                            <select id="taskStatus" name="taskStatus" class="form-control" required>
                                <option value="À faire">À faire</option>
                                <option value="En cours">En cours</option>
                                <option value="Terminé">Terminé</option>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="submit" id="addTaskBtn" name="ajouTer" class="btn btn-primary">Ajouter la tâche</button>
                            <button type="button" class="btn btn-cancel" onclick="closeTaskModal()">Annuler</button>
                        </div>
                    
                </div>
                
            </div>

        

        <h1>Mes Tâches</h1>
        <div class="board">
            <div class="column" data-status="todo">
                <div class="column-header">
                <span>À faire</span>
                    <span class="task-count" id="todo-count"><?php if(isset($_GET['group_id']) && isset($_GET['project_id'])){ 
                    $group_id=$_GET['group_id'];
                    $proj_id=$_GET['project_id'];
                        $sql_todo = "SELECT * FROM taches WHERE group_id = ? AND project_id=? AND tach_etat = 'À faire'";
                        $stmt_todo = $conn->prepare($sql_todo);
                        $stmt_todo->bind_param("ii", $group_id,$proj_id);
                        $stmt_todo->execute();
                        $result_todo = $stmt_todo->get_result();
                        echo $result_todo->num_rows;
                    } 
                        ?></span>
                </div>
                <div class="task-list" id="todo-list">
                    <?php
                    if(isset($_GET['group_id']) && isset($_GET['project_id'])){ 
                        $proj_id=$_GET['project_id'];
                        $group_id=$_GET['group_id'];
                $result_todo = getTasksByStatus($conn, $group_id, $proj_id, 'À faire');
            while ($task = $result_todo->fetch_assoc()) {
                $stat = $task['tach_etat'];
                $statu = ($stat === 'À faire') ? 'En cours' : 'À faire'; // Toggle status

                echo "<div class='task-card'>";
                echo "<div class='task-title'>" . htmlspecialchars($task['tach_nom']) . "</div>";
                echo "<div class='task-footer'>";
                echo "<a href='tasks.php?ID=" . htmlspecialchars($task['user_id']) . "&task_ID=" . htmlspecialchars($task['tach_id']) . "&group_ID=" . htmlspecialchars($task['group_id']) . "&data-role=" . htmlspecialchars($statu) . "&project_id=" . htmlspecialchars($proj_id) . "'>
                        Passer " . ucfirst($statu) . "
                    </a>";
                echo "<span class='deadline'><i class='bx bx-calendar'></i> " . htmlspecialchars($task['datelim']) . "</span>";
                echo "</div></div>";
            }
        }
            ?>
                </div>
                </div>
            <div class="column" data-status="doing">
                <div class="column-header">
                    <span>En cours</span>
                    <span class="task-count" id="doing-count"><?php
                    if(isset($_GET['group_id']) && isset($_GET['project_id'])){ 
                        $proj_id=$_GET['project_id'];
                        $group_id=$_GET['group_id'];
                        $sql_doing = "SELECT * FROM taches WHERE group_id = ? AND project_id=? AND tach_etat = 'En cours'";
                        $stmt_doing = $conn->prepare($sql_doing);
                        $stmt_doing->bind_param("ii", $group_id,$proj_id);
                        $stmt_doing->execute();
                        $result_doing = $stmt_doing->get_result();
                        echo $result_doing->num_rows;
                    }
                    ?></span>
                </div>
                <div class="task-list" id="doing-list"></div>
                
               
               <?php
               if(isset($_GET['group_id']) && isset($_GET['project_id'])){ 
                $proj_id=$_GET['project_id'];
                $group_id=$_GET['group_id'];
        $result_done = getTasksByStatus($conn, $group_id, $proj_id, 'En cours');
                while ($task = $result_done->fetch_assoc()) {
                    $stat = $task['tach_etat'];
                    $statu = ($stat === 'En cours') ? 'Terminé' : 'En cours'; // Toggle status
    
                    echo "<div class='task-card'>";
                    echo "<div class='task-title'>" . htmlspecialchars($task['tach_nom']) . "</div>";
                    echo "<div class='task-footer'>";
                    echo "<a href='tasks.php?ID=" . htmlspecialchars($task['user_id']) . "&task_ID=" . htmlspecialchars($task['tach_id']) . "&group_ID=" . htmlspecialchars($task['group_id']) . "&data-role=" . htmlspecialchars($statu) . "&project_id=" . htmlspecialchars($proj_id) . "'>
                            Passer " . ucfirst($statu) . "
                        </a>";
                    echo "<span class='deadline'><i class='bx bx-calendar'></i> " . htmlspecialchars($task['datelim']) . "</span>";
                    echo "</div></div>";
                }
            }
                ?>
            </div>
            <div class="column" data-status="done">
                <div class="column-header">
                    <span>Terminé</span>
                    <span class="task-count" id="done-count"><?php
                    if(isset($_GET['group_id']) && isset($_GET['project_id'])){ 
                        $proj_id=$_GET['project_id'];
                        $group_id=$_GET['group_id'];
                        $sql_done = "SELECT * FROM taches WHERE group_id = ? AND project_id=? AND tach_etat = 'Terminé'";
                        $stmt_done = $conn->prepare($sql_done);
                        $stmt_done->bind_param("ii", $group_id,$proj_id);
                        $stmt_done->execute();
                        $result_done = $stmt_done->get_result();
                        echo $result_done->num_rows;
                    }
                    ?></span>
                </div>
                <div class="task-list" id="done-list"></div>
                <?php
                if(isset($_GET['group_id']) && isset($_GET['project_id'])){ 
                    $proj_id=$_GET['project_id'];
                    $group_id=$_GET['group_id'];
            $result_done = getTasksByStatus($conn, $group_id, $proj_id, 'Terminé');
            while ($task = $result_done->fetch_assoc()) {
                $stat = $task['tach_etat'];
                $statu = ($stat === 'Terminé') ? 'À faire' : 'Terminé'; // Toggle status

                echo "<div class='task-card'>";
                echo "<div class='task-title'>" . htmlspecialchars($task['tach_nom']) . "</div>";
                echo "<div class='task-footer'>";
                echo "<a href='tasks.php?ID=" . htmlspecialchars($task['user_id']) . "&task_ID=" . htmlspecialchars($task['tach_id']) . "&group_ID=" . htmlspecialchars($task['group_id']) . "&data-role=" . htmlspecialchars($statu) . "&project_id=" . htmlspecialchars($proj_id) . "'>
                        Passer " . ucfirst($statu) . "
                    </a>";
                echo "<span class='deadline'><i class='bx bx-calendar'></i> " . htmlspecialchars($task['datelim']) . "</span>";
                echo "</div></div>";
            }
        }
            ?>
            </div>
        </div>
        </form>
    </div>

    <script>
       document.addEventListener("DOMContentLoaded", function () {
    // Add event listener to the "Ajouter la tâche" button
    document.getElementById("addTaskBtn").addEventListener("click", function (event) {
        event.preventDefault(); // Prevent form submission

        let title = document.getElementById("taskTitle").value;
        let project = document.getElementById("taskProject").value;
        let dueDate = document.getElementById("taskDueDate").value;
        let status = document.getElementById("taskStatus").value;

        if (!title || !project || !dueDate || !status) {
            alert("Veuillez remplir tous les champs!");
            return;
        }

        // Create FormData to send to PHP
        let formData = new FormData();
        formData.append("taskTitle", title);
        formData.append("taskProject", project);
        formData.append("taskDueDate", dueDate);
        formData.append("taskStatus", status);

        // Send the data to the server
        fetch(window.location.href, {
            method: "POST",
            body: formData
        })
        .then(response => response.json()) // Parse the response as JSON
        .then(data => {
            if (data.success) {
                alert(data.message); // Show success message
                location.reload(); // Reload the page to see the new task
            } else {
                alert("Erreur: " + data.message);
            }
        })
        .catch(error => {
            console.error("Erreur:", error);
            alert("Une erreur est survenue lors de l'ajout de la tâche.");
        });
    });
});

        function showTaskModal() {
            document.getElementById('addTaskModal').style.display = 'block';
        }

        function closeTaskModal() {
            document.getElementById('addTaskModal').style.display = 'none';
        }
    </script>

</body>
</html>