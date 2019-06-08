<?php 
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

require_once('database.php');

$dbConnection = new Database();
$conn = $dbConnection->connect();

 if(isset($_GET['id'])){

 	$postId = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
 		'options' => [
 			'default' => 'all_post',
 			'min_range' => 1

 		]
 	]);
 
 }
  else{
 	$postId = 'all_post';
 }

 $sql = is_numeric($postId) ? "SELECT * FROM task where id=$postId" : "SELECT * FROM task ORDER BY priority ASC";
 $stmt = $conn->prepare($sql);
 $stmt->execute();
 if($stmt->rowCount() > 0){
 	$allPost = [];
 	$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

 	foreach ($row as $r) {
 		
 		$singlePost = [
 			'id' => $r['id'],
 			'title' => $r['title'],
 			'priority' => $r['priority'],
 			'description' => $r['description']
 		];

 		array_push($allPost, $singlePost);

 	}
 echo json_encode($allPost);

 } else{
 	echo json_encode(['message' => 'No user found']);
 }
