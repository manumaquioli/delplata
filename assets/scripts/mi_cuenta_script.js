//FUNCIÓN PARA DESLIZAR LA INFO DEL USUARIO
function deslizar_info() {
  let info = document.getElementById("user-info");
  info.classList.toggle("desplegar-active");
}

//FUNCIÓN PARA MOSTRAR LAS DISTINTAS OPCIONES
let opcs = document.getElementsByClassName("panel-items");
let containers = document.getElementsByClassName("content-containers");
let correo_enlace = document.getElementById("correo-enlace");
let ubicacion_enlace = document.getElementById("ubicacion-enlace");
function changeData() {
  for (let x = 0; x < containers.length; x++) {
    if (
      containers[x].classList.contains("function-in") &&
      containers[x] != document.getElementById("change-data-container")
    ) {
      containers[x].classList.remove("function-in");
      containers[x].classList.add("function-out");
      setTimeout(() => {
        containers[x].classList.remove("function-out");
      }, 600);
    }
    document
      .getElementById("change-data-container")
      .classList.add("function-in");
  }
}

for (let i = 0; i < opcs.length; i++) {
  opcs[i].addEventListener("click", () => {
    for (let x = 0; x < containers.length; x++) {
      if (
        containers[x].classList.contains("function-in") &&
        containers[x] != containers[i]
      ) {
        containers[x].classList.remove("function-in");
        containers[x].classList.add("function-out");
        setTimeout(() => {
          containers[x].classList.remove("function-out");
        }, 600);
      }
    }
    containers[i].classList.add("function-in");
  });
}

//FUNCIÓN PARA DESPLEGAR EL MENÚ EN MÓVIL
try {
  let btn = document.getElementsByClassName("ham-btn")[0];
  let menu = document.getElementById("panel");
  btn.addEventListener("click", () => {
    btn.classList.toggle("btn-active");
    menu.classList.toggle("menu-active");
  });
} catch (e) {}
