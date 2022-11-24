let info = document.getElementById("user-info");
//FUNCIÓN PARA DESLIZAR LA INFO DEL USUARIO
function deslizar_info() {
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
} catch (e) {}
