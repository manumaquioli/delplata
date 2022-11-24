<?php

try{
    include_once "conexionbd.php";
    $GLOBALS['connDB'] = $connDB;
}
catch(Exception $e){
    echo "Error de conexión con la base de datos.";
    return;
}
$GLOBALS['prohibidos'] = 0;
$GLOBALS['useremail'] = 0;
$GLOBALS['email'] = 0;
$GLOBALS['tuEmail'] = 0;
$GLOBALS['existente'] = 0;
$GLOBALS['userlength'] = 0;
$GLOBALS['spaces'] = 0;
$GLOBALS['passwordlength'] = 0;
$GLOBALS['userpass'] = 0;
$GLOBALS['nombrelength'] = 0;
$GLOBALS['apellidolength'] = 0;
$GLOBALS['cilength'] = 0;
$GLOBALS['ci'] = 0;
$GLOBALS['cedulaexistente'] = 0;
$GLOBALS['tellength'] = 0;
$GLOBALS['tel'] = 0;
$GLOBALS['celularexistente'] = 0;
$GLOBALS['edad'] = 0;
$GLOBALS['errorSuspendido'] = 0;
$GLOBALS['errorTipo'] = 0;
$GLOBALS['errorCargaUser'] = 0;
$GLOBALS['ciudadlength'] = 0;
$GLOBALS['calle1length'] = 0;
$GLOBALS['calle2length'] = 0;
$GLOBALS['calle3length'] = 0;
$GLOBALS['nrolength'] = 0;
$GLOBALS['updateerror'] = 0;
$GLOBALS['updExistenteCorreo'] = 0;
$GLOBALS['updExistenteTel'] = 0;

//Clase Usuario con todos sus atributos.
class Usuario{
    private $username;
    private $password;
    private $tipo;
    private $email;
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $fechaNac;
    private $suspendido;
    private $ciudad;
    private $calle1;
    private $calle2;
    private $calle3;
    private $nro;

//Constructor de Usuario
    public function __construct($user, $pass, $tip, $mail, $nom, $ape, $ci, $tel, $fecha){
        //Conversión de datos para que se puedan incluir en sentencias SQL sin problemas.
        $this->setUser($user);
        $this->setTipo($tip);
        $this->setCedula($ci);
        $this->setPass($pass);
        $this->setEmail($mail);
        $this->setNombre($nom);
        $this->setApellido($ape);
        $this->setTel($tel);
        $this->setFecha($fecha);
        $this->setSuspendido(0);
        $GLOBALS['userLoaded'] = 0;
    }
    
