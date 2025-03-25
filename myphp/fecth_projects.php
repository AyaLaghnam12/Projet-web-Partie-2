<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode([]); // Return an empty array if the user is not logged in
    exit();
}

$server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "utilisateurs";
$conn = mysqli_connect($server, $db_user, $db_pass, $db_name);

// Check if the group_id is provided in the GET request
if (isset($_POST['group-select'])) {
    $group_id = $_POST['group_select'];
    $SQL2="SELECT user_id FROM username=?";
    $stmt1=$conn->prepare($SQL2);
    $stmt1->bind_param("s", $username);
    $stmt1->execute();
    $resu = $stmt1->get_result();
    if($Row=$resu->fetch_assoc()){
        $user_id=$Row['user_id'];
    }
    // Prepare SQL query to get projects for the given group
    $SQL = "SELECT projects.project_id, projects.project_name 
            FROM projects
            INNER JOIN groupes ON projects.project_id = groupes.project_id
            WHERE projects.group_id = ? and projects.user_id=?";

    $stmt = $conn->prepare($SQL);
    $stmt->bind_param("ii", $group_id,$user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch projects and store in an array
    $projects = [];
    while ($row = $result->fetch_assoc()) {
        $projects[] = [
            'project_id' => $row['project_id'],
            'project_name' => $row['project_name']
        ];
    }

    // Return projects as a JSON response
    echo json_encode($projects);
} else {
    echo json_encode([]); // Return an empty array if no group_id is provided
}
?>
