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
    <link rel="stylesheet" href="../estilos/equipo_styles.css">
</head>
<body>
    
<?php
    display_header();
?>

    <div id="team-container">
        <div class="team-items">
            <img src="../imagenes/jovana-kovac-adult-2.jpg" alt="">
            <div class='info'>
                <section class='name-section'>
                    <p>Sofía</p>
                    <p>Fernández</p>
                </section>
                <p>Ella es una gran líder, siempre atenta a todo lo que pueda significar un inconveniente, no se le escapa ningún detalle.</p>
            </div>
        </div>
        <div class="team-items">
            <img src="../imagenes/goran-anucojic-adult.jpg" alt="">
            <div class='info'>
                <section class='name-section'>
                    <p>Carlos</p>
                    <p>Marquez</p>
                </section>
                <p>Él es quien nos asesora en cuestiones legales, cualquier duda que tengas sobre trámites y procesos legales, Carlos lo resuelve.</p>
            </div>
        </div>
        <div class="team-items">
            <img src="../imagenes/iva-adult.jpg" alt="">
            <div class='info'>
                <section class='name-section'>
                    <p>María</p>
                    <p>Gómez</p>
                </section>
                <p>Es la mejor para administrar cualquier cosa que pueda ser administrada, no importa que tan caótico sea algo, ella lo organiza.</p>
            </div>
        </div>
        <div class="team-items">
            <img src="../imagenes/dusan_adult.jpg" alt="">
            <div class='info'>
                <section class='name-section'>
                    <p>Rodrigo</p>
                    <p>Pérez</p>
                </section>
                <p>Rodrigo es el encargado del stock, él sabe todo lo que le puedas llegar a preguntar sobre nuestros productos, hasta memoriza sus códigos.</p>
            </div>
        </div>
        <div class="team-items">
            <img src="../imagenes/suzana-andjelkovic-adult-2.jpg" alt="">
            <div class='info'>
                <section class='name-section'>
                    <p>Nadia</p>
                    <p>Lima</p>
                </section>
                <p>Nadia sabe mucho de finanzas, es la que nos dice cómo está nuestra economía y realiza otras tareas referentes a ese aspecto.</p>
            </div>
        </div>
        <div class="team-items">
            <img src="../imagenes/slobodan-adult.jpg" alt="">
            <div class='info'>
                <section class='name-section'>
                    <p>Pedro</p>
                    <p>Machado</p>
                </section>
                <p>Pedro se encarga de la publicidad, sabe como llegar a las personas, es el integrante más amistoso del grupo.</p>
            </div>
        </div>
    </div>

<?php
    display_footer();
?>

    <script src="../scripts/enlaces_script.js"></script>
    
    <style>.disclaimer{visibility:hidden;}</style>
    <style>
        #ult + div{
            visibility:hidden;
        }
        </style>
        <div id="ult"></div>
        
</body>
</html>