    //Ingresar en la BD--------------------------------------------------------------------------------------------------------------------------------------------------
    public function ingresarUsuarioBd($user, $pass, $tipo, $email, $nombre, $ape, $ci, $tel, $fechaNac, $suspendido){
        if($user != null && $pass != null && $tipo != null && $email != null && $nombre != null && $ape != null && $ci != null && $tel != null && $fechaNac != null && ($suspendido==1 || $suspendido == 0)){
            //----Consulto si ya hay datos de la persona ingresados----------------------------------
            $consultaci = mysqli_query($GLOBALS['connDB'], "SELECT ci FROM persona");
            $arrayconsultaci = array();
            $ciPersonaExisteBool = 0;
            while($fila = mysqli_fetch_array($consultaci)){
                $arrayconsultaci[] = $fila;
            }
            foreach($arrayconsultaci as $valor){
                //Verifica si la cédula ya está en uso.
                if($this->getCi() == $valor['ci']){
                    $ciPersonaExisteBool = 1;
                }
            }
            if($GLOBALS['existente'] == 0 && $GLOBALS['celularexistente'] == 0 && $GLOBALS['cedulaexistente'] == 0){
                //---------------------------------------------------------//
                if($ciPersonaExisteBool == 1){
                    $pass = password_hash($this->getPassword(), PASSWORD_DEFAULT);
                    $sqlUser = "INSERT INTO usuario VALUES ('{$this->getUser()}', '$pass', '{$this->getCi()}', '{$this->getTipo()}', '{$this->getSuspendido()}')";
                    $sqlTipo = "INSERT INTO {$this->getTipo()} VALUES('{$this->getUser()}')";
                    if($GLOBALS['connDB']->query($sqlUser) === TRUE && $GLOBALS['connDB']->query($sqlTipo) === TRUE){
                        //Usuario cargado correctamente en la tabla usuario y 
                        //La tabla de su tipo de usuario (cliente/empleado/admin)
                        $GLOBALS['errorCargaUser'] = 0;
                    }else{
                        //En caso de haberse cargado datos solamente en la tabla usuario 
                        //Los elimino para no tener un registro en usuario que no está
                        //En la tabla del tipo de usuario (cliente/empleado/admin)
                        $sqlDel = "DELETE FROM usuario WHERE nomb_usu='".$this->getUser()."'";
                        $GLOBALS['connDB']->query($sqlDel);
                        $GLOBALS['errorCargaUser'] = 1;
                    }
                }else if($ciPersonaExisteBool == 0){
                    $pass = password_hash($this->getPassword(), PASSWORD_DEFAULT);
                    $sqlper = "INSERT INTO persona (ci, nombre, apellido, fecha_nac, correo) VALUES ('{$this->getCi()}', '{$this->getNombre()}', '{$this->getApe()}', '{$this->getFechaNac()}', '{$this->getEmail()}')";
                    $sqluser = "INSERT INTO usuario VALUES ('{$this->getUser()}', '{$pass}', '{$this->getCi()}', '{$this->getTipo()}', '{$this->getSuspendido()}')";
                    $sqlTipo = "INSERT INTO {$this->getTipo()} VALUES ('{$this->getUser()}')";
                    $sqltel = "INSERT INTO tel VALUES ('{$this->getCi()}', '{$this->getTel()}')";
                    try{
                        if($GLOBALS['connDB']->query($sqlper) === TRUE){
                            //Se guardaron datos en persona
                            if($GLOBALS['connDB']->query($sqltel) === TRUE){
                                //Se guardaron datos en tel
                                if($GLOBALS['connDB']->query($sqluser) === TRUE){
                                    //Se guardaron datos en usuario
                                    if($GLOBALS['connDB']->query($sqlTipo) === TRUE){
                                        //Se guardaron datos en cliente/empleado/adm
                                        $GLOBALS['errorCargaUser'] = 0;
                                    }else{
                                        //En caso de haberse cargado datos solamente en la tabla usuario 
                                        //Los elimino para no tener un registro en usuario que no está
                                        //En la tabla del tipo de usuario (cliente/empleado/admin)
                                        $sqlDel = "DELETE FROM usuario WHERE nomb_usu='".$this->getUser()."'";
                                        $GLOBALS['connDB']->query($sqlDel);
                                        $GLOBALS['errorCargaUser'] = 1;
                                    }
                                }else{
                                    //No se guardaron datos en usuario
                                    $GLOBALS['errorCargaUser'] = 1;
                                }
                            }else{
                                //En caso de haberse cargado datos en la tabla persona
                                //Pero no en la tabla tel, elimino los datos de la tabla
                                //Persona, NO debe haber una persona sin número de teléfono
                                $sqlDel = "DELETE FROM persona WHERE ci='".$this->getCi()."'";
                                $GLOBALS['connDB']->query($sqlDel);
                                $GLOBALS['errorCargaUser'] = 1;
                            }
                        }else{
                        //No se guardaron datos en persona
                        $GLOBALS['errorCargaUser'] = 1;
                        }
                    }
                    catch(Exception $e){
                        echo "ERROR CON LA BASE DE DATOS";
                    }
                }
            }
        }else{
            //$GLOBALS['a'] = $this->getUser(). " | ".$this->getPassword(). " | " . $this->getTipo(). " | ". $this->getEmail(). " | " .$this->getNombre(). " | ".$this->getApe(). " | ".$this->getCi(). " | ". $this->getTel(). " | ".$this->getFechaNac(). " | ". $this->getSuspendido();
            return;
        }
    }

