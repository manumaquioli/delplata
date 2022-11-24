let info = document.getElementById("user-info");
//FUNCIÓN PARA DESLIZAR LA INFO DEL USUARIO
function deslizar_info() {
  info.classList.toggle("desplegar-active");
}
//FUNCIÓN PARA SLIDER DE PRODUCTOS
let left_btn = document.getElementById("left-btn");
let right_btn = document.getElementById("right-btn");

left_btn.addEventListener("click", () => {
  document.getElementById("prod-slider").scrollTo(0, 0);
});
right_btn.addEventListener("click", () => {
  document.getElementById("prod-slider").scrollTo(1214, 0);
});

//FUNCIÓN PARA DESPLEGAR EL MENÚ EN MÓVIL
try {
  let btn = document.getElementsByClassName("ham-btn")[0];
  let menu = document.getElementById("menu-movil");
  btn.addEventListener("click", () => {
    btn.classList.toggle("btn-active");
    menu.classList.toggle("menu-active");
  });

  //Oculta el menú lateral si se presiona el botón contacto.
  document.getElementById("contacto-btn").addEventListener("click", () => {
    btn.classList.remove("btn-active");
    menu.classList.remove("menu-active");
  });
} catch (e) {}
