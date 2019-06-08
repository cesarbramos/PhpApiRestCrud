<?php
// SET HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'database.php';
$database = new Database();
$conn = $database->connect();

$datos = json_decode(file_get_contents("php://input"));

$msg['message'] = '';

if(!empty($datos)){

$sqlFindAll = "SELECT * FROM task WHERE id=$datos->id";

$stm = $conn->prepare($sqlFindAll);
$stm-> execute();
if($stm->rowCount() > 0 ){

		
		if(isset($datos->id) && !empty($datos->id)){
			$sql = "DELETE FROM task WHERE id=$datos->id";
			$stmt = $conn->prepare($sql);
			if($stmt->execute()){
				$msg['message'] = "Task #$datos->id was delete!";
				
			} else{
				$msg['message'] = "We can't execute the action";
			}
			
		} else{
			$msg['message'] = "Id no most be null or empty";
		}
	
	} else{
		$msg['message'] = "Do not exist task id: $datos->id";
	}
} else{
		$msg['message'] = "Please send me data";
	}



echo json_encode($msg);