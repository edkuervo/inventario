$(document).ready(function(){
	const header = document.querySelector('header');

	let requestURL = 'inventario.json';
	let request = new XMLHttpRequest();
	request.open('GET', requestURL);
	request.responseType = 'json';
	request.send();
	request.onload = function() {
		const inventario = request.response;
		prueba(inventario, header);
	}
});

function prueba(inv, header) {
	const myH2 = document.createElement('h2');
	myH2.textContent = inv['squadname'];
	header.appendChild(myH2);
}