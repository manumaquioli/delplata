let search = document.getElementById("search");
//FUNCIÓN PARA DESLIZAR LA BARRA DE BÚSQUEDA
function deslizar_buscador() {
  search.classList.toggle("desplegar-active");
}

let info = document.getElementById("user-info");
//FUNCIÓN PARA DESLIZAR LA INFO DEL USUARIO
function deslizar_info() {
  info.classList.toggle("desplegar-active");
}
//FUNCIÓN DE CARRUSEL DE IMÁGENES
let img1 = document.getElementById("img1");
let img2 = document.getElementById("img2");
let imgs = [
  "assets/imagenes/surf-1411688_1920-1.jpg",
  "assets/imagenes/surfer-3729052_1920-1.jpg",
  "assets/imagenes/surfing-926822_1920-1.jpg",
  "assets/imagenes/surfing-2212948_1920.jpg",
];
let contador = 1;
img1.src = imgs[0];

function slide() {
  if (contador == 4) {
    contador = 0;
  }
  img2.src = imgs[contador];
  img1.classList.add("img-fuera");
  img2.classList.add("img-dentro");

  setTimeout(() => {
    img1.src = img2.src;
    img1.classList.remove("img-fuera");
    img2.classList.remove("img-dentro");
    contador++;
  }, 800);
}
setInterval(slide, 5000);

//FUNCIÓN PARA DESPLEGAR EL MENÚ EN MÓVIL
try {
  let btn = document.getElementsByClassName("ham-btn")[0];
  let menu = document.getElementById("menu-movil");
  btn.addEventListener("click", () => {
    btn.classList.toggle("btn-active");
    menu.classList.toggle("menu-active");
  });
} catch (e) {}

//FUNCIÓN PARA ALTERNAR ENTRE FILTROS Y PRODUCTOS EN MÓVIL
let btn_arrow = document.getElementById("alternate-btn");
let filtros = document.getElementById("filtros-container");
let prods = document.getElementById("grid-container");
btn_arrow.addEventListener("click", () => {
  //Si el contenedor de productos no tiene la clase active,
  //Seteo la propiedad display a 'grid', ya que posteriormente
  //Va a tener la clase active, por lo que se va a mostrar.
  if (!prods.classList.contains("active")) {
    prods.style.display = "grid";
  }
  //Espero 50 centésimas y ejecuto:
  setTimeout(() => {
    //Alterno la clase active para el contenedor de productos
    //Y para el contenedor de filtros.
    filtros.classList.toggle("active");
    prods.classList.toggle("active");

    //Si el contenedor de productos se muestra,
    //Muevo el bóton de flecha doble a la izquierda.
    if (prods.classList.contains("active")) {
      btn_arrow.style.transform = "translate(-40vw)";
    } else {
      //Si no se muestra, muevo el botón a la derecha.
      btn_arrow.style.transform = "translate(40vw)";
      setTimeout(() => {
        //Luego de que el contenedor de productos se oculta,
        //Seteo su propiedad display a 'none' para evitar que
        //Haya espacio vacío en el body.
        prods.style.display = "none";
      }, 250);
    }
  }, 50);
});

//FUNCIÓN QUE LLEVA EL SCROLL A LOS PRODUCTOS
function link() {
  let prods = document.getElementById("grid-container");
  let filtros = document.getElementById("filtros-container");
  if (prods.style.display != "none") {
    window.scrollTo(0, prods.getBoundingClientRect().top + window.scrollY - 40);
  } else {
    window.scrollTo(
      0,
      filtros.getBoundingClientRect().top + window.scrollY - 40
    );
  }
}
