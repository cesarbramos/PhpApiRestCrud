<?php
//SET HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'database.php';
$database = new Database();
$conn = $database->connect();

$datos = json_decode(file_get_contents("php://input"));
$msg['message'] = '';

if(isset($datos)){

	$getSql = "SELECT * FROM task WHERE id=$datos->id";
	$getStmt = $conn->prepare($getSql);
	$getStmt->execute();

	if(!empty($datos)){

		if($getStmt->rowCount() > 0){

		$row = $getStmt->fetchAll(PDO::FETCH_ASSOC);

		$taskTitle = isset($datos->title) ? $datos->title : $row['title'];
		$taskDescription = isset($datos->description) ? $datos->description : $row['description'];
		$taskPriority = isset($datos->priority) ? $datos->priority : $row['priority'];

		$updateSql = "UPDATE task SET title=:title, description=:description, priority=:priority 
		WHERE id=:id";

		$updateStmt = $conn->prepare($updateSql);
		$updateStmt->bindValue(":title", htmlspecialchars(strip_tags($taskTitle)), PDO::PARAM_STR);
		$updateStmt->bindValue(":description", htmlspecialchars(strip_tags($taskDescription)), PDO::PARAM_STR);
		$updateStmt->bindValue(":priority", htmlspecialchars(strip_tags($taskPriority)), PDO::PARAM_STR);
		$updateStmt->bindValue(":id", htmlspecialchars(strip_tags($datos->id)), PDO::PARAM_INT);
		
		if($updateStmt->execute()){
			$msg['message'] = "Task updated successfully";
		} else{
			$msg['message'] = "Appears to we're having problems! :(";
		}

		} else{
			$msg['message'] = "ID task doesn't exist!";
		}


	} else{
		$msg['message'] = "Empty, send me something";
	}

} else{
	$msg['message'] = "You wasn't send me anything";
}

echo json_encode($msg);