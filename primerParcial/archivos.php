<?php
require_once './clases.php';
require __DIR__.'/vendor/autoload.php';
use \Firebase\JWT\JWT;
//composer require firebase/php-jwt <-en la terminal


//JSON
class Archivos{
    //Guardar en JSON
    static function guardarJson($name, $obj)
    {
        $array = Archivos::obtenerJson($name);

        if(isset($array)){
            //AGREGAR LA COMPROBACION DE SI YA EXISTEN
            $file = fopen("./".$name, "w");
            array_push($array, $obj);
    
            fwrite($file, json_encode($array));
            fclose($file);
        }else{
            $array2 = array();
            $file = fopen("./".$name, "w");
            array_push($array2, $obj);
    
            fwrite($file, json_encode($array2));
            fclose($file);
        }
    }
    
    //Traer archivo tipo JSON
    static function obtenerJson($ruta){
        if(file_exists($ruta)){
            $ar = fopen($ruta, "r");
            $lista = json_decode(fgets($ar));
            fclose($ar);
            return $lista;
        }
    }
    
    static function guardarSerializado($obj, $name)
    {
        $oldArray = Archivos::obtenerSerializado($name);

        $flag = false;
        foreach($oldArray as $a){
            if($a == $obj){
                $flag = true;
            }
        }
        if($flag == false){
            $file = fopen("./" . $name, "a");
    
            fwrite($file, serialize($obj).PHP_EOL);
    
            fclose($file);
        }else{
            echo "El objeto ya existe.";
            jump();
        }
    }

    static function obtenerSerializado($name)
    {
        $list = array();
        $file = fopen("./".$name, "r");

        while(!feof($file)){
            $obj = unserialize(fgets($file));
            
            if($obj != null)
            {
                array_push($list, $obj);
            }
        }
        
        fclose($file);

        return $list;
    }
    
    static function guardar($obj, $ruta)
    {
        $file = fopen("./" . $ruta, "a");
        
        fwrite($file, $obj.PHP_EOL);
        
        fclose($file);
    }

    static function obtener($name)
    {
        $list = array();
        $file = fopen("./".$name, "r");
    
        while(!feof($file)){
            $obj = fgets($file);
    
            if($obj != null)
            {
                array_push($list, $obj);
            }
        }
    
        fclose($file);
    
        return $list;
    }
    /***
     * en saveFile[0] devuelve si funciono.
     * en saveFile[1] guarda el nuevo nombre.
     */
    static function saveFile($file, $ruta, $type){
        $random = rand(1000,9999);
        
        $origin = $file["tmp_name"];
        $destination = (explode('.',$file['name']));
        $ext = array_pop($destination);

        $newName = implode($destination)."-".$random.".".$ext;

        if($ext == $type){
            
            $sePudo = move_uploaded_file($origin, $ruta.$newName);
            if($sePudo == 1){
                return array ($sePudo, $newName);
            }
        }else{
            echo "El tipo de archivo no coincide";
        }
    }

    /**
     * true si pudo,
     * false si no
     */
    static function deleteFile($file, $ruta, $dest){
        $retorno = false;
        $sePudo = copy("/".$file, "backups" ."/delete-".$file);
        if($sePudo == true){
            $retorno = unlink($ruta.$file);
        }else{
            echo "no encontro el archivo";
        }
        return $retorno;
    }

    static function waterMark($dest, $orig){
        $mark = imagecreatefromjpeg($orig);
        $im = imagecreatefromjpeg($dest);
        $retorno = imagecopymerge($im, $mark, 0,0, 0,0, imagesx($mark),imagesy($mark), 40);
        
        imagejpeg($im, $dest);
        imagedestroy($im);

        return $retorno;

        }    
}

class Token{
    /**
     * devuelve string con el token.
     */
    static function crearToken($payload, $key){

        $retorno = "";
        if(!empty($key) && !empty($payload)){
            $retorno = JWT::encode($payload, $key);
        }else{
            $retorno = "ERROR!!";
        }

        return $retorno;
    }

    static function obtenerToken($key){
        try{
            $token = $_SERVER['HTTP_TOKEN'];
            $decoded = JWT::decode($token,$key, array('HS256'));
            
            return $decoded;  
       
        }
        catch(\Throwable $th){
            return 'error';
        }
    }
    static function autenticarToken($key, $ok, $fail){
        if(Token::obtenerToken($key) == 'error')
        {
            echo $fail;
            return false;
        }else{
            echo $ok;
            return true;
        }
    }
}
function jump($count=1){
        $i = 0;
        while($i <= $count){
            echo "<br>";
            $i ++;
        }   
}
