<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Get all on `tbl_question`
$app->get('/api/questions', function( Request $request, Response $response ){
	$sql = "SELECT * FROM `tbl_question` ORDER BY id DESC";

	try{
		$db = new db();

		$db = $db->connect();

		$stmt = $db->query( $sql );
		$questions = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo generate_json( $questions );
	} catch(PDOException $e ) {
		echo $e->getMessage();
	}

});

// Get single question
$app->get('/api/question/{id}', function( Request $request, Response $response ){
	$id = $request->getAttribute('id');

	$sql = "SELECT * FROM `tbl_question` WHERE id = $id";

	try{
		$db = new db();

		$db = $db->connect();

		$stmt = $db->query( $sql );
		$question = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo generate_json( $question );
	} catch(PDOException $e ) {
		echo $e->getMessage();
	}

});

// Add single question
$app->post('/api/question/add', function( Request $request, Response $response ){
	$question = $request->getParam('text');
	$answer = $request->getParam('answer');
	$hide = $request->getParam('hide');

	$sql = "INSERT INTO `tbl_question` (`text`, `answer`, `hide`) VALUES (:text, :answer, :hide) ";

	try{
		$db = new db();

		$db = $db->connect();

		$stmt = $db->prepare( $sql );
		$stmt->bindParam(':text', $question);
		$stmt->bindParam(':answer', $answer);
		$stmt->bindParam(':hide', $hide);

		$stmt->execute();

		$data = array( 'message' => 'Question has been added' );

		echo generate_json( $data );

	} catch(PDOException $e ) {
		echo $e->getMessage();
	}

});

// Update single question
$app->put('/api/question/update/{id}', function( Request $request, Response $response ){
	$id = $request->getAttribute('id');
	$question = $request->getParam('question');
	$answer = $request->getParam('answer');
	$hide = $request->getParam('hide');

	$sql = "UPDATE `tbl_question` SET question = :question, answer = :answer, hide = :hide WHERE id = $id";

	try{
		$db = new db();

		$db = $db->connect();

		$stmt = $db->prepare( $sql );
		$stmt->bindParam(':question', $question);
		$stmt->bindParam(':answer', $answer);
		$stmt->bindParam(':hide', $hide);

		$stmt->execute();

		$data = array( 'message' => 'Question has been updated' );

		echo generate_json( $data );

	} catch(PDOException $e ) {
		echo $e->getMessage();
	}

});

// Delete single question
$app->delete('/api/question/delete/{id}', function( Request $request, Response $response ){
	$id = $request->getAttribute('id');

	$sql = "DELETE FROM `tbl_question` WHERE id = $id";

	try{
		$db = new db();

		$db = $db->connect();

		$stmt = $db->prepare( $sql );
		$stmt->execute();
		$db = null;
		$data = array( 'message' => 'Question deleted' );

		echo generate_json( $data );

	} catch(PDOException $e ) {
		echo $e->getMessage();
	}

});