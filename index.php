<?php
try{
    include "assets/php/loginV3/conexionbd.php";
}
catch(Exception $e){
    echo "Error de conexión con la base de datos";
    return;
}
ob_start();
if(!isset($_SESSION['logUsuario'])){session_start();}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1">
    <link rel="icon" href="assets/iconos/Captura.PNG">
    <title>Indumentarias del Plata</title>
    <script src="https://kit.fontawesome.com/310348eaa9.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/estilos/styles.css">
</head>
<body>
    <?php
            if(isset($_GET["logout"])){
                session_unset();
            }
            if(!empty($_SESSION['logUsuario']) && $_SESSION['logTipo'] == "cliente"){
                //función comprobar_susp definida en conexionbd.php
                if(comprobar_susp($connDB, $_SESSION['logUsuario'])){
                    session_unset();
                    header("location:assets/enlaces/login.php");
                }
                echo '<i class="fa-solid fa-bars ham-btn"></i>
                        <div id="menu-movil">
                            <a href="index.php" class="movil-items">Inicio</a>
                            <a href="assets/enlaces/institucional.php" class="movil-items">Institucional</a>
                            <a href="assets/enlaces/equipo.php" class="movil-items">Equipo</a>
                            <a href="#footer" class="movil-items">Contacto</a>
                       <!-- <img id="menu-logo" src="assets/iconos/Captura.PNG" alt=""> -->
                        </div>
                ';
                ?>
                <script src='assets/scripts/script_carrito.js'></script>
                <header id="navbar">
                    <a href="index.php" id="logo"><img src="assets/iconos/Captura.PNG" alt=""></a>
            
                    <div id="items-container">
                        <a href='index.php'>Inicio</a>
                        <a href='assets/enlaces/institucional.php'>Institucional</a>
                        <a href='assets/enlaces/equipo.php'>Equipo</a>
                        <a href='#footer'>Contacto</a>
                        <a href="assets/enlaces/cart.php"><i class="fa-solid fa-cart-shopping header-icons"></i></a>
                    </div>   
                    <div id="text-header-container">
                        Indumentarias del Plata
                        <a href="assets/enlaces/cart.php"><i class="fa-solid fa-cart-shopping header-icons"></i></a>
                    </div>
                    
                    <div id="user-container" onclick="deslizar_info()">
                       <?php echo $_SESSION['logUsuario'].'<i id="user-icon" class="fa-solid fa-user"></i>'?>
                    </div>
                    </header>
                    <form id="user-info" action="index.php?logout">
                        <div id="account"><a href="assets/enlaces/mi_cuenta.php">Mi Cuenta</a></div>
                        <div id="historial"><a href="assets/enlaces/mis_compras.php">Mis Compras</a></div>
                        <input type="submit" id="logout" readonly="readonly" value="Cerrar sesión" name="logout">
                      </form>
           <?php }else if(empty($_SESSION['logUsuario'])){
                session_unset();
                ?>
                    <i class="fa-solid fa-bars ham-btn"></i>
                        <div id="menu-movil">
                            <a href='index.php' class='movil-items'>Inicio</a>
                            <a href='assets/enlaces/institucional.php' class='movil-items'>Institucional</a>
                            <a href='assets/enlaces/equipo.php' class='movil-items'>Equipo</a>
                            <a href='#footer' class='movil-items'>Contacto</a>
                            <!-- <img id="menu-logo" src="assets/iconos/Captura.PNG" alt=""> -->
                        </div>
                     <header id="navbar">
                        <a href="#" id="logo"><img src="assets/iconos/Captura.PNG" alt=""></a>
                    <div id="items-container">
                    <a href='index.php'>Inicio</a>
                        <a href='assets/enlaces/institucional.php'>Institucional</a>
                        <a href='assets/enlaces/equipo.php'>Equipo</a>
                        <a href='#footer'>Contacto</a>
                    </div>

                    <div id="text-header-container">
                        Indumentarias del Plata
                    </div>

                    <div id="icons-container">
                        <a href="assets/enlaces/login.php"><i id='user-icon' class="fa-solid fa-user header-icons"></i></a>
                    </div>
                </header>
           <?php }else if(!empty($_SESSION['logUsuario']) && $_SESSION['logTipo'] == "adm"){
                    header("location:assets/enlaces/panel_adm.php");
                }else if(!empty($_SESSION['logUsuario']) && $_SESSION['logTipo'] == "empleado"){
                    header("location:assets/enlaces/panel_emp.php");
                }
            ?>

    <section id="banner">
        <img src="assets\imagenes\surfing-2212948_1920.jpg" alt="" id="img1">
        <img src="" alt="" id="img2">
    </section>

    <hr>

    <div id="alternate-container"><i class="fa-solid fa-arrows-left-right" id="alternate-btn"></i></div>

    <div id="filtros-container">
        
        <section id="search">
                <input type="text" name="buscar" placeholder="Escribe aquí" id="search-bar">
                <button type="button" id="buscar-button">Buscar</button>
                
                <section id="active-filters">
                </section>
        </section>


        <div id="genero">
            <h3>GÉNERO</h3><br class="br-movil">
            <a id="genero-hombre" class="genero">Hombre</a><br>
            <a id="genero-mujer" class="genero">Mujer</a><br>
        </div>

        <div id="categorias">
            <h3>CATEGORÍAS</h3><br class="br-movil">
            <a id="categorias-indumentaria_acuatica" class="categorias">Indumentaria acuática</a> <br>
            <a id="categorias-tablas" class="categorias">Tablas</a><br>
            <a id="categorias-calzas" class="categorias">Calzas</a><br>
            <a id="categorias-camperas" class="categorias">Camperas</a><br>
            <a id="categorias-conjuntos" class="categorias">Conjuntos deportivos</a><br>
            <a id="categorias-pantalones" class="categorias">Pantalones</a><br>
            <a id="categorias-pelotas"class="categorias">Pelotas</a><br>
            <a id="categorias-shorts" class="categorias">Shorts</a><br>
            <a id="categorias-trajes_de_baño" class="categorias">Trajes de baño</a><br>
            <a id="categorias-polleras" class="categorias">Polleras</a><br>
            <a id="categorias-tops" class="categorias">Tops</a><br>
            <a id="categorias-camisetas_de_futbol" class="categorias">Camisetas de Fútbol</a><br>
        </div>

        <div id="marcas">
            <h3>MARCAS</h3><br class="br-movil">
            <a id="marcas-adidas" class="marcas">Adidas</a><br>
            <a id="marcas-nike" class="marcas">Nike</a><br>
            <a id="marcas-puma" class="marcas">Puma</a><br>
            <a id="marcas-reebok" class="marcas">Reebok</a><br>
            <a id="marcas-speedo" class="marcas">Speedo</a><br>
            <a id="marcas-umbro" class="marcas">Umbro</a><br>
        </div>

        <div id="precio">
            <h3>PRECIO (U$D)</h3><br class="br-movil">
            <?php
            echo "<form action='' method='get'>
                    <input type='number' name='desde' placeholder='Desde' value='' id='input-precioInicial' class='inputs-precio'>
                    <span>-</span>
                    <input type='number' name='hasta' placeholder='Hasta' value='' id='input-precioFinal' class='inputs-precio'><br>
                    <input type='button' value='OK' id='precio-btn'>
                </form>"
            ?>
        </div>

        <div id="disciplina">
            <h3>DISCIPLINA</h3><br class="br-movil">
            <a id="disciplina-running" class="disciplina">Running</a><br>
            <a id="disciplina-futbol" class="disciplina">Fútbol</a><br>
            <a id="disciplina-basket" class="disciplina">Basket</a><br>
            <a id="disciplina-natacion" class="disciplina">Natación</a><br>
            <a id="disciplina-surf" class="disciplina">Surf</a><br>
            <a id="disciplina-waterpolo" class="disciplina">Waterpolo</a><br>
        </div>
    </div>
    <section id="grid-container" class="active active2">
    </section>
    <script>
        $(document).ready(() => {
            let filtros = new Map();
            //Filtro precios
            filtros.set("precioInicial", 1);
            filtros.set("precioFinal", 100000);
            //Filtro cat
            filtros.set("cat", null);
            //Filtro búsqueda
            filtros.set("busqueda", null);
            //Filtro disciplina
            filtros.set("disciplina", null);
            //Filtro marca
            filtros.set("marca", null);
            //Filtro género
            filtros.set("genero", null);
            //Filtro paginado
            if(localStorage.getItem("pag") <= 0 || localStorage === null){
                localStorage.setItem("pag", 1);
            }
            function consulta(){
                let busqueda = filtros.get("busqueda");
                let cat = filtros.get("cat");
                let precioInicial = filtros.get("precioInicial");
                let precioFinal = filtros.get("precioFinal");
                let disciplina = filtros.get("disciplina");
                let marca = filtros.get("marca");
                let genero = filtros.get("genero");
                let pag = localStorage.getItem("pag");

                $.ajax({
                    url:'assets/php/loginV3/validarFiltros.php',
                    method:'POST',
                    data:{
                        cat: cat,
                        busqueda: busqueda,
                        precioInicial: precioInicial,
                        precioFinal: precioFinal,
                        disciplina: disciplina,
                        marca: marca,
                        genero: genero,
                        pag: pag
                    },
                    success: function(response){
                        document.getElementById("grid-container").innerHTML=response+"<div id='change-page-container'><button id='change-page-previous'>Anterior</button><button id='change-page-next'>Siguiente</button></div>";
                        document.getElementById("active-filters").innerHTML = "";
                        if(precioInicial != 1 || precioFinal != 100000){
                            if(precioInicial != 1 && precioFinal == 100000){
                                document.getElementById("active-filters").innerHTML += "<div>Desde: "+precioInicial+" <i id='x-precio' class='fa-solid fa-x'></i></div>";
                            }else if(precioInicial == 1 && precioFinal != 100000){
                                document.getElementById("active-filters").innerHTML += "<div> Hasta: "+precioFinal+" <i id='x-precio' class='fa-solid fa-x'></i></div>";
                            }else{
                                document.getElementById("active-filters").innerHTML += "<div>Desde: "+precioInicial+" Hasta: "+precioFinal+" <i id='x-precio' class='fa-solid fa-x'></i><div>";
                            }
                        }
                        for(const [k,v] of filtros.entries()){
                            let x;
                            if((v != null && v != "") && (k != "precioInicial" && k != "precioFinal")){
                                x = v;
                                if(v == "H"){
                                    x = "Hombre";
                                }else if(v == "M"){
                                    x = "Mujer";
                                }
                                switch (k) {
                                    case "cat":
                                        document.getElementById("active-filters").innerHTML += "<div>Categoría: "+x+" <i id='x-cat' class='fa-solid fa-x'></i></div>";
                                        break;
                                    case "busqueda":
                                        document.getElementById("active-filters").innerHTML += "<div>Búsqueda: "+x+" <i id='x-busqueda' class='fa-solid fa-x'></i></div>";
                                        break;
                                    case "disciplina":
                                        document.getElementById("active-filters").innerHTML += "<div>Disciplina: "+x+" <i id='x-disciplina' class='fa-solid fa-x'></i></div>";
                                        break;
                                    case "marca":
                                        document.getElementById("active-filters").innerHTML += "<div>Marca: "+x+" <i id='x-marca' class='fa-solid fa-x'></i></div>";
                                        break;
                                        case "genero":
                                            document.getElementById("active-filters").innerHTML += "<div>Género: "+x+" <i id='x-genero' class='fa-solid fa-x'></i></div>";
                                            break;
                                    default:
                                        break;
                                }
                            }
                        }
                        $("#change-page-previous").on("click", changePrev);
                        $("#change-page-next").on("click", changeNext);
                        
                        if((filtros.get("busqueda") == "" || filtros.get("busqueda") == null) && filtros.get("cat") == null && filtros.get("precioInicial") == 1 &&
                        filtros.get("precioFinal") == 100000 && filtros.get("disciplina") == null && filtros.get("marca") == null &&
                        filtros.get("genero") == null){
                            $("#clear-filters-btn").remove();
                        }else{
                            document.getElementById("active-filters").innerHTML += "<button id='clear-filters-btn'>Limpiar</button>";
                            $("#clear-filters-btn").on("click", clearFilters);
                            $(".fa-x").on("click", clearFilter);
                        }
                    }
                });
            }
            consulta();

            function changePrev(){
                if(localStorage.getItem("pag") > 1){
                    localStorage.setItem("pag", parseInt(localStorage.getItem("pag"))-1);
                    consulta();
                    link();
                }
            }
            function changeNext(){
                let num = document.getElementById("cantProds").value;
                let cantPages = num/15;
                if((cantPages % 1) > 0){
                    cantPages = parseInt(cantPages)+1;
                }
                if(localStorage.getItem("pag") < cantPages){
                    localStorage.setItem("pag", parseInt(localStorage.getItem("pag"))+1);
                    consulta();
                    link();
                }
            }
            function clearFilter(e){
                let id = e.target.id.substring(2);
                if(id == "precio"){
                    filtros.set('precioInicial', 1);
                    filtros.set('precioFinal', 100000);
                }else{
                    filtros.set(id, null);
                }
                if(id == "busqueda"){
                    document.getElementById("search-bar").value = "";
                }
                consulta();
            }
            function clearFilters(){
                //Filtro precios
                filtros.set("precioInicial", 1);
                filtros.set("precioFinal", 100000);
                //Filtro cat
                filtros.set("cat", null);
                //Filtro búsqueda
                filtros.set("busqueda", null);
                //Filtro disciplina
                filtros.set("disciplina", null);
                //Filtro marca
                filtros.set("marca", null);
                //Filtro género
                filtros.set("genero", null);
                //Limpio la barra de búsqueda
                document.getElementById("search-bar").value = "";
                localStorage.setItem("pag", 1);
                consulta();
            }
            $(".categorias").on("click", (event) => {
                let id = document.getElementById(event.target.id);
                let categoria = (id.textContent);
                filtros.set("cat", categoria);
                localStorage.setItem("pag", 1);
                consulta();
                link();
            });
            $("#r-info-2-btn").on("click", ()=>{
                clearFilters();
                filtros.set("cat", "Tablas");
                localStorage.setItem("pag", 1);
                consulta();
                link();
            });     
            $("#buscar-button").on("click", ()=>{
                let busqueda = document.getElementById("search-bar").value;
                filtros.set("busqueda", busqueda);
                localStorage.setItem("pag", 1);
                consulta();
            });  
            $(".genero").on("click", (event)=>{
                let genero = event.target.id;
                if(genero=="genero-hombre"){genero="H";}
                else if(genero=="genero-mujer"){genero="M"}
                else{genero="";}
                filtros.set("genero", genero);
                localStorage.setItem("pag", 1);
                consulta();
                link();
            });
            
            $("#l-info-2-btn1").on("click", ()=>{
                clearFilters();
                filtros.set("genero", "H");
                localStorage.setItem("pag", 1);
                consulta();
                link();
            });

            $("#l-info-2-btn2").on("click", ()=>{
                clearFilters();
                filtros.set("genero", "M");
                localStorage.setItem("pag", 1);
                consulta();
                link();
            });
            
            $(".disciplina").on("click", (event) => {
                let id = document.getElementById(event.target.id);
                let disciplina = (id.textContent);
                filtros.set("disciplina", disciplina);
                localStorage.setItem("pag", 1);
                consulta();
                link();
            });

            $(".marcas").on("click", (event) => {
                let id = document.getElementById(event.target.id);
                let marca = (id.textContent);
                filtros.set("marca", marca);
                localStorage.setItem("pag", 1);
                consulta();
                link();
            });

            $("#precio-btn").on("click", () =>{
                let precioInicial = parseInt(document.getElementById("input-precioInicial").value);
                let precioFinal = parseInt(document.getElementById("input-precioFinal").value);
                if(!isNaN(precioInicial) || !isNaN(precioFinal)){
                    if(isNaN(precioInicial)){
                        filtros.set("precioInicial", 1);
                        filtros.set("precioFinal", precioFinal);
                    }else if(isNaN(precioFinal)){
                        filtros.set("precioInicial", precioInicial);
                        filtros.set("precioFinal", 100000);
                    }else{
                        filtros.set("precioInicial", precioInicial);
                        filtros.set("precioFinal", precioFinal);
                    }
                    localStorage.setItem("pag", 1);
                    consulta();
                    link();
                }
            });
            let barraBusqueda = document.getElementById("search-bar"), precio1 = document.getElementById("input-precioInicial"), precio2 = document.getElementById("input-precioFinal");
            
            barraBusqueda.addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    document.getElementById("buscar-button").click();
                }
            });
            precio1.addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    document.getElementById("precio-btn").click();
                }
            });
            precio2.addEventListener("keypress", function(event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    document.getElementById("precio-btn").click();
                }
            });
    });
    </script>
    <hr>
    <section id="info">
        <section id="left-info">
            <div id="l-info-1">
                <img src="assets/imagenes/african-american-fitness-model-and-caucasian-man-talking-while-training-outdoors.jpg" alt="">
                <p>Nuestro catálogo de ropa deportiva ofrece indumentaria cómoda y novedosa, para que encuentres lo que mejor se adapte a tí!</p>
            </div>
            <div id="l-info-2">
                <h1>ROPA <br> DEPORTIVA </h1>
                <button id='l-info-2-btn1'>HOMBRE</button>
                <button id='l-info-2-btn2'>MUJER</button>
            </div>
        </section>

        <section id="right-info">
            <div id="r-info-1">
                <img src="assets/imagenes/boy-1853960_1920.jpg" alt="">
            </div>
            <div id="r-info-2">
                <p>Contamos con una amplia variedad de tablas, para que puedas aventurarte en donde quieras, con el mejor equipamiento y un estilo envidiable.</p>
                <h1>LAS MEJORES MARCAS</h1>
                <button id='r-info-2-btn'>COMPRAR AHORA</button>
            </div>
        </section>
    </section>

    <footer id='footer'>
        <div id="footer-main-container">
            <div id="redes" class="footer-items-containers">
                <h1 class="title">Redes</h1>
                <p><a href=""><i class="fa-brands fa-twitter footer-icons" id="tw">&nbsp<b>Twitter</b></i></a></p>
                <p><a href=""><i class="fa-brands fa-facebook-f footer-icons" id="fb">&nbsp&nbsp<b>Facebook</b></i></a></p>
                <p><a href=""><i class="fa-brands fa-instagram footer-icons" id="ig">&nbsp<b>Instagram</b></i></a></p>
            </div>
            <div id="about" class="footer-items-containers">
                <h1 class="title">Sobre nosotros</h1>
                <p><a href="assets/enlaces/institucional.php">Insitucional</a></p>
                <p><a href="assets/enlaces/equipo.php">Equipo</a></p>
            </div>
            <div id="contacto" class="footer-items-containers">
                <h1 class="title">Contacto</h1>
                <p>Montevideo, Carrasco</p>
                <p>Mones Roses 6633</p>
                <p>43856524</p>
                <p>delplata@gmail.com</p>
            </div>
        </div>
        <hr id="linea-footer">
        <p>Copyright &#169; 2022 Cubik WDD.</p>
    </footer>
    <div id='alert'>
        <h2></h2>
    </div>
    <script src="assets/scripts/main.js"></script>
    <style>.disclaimer{visibility:hidden;}</style>
    <style>
        #ult + div{
            visibility:hidden;
        }
        </style>
        <div id="ult"></div>
</body> 
</html>