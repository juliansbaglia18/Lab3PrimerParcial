<?php
require_once "./archivos.php";
require_once "./clases.php";

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'];

$key = "pro3-parcial";

switch($path)
{
    case '/registro':
        pathRegistro($method);
    break;
    case '/login':
        pathLogin($method);
    break;
    case '/ingreso':
        pathIngreso($method);
    break;
    case '/retiro':
        pathRetiro($method);
    break;
    case '/users':
        pathUsers($method);
    break;

    break;
    default:
    echo "Path erroneo";
    break;
}
die();

function pathRegistro($method){
    if($method == 'POST'){
        $email = $_POST['email']??"Falta email";
        $tipo = $_POST['tipo']??"Falta tipo";
        $clave = $_POST['password']??"Falta password";
        $foto = $_FILES['imagen']['name']??"";
        $ruta =  "registro.txt";

        $flag = false;

        if($tipo != "admin" && $tipo != "user"){
            $tipo = "Invalido";
        }
        
        //verifico con el mail que no haya otro igual, sino no carga
        $list = Archivos::obtenerJson($ruta);
        if(isset($list)>0){
            foreach($list as $a){
                if($a->_email == $email){
                    echo "Email cargado previamente.";
                    $flag = true;
                }
            }
        }
        if($flag == false){
            //Verifico que en la foto haya algo porque sino falla
            if(!empty($foto)){
                $fotoNew = Archivos::saveFile($_FILES['imagen'], $ruta, "jpg")[1];
            }else{
                $fotoNew = "Falta foto.";
            }
            $usuario = new Usuario($email, $tipo ,$clave, $fotoNew);
            echo $usuario;
            Archivos::guardarJson($ruta, $usuario);
        }

    }else if($method == 'GET'){
        echo "Entro por GET.";
    }else{
        echo "Metodo no permitido";
    }
}

function pathLogin($method){
    if($method == 'POST'){
        echo "Entro por POST";
        jump();
        $email = $_POST['email']??"Error";
        $clave = $_POST['password']??"Error";
        $key = "primerparcial";
        $ruta =  "registro.txt";
        
        $flag = false;
        
        $list = Archivos::obtenerJson($ruta);
        
        if(isset($list)>0){
            foreach($list as $a){
                if($a->_email == $email && $a->_clave == $clave){
                    echo "Login de ".$a->_email . " correcto.";
                    jump();

                    $auxArray = array();
                    array_push($auxArray, $a->_email);
                    array_push($auxArray, $a->_tipo);

                    echo "Clave:<br>".Token::crearToken($auxArray, $key);
                    $flag = true;
                }
            }
            if(!$flag){
                echo "No se encontro a nadie con el usuario y clave indicada.";
            }
        }else{
            echo "No se cargo nada.";
        }
    }else{
        echo "Metodo no permitido.";
    }
}


function pathIngreso($method){
    $ruta = "Autos.txt";
    $key = "primerparcial";

    
    if($method == 'POST'){
        jump();
        if (Token::autenticarToken($key, "", "Token incorrecto")==false){
            return;
        }

        $array = Token::obtenerToken($key);
        $patente = $_POST['patente']??"Error";

        if($array[1] == "admin"){
            echo "Tipo de usuario invalido.";
            //return;
        }
        $hoy = date("h").date("h");

        $ingreso = new Ingreso($patente, $hoy, $array[0]);
        echo $ingreso;
        Archivos::guardarJson($ruta, $ingreso);

    }else if($method == 'GET'){
        echo "Entro por GET";
    }else{
        echo "Metodo no permitido";
    }
}

function pathRetiro($method){
    $ruta = "Autos.txt";
    $key = "primerparcial";
    
    if($method == 'POST'){
        echo "entro por POST";
        
    }
    else if($method == 'GET')
    {
        $patente = $_GET['patente']??"Error";
        $importe = 0;
        $flag = false;
        $fecha_egreso = date("h").date("h");

        if (Token::autenticarToken($key, "", "Token incorrecto")==false){
            return;
        }

        $array = Token::obtenerToken($key);
        $patente = $_POST['patente']??"Error";

        if($array[1] == "admin"){
            echo "Tipo de usuario invalido.";
            return;
        }
        $list = Archivos::obtenerJson($ruta);
        foreach($list as $a){
            if($a->_patente = $patente)
            {
                $importe = getdate()["hours"] - $a->_hora;
                $flag = true;
            }
        }

        if($flag == true){
            echo "asfdds";
        }   
    }
}

function pathUsers($method)
{
    if($method == 'POST'){
        $key = "primerparcial";

        $ruta =  "registro.txt";
        if (Token::autenticarToken($key, "", "Token incorrecto")==false){
            return;
        }
        $aux = "";

        $array = Token::obtenerToken($key);
        $list = Archivos::obtenerJson($ruta);

        $fotoNew = Archivos::saveFile($_FILES['imagen'],"", "jpg")[1];

        foreach($list as $a){
            if($array[0] == $a->_email){

                $aux = $a->_foto;
                $a->_foto = $fotoNew;
            }
        }

        if(Archivos::deleteFile($aux,"", "backups")){
            echo "El archivo se movio.";
        }else{
            echo"El archivo no se movio";
        }
        
        
    }else if($method == 'GET'){
        echo "entro por GET";
        jump();
    }else{
        echo "Metodo no permitido";
    }
}