<?php
try{
    include_once "conexionbd.php";
    $GLOBALS['connDB'] = $GLOBALS['connDB'];
}
catch(Exception $e){
    echo "Error de conexión con la base de datos.";
    return;
}
$GLOBALS['prohibidos']=0;
$GLOBALS['errorCarga']=0;
$GLOBALS['precioNegativo']=0;
$GLOBALS['errorVisibilidad']=0;
$GLOBALS['stockNegativo']=0;
$GLOBALS['minStockNegativo']=0;
$GLOBALS['descuentoInvalido']=0;
$GLOBALS['compradosNegativo']=0;

    class Producto{
        private $nombProd;
        private $precioProd;
        private $categoriaProd;
        private $generoProd;
        private $subCatProd;
        private $marcaProd;
        private $visibilidadProd;
        private $stockProd;
        private $descuentoProd;
        private $descripcionProd;
        private $compradosProd;
        private $imgProd;
        private $minStockProd;

        //Constructor
        public function __construct($nomb, $precio, $categoria, $genero, $subCat, $marca, $visibilidad, $stock, $descuento, $descripcion, $comprados, $minStock){
            $this->setNomb($nomb);
            $this->setPrecio($precio);
            $this->setCat($categoria);
            $this->setGenero($genero);
            $this->setSubCat($subCat);
            $this->setMarca($marca);
            $this->setVisibilidad($visibilidad);
            $this->setStock($stock);
            $this->setDescuento($descuento);
            $this->setDescripcion($descripcion);
            $this->setComprados($comprados);
            $this->setMinStock($minStock);
            }

        public function ingresarProdBd(){
            if($this->getNombre() != null && $this->getPrecio() != null && $this->getCat() != null && $this->getGenero() != null && $this->getMarca() != null && $this->getVisibilidad() !== null && $this->getStock() != null && $this->getDescuento() != null && $this->getDescripcion() != null && $this->getImg() != null){
                $sql = "INSERT INTO producto (nomb_prod, precio, categoria, genero, subcategoria, marca, público, stock, descuento, descripcion, comprados, img, min_stock)
                VALUES ('{$this->getNombre()}', '{$this->getPrecio()}', '{$this->getCat()}', '{$this->getGenero()}', '{$this->getSubCat()}', '{$this->getMarca()}', '{$this->getVisibilidad()}', '{$this->getStock()}', '{$this->getDescuento()}', '{$this->getDescripcion()}', 0, '{$this->getImg()}', '{$this->getMinStock()}')";
                $fecha = date("Y-m-d");
                try{
                    if($GLOBALS['connDB']->query($sql) === TRUE){
                            
                        $id = $this->getIdProd();
                        $sqlCarga = "INSERT INTO carga VALUES ('{$_SESSION['logUsuario']}', '$id', '$fecha')";
                        if($GLOBALS['connDB']->query($sqlCarga) === TRUE){
                            $GLOBALS['errorCarga']=0;
    
                        }
                        else{
                            $GLOBALS['errorCarga']=mysqli_error($GLOBALS['connDB']);
                        }
                    }else{
                        $GLOBALS['errorCarga']=mysqli_error($GLOBALS['connDB']);
                    }
                }
                catch(Exception $e){
                    echo "ERROR CON LA BASE DE DATOS.";
                }
            }else{
                return;
            }
        }
        public function updateProdBd($id){
            $sqlUpdate = "UPDATE producto SET nomb_prod='{$this->getNombre()}', precio='{$this->getPrecio()}', categoria='{$this->getCat()}', descuento='{$this->getDescuento()}', descripcion='{$this->getDescripcion()}', min_stock='{$this->getMinStock()}'
            WHERE id_prod='$id'";
            $sqlUpdateImg = "UPDATE producto SET img='{$this->getImg()}' WHERE id_prod='$id'";
            try{
                $GLOBALS['connDB']->query($sqlUpdate);
                if(strlen($this->imgProd)>0){
                    $GLOBALS['connDB']->query($sqlUpdateImg);
                }
            }
            catch(Exception $e){
                echo "ERROR CON LA BASE DE DATOS";
            }
        }
        //Setters para todos los atributos de Producto.
        public function setNomb($nomb){
            $prohibidosNombre=0;
            $nomb = mysqli_real_escape_string($GLOBALS['connDB'], $nomb);
            if(strpos($nomb, "<") !== false || strpos($nomb, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosNombre=1;
                return;
            }
            if($prohibidosNombre!=1){
                $this->nombProd = $nomb;
            }
        }
        public function setPrecio($precio){
            $prohibidosPrecio=0;
            $precioVacio=0;
            $precio = mysqli_real_escape_string($GLOBALS['connDB'], $precio);
            if($precio=="" || $precio==null){
                $precioVacio=1;
                return;
            }
            if(strpos($precio, "<") !== false || strpos($precio, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosPrecio=1;
                return;
            }
            $precioNegativo = 0;
            if($precio <= 0){
                $GLOBALS['precioNegativo'] = 1;
                $precioNegativo = 1;
                return;
            }
            if($prohibidosPrecio!=1 && $precioNegativo != 1){
                $this->precioProd = $precio;
            }
        }
        public function setCat($categoria){
            $prohibidosCategoria=0;
            $categoria = mysqli_real_escape_string($GLOBALS['connDB'], $categoria);
            if(strpos($categoria, "<") !== false || strpos($categoria, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosCategoria=1;
                return;
            }
            if($prohibidosCategoria!=1){
                $this->categoriaProd = $categoria;
            }
        }
        public function setGenero($genero){
            $prohibidosGenero=0;
            $genero = mysqli_real_escape_string($GLOBALS['connDB'], $genero);
            if(strpos($genero, "<") !== false || strpos($genero, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosGenero=1;
                return;
            }
            if($prohibidosGenero!=1){
                $this->generoProd = $genero;
            }
        }
        public function setSubCat($subCat){
            $prohibidosSubCat=0;
            $subCat = mysqli_real_escape_string($GLOBALS['connDB'], $subCat);
            if(strpos($subCat, "<") !== false || strpos($subCat, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosSubCat=1;
                return;
            }
            if($prohibidosSubCat!=1){
                $this->subCatProd = $subCat;
            }
        }
        public function setMarca($marca){
            $prohibidosMarca=0;
            $marca = mysqli_real_escape_string($GLOBALS['connDB'], $marca);
            if(strpos($marca, "<") !== false || strpos($marca, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosMarca=1;
                return;
            }
            if($prohibidosMarca!=1){
                $this->marcaProd = $marca;
            }
        }
        public function setVisibilidad($visibilidad){
            $prohibidosVisibilidad=0;
            $visibilidad = mysqli_real_escape_string($GLOBALS['connDB'], $visibilidad);
            if(strpos($visibilidad, "<") !== false || strpos($visibilidad, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosVisibilidad=1;
                return;
            }
            $errorVisibilidad = 0;
            if($visibilidad != 0 && $visibilidad != 1){
                $GLOBALS['errorVisibilidad'];
                $errorVisibilidad = 1;
                return;
            }
            if($prohibidosVisibilidad!=1 && $errorVisibilidad != 1){
                $this->visibilidadProd = $visibilidad;
            }
        }
        public function setStock($stock){
            $prohibidosStock=0;
            $stock = mysqli_real_escape_string($GLOBALS['connDB'], $stock);
            if(strpos($stock, "<") !== false || strpos($stock, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosStock=1;
                return;
            }
            $stockNegativo=0;
            if($stock < 0){
                $GLOBALS['stockNegativo']=1;
                $stockNegativo=1;
                return;
            }
            if($prohibidosStock!=1 && $stockNegativo!=1){
                $this->stockProd = $stock;
            }
        }
        public function setDescuento($descuento){
            $prohibidosDescuento=0;
            $descuento = mysqli_real_escape_string($GLOBALS['connDB'], $descuento);
            if(strpos($descuento, "<") !== false || strpos($descuento, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosDescuento=1;
                return;
            }
            $descuentoInvalido = 0;
            if($descuento > 99 || $descuento < 0){
                $GLOBALS['descuentoInvalido']=1;
                $descuentoInvalido = 1;
                return;
            }
            if($prohibidosDescuento!=1 && $descuentoInvalido !=1){
                $this->descuentoProd = $descuento;
            }
        }
        public function setDescripcion($descripcion){
            $prohibidosDescripcion=0;
            $descripcion = mysqli_real_escape_string($GLOBALS['connDB'], $descripcion);
            if(strpos($descripcion, "<") !== false || strpos($descripcion, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosDescripcion=1;
                return;
            }
            if($prohibidosDescripcion!=1){
                $this->descripcionProd = $descripcion;
            }
        }
        public function setComprados($comprados){
            $prohibidosComprados=0;
            $comprados = mysqli_real_escape_string($GLOBALS['connDB'], $comprados);
            if(strpos($comprados, "<") !== false || strpos($comprados, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosComprados=1;
                return;
            }
            $compradosNegativo = 0;
            if($comprados < 0){
                $GLOBALS['compradosNegativo'] = 1;
                $compradosNegativo = 1;
                return;
            }
            if($prohibidosComprados!=1 && $compradosNegativo != 1){
                $this->compradosProd = $comprados;
            }
        }
        public function setImg($img){
            if(empty($img)){
                $GLOBALS['vacio']=1;
            }
            $prohibidosImg=0;
            if(strpos($img, "<") !== false || strpos($img, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosImg=1;
                return;
            }
            if($prohibidosImg!=1){
                if($_FILES['imagenProd']['type'] == "image/jpg" || $_FILES['imagenProd']['type'] == "image/jpeg" || $_FILES['imagenProd']['type'] == "image/png" || $_FILES['imagenProd']['type'] == "image/jfif"){
                    $imgEnc = md5($_FILES['imagenProd']['tmp_name']);
                    $ruta = "../../imagenes/$imgEnc.jpg";
                    if(move_uploaded_file($_FILES['imagenProd']['tmp_name'], $ruta)){
                        $this->imgProd = $imgEnc.".jpg";
                    }
                }
            }
        }
        public function setImgMod($img){
            if(empty($img)){
                $GLOBALS['vacio']=1;
                return;
            }
            $prohibidosImg=0;
            if(strpos($img, "<") !== false || strpos($img, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosImg=1;
                return;
            }
            if($prohibidosImg!=1 && isset($_FILES['imagenProdMod']['tmp_name'])){
                if($_FILES['imagenProdMod']['type'] == "image/jpg" || $_FILES['imagenProdMod']['type'] == "image/jpeg" || $_FILES['imagenProdMod']['type'] == "image/png" || $_FILES['imagenProdMod']['type'] == "image/jfif"){
                    $imgEnc = md5($_FILES['imagenProdMod']['tmp_name']);
                    $ruta = "../../imagenes/$imgEnc.jpg";
                    if(move_uploaded_file($_FILES['imagenProdMod']['tmp_name'], $ruta)){
                        $this->imgProd = $imgEnc.".jpg";
                    }
                }
            }
        }

        public function setMinStock($minStock){
            $prohibidosStock=0;
            $minStock = mysqli_real_escape_string($GLOBALS['connDB'], $minStock);
            if(strpos($minStock, "<") !== false || strpos($minStock, ">") !== false){
                $GLOBALS['prohibidos']=1;
                $prohibidosStock=1;
                return;
            }
            $minStockNegativo = 0;
            if($minStock < 0){
                $GLOBALS['minStockNegativo'] = 1;
                $minStockNegativo = 1;
                return;
            }
            if($prohibidosStock!=1 && $minStockNegativo!=1){
                $this->minStockProd = $minStock;
            }
        }
        
        //Getters para todos los atributos de Producto.
        public function getNombre(){
            return $this->nombProd;
        }
        public function getPrecio(){
            return $this->precioProd;
        }
        public function getCat(){
            return $this->categoriaProd;
        }
        public function getGenero(){
            return $this->generoProd;
        }
        public function getSubCat(){
            return $this->subCatProd;
        }
        public function getMarca(){
            return $this->marcaProd;
        }
        public function getVisibilidad(){
            return $this->visibilidadProd;
        }
        public function getStock(){
            return $this->stockProd;
        }
        public function getDescuento(){
            return $this->descuentoProd;
        }
        public function getDescripcion(){
            return $this->descripcionProd;
        }
        public function getComprados(){
            return $this->compradosProd;
        }
        public function getImg(){
            return $this->imgProd;
        }
        public function getIdProd(){
            $id="";
            $sqlId = "SELECT * FROM producto WHERE nomb_prod='{$this->getNombre()}' AND precio='{$this->getPrecio()}' AND descripcion='{$this->getDescripcion()}' AND img='{$this->getImg()}'";
            try{
                $consulta = mysqli_query($GLOBALS['connDB'], $sqlId);
                $arrayId = array();
                while($fila = mysqli_fetch_array($consulta)){
                    $arrayId[] = $fila;
                }
                foreach($arrayId as $valor){
                    $id=$valor['0'];
                }
            }
            catch(Exception $e){
                echo "ERROR CON LA BASE DE DATOS";
            }
            return $id;
        }
        public function getMinStock(){
            return intval($this->minStockProd);
        }
    }
?>