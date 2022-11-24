<?php
ob_start();
function display_header(){

    if(!isset($_SESSION['logUsuario'])){
        session_start();
    }
    if(isset($_GET["logout"])){
        session_unset();
        header("location:../../index.php");
    }
    if(!empty($_SESSION['logUsuario']) && $_SESSION['logTipo'] == "cliente"){
        echo '<i class="fa-solid fa-bars ham-btn"></i>
                <div id="menu-movil">
                    <a href="../../index.php" class="movil-items">Inicio</a>
                    <a href="institucional.php" class="movil-items">Institucional</a>
                    <a href="equipo.php" class="movil-items">Equipo</a>
                    <a href="#footer" class="movil-items">Contacto</a>
               <!-- <a href="../../index.php"><img id="menu-logo" src="../iconos/Captura.PNG" alt=""></a> -->
                </div>
        ';
        ?>
        <header id="navbar">
            <a href="../../index.php" id="logo"><img src="../iconos/Captura.PNG" alt=""></a>
            <div id="items-container">
                <a href='../../index.php'>Inicio</a>
                <a href='institucional.php'>Institucional</a>
                <a href='equipo.php'>Equipo</a>
                <a href='#footer'>Contacto</a>
                <a href="cart.php"><i class="fa-solid fa-cart-shopping header-icons"></i></a>
            </div>
            <div id="text-header-container">
                Indumentarias del Plata
                <a href="cart.php"><i class="fa-solid fa-cart-shopping header-icons"></i></a>
            </div>  
            <div id="user-container" onclick="deslizar_info()">
               <?php echo $_SESSION['logUsuario'].'<i id="user-icon" class="fa-solid fa-user"></i>'?>
            </div>       
            </header>
                <form id="user-info" action="../../index.php?logout">
                <div id="account"><a href="mi_cuenta.php">Mi Cuenta</a></div>
                <div id="historial"><a href="mis_compras.php">Mis Compras</a></div>
                <input type="submit" id="logout" readonly="readonly" value="Cerrar sesión" name="logout">
              </form>
   <?php }else{
        session_unset();
        ?>
            <i class="fa-solid fa-bars ham-btn"></i>
            <div id="menu-movil">
                <a href="../../index.php" class="movil-items">Inicio</a>
                <a href="institucional.php" class="movil-items">Institucional</a>
                <a href="equipo.php" class="movil-items">Equipo</a>
                <a href="#footer" id='contacto-btn' class="movil-items">Contacto</a>
            </div>
            <header id="navbar">
                <a href="../../index.php" id="logo"><img src="../iconos/Captura.PNG" alt=""></a>
                <div id="items-container">
                    <a href='../../index.php'>Inicio</a>
                    <a href='institucional.php'>Institucional</a>
                    <a href='equipo.php'>Equipo</a>
                    <a href='#footer'>Contacto</a>
                </div>
                <div id="text-header-container">
                    Indumentarias del Plata
                </div>   
                <div id="icons-container">
                    <a href="../enlaces/login.php"><i class="fa-solid fa-user header-icons"></i></a>
                </div>
            </header>
   <?php }
    }
    function display_footer(){
        ?>
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
                <p><a href="">Insitucional</a></p>
                <p><a href="">Equipo</a></p>
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
<?php
    }
?>