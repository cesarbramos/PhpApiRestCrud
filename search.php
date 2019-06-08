<?php 
#SET HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once("database.php");

$database = new Database();
$conn = $database->connect();

$msg['message'] = '';

if(isset($_GET['q'])){

	$search = $_GET['q'];

	$sql = "SELECT * FROM task WHERE title LIKE '%$search%'";

	$stmt = $conn->prepare($sql);

	$stmt->execute();

	if($stmt->rowCount() !== 0){

		$allTask = [];
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

		foreach ($row as $r) {
			$singlePost = [
				"id" => $r['id'],
				"title" => $r['title'],
				"description" => $r['description'],
				"priority" => $r['priority']

			];

			array_push($allTask, $singlePost);
		}

		echo json_encode($allTask);
		

	} else {
		$msg['message'] = "Task no found";

		echo json_encode($msg);
	}


} else{
	$msg['message'] = "Any";

	echo json_encode($msg);
}



