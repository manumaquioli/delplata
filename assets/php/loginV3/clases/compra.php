<?php
// try{
//    $GLOBALS['connDB'] = $connDB;
//  }
//  catch(Exception $e){
//     echo "Error de conexión con la base de datos.";
//     return;
// }
     class Compra{
        private $codCompra;
        private $fecha;
        private $montoTotal;
        private $comprador;
        private $prods = array();

        public function __construct($codigoCompra, $fechaCompra, $montoCompra, $compradorCompra){
             $this->codCompra = $codigoCompra;
             $this->fecha = $fechaCompra;
             $this->montoTotal = $montoCompra;
            $this->comprador = $compradorCompra;
        }

         /*Setters*/
        public function setCodCompra($cod){
            $this->codCompra = $cod;
        }
        public function setfecha($fec){
            $this->fecha = $fec;
        }
        public function setMontoTotal($mont){
           $this->montoTotal = $mont;
        }
       public function setComprador($comp){
           $this->comprador = $comp;
        }
       public function setProds($prodNomb, $prodPrec, $cantidad, $id, $cat){
            array_push($this->prods, array($prodNomb, $prodPrec, $cantidad, $id, $cat));
        }

       /*Getters*/
       public function getCodCompra(){
           return $this->codCompra;
       }
       public function getfecha(){
           return $this->fecha;
       }
       public function getMontoTotal(){
            return $this->montoTotal;
       }
       public function getComprador(){
          return $this->comprador;
       }
       public function getProds(){
          return $this->prods;
       }
   }


?>