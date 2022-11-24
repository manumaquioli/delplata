//FUNCIÓN PARA DESLIZAR LA INFO DEL USUARIO
function deslizar_info() {
  let info = document.getElementById("user-info");
  info.classList.toggle("desplegar-active");
}

function addToCart(id, e) {
  const data = new FormData();
  data.set("id", id);

  if (document.URL.search("assets") == -1) {
    url = "assets/php/loginV3/validarCart.php";
  } else {
    url = "../php/loginV3/validarCart.php";
  }
  fetch(url, {
    method: "POST",
    body: data,
  })
    .then(function (response) {
      if (response.ok) {
        return response.text();
      } else {
        throw "Error";
      }
    })
    .then(function (texto) {
      document.getElementById("alert").children[0].innerHTML = texto;
      document.getElementById("alert").classList.add("alert-active");
      setTimeout(() => {
        document.getElementById("alert").classList.remove("alert-active");
      }, 1500);
      return texto;
    })
    .catch(function (err) {
      console.log(err);
    });
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
