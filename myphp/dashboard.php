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
   $_SESSION['email']=$email; 
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .stats-container {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 18px;
            font-weight: 600;
            color: #555;
        }

        .stat-card i {
            font-size: 30px;
            color: #3498db;
        }

        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .stats-container {
                flex-direction: column;
            }
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
        <h1>Dashboard</h1>

        <div class="stats-container">
            <div class="stat-card">
                <i class='bx bx-task'></i>
                <span>10 Tâches en cours</span>
            </div>
            <div class="stat-card">
                <i class='bx bxs-user'></i>
                <span>5 Membres actifs</span>
            </div>
            <div class="stat-card">
                <i class='bx bxs-check-circle'></i>
                <span>20 Tâches terminées</span>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="taskChart"></canvas>
        </div>
    </div>

    <script>
        
        const ctx = document.getElementById('taskChart').getContext('2d');
        const taskChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [{
                    label: 'Tâches terminées',
                    data: [2, 10, 12, 6, 6, 8],
                    backgroundColor: '#3498db'
                }, {
                    label: 'Tâches en cours',
                    data: [3, 7, 3, 11, 4, 5],
                    backgroundColor: '#e74c3c'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>
