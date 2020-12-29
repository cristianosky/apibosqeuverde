<?php
require_once 'clases/respuestas.class.php';
require_once 'clases/plantas.class.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


$_respuestas = new respuestas;
$_plantas = new planta;


if($_SERVER['REQUEST_METHOD'] == "GET"){

    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listadeplanas = $_plantas->listPlantas($pagina);
        header("Content-Type: application/json");
        echo json_encode($listadeplanas);
        http_response_code(200);
    }else if(isset($_GET['id'])){
        $plantasid = $_GET['id'];
        $datosPlantas = $_plantas->obtenerPlanta($plantasid);
        header("Content-Type: application/json");
        echo json_encode($datosPlantas);
        http_response_code(200);
    } else if(isset($_GET["buscar"])){
        $nombrePlanta = $_GET['buscar'];
        $busquedaPlantas = $_plantas->buscarPlanta($nombrePlanta);
        header("Content-Type: application/json");
        echo json_encode($busquedaPlantas);
        http_response_code(200);
        
    }else if(isset($_GET["categoria"])){
        $nombrePlanta = $_GET['categoria'];
        $busquedaPlantas = $_plantas->buscarPlantaCate($nombrePlanta);
        header("Content-Type: application/json");
        echo json_encode($busquedaPlantas);
        http_response_code(200);
        
    }
    
}else if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos los datos al manejador
    $datosArray = $_plantas->post($postBody);
    //delvovemos una respuesta 
     header('Content-Type: application/json');
     if(isset($datosArray["result"]["error_id"])){
         $responseCode = $datosArray["result"]["error_id"];
         http_response_code($responseCode);
     }else{
         http_response_code(200);
     }
     echo json_encode($datosArray);
    
}else if($_SERVER['REQUEST_METHOD'] == "PUT"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos datos al manejador
    $datosArray = $_plantas->put($postBody);
      //delvovemos una respuesta 
   header('Content-Type: application/json');
   if(isset($datosArray["result"]["error_id"])){
       $responseCode = $datosArray["result"]["error_id"];
       http_response_code($responseCode);
   }else{
       http_response_code(200);
   }
   echo json_encode($datosArray);

} else if($_SERVER['REQUEST_METHOD'] == "DELETE"){
        
    if(isset($_GET['id'])){
        $plantasid = $_GET['id'];
        $datosPlantas = $_plantas->eliminarPlanta($plantasid);
        header("Content-Type: application/json");
        echo json_encode($datosPlantas);
        http_response_code(200);
    }
    
}else{
    header('Content-Type: application/json');
    $datosArray = $_respuestas->error_405();
    echo json_encode($datosArray);
}


?>  