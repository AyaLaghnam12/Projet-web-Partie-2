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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message']) && isset($_POST['group_id'])) {
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
    $group_id = $_POST['group_id'];
    
    if (!empty($message)) {
        // Get user ID
        $SQL5 = "SELECT user_id FROM users WHERE username=?";
        $stmt1 = $conn->prepare($SQL5);
        $stmt1->bind_param("s", $username);
        $stmt1->execute();
        $resul = $stmt1->get_result();
        
        if ($rOW = $resul->fetch_assoc()) {
            $user_id = $rOW['user_id'];
            
            // Insert message
            $SQL6 = "INSERT INTO messages(user_id, group_id, message, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
            $stmt2 = $conn->prepare($SQL6);
            $stmt2->bind_param("iis", $user_id, $group_id, $message);
            $stmt2->execute();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de Communication</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #ffffffcd;
            color: #333;
           


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

        
        .container {
            display: flex;
            width: 100%;
            height: 100vh;
            margin-left: 250px;
            justify-content: center;
            align-items: center;
        }

        .chat-container {
            width: 70%;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
           
        }

        .chat-header {
            background: #5c4f9c;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 18px;
        }

        .chat-box {
            padding: 15px;
            height: 300px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            border-bottom: 1px solid #ddd;
        }

        .chat-input {
            display: flex;
            padding: 10px;
            background: white;
            border-top: 1px solid #ddd;
        }

        .chat-input select,
        .chat-input input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            margin-right: 5px;
        }

        .chat-input select {
            width: 30%;
        }

        .chat-input input {
            flex: 1;
        }

        .chat-input button {
            background: #372a4e;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .message {
            padding: 8px;
            margin: 5px 0;
            border-radius: 5px;
            width: fit-content;
            max-width: 80%;
            font-size: 14px;
        }

        .sent {
            background: #ffcccc; /* Rose pour Utilisateur 1 */
            color: black;
            align-self: flex-end;
            text-align: right;
        }

        .received {
            background: #cce5ff; /* Bleu pour Utilisateur 2 */
            color: black;
            align-self: flex-start;
            text-align: left;
        }
        .message, .message-sent {
    padding: 8px;
    margin: 5px 0;
    border-radius: 5px;
    width: fit-content;
    max-width: 80%;
    font-size: 14px;
}
.sent, .message-sent {
    background: #ffcccc; /* Rose pour Utilisateur 1 */
    color: black;
    align-self: flex-end;
    text-align: right;
}
.message-time {
    font-size: 12px;
    color: #999;
    margin-top: 4px;
    text-align: right;
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

   
    <div class="container">
        <div class="chat-container">
            <form id="messageForm" method="post">
            <div class="chat-header">
                <h2>Chat de Projet</h2>
            </div>
            <div class="chat-box" id="chatBox">
            <div class="chat-messages">
            
            <?php
                    if(isset($_GET['group_id'])){
                        $group_id=$_GET['group_id'];
                        $SQL = "SELECT messages.message, messages.created_at, users.username 
                                FROM messages 
                                JOIN users ON messages.user_id = users.user_id
                                WHERE messages.group_id = ?
                                ORDER BY messages.created_at ASC";
                        $stmt = $conn->prepare($SQL);
                        $stmt->bind_param("i", $group_id);
                        $stmt->execute();
                        $res = $stmt->get_result();

                        while ($row = $res->fetch_assoc()) {
                            $senderName = htmlspecialchars($row['username']);
                            $message = htmlspecialchars($row['message']);
                            $messageTime = date("h:i A", strtotime($row['created_at']));

                            if ($senderName === $username) {
                                echo "<div class='message sent'><div class='sender-name'>vous</div>{$message}<div class='message-time'>{$messageTime}</div></div>";
                            } else {
                                echo "<div class='message received'><div class='sender-name'>{$senderName}</div>{$message}<div class='message-time'>{$messageTime}</div></div>";
                            }
                        }
                    }
                    ?>
            
              
            
            
            
              
                
            
        </div>
        </div>
            <div class="chat-input">
            <div id="userSelect" name="group-select">
                    <?php
                    $SQL4="SELECT user_id FROM users WHERE username=?";
                    $stmt=$conn->prepare($SQL4);
                        $stmt->bind_param("s",$username);
                        $stmt->execute();
                        $res=$stmt->get_result();
                            
                                if($row=$res->fetch_assoc()){
                                     
                        $user_id=$row['user_id'];
                    $SQL3="SELECT groupes.group_id,groupes.group_name FROM groupes INNER JOIN membres_groupes ON groupes.group_id=membres_groupes.group_id WHERE membres_groupes.user_id=? or groupes.createur=?";
                    $stmt=$conn->prepare($SQL3);
                    $stmt->bind_param("is",$user_id,$username);
                    $stmt->execute();
                    $resu=$stmt->get_result();
                    while($ROW=$resu->fetch_assoc()){
                        echo "<div class='group-link'>";
                        echo "<a class='group-link' href='messages.php?group_id=" . htmlspecialchars($ROW['group_id']) . "'>" . htmlspecialchars($ROW['group_name']) . "</a><br>";
                        echo "</div>";
                    }               
                }
                    ?>
                </div>
                <input type="hidden" name="group_id" value="<?php echo isset($_GET['group_id']) ? $_GET['group_id'] : ''; ?>">
                <input type="text" name="message" id="messageInput" placeholder="Écrire un message...">
                
                <button  type="submit" id="sendBtn" onclick="sendMessage()">Envoyer</button>
                
            </div>
        </div>
    </form>
    </div>
</div>


    <script src="scri.js">
        
    </script>

</body>
</html>