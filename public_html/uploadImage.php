<?php
 try {
     
if ($_FILES) {  
  

    

$target_path = "image/mensajeros/";
$nombreImagen = basename($_FILES['file']['name']);
$nombreImagen = $_POST['id'].".jpg";
$target_path = $target_path . $nombreImagen;
 if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
     
   $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_domigo";

    $respuesta = array();

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
     $id = $_POST['id'];
     $ruta = "http://" . $_SERVER['HTTP_HOST'] . "/domigo_backend-master/public_html/".$target_path;
     $sql = "UPDATE usuario SET imagen='$ruta' WHERE  id= $id";


       if ($conn->query($sql) === TRUE) {
                echo "El archivo ". basename( $_FILES['file']['name']). " ha sido subido";
       }
} else{
echo "Ha ocurrido un error";
}

}else{
        echo "Ha ocurrido un error";
    }

} catch (Exception $exc) {
 echo 'Error de aplicacion: ' . $exc->getMessage();
 }
?>