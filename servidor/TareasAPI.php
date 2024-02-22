<?php
require_once "TareasDB.php";
class TareasAPI {
public function API(){
header('Content-Type: application/JSON');
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
case 'GET':
$this->procesaListar();
break;
case 'POST':
$this->procesaGuardar();
break;
case 'PUT':
$this->procesaActualizar();
break;
case 'DELETE':
$this->procesaEliminar();
break;
default:
echo 'MÉTODO NO SOPORTADO';
break;
}
}
function response($code=200, $status="", $message="") {
http_response_code($code);
if( !empty($status) && !empty($message) ){
$response = array("status" => $status
,"message"=>$message);
echo json_encode($response, JSON_PRETTY_PRINT);
}
}
function procesaListar(){
if($_GET['action']=='tareas'){ 
$tareasDB = new TareasDB();
if(isset($_GET['id'])){ 
$response = $tareasDB->dameUnoPorId($_GET['id']);
echo json_encode($response,
JSON_PRETTY_PRINT);
}else{
$response = $tareasDB->dameLista(); 
echo json_encode($response,
JSON_PRETTY_PRINT); 
}
}else{
$this->response(400);
}
}
function procesaGuardar(){
if($_GET['action']=='tareas'){ 
$obj = json_decode( file_get_contents('php://input') );
$objArr = (array)$obj;
if (empty($objArr)){
$this->response(422,"error","Nothing to add. Check
json");
}else if(isset($obj->titulo)){
$tareasDB = new TareasDB();
$tareasDB->guarda( $obj->titulo, $obj->descripcion,
$obj->prioridad );
$this->response(200,"success","new record added");
}else{
$this->response(422,"error","The property is not
defined");
}
} else{
$this->response(400);
}
}
function procesaActualizar() {
if( isset($_GET['action']) && isset($_GET['id']) ){
if($_GET['action']=='tareas'){
$obj = json_decode( file_get_contents('php://input') );
$objArr = (array)$obj;
if (empty($objArr)){
$this->response(422,"error","Nothing to add. Check
json");
}else if(isset($obj->titulo)){
$tareasDB = new TareasDB();
$tareasDB->actualiza($_GET['id'], $obj->titulo,
$obj->descripcion, $obj->prioridad );
$this->response(200,"success","Record updated");
}else{
$this->response(422,"error","The property is not
defined");
}
exit;
}
}
$this->response(400);
}
function procesaEliminar(){
if( isset($_GET['action']) && isset($_GET['id']) ){
if($_GET['action']=='tareas'){
$tareasDB = new TareasDB();
$tareasDB->elimina($_GET['id']);
$this->response(204);
exit;
}
}
$this->response(400);
}
}