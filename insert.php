<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('database.php');

$database = new Database();
$conn = $database->connect();

$datos = json_decode(file_get_contents("php://input"));

$msg['message'] = '';

if(isset($datos->title) && isset($datos->priority) && isset($datos->description)){

	if(!empty($datos->title) && !empty($datos->priority) && !empty($datos->description)){
	    
	    $query = "INSERT INTO task(title,priority,description) VALUES(:title,:priority,:description)";
	    $stmt = $conn->prepare($query);
	    $stmt->bindValue(':title', htmlspecialchars($datos->title), PDO::PARAM_STR);
	    $stmt->bindValue(':priority', htmlspecialchars($datos->priority), PDO::PARAM_STR);
	    $stmt->bindValue(':description', htmlspecialchars($datos->description), PDO::PARAM_STR);

	    if( $stmt->execute()){
	    	$msg['message'] = 'Datos insertados correctamente';
	    } else{
	    	$msg['message'] = 'Datos no insertados :(';

	    }
	} else{
	    	$msg['message'] = 'Algunos campos pueden estar vacios';
	}

} else{
	    	$msg['message'] = 'Por favor inserte todos los campos';

}
echo json_encode($msg);