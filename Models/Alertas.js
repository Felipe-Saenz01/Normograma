function AlertEl(id){
    Swal.fire({
	title: 'Esta seguro?',
	text: "Una vez eliminada esta norma no sera posible recuperarla",
	icon: 'warning',
	showCancelButton: true,
	confirmButtonColor: '#037207',
	cancelButtonColor: '#d33',
	confirmButtonText: 'Eliminar',
	cancelButtonText: 'cancelar'
    }).then((result) => {
	if (result.isConfirmed) {
	    window.location.href = "eliminar.php?id="+id;
	}
    })
}

function alertClaEl(id){

	Swal.fire({
		title: 'Esta seguro?',
		text: "Una vez eliminada esta Clasificación no será posible recuperarla",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#037207',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Eliminar',
		cancelButtonText: 'cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "eliminarParametro.php?id="+id+"&origen=clasificacion";
		}
		})

}

function alertDep(id){

	Swal.fire({
		title: 'Esta seguro?',
		text: "Una vez eliminada esta Dependencia no sera posible recuperarla",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#037207',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Eliminar',
		cancelButtonText: 'cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "eliminarParametro.php?id="+id+"&origen=dependencia";
		}
		})

}

function alertEstade(id){

	Swal.fire({
		title: 'Esta seguro?',
		text: "Una vez eliminado este estado no sera posible recuperarlo",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#037207',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Eliminar',
		cancelButtonText: 'cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "eliminarParametro.php?id="+id+"&origen=estado";
		}
		})

}

function alertEmisor(id){

	Swal.fire({
		title: 'Esta seguro?',
		text: "Una vez eliminado este emisor no sera posible recuperarlo",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#037207',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Eliminar',
		cancelButtonText: 'cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "eliminarParametro.php?id="+id+"&origen=emisor";
		}
		})

}

function alertCuent(id){

	Swal.fire({
		title: 'Esta seguro?',
		text: "Una vez eliminado este Usuario no sera posible recuperarlo",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#037207',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Eliminar',
		cancelButtonText: 'cancelar'
		}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = "eliminarC.php?id="+id;
		}
		})

}