    //Update usuario-----------------------------------------------------------------------------------------------------------------------------------------------------
    public function updateUsuarioBd($ci, $email, $tel, $ciudad, $calle1, $calle2, $calle3, $nro, $tipo, $pass, $tipoCambio){
        try{
            $consultacorreo = mysqli_query($GLOBALS['connDB'], "SELECT correo FROM persona WHERE ci!='$ci'");
            $arrayconsultacorreo = array();
            while($fila = mysqli_fetch_array($consultacorreo)){
                $arrayconsultacorreo[] = $fila;
            }
            foreach($arrayconsultacorreo as $valor){
                //Verifica si el correo ingresado no está en uso.
                if($email == $valor['correo']){
                    $GLOBALS['updExistenteCorreo'] = 1;
                    break;
                }
            }

            $consultatel = mysqli_query($GLOBALS['connDB'], "SELECT num FROM tel WHERE ci != '$ci'");
            $arrayconsultatel = array();
            while($fila = mysqli_fetch_array($consultatel)){
                $arrayconsultatel[] = $fila;
            }
            foreach($arrayconsultatel as $valor){
                //Verifica si el número telefónico ya está en uso.
                if($tel == $valor['num']){
                    $GLOBALS['updExistenteTel'] = 1;
                    break;
                }
            }
                if(!isset($_SESSION['logUsuario'])){
                    session_start();
                }
                if($GLOBALS['prohibidos']==1){
                    return $GLOBALS['prohibidos'] = 1;
                }
                if($this->getUser()==$_SESSION['logUsuario'] && $tipoCambio==2){
                    return $GLOBALS['updateerror'] = 1;
                }
                if($ci != null){
                    if($email != null && $GLOBALS['updExistenteCorreo'] != 1 && $GLOBALS['tuEmail']!=1 && $GLOBALS['email']!=1){
                        mysqli_query($GLOBALS['connDB'], "UPDATE persona SET correo='$email' WHERE ci='$ci'");
                    }
                    if($tel != null && $GLOBALS['updExistenteTel'] !=1 && $GLOBALS['tellength']!=1 && $GLOBALS['tel']!=1){
                        mysqli_query($GLOBALS['connDB'], "UPDATE tel SET num='$tel' WHERE ci='$ci'");
                    }
                    if($GLOBALS['tuCiudad']!=1){
                        mysqli_query($GLOBALS['connDB'], "UPDATE persona SET ciudad='$ciudad' WHERE ci='$ci'");
                    }
                    if($GLOBALS['tuCalle1']!=1){
                        mysqli_query($GLOBALS['connDB'], "UPDATE persona SET calle1='$calle1' WHERE ci='$ci'");
                    }
                    if($GLOBALS['tuCalle2']!=1){
                        mysqli_query($GLOBALS['connDB'], "UPDATE persona SET calle2='$calle2' WHERE ci='$ci'");
                    }
                    if($GLOBALS['tuCalle3']!=1){
                        mysqli_query($GLOBALS['connDB'], "UPDATE persona SET calle3='$calle3' WHERE ci='$ci'");
                    }
                    if($GLOBALS['tuNro']!=1){
                        mysqli_query($GLOBALS['connDB'], "UPDATE persona SET nro='$nro' WHERE ci='$ci'");
                    }
                    if(isset($GLOBALS['errTipo']) && $GLOBALS['errTipo']!=1 && $GLOBALS['tipoCmp'] == 0){
                        mysqli_query($GLOBALS['connDB'], "UPDATE usuario SET tipo='$tipo' WHERE ci='$ci' AND nomb_usu='{$this->getUser()}'");
                        mysqli_query($GLOBALS['connDB'], "DELETE FROM {$this->getTipo()} WHERE nomb_usu='{$this->getUser()}'");
                        mysqli_query($GLOBALS['connDB'], "INSERT INTO {$tipo} VALUES ('{$this->getUser()}')");
                    }
                    if($pass!=null){
                        mysqli_query($GLOBALS['connDB'], "UPDATE usuario SET pass='$pass' WHERE ci='$ci' AND nomb_usu='{$this->getUser()}'");
                    }
                }
                else{
                    $GLOBALS['updateerror'] = 1;
                }
        }
        catch(Exception $e){
            echo "ERROR BASE DE DATOS";
        }
    }
    
    //Setters de todos los atributos de Usuario.

