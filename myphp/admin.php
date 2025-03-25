<?php
session_start();
$server="localhost";
$db_user="root";
$db_pass="";
$db_name="utilisateurs";
$conn="";
$conn=mysqli_connect($server,$db_user,$db_pass,$db_name);
$email=$_SESSION["email"];
$userna="SELECT username from users WHERE email=?";
$stmt=$conn->prepare($userna);
$stmt->bind_param("s",$email);
$stmt->execute();
$result=$stmt->get_result();
if($result->num_rows >0){
   $user = $result->fetch_assoc();
    $username=$user['username'];
    $_SESSION['username']=$username;
   $_SESSION['email']=$email; 
}
if(isset($_GET['id']) && isset($_GET['group_id'])){
    $user_ID=$_GET['id'];
    $group_ID=$_GET['group_id'];
    $SQL4="DELETE FROM membres_groupes WHERE user_id=? and group_id=?";
    $stmt=$conn->prepare($SQL4);
    $stmt->bind_param("ii",$user_ID,$group_ID);
    $stmt->execute();
    
    }
    if(isset($_GET['ID']) && isset($_GET['group_ID']) && isset($_GET['data-role'])){
        $user_ID1=intval($_GET['ID']);
    $group_ID1=intval($_GET['group_ID']);
    $new_role = htmlspecialchars($_GET['data-role']);
    $SQL5="UPDATE membres_groupes SET role=? WHERE group_id=? and user_id=?";
    $stmt=$conn->prepare($SQL5);
    $stmt->bind_param("sii",$new_role,$group_ID1,$user_ID1);
    $stmt->execute();
    }
    
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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

        .admin-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .admin-section h2 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background: #3498db;
            color: white;
        }

        .role {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
        }

        .admin { background: #0d4e20; }
        .moderator { background: #f39c12; }
        .user { background: #3d1a88; }

        .action-buttons button {
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 5px;
        }

        .edit-btn { background: #04055b; color: white; }
        .delete-btn { background: #6a1212; color: white; }

        .settings-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .settings-form label {
            font-weight: 600;
        }

        .settings-form input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }

        .save-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: fit-content;
            transition: 0.3s;
        }

        .save-btn:hover {
            background: #2980b9;
        }

        @media (max-width: 768px) {
            table {
                font-size: 12px;
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
  
  .btn-danger {
    background-color: #dc3545;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .btn-danger:hover {
    background-color: #c82333;
  }
  
  .warning-text {
    color: #dc3545;
    font-size: 0.9em;
    margin-top: 10px;
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
        <form method="post">
        <h1>Admin Panel</h1>
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
                            echo "<a class='group-link' href='admin.php?group_id=" . htmlspecialchars($ROW['group_id']) . "'>" . htmlspecialchars($ROW['group_name']) . "</a><br>";
                            echo "</div>";
                        }
                        
                   
                        

                        
                ?>
                
            </div>
            <div class="delete-group-container">
  <h3>Supprimer un groupe</h3>
  <div class="group-selection">
    <label for="group-select">Sélectionner un groupe:</label>
    <?php
               
                            
                                
                                 $SQL8="SELECT group_id,group_name FROM groupes WHERE createur=?";
                        $stmt=$conn->prepare($SQL8);
                        $stmt->bind_param("s",$username);
                        $stmt->execute();
                        $result=$stmt->get_result();
                        while($ROW=$result->fetch_assoc()){
                           
                            echo "<div class='group-link'>";
                            echo "<a class='group-link' href='admin.php?group_Id=" . htmlspecialchars($ROW['group_id']) . "'>" . htmlspecialchars($ROW['group_name']) . "</a><br>";
                            echo "</div>";
                            if(isset($_GET['group_Id']) && isset($_POST['delete'])){
                               $group_id=$_GET['group_Id'];
                                $SQL9="DELETE FROM groupes WHERE group_id=?";
                                $st=$conn->prepare($SQL9);
                                $st->bind_param("i",$group_id);
                                $st->execute();
                            }
                               
                        }
                        
                     

                        
                ?>
  </div>
  <div class="confirmation">
    <button  id="delete-group-btn" class="btn-danger">Supprimer le groupe</button>
    <p class="warning-text">Attention: Cette action est irréversible!</p>
  </div>
</div>
        <div class="admin-section">
            <h2>Utilisateurs</h2>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
                <?php
                if(isset($_GET['group_id'])){ 
                    
                    $group=$_GET['group_id'];
                    $SQL2="SELECT role from membres_groupes where group_id=?";
                    $stmt=$conn->prepare($SQL2);
                $stmt->bind_param("i",$group);
                $stmt->execute();
                $resu=$stmt->get_result();
                while($Row=$resu->fetch_assoc()){
                    $role=$Row['role'];
                $SQL1="SELECT membres_groupes.user_id,users.username,users.email,membres_groupes.role FROM users INNER JOIN membres_groupes  ON users.user_id=membres_groupes.user_id INNER JOIN groupes ON groupes.group_id = membres_groupes.group_id  WHERE groupes.createur=? or membres_groupes.role=? and groupes.group_id=?";
                $stmt=$conn->prepare($SQL1);
                $stmt->bind_param("ssi",$username,$role,$group);
                $stmt->execute();
                $res=$stmt->get_result();
                
                echo"
                <tr>
                    <td>
                    $username
                    </td>
                    <td>$email</td>
                    <td><span class='role admin'>Admin</span></td>
                    <td class='action-buttons'>
                        
                        
                    </td>
                </tr>";
                while($row=$res->fetch_assoc()){ 
                     $Role = $row['role']; 
    $newRole = ($Role === 'admin') ? 'membre' : 'admin';

                    echo"
                <tr>
                    <td>"
                   .$row['username'].
                    "</td>
                    <td>".$row['email']."</td>
                    <td>".$row['role']."</td>
                    <td class='action-buttons'>
                        <a href='admin.php?ID=".htmlspecialchars($row['user_id'])."&group_ID=".htmlspecialchars($group)." &data-role=".htmlspecialchars($newRole)."'>
                Passer ".ucfirst($newRole)."
            </a>
                        <a href='admin.php?id=".$row['user_id']."&group_id=".$group."&action=delete' style='color: red;'>Supprimer</a> 
                    </td>
                </tr>";
                }
            }
        }
            
        
                ?>
                
            </table>
        </div>
        </form>
    </div>
<script src="scr.js">
    </script>
</body>
</html>
