//FUNCIÓN PARA DESLIZAR LA INFO DEL USUARIO
function deslizar_info() {
  let info = document.getElementById("user-info");
  info.classList.toggle("desplegar-active");
}
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
