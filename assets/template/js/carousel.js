/*var IMAGENES = [
                'assets/template/img/nc.svg',
                'assets/template/img/ts.svg',
                'assets/template/img/ffc.svg'
            ];*/
$(function() {
  	//"use strict"; // Start of use strict
  	console.log("Loaded...");
	$('#retroceder').click(function() {
		retrocederFoto();
	});
	$('#avanzar').click(function() {
		pasarFoto();
	});

    $('#retroceder-dm').click(function() {
        changeDrawMode(-1);
    });
    $('#avanzar-dm').click(function() {
        changeDrawMode(1);
    });

  	//console.log($('#retroceder'));
	// Iniciar
	renderizarImagen();

}); // End of use strict

function pasarFoto() {
    console.log("pasarFoto");
    if(posicionActual >= IMAGENES.length - 1) {
        posicionActual = 0;
    } else {
        posicionActual++;
    }
    changePattern(posicionActual);
    //renderizarImagen();
}

/**
 * Funcion que cambia la foto en la anterior posicion
 */
function retrocederFoto() {
    console.log("retrocederFoto");
    if(posicionActual <= 0) {
        posicionActual = IMAGENES.length - 1;
    } else {
        posicionActual--;
    }
    changePattern(posicionActual);
    //renderizarImagen();
}

/**
 * Funcion que actualiza la imagen de imagen dependiendo de posicionActual
 */
function renderizarImagen () {
    $('#imagen').css("background-image","url("+base_url + IMAGENES[posicionActual]+")");
}