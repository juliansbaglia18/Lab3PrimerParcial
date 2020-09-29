<?php

class Usuario
{
    public $_email;
    public $_tipo;
    public $_clave;
    public $_foto;

    public function __construct($email, $tipo, $clave, $foto)
    {
        $this->_email = $email;
        $this->_tipo = $tipo;
        $this->_clave = $clave;
        $this->_foto = $foto;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __toString()
    {
        return "Email: " . $this->_email . "<br>Tipo: " . $this->_tipo . "<br>Clave: " . $this->_clave . "<br>Foto: " . $this->_foto . "<br><br>";
    }
}
class Retiro
{
    public $_patente;
    public $_hora;
    public $_dia;
    public $_email;

    public function __construct($patente, $hora, $dia, $email)
    {
        $this->_patente = $patente;
        $this->_hora = $hora;
        $this->_dia = $dia;
        $this->_email = $email;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __toString()
    {
        return "Patente: ". $this->_patente . ". Dia: ".$this->_dia . ". Hora: ".$this->_hora.". email: " . $this->_email;
    }

}
class Ingreso
{
    public $_patente;
    public $_hora;
    public $_email;

    public function __construct($patente, $hora, $email)
    {
        $this->_patente = $patente;
        $this->_hora = $hora;
        $this->_email = $email;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function __toString()
    {
        return "Patente: ". $this->_patente . "Hora: ".$this->_hora.". email: " . $this->_email;
    }

}