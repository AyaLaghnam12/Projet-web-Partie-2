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
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        /* Add relevant styles here (you can reuse some of your previous styles) */
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
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
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
            background: rgba(255, 255, 255, 0.1);
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
            overflow-y: auto;
            height: 100vh;
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
        
        .content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        
        .members-list, .groups-list, .invitations-list {
            flex: 1;
            min-width: 300px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #eee;
        }
        
        h2 {
            color: #3498db;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        ul {
            list-style-type: none;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .content ul li {
            padding: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0;
        }
        
        .content ul li:last-child {
            border-bottom: none;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        button.remove {
            background-color: #e74c3c;
        }
        
        button.remove:hover {
            background-color: #c0392b;
        }
        
        .invite-form {
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 15px;
            margin-top: 20px;
            flex-basis: 100%;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        select, input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .success-message, .error-message {
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
            display: none;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .group-item {
            background: #f8f9fa;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #3498db;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .group-item button {
            background: none;
            border: none;
            color: #e74c3c;
            cursor: pointer;
            font-size: 18px;
        }

        .group-item button:hover {
            background: rgba(231, 76, 60, 0.1);
            transform: scale(1.1);
        }
        
        .empty-list {
            text-align: center;
            padding: 20px;
            color: #777;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .content {
                flex-direction: column;
            }
            
            .members-list, .groups-list, .invitations-list {
                width: 100%;
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
    </style>
    <title>My Groups</title>
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
        <h1><i class='bx bxs-group' style="margin-right: 10px;"></i>Gestion des Invitations</h1>
        
        <div class="content">
            <div class="members-list">
                <h2>Membres</h2>
                <ul id="members">
                    <?php
                if(isset($_GET['group_id'])){
                    $id=$_GET['group_id'];
                    $SQL9="SELECT users.username FROM users
            INNER JOIN membres_groupes ON users.user_id = membres_groupes.user_id
            WHERE membres_groupes.group_id=?";
            $stmt=$conn->prepare($SQL9);
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $result1=$stmt->get_result();
            if ($result1->num_rows > 0) {
                while ($row = $result1->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($row['username']) . "</li>";
                }
            }  
            }
                ?>
                        </ul>
            </div>
            
            <div class="groups-list">
                <h2>Groupes</h2>
                <?php
                $SQL6="SELECT user_id FROM users WHERE username=?";
                $stmt=$conn->prepare($SQL6);
                    $stmt->bind_param("s",$username);
                    $stmt->execute();
                    $res=$stmt->get_result();
                        
                            if($row=$res->fetch_assoc()){
                                 
                            
                        
                        $user_id=$row['user_id'];
                        $SQL7="SELECT group_id FROM membres_groupes where user_id=? ";
                        $stmt=$conn->prepare($SQL7);
                        $stmt->bind_param("i",$user_id);
                        $stmt->execute();
                        $resul=$stmt->get_result();
                        if($resul->num_rows > 0){
                            while($ROw=$resul->fetch_assoc()){
                                $group_id = $ROw['group_id'];
                                 $SQL8="SELECT group_id,group_name FROM groupes WHERE group_id=?";
                        $stmt=$conn->prepare($SQL8);
                        $stmt->bind_param("i",$group_id);
                        $stmt->execute();
                        $result=$stmt->get_result();
                        while($ROW=$result->fetch_assoc()){
                           
                            echo "<div class='group-link'>";
                            echo "<a class='group-link' href='amis.php?group_id=" . htmlspecialchars($group_id) . "'>" . htmlspecialchars($ROW['group_name']) . "</a><br>";
                            echo "</div>";
                        }
                        }
                    }
                        
                }
                        

                        
                ?>
                
            </div>
            
            
            <div class="invite-form">
    <h2>Créer un Groupe</h2>
    <form id="invitation-form" action="amis.php" method="post">
        <div class="form-group">
            <label for="group-name">Nom du Groupe</label>
            <input type="text" id="group-name" name="group" required>
            <?php
         $server="localhost";
         $db_user="root";
         $db_pass="";
         $db_name="utilisateurs";
         $conn="";
         $conn=mysqli_connect($server,$db_user,$db_pass,$db_name);
        if( isset($_POST['group'])){
                    
                    $group=$_POST['group'];
                    $username=$_SESSION['username'];
                    $SQL="INSERT IGNORE INTO groupes(group_name,created_at,createur) VALUES(?,CURRENT_TIMESTAMP,?)";
                    $stmt=$conn->prepare($SQL);
                    $stmt->bind_param("ss",$group,$username);
                    $stmt->execute();
                }
                ?>
        </div>
        
        <button type="submit">Créer le Groupe</button>
    </form>
    </div>
                <div class="select-group-form">
                <h2>Sélectionner un Groupe</h2>
                <form id="select-group-form" method="post" action="amis.php">
                    <div class="form-group">
                        <label for="membre-select">Nom du Membre</label>
                        <input id="membre-select"  name="membre-select" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="group-select">Sélectionner le Groupe</label>
                        <select id="group-select" name="group-select" required>
                        <?php
                                $server = "localhost";
                                $db_user = "root";
                                $db_pass = "";
                                $db_name = "utilisateurs";
                                $conn = mysqli_connect($server, $db_user, $db_pass, $db_name);
                         $username=$_SESSION['username'];
                        $SQL4="SELECT user_id FROM users where username=?";
                        $stmt=$conn->prepare($SQL4);
                        $stmt->bind_param("s",$username);
                        $stmt->execute();
                        $res=$stmt->get_result();
                        if($res->num_rows > 0){
                            while($row=$res->fetch_assoc()){
                                 
                           
                        $user_id=$row['user_id'];
                        $SQL5="SELECT groupes.group_id,groupes.group_name FROM groupes  WHERE groupes.createur=?";
                        $stmt=$conn->prepare($SQL5);
                        $stmt->bind_param("s",$username);
                        $stmt->execute();
                        $resu=$stmt->get_result();
                        if($resu->num_rows > 0){
                            while ($row = $resu->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($row['group_id']) . "'>" . htmlspecialchars($row['group_name']) . "</option>";
                            }
                        }
                    }
                }
                        
                        ?>
                        
                        </select>
                        <?php
                        
                            if(isset($_POST['membre-select']) && isset($_POST['group-select'])){
                                $membre = trim($_POST['membre-select']);
                                $group_id = intval($_POST['group-select']);
                            $SQL1="SELECT user_id FROM users where username=?";
                            $stmt=$conn->prepare($SQL1);
                            $stmt->bind_param("s",$membre);
                            $stmt->execute();
                            $res=$stmt->get_result();
                            
                            if($row = $res->fetch_assoc()){
                               
                                     $user_id=$row['user_id'];
                                     $SQL3="INSERT IGNORE INTO membres_groupes(user_id,group_id,role,joined_at) VALUES(?,?,'membre',CURRENT_TIMESTAMP)";
                                     $stmt=$conn->prepare($SQL3);
                                     $stmt->bind_param("ii",$user_id,$group_id);
                                     $stmt->execute();
                            }
                            
                           
                             
                        }
                       
                        ?>
                        
                    </div>
                    <button type="submit" name="add_to_group">Ajouter au groupe</button>
                    
                
                
                </form>
            </div>
        </div>
        </div>

   
    <script src="scrip.js"></script>
</body>
</html>