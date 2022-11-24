<?php
    include "../php/loginV3/header.php";
try{
    include "../php/loginV3/conexionbd.php";
}
catch(Exception $e){
    echo "Error de conexión con la base de datos";
    return;
}
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <link rel="icon" href="../iconos/Captura.PNG">
    <title>Indumentarias del Plata</title>
    <script src="https://kit.fontawesome.com/310348eaa9.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos/header_styles.css">
    <link rel="stylesheet" href="../estilos/footer_styles.css">
    <link rel="stylesheet" href="../estilos/cart_styles.css">
</head>
<body>
    
<?php
    if(isset($_SESSION['logUsuario']) && comprobar_susp($connDB, $_SESSION['logUsuario'])){
        session_unset();
        header("location:login.php");
    }
    else if (!isset($_SESSION['logUsuario'])){
        header("location:login.php");
    }
    display_header();
    if($_SESSION['logTipo'] != 'cliente'){
        session_unset();
        header("location:login.php");
    }
    ?>
    <div id='cart-container'>
        <div id='cart-items-container'>
    <?php
        $prods = getCart($connDB, $_SESSION['logUsuario']);
        if(empty($prods)){
            echo "<h1 id='mensajes_carrito' class='show-mensajes_carrito'> El carrito está vacío.</h1>";
        }else{
            echo "<h1 id='mensajes_carrito' class=''> El carrito está vacío.</h1>";
        }
            foreach($prods as $value){
                $id = $value['id_prod'];
                echo "<div id='div$id' class='cart-item'>";
                echo "<img src='../imagenes/$value[img]' class='prod-img'>";
                // echo "<p id='id$id'>ID: ".$value['id_prod']."</p>";
                echo "<p id ='nombre$id'>Nombre: ".$value['nomb_prod']."</p>";
                echo "<p id='descuento$id'>Descuento: ".$value['descuento']."</p>";
                echo "<div class='btn-cart-container'>";
                    echo "<button id='a$id' class='btn1'><i id='d$id' class='fa-solid fa-xmark'></i></button>";
                    echo "<button id='b$id' class='btn2'><i id='e$id' class='fa-solid fa-plus'></i></button>";
                    echo "<button id='c$id' class='btn3'><i id='f$id' class='fa-solid fa-minus'></i></button>";
                echo "</div>";
                echo "<p id='cantidad$id'>Cantidad: ".$value['cantidad']."</p>";
                echo "<p id='precio$id'>Precio unidad: ".$value['precio']."</p>";
                echo "</div>";
            }
            
    ?>  </div>
        <!-- <p id='mensajes_carrito'></p> -->
        <div id=btn-section>
            <button id='cart_vaciar'>Vaciar carrito</button>
            <button id='cart_compra'> Realizar compra </button>
            <?php
            $usd = "U $ S";
            $usd = str_replace(" ", "", $usd); 
            $precioFinal = floatval(carrito_calcular_precio($connDB, $_SESSION["logUsuario"]));
            echo "<div><p id='precioFinal'>Total: $usd $precioFinal</p></div>";
            ?>
        </div>
    </div>

    <script>
        function controlVacio(){
            if(document.getElementsByClassName("cart-item").length<=0){
                document.getElementById("cart_vaciar").disabled=true;
                document.getElementById("cart_compra").disbled=true;
            }
            else{
                document.getElementById("cart_vaciar").disabled=false;
                document.getElementById("cart_compra").disbled=false;
            }
        }
        $(document).ready(function(){
            controlVacio();
            $("#cart_vaciar").on("click", function(){
                let vaciarCart = "TRUE";

                $.ajax({
                    url:'../php/loginV3/validarCart.php',
                    method: 'POST',
                    data: {
                        cart_vaciar: vaciarCart
                    },
                    success: function(response){
                        let respuesta = JSON.parse(response);
                        if(respuesta.status == "1"){
                            $("#mensajes_carrito").addClass("show-mensajes_carrito");
                            document.getElementById("mensajes_carrito").innerHTML="Carrito vaciado correctamente.";
                            document.getElementById("precioFinal").innerHTML = "Total: U$D"+respuesta.precioFinal;
                            $(".cart-item").remove();
                            controlVacio();
                        }
                    },
                    dataType:'text'
                });
            });

            $(".btn1").click(function removeProd(event){
                let idTarget = event.target.id.match(/(\d+)/g).toString();
                $.ajax({
                    url:'../php/loginV3/validarCart.php',
                    method: 'POST',
                    data: {
                        cart_eliminarProd: "TRUE",
                        idEliminar: idTarget
                    },
                    success: function(response){
                        let respuesta = JSON.parse(response);
                        if(respuesta.status=="1"){
                            let divId = "div"+idTarget;
                            document.getElementById(divId).remove();
                            if(document.getElementsByClassName("cart-item").length == 0){
                                $("#mensajes_carrito").addClass('show-mensajes_carrito');
                            }
                            controlVacio();
                        }
                        document.getElementById("precioFinal").innerHTML = "Total: U$D"+respuesta.precioFinal;
                    },
                    dataType:'text'
                });

            });

            $(".btn2").click(function(event){
                let idTarget = event.target.id.match(/(\d+)/g).toString();
                $.ajax({
                    url:'../php/loginV3/validarCart.php',
                    method: 'POST',
                    data: {
                        cart_aumentar: "TRUE",
                        idAumentar: idTarget
                    },
                    success: function(response){
                        let respuesta = JSON.parse(response);
                        if(respuesta.status=="1"){
                            let divId = "cantidad"+idTarget;
                            document.getElementById(divId).innerHTML="Cantidad: "+respuesta.cantidad;
                            document.getElementById("precioFinal").innerHTML="Total: U$D"+respuesta.precioFinal;
                            controlVacio();
                        }
                    },
                    dataType:'text'
                });

            });

            $(".btn3").click(function(event){
                let idTarget = event.target.id.match(/(\d+)/g).toString();
                $.ajax({
                    url:'../php/loginV3/validarCart.php',
                    method: 'POST',
                    data: {
                        cart_restar: "TRUE",
                        idRestar: idTarget
                    },
                    success: function(response){
                        let respuesta = JSON.parse(response);
                        if(respuesta.status=="1"){
                            let divId = "cantidad"+idTarget;
                            document.getElementById(divId).innerHTML="Cantidad: "+respuesta.cantidad; 
                            document.getElementById("precioFinal").innerHTML="Total: U$D"+respuesta.precioFinal;
                        }
                        else if(respuesta.status=="2"){
                            let divId = "div"+idTarget;
                            document.getElementById(divId).remove();
                            document.getElementById("precioFinal").innerHTML="Total: U$D"+respuesta.precioFinal;
                        }
                        if(document.getElementsByClassName("cart-item").length == 0){
                            $("#mensajes_carrito").addClass('show-mensajes_carrito');
                        }
                        controlVacio();
                    },
                    dataType:'text'
                });

            });

            $("#cart_compra").click(function(){
                $.ajax({
                    url:'../php/loginV3/validarCart.php',
                    method: 'POST',
                    data:{
                        cart_comprar: "TRUE"
                    },
                    success:function(response){
                        let respuesta = JSON.parse(response);
                        if(respuesta.status=="1"){
                            document.getElementById("mensajes_carrito").classList.add("show-mensajes_carrito");
                            document.getElementById("mensajes_carrito").innerHTML="Compra realizada correctamente.";
                            document.getElementById("precioFinal").innerHTML="Total: U$D0";
                            $('.cart-item').remove();
                        }
                        else if(respuesta.status=="0"){
                            document.getElementById("mensajes_carrito").classList.add("show-mensajes_carrito");
                            document.getElementById("mensajes_carrito").innerHTML="Ocurrió un error en la compra.";
                        }else if(respuesta.status == "2"){
                            document.getElementById("mensajes_carrito").classList.add("show-mensajes_carrito");
                            document.getElementById("mensajes_carrito").innerHTML="El carrito está vacío.";
                        }else if(respuesta.status == '3'){
                            document.getElementById("mensajes_carrito").classList.add("show-mensajes_carrito");
                            document.getElementById("mensajes_carrito").innerHTML="Lo sentimos, no hay stock disponible.";
                        }else{
                            console.log(respuesta.status);
                        }
                        controlVacio();
                    },
                    dataType:'text'
                });
            });

        });
    </script>


<?php
    display_footer();
?>
    <script src='../scripts/script_carrito.js'></script>
    
     <style>.disclaimer{visibility:hidden;}</style>
    <style>
        #ult + div{
            visibility:hidden;
        }
        </style>
        <div id="ult"></div>
</body>
</html>