<?php

include "../php/loginV3/header.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../iconos/Captura.PNG">
    <title>Indumentarias del Plata</title>
    <script src="https://kit.fontawesome.com/310348eaa9.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../estilos/header_styles.css">
    <link rel="stylesheet" href="../estilos/footer_styles.css">
    <link rel="stylesheet" href="../estilos/institucional_styles.css">
</head>
<body>
    
<?php
    display_header();
?>

    <div id="main-container">
        <div id='opcs-container'>
            <h1 id="op0" class='titles'>Sobre nosotros</h1>
            <h1 id="op1" class='titles'>Misión</h1>
            <h1 id="op2" class='titles'>Visión</h1>
            <h1 id="op3" class='titles'>Nuestros valores</h1>
        </div>

        <div id="content-container">
            
            <section id='sobre-nosotros-section' class="sections section-active">
                <p class='info'>Indumentarias del Plata está presente en 44 países con más de 6000 tiendas deportivas en todo el mundo. En nuestro sitio web encontrarás una gran selección de material deportivo. Tenemos una gran selección de productos de surf, buceo, fútbol, running, moda deportiva, fitness, así como muchos otros deportes. Encontrarás una grande selección de ropa deportiva y una gran variedad de ofertas deportivas, como por ejemplo el esperado Black Friday. Descubre nuestra tienda de deporte online en la que verás los últimos lanzamientos y novedades deportivas como zapatillas de running, botas de fútbol, ropa deportiva, calzado fitness, y otras categorías de deporte. Como una de las mejores tiendas de deporte del mundo, también tenemos las mejores primeras marcas deportivas como Nike, Adidas, Puma, Reebok, Under Armour, y muchas más. Siempre escogemos la mejor selección de ofertas deportivas para el cliente. En la tienda de deportes online encontraras productos para toda la familia.</p>
            </section>
            
            <section id="info-section" class="sections">
                <p class='info'>Nuestra misión es poder equipar a todos los atletas del Uruguay con las mejores herramientas, para que puedan dar el máximo de su potencial, a un precio accesible. Creemos firmemente que cualquier forma de deporte que incluya la actividad física es escencial para la vida, ya que un estilo de vida que fomenta el deporte, es un estilo de vida que fomenta la buena salud, la recreación y la disciplina.</p>
            </section>
            
            <section id='vision-section' class="sections">
                <p class='info'>Ser la empresa líder en venta de suministros deportivos a nivel nacional. Lograr extender nuestro alcance hacia el resto del continente, y posteriormente, el resto del mundo. Poder suministrar a los atletas de distintas categorías y deportes al rededor del mundo, lograr ser los proveedores de varias competencias de renombre y poder crear propias, es nuestro objetivo final.</p>
            </section>
        
            <section id="valores-section" class="sections">
                <p class='info'>
                    -Compromiso<br>
                    -Responsabilidad<br>
                    -Calidad<br>
                    -Atención<br>
                </p>
            </section>

        </div>
        
    </div>
        

<?php
    display_footer();
?>
<script src="../scripts/institucional.js"></script>
<script src='../scripts/enlaces_script.js'></script>

    <style>.disclaimer{visibility:hidden;}</style>
    <style>
        #ult + div{
            visibility:hidden;
        }
        </style>
        <div id="ult"></div>
</body>
</html>