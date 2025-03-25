<?php
session_start();

$server="localhost";
$db_user="root";
$db_pass="";
$db_name="utilisateurs";
$conn="";
$conn=mysqli_connect($server,$db_user,$db_pass,$db_name);

$username=$_SESSION['username'];



if (isset($_POST['noam']) && isset($_POST['group-select'])) {
   
    $nom = trim($_POST['noam']);
    $group_id=$_POST['group-select'];
    
    $SQL = "SELECT user_id FROM users WHERE username=?";
    $stmt = $conn->prepare($SQL);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if($row=$res->fetch_assoc()){
        $user_id=$row['user_id'];
    }

    
        
        $SQL1 = "INSERT INTO projects (project_name, user_id, group_id, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt1 = $conn->prepare($SQL1);
        $stmt1->bind_param("sii", $nom, $user_id, $group_id);
        $stmt1->execute();
  
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projets</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
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
            width: calc(100% - 270px);
            display: flex;
            flex-direction: column;
        }

        h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .add-project {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            width: fit-content;
            transition: 0.3s;
        }

        .add-project i {
            margin-right: 8px;
        }

        .add-project:hover {
            background: #2980b9;
        }

        .projects-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .project-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: calc(33.33% - 20px);
            min-width: 250px;
            transition: 0.3s;
        }

        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .project-card h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .project-card p {
            font-size: 14px;
            color: #666;
            margin: 10px 0;
        }

        .status {
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            width: fit-content;
        }

        .todo { background: #e74c3c; }
        .doing { background: #f39c12; }
        .done { background: #2ecc71; }

        @media (max-width: 768px) {
            .projects-container {
                flex-direction: column;
            }
            .project-card {
                width: 100%;
            }
        }
        .members-list, .groups-list, .invitations-list {
            flex: 1;
            min-width: 300px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #eee;
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
                            echo "<a class='group-link' href='projects.php?group_id=" . htmlspecialchars($ROW['group_id']) . "'>" . htmlspecialchars($ROW['group_name']) . "</a><br>";
                            echo "</div>";
                        }
                        
                   
                        

                        
                ?>
                
            </div>
        <h1>Projets</h1>
        <form class="add-project-form" method="post" action="projects.php">
        <select id="group-select" name="group-select" required>
            <?php
               
                            
                                
            $SQL9="SELECT groupes.group_id,groupes.group_name FROM groupes INNER JOIN membres_groupes ON groupes.group_id=membres_groupes.group_id WHERE createur=? or role='admin'";
            
            $stmt=$conn->prepare($SQL9);
   $stmt->bind_param("s",$username);
   $stmt->execute();
   $result=$stmt->get_result();
   while($ROW=$result->fetch_assoc()){
      
       
       echo "<option value='". htmlspecialchars($ROW['group_id']) . "'>" . htmlspecialchars($ROW['group_name']) . "</option>";
       
   }
   

   

   
?>
            </select>
        <input type="text" name="noam" id="project-name" placeholder="Nom du projet">
    <button type="submit" class="add-project"><i class='bx bx-plus'></i> Ajouter un Projet</button>
</form>
        <div class="projects-container">
            <?php
            if(isset($_GET['group_id'])){ 
                $GROUP=$_GET['group_id'];
            $SQL3="SELECT project_id,project_name FROM projects WHERE group_id=?";
            $stmt3=$conn->prepare($SQL3);
            $stmt3->bind_param("i",$GROUP);
            $stmt3->execute();
            $resu=$stmt3->get_result();
            while($Row=$resu->fetch_assoc()){ 
                $proj_id=$Row['project_id'];
                $SQL5="SELECT count(*) FROM taches WHERE project_id=? and tach_etat='En cours' or tach_etat='Terminé'";
                $stmt=$conn->prepare($SQL5);
                $stmt->bind_param("i",$proj_id);
                $stmt->execute();
                $stmt->bind_result($num_rows);
                $stmt->fetch();
                $stmt->close();
                if($num_rows == 0){ 
                echo "<div class='project-card'>";
                echo "<h3>".$Row['project_name']."</h3>
                <div class='status todo'>À faire</div>";
            echo "</div>";
                }
                else{
                    echo "<div class='project-card'>";
                echo "<h3>".$Row['project_name']."</h3>
                <div class='status doing'>En cours</div>";
            echo "</div>";
                }
                $SQL10="SELECT count(*) FROM taches WHERE project_id=? AND tach_etat!='Terminé'";
                $stmt=$conn->prepare($SQL10);
                $stmt->bind_param("i",$proj_id);
                $stmt->execute();
                $stmt->bind_result($Num_rows);
                $stmt->fetch();
                $stmt->close();
                if($Num_rows == 0 ){
                    echo "<div class='project-card'>";
                echo "<h3>".$Row['project_name']."</h3>
                <div class='status done'>Terminé</div>";
            echo "</div>";
                }
            }
            }
            ?>
            
           
        </div>
        
    </div>
<script src="script2.js">
    
</script>
</body>
</html>