    //Setter de nombre de usuario----------------------------------------------------------------------------------------------------------------------------------------
    public function setUser($user){
        $prohibidosUser = 0;
        $spacesUsuario = 0;
        $usuarioExistente = 0;
        $user = mysqli_real_escape_string($GLOBALS['connDB'], $user);
        $user = trim($user);
        if(strpos($user, "<") !== false || strpos($user, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosUser = 1;       
            return;
        }
        if(strlen($user) < 5 || strlen($user) > 16){
            $GLOBALS['userlength'] = 1;
            return;
        }
        //Si el usuario ingresado es un email se redireccionará a un error.
        if(filter_var($user, FILTER_VALIDATE_EMAIL)){
            $GLOBALS['useremail'] = 1;
            return;
        }
        if(strpos($user, ' ')) {
            $GLOBALS['spaces'] = 1;
            $spacesUsuario = 1;
            return;
        }
        $consultanomb = mysqli_query($GLOBALS['connDB'], "SELECT nomb_usu FROM usuario");
        $arrayconsultanomb = array();
        while($fila = mysqli_fetch_array($consultanomb)){
            $arrayconsultanomb[] = $fila;
        }
        //Recorre el arreglo de la consulta (nombres de usuario).
        foreach($arrayconsultanomb as $valor){
            //Verifica si el nombre de usuario ingresado no está en uso.
            if($user == $valor['nomb_usu']){
                //Si está en uso el usuario, redireccionará a error.
                //header("location:../../enlaces/register.php?error=existente");
                $GLOBALS['existente'] = 1;
                $usuarioExistente = 1;
                break;
                return;
            }
        }
        if($prohibidosUser!=1 && $GLOBALS['userlength']!=1 && $spacesUsuario !=1){
            $this->username = $user;
        }
    }

    //Setter de pass-------------------------------------------------------------------------------------------------------------------------------------------------------
    public function setPass($pass){
        $passwordSpaces = 0;
        $pass = trim($pass);
        if(strlen($pass) < 8 || strlen($pass) > 24){
            $GLOBALS['passwordlength'] = 1;   
            return;
        }
        if($pass == $this->getUser()){
            $GLOBALS['userpass'] = 1;
            return;
        }
        if(strpos($pass, ' ')){
            $GLOBALS['spaces'] = 1;
            $passwordSpaces = 1;
            return;
        }
        if($GLOBALS['passwordlength']!=1 && $GLOBALS['userpass']!=1 && $passwordSpaces!=1){
            $this->password = $pass;
        }
    }
    //Setter de tipo-------------------------------------------------------------------------------------------------------------------------------------------------------
    public function setTipo($tip){
        $prohibidosTipo = 0;
        $tip = mysqli_real_escape_string($GLOBALS['connDB'], $tip);
        if(strpos($tip, "<") !== false || strpos($tip, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosTipo = 1;
            return;
        }
        if($tip!="empleado" && $tip!="adm" && $tip!="cliente"){
            if($prohibidosTipo!=1){
                if(strcmp($tip, "0") == 0){
                    $this->tipo = "cliente";
                }else if(strcmp($tip, "1") == 0){
                    $this->tipo = "empleado";
                }else if(strcmp($tip, "2") == 0){
                    $this->tipo = "adm";
                }else{
                    $GLOBALS['errorTipo'] = 1;
                    return;
                }
            }
        }
        if($tip=="empleado"){$this->tipo = "empleado";}
        if($tip=="cliente"){$this->tipo = "cliente";}
        if($tip=="adm"){$this->tipo = "adm";}
    }
    //Setter de email------------------------------------------------------------------------------------------------------------------------------------------------------
    public function setEmail($mail){
        $prohibidosEmail = 0;
        $mailexiste = 0;
        $mail = mysqli_real_escape_string($GLOBALS['connDB'], $mail);
        if(strpos($mail, "<") !== false || strpos($mail, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosEmail = 1;
            return;
        }
        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
            $GLOBALS['email'] = 1;
            return;
        }else{
            $GLOBALS['email']=0;
        }
        $consultacorreo = mysqli_query($GLOBALS['connDB'], "SELECT correo FROM persona WHERE ci != '{$this->getCi()}'");
        $arrayconsultacorreo = array();
        while($fila = mysqli_fetch_array($consultacorreo)){
            $arrayconsultacorreo[] = $fila;
        }
        foreach($arrayconsultacorreo as $valor){
            //Verifica si el correo ingresado no está en uso.
            if($mail == $valor['correo']){
                $GLOBALS['existente'] = 1;
                $mailexiste = 1;
                break;
                return;
            }
        }
        if($GLOBALS['email']!=1 && $prohibidosEmail!=1){
            $this->email = $mail; 
        }
    }
    //Setter de nombre-----------------------------------------------------------------------------------------------------------------------------------------------------
    public function setNombre($nom){
        $prohibidosNombre = 0;
        $nom = mysqli_real_escape_string($GLOBALS['connDB'], $nom);
        if(strpos($nom, "<") !== false || strpos($nom, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosNombre = 1;
            return;
        }
        // Si el tamaño del nombre no cumple con los requisitos:
        if(strlen($nom) < 2 || strlen($nom) > 24){
            $GLOBALS['nombrelength'] = 1;
            return;
        }
        if($prohibidosNombre!=1 && $GLOBALS['nombrelength']!=1){
            $this->nombre = $nom; 
        }
    }

    //Setter de apellido---------------------------------------------------------------------------------------------------------------------------------------------------
    public function setApellido($ape){
        $prohibidosApe = 0;
        $ape = mysqli_real_escape_string($GLOBALS['connDB'], $ape);
        if(strpos($ape, "<") !== false || strpos($ape, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosApe = 1;
            return;
        }
        // Si el tamaño del apellido no cumple con los requisitos:
        if(strlen($ape) <3 || strlen($ape) > 30){
            $GLOBALS['apellidolength'] = 1;
            return;
        }
        if($prohibidosApe!=1 && $GLOBALS['apellidolength']!=1){
            $this->apellido = $ape; 
        }
    }
    //Setter de cedula-----------------------------------------------------------------------------------------------------------------------------------------------------
    public function setCedula($ci){
        $prohibidosCi = 0;
        $ci = mysqli_real_escape_string($GLOBALS['connDB'], $ci);
        if(strpos($ci, "<") !== false || strpos($ci, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosCi = 1;
            return;
        }
        // Si el tamaño de la cédula no cumple con los requisitos:
        if(strlen($ci) < 8 || strlen($ci) > 8){
            $GLOBALS['cilength'] = 1;
            
            return;
        }
        if($this->validarci($ci) == 0){
            $GLOBALS['ci'] = 1;
            return;
        }
        $consultaci = mysqli_query($GLOBALS['connDB'], "SELECT ci FROM usuario WHERE tipo = '{$this->getTipo()}'");
        $arrayconsultaci = array();
        while($fila = mysqli_fetch_array($consultaci)){
            $arrayconsultaci[] = $fila;
       }
        foreach($arrayconsultaci as $valor){
            //Verifica si la cédula ya está en uso.
            if($ci == $valor['ci']){
                $GLOBALS['cedulaexistente'] = 1;                    
                break;
                return;
            }
        }
        if($prohibidosCi!=1 && $GLOBALS['cilength']!=1 && $GLOBALS['ci']!=1){
            $this->cedula = $ci;
        }
    }
    //Setter de tel--------------------------------------------------------------------------------------------------------------------------------------------------------
    public function setTel($tel){
        $prohibidosTel = 0;
        $tel = mysqli_real_escape_string($GLOBALS['connDB'], $tel);
        if(strpos($tel, "<") !== false || strpos($tel, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosTel = 1;
            return;
        }
        //Si el tamaño del teléfono no cumple con los requisitos.
        if(strlen($tel) < 8 || strlen($tel) >9){
            $GLOBALS['tellength'] = 1;
            return;
        }
         //Verifica si el número de teléfono ingresado por el usuario cumple con los requisitos:
         //Los requisitos son: debe comenzar por 0, luego debe tener un 9, después número que no sea 0, seguido de 6 números cualesquiera.                                           
        if(!preg_match("/^[0]{1}[9]{1}[1-9]{1}[0-9]{6}$/", $tel)){
            $GLOBALS['tel'] = 1;
            return;
        }
        $consultatel = mysqli_query($GLOBALS['connDB'], "SELECT num FROM tel WHERE ci != '{$this->getCi()}'");
        $arrayconsultatel = array();
        while($fila = mysqli_fetch_array($consultatel)){
            $arrayconsultatel[] = $fila;
        }
        foreach($arrayconsultatel as $valor){
            //Verifica si el número telefónico ya está en uso.
            if($tel == $valor['num']){
                $GLOBALS['celularexistente'] = 1;
                break;
                return;
            }
        }
        if($prohibidosTel!=1 && $GLOBALS['tellength']!=1 && $GLOBALS['tel']!=1){
            $this->telefono = $tel; 
        }
        
    }
    //Setter de fecha------------------------------------------------------------------------------------------------------------------------------------------------------
    public function setFecha($fecha){
        $prohibidosFecha = 0;
        $fecha = mysqli_real_escape_string($GLOBALS['connDB'], $fecha);
        if(strpos($fecha, "<") !== false || strpos($fecha, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosFecha = 1;
            return;
        }
        if($this->calcEdad($fecha) < 18){
            $GLOBALS['edad'] = 1;
            return;
        }
        if($prohibidosFecha!=1 && $GLOBALS['edad']!=1){
            $this->fechaNac = $fecha; 
        }
        
    }

    //Setter de suspendido-------------------------------------------------------------------------------------------------------------------------------------------------
    public function setSuspendido($susp){
        $prohibidosSuspendido = 0;
        $susp = mysqli_real_escape_string($GLOBALS['connDB'], $susp);
        if(strpos($susp, "<") !== false || strpos($susp, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosSuspendido = 1;
            return;
        }
        $errorSuspendido = 0;
        if($susp != 0 && $susp != 1){
            $GLOBALS['errorSuspendido'];
            $errorSuspendido = 1;
            return;
        }
        if($prohibidosSuspendido == 0 && $errorSuspendido == 0){
            $this->suspendido = $susp;
        }
    }

    //Setter de ciudad-------------------------------------------------------------------------------------------------------------------------------------------------
    public function setCiudad($ciudad){
        $prohibidosCiudad = 0;
        $ciudad = mysqli_real_escape_string($GLOBALS['connDB'], $ciudad);
        if(strpos($ciudad, "<") !== false || strpos($ciudad, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosCiudad = 1;
            return;
        }
        $ciudadLength = 0;
        if(strlen($ciudad) >= 50 || strlen($ciudad) < 2){
            $GLOBALS['ciudadlength'];
            $ciudadLength = 1;
            return;
        }
            if(!preg_match("/^[a-zA-Z\d]*$/", $ciudad)) {
                return;
            }
        if($prohibidosCiudad == 0 && $ciudadLength == 0){
            $this->ciudad = $ciudad;
        }
    }

    //Setter de calle1-------------------------------------------------------------------------------------------------------------------------------------------------
    public function setCalle1($calle1){
        $prohibidosCalle1 = 0;
        $calle1 = mysqli_real_escape_string($GLOBALS['connDB'], $calle1);
        if(strpos($calle1, "<") !== false || strpos($calle1, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosCalle1 = 1;
            return;
        }
        $calle1Length = 0;
        if(strlen($calle1) >= 50 || strlen($calle1) < 0){
            $GLOBALS['calle1length'];
            $calle1Length = 1;
            return;
        }
        if($prohibidosCalle1 == 0 && $calle1Length == 0){
            $this->calle1 = $calle1;
        }
    }

    //Setter de calle2-------------------------------------------------------------------------------------------------------------------------------------------------
    public function setCalle2($calle2){
        $prohibidosCalle2 = 0;
        $calle2 = mysqli_real_escape_string($GLOBALS['connDB'], $calle2);
        if(strpos($calle2, "<") !== false || strpos($calle2, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosCalle2 = 1;
            return;
        }
        $calle2Length = 0;
        if(strlen($calle2) >= 50 || strlen($calle2) < 0){
            $GLOBALS['calle2length'];
            $calle2Length = 1;
            return;
        }
        if($prohibidosCalle2 == 0 && $calle2Length == 0){
            $this->calle2 = $calle2;
        }
    }

    //Setter de calle3-------------------------------------------------------------------------------------------------------------------------------------------------
    public function setCalle3($calle3){
        $prohibidosCalle3 = 0;
        $calle3 = mysqli_real_escape_string($GLOBALS['connDB'], $calle3);
        if(strpos($calle3, "<") !== false || strpos($calle3, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosCalle3 = 1;
            return;
        }
        $calle3Length = 0;
        if(strlen($calle3) >= 50 || strlen($calle3) < 0){
            $GLOBALS['calle3length'];
            $calle3Length = 1;
            return;
        }
        if($prohibidosCalle3 == 0 && $calle3Length == 0){
            $this->calle3 = $calle3;
        }
    }

    //Setter de nro-------------------------------------------------------------------------------------------------------------------------------------------------
    public function setNro($nro){
        $prohibidosNro = 0;
        $nroLength = 0;
        $nro = mysqli_real_escape_string($GLOBALS['connDB'], $nro);
        if(strpos($nro, "<") !== false || strpos($nro, ">") !== false){
            $GLOBALS['prohibidos'] = 1;
            $prohibidosNro = 1;
            return;
        }
        $nro = 0;
        if(strlen($nro) >= 200 || strlen($nro) < 0){
            $GLOBALS['nrolength'];
            $nroLength = 1;
            return;
        }
        if($prohibidosNro == 0 && $nroLength == 0){
            $this->nro = $nro;
        }
    }

//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    //Getters de todos los atributos de Usuario.
    public function getUser(){
        return $this->username;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getPassword(){
        return $this->password;
    }
    public function getTipo(){
        return $this->tipo;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getApe(){
        return $this->apellido;
    }
    public function getCi(){
        return $this->cedula;
    }
    public function getTel(){
        return $this->telefono;
    }
    public function getFechaNac(){
        return $this->fechaNac;
    }
    public function getSuspendido(){
        return $this->suspendido;
    }
    public function getCiudad(){
        return $this->ciudad;
    }
    public function getCalle1(){
        return $this->calle1;
    }
    public function getCalle2(){
        return $this->calle2;
    }
    public function getCalle3(){
        return $this->calle3;
    }
    public function nro(){
        return $this->nro;
    }
    
    //Función para verificar cédula
    public function validarci( string $ci ) : bool{
        //Se crea la variable ciSinDigito, que es la cédula a verificar sin su dígito verificador, para que pueda pasar por el proceso.
        $ciSinDigito = str_pad( $ci, 7, '0', STR_PAD_LEFT );
        //Variable a, que va a guardar la suma de todos los módulos de los 7 dígitos de una cédula multiplicados por su correspondiente del patrón de verificación.
        $a = 0;

        //Patrón para verificar si una cédula es válida en el país.
        $patronVerificar = "2987634";
        for ($i = 0; $i < 7; $i++) {
            $digitoVerificar = $patronVerificar[$i];
            $digitoCi = $ciSinDigito[$i];

            $a += (intval($digitoVerificar)*intval($digitoCi))%10;
        }
        //Si el módulo de a en 10 es 0
        if($a % 10 == 0){
            //Verifica si el módulo 0, si éste es igual al dígito verificador.
            if(intval($ci[7])==0){
                //Si se cumple retorna 1.
                return 1;
            }
            //Si no se cumple retorna 2.
            else{
                return 0;
            }
        }
        //Si el módulo de a en 10 no es 0
        else {
            //Verifica si el dígito verificador es igual a 10 menos el módulo de a en 10.
            if(intval($ci[7]) == (10-$a % 10)){
                //Si se cumple retorna 1.
                return 1;
            }
            //Si no se cumple retorna 2.
            else{
                return 0;
            }
        }
    }
    //Función para verificar que el usuario a registrar sea mayor de 18 años
    function calcEdad($fechaNacimiento): bool{
        //Toma la fecha actual como string y la pasa a int de tiempo.
        $fecha_actual = strtotime(date("Y-m-d H:i:s"));
        //Toma la fecha de nacimiento y la pasa a int de tiempo.
        $fechaNac = strtotime($fechaNacimiento);
        //Si pasaron más de 18 años entre la fecha actual y la fecha de nacimiento retorna 1.
        //Se divide entre 31550000 porque es 1 año en formato Unix o Epoch.
        if((($fecha_actual - $fechaNac) / (31550000)) >= 18 && (($fecha_actual - $fechaNac) / (31550000)) <= 120){
            return 1;
        }
        //Si no pasaron más de 18 años desde la fecha de nacimiento hasta la actual retorna 0.
        else{
            return 0;
        }
    }
    //Función para verificar si ya existe una persona con determinada ci, devuelve un bool.
    public function ciPersonaExiste($ci): bool{
        $consultaci = mysqli_query($GLOBALS['connDB'], "SELECT ci FROM persona");
        $arrayconsultaci = array();
        $ciPersonaExisteBool = 0;
        while($fila = mysqli_fetch_array($consultaci)){
            $arrayconsultaci[] = $fila;
        }
        foreach($arrayconsultaci as $valor){
            //Verifica si la cédula ya está en uso.
            if($ci == $valor['ci']){
                $ciPersonaExisteBool = 1;
            }
        }
        if($ciPersonaExisteBool == 1){
            return 1;
        }
        else if ($ciPersonaExisteBool == 0){
            return 0;
        }
    }
}
?>