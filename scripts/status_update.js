"use strict";
const request = new XMLHttpRequest();

window.onload = () => {
	window.setInterval(requestData, 2000)
}

function process(jsonString) {
	let statusArr = JSON.parse(jsonString)
	if(!statusArr || !Array.isArray(statusArr)) return
	let bestellstatus = document.getElementById('bestellstatus')
	if(!bestellstatus) return
	clearChilds(bestellstatus)
	for(let statusObj of statusArr) {
		if(!statusObj.name && !statusObj.status) continue
		bestellstatus.appendChild(createListItem(statusObj.name))
		bestellstatus.appendChild(createListItem(statusObj.status))
	}
}

function clearChilds(htmlElem) {
	while(htmlElem.hasChildNodes()) {
		htmlElem.removeChild(htmlElem.firstChild)
	}
}

function createListItem(text) {
	if(!text) return
	let listItem = document.createElement('li')
	let textNode = document.createTextNode(`${text}`)
	listItem.appendChild(textNode)
	return listItem
}

function requestData() {
	request.open('GET', 'KundenStatus.php')
	request.onreadystatechange = processData
	request.send(null)
}

function processData() {
	if(request.readyState != 4) return
	if(request.status != 200) return console.error('Uebertragung fehlgeschlagen')
	if(request.responseText) return console.error('Dokument ist leer')
	process(request.responseText)
}
