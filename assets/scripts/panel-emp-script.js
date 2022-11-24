//FUNCIÓN PARA DESLIZAR LA INFO DEL USUARIO
function deslizar_info() {
  let info = document.getElementById("user-info");
  info.classList.toggle("desplegar-active");
}

//FUNCIÓN PARA MOSTRAR LAS DISTINTAS FUNCIONALIDADES DEL EMPLEADO
let opcs = document.getElementsByClassName("panel-items");
let containers = document.getElementsByClassName("function-containers");

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

//FUNCIÓN PARA FILTROS DE GESTIONES (HABILITA CAMPOS, NO FILTRA)
select = document.getElementById("gestiones-select");
select.addEventListener("change", () => {
  document.getElementById("busqueda-gestion").value = "";
  document.getElementById("busqueda-gestion-fecha").value = "";
  for (let i of document.getElementsByClassName("checkbox-labels")) {
    i.children[0].checked = false;
  }

  if (
    select.options[select.selectedIndex].value == 1 ||
    select.options[select.selectedIndex].value == 4
  ) {
    //Si la opción seleccionada tiene valor 1 o 4 se muestra solo un campo de texto.
    for (let v of document.getElementsByClassName("checkbox-labels")) {
      v.style.display = "none";
    }
    document.getElementById("busqueda-gestion").type = "text";
    document.getElementById("busqueda-gestion").style.display = "unset";
    document.getElementById("busqueda-gestion-fecha").style.display = "none";
  } else if (select.options[select.selectedIndex].value == 2) {
    //Si la opción seleccionada tiene valor 2 se muestran campos de tipo checkbox.
    document.getElementById("busqueda-gestion").style.display = "none";
    document.getElementById("busqueda-gestion-fecha").style.display = "none";
    for (let v of document.getElementsByClassName("checkbox-labels")) {
      v.style.display = "unset";
    }
  } else if (select.options[select.selectedIndex].value == 3) {
    //Si la opción seleccionada tiene valor 3 se muestran dos campos de fecha.
    for (let v of document.getElementsByClassName("checkbox-labels")) {
      v.style.display = "none";
    }
    document.getElementById("busqueda-gestion").type = "date";
    document.getElementById("busqueda-gestion").style.display = "unset";
    document.getElementById("busqueda-gestion-fecha").style.display = "unset";
  }
});
//FUNCIÓN PARA DESPLEGAR EL MENÚ EN MÓVIL
try {
  let btn = document.getElementsByClassName("ham-btn")[0];
  let menu = document.getElementById("panel");
  btn.addEventListener("click", () => {
    btn.classList.toggle("btn-active");
    menu.classList.toggle("menu-active");
  });
} catch (e) {
  console.log(e);
}

//Función para mostrar distintas columnas en la seccion de gestiones.
let checks = document.getElementsByClassName("checks-col");
for (const check of checks) {
  check.checked = true;
}
document.getElementById("col-select-btn").addEventListener("click", () => {
  for (let check of checks) {
    //alert(check.name);
    if (check.checked) {
      for (let col of document.getElementsByClassName(check.name)) {
        // col.style.backgroundColor='green';
        // col.style.display='inherit';
        col.classList.remove("hide-cols");
      }
    } else {
      for (let col of document.getElementsByClassName(check.name)) {
        // col.style.backgroundColor='red';
        // col.style.display='none';
        col.classList.add("hide-cols");
      }
    }
  }
});
