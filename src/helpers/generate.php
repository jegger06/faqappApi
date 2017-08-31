<?php 

function generate_json($data){
	header("access-control-allow-origin: *");
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	header('Content-type: application/json');
	echo json_encode($data);
}