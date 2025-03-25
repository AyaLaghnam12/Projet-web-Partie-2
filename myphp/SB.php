<?php
session_start();
$username=$_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
     </style>
    <title>Document</title>
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
</body>
</html>