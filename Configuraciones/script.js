let mostrador = document.getElementById("mostrador");
let seleccion = document.getElementById("seleccion");
let imgSeleccionada = document.getElementById("img");
let modeloSeleccionado = document.getElementById("modelo");
let descripSeleccionada = document.getElementById("descripcion");

let precioSeleccionado = document.getElementById("precio");


function cargar(item) {
    quitarBordes();
    item.style.border = "2px solid #071abeff"; // Tu color de borde

    // Muestra la sección de selección
    mostrador.style.width = "60%";
    seleccion.style.width = "40%";
    seleccion.style.opacity = "1";

    // Rellena los datos en el modal
    imgSeleccionada.src = item.querySelector("img").src;
    modeloSeleccionado.innerHTML = item.querySelector(".descripcion").innerHTML;
    descripSeleccionada.innerHTML = item.querySelector(".full-descripcion").innerHTML;
    precioSeleccionado.innerHTML = item.querySelector(".precio").innerHTML;
}

function cerrar() {
    mostrador.style.width = "100%";
    seleccion.style.width = "0%";
    seleccion.style.opacity = "0";
    quitarBordes();
}


function quitarBordes() {
    var items = document.getElementsByClassName("item");

    for (let i = 0; i < items.length; i++) {
        items[i].style.border = "none";
    }
}