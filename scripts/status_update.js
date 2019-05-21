"use strict";
const request = new XMLHttpRequest();

window.onload = () => {
	window.setInterval(requestData, 2000)
}

function process(jsonString) {
	let statusArr = JSON.parse(jsonString)
	if(!statusArr || !Array.isArray(statusArr)) return
	let bestellstatus = document.getElementById('Bestellstatus')
	if(!bestellstatus) return
	clearChilds(bestellstatus)
	for(let statusObj of statusArr) {
		let statusPara = createStatusLine(statusObj)
		if(!statusPara) continue
		bestellstatus.appendChild(statusPara)
	}
}

function clearChilds(htmlElem) {
	while(htmlElem.firstChild) {
		htmlElem.removeChild(htmlElem.firstChild)
	}
}

function createStatusLine(statusObj) {
	if(!(statusObj && statusObj.name && statusObj.status)) return undefined
	let para = document.createElement('p')
	let textNode = document.createTextNode(`${statusObj.name}: ${statusObj.status}`)
	para.appendChild(textNode)
	return para
}

function requestData() {
	request.open('GET', 'KundenStatus.php')
	request.onreadystatechange = processData
	request.send(null)
}

function processData() {
	if(request.readyState != 4) return
	if(request.status != 200) return console.error('Uebertragung fehlgeschlagen')
	if(request.responseText == null) return console.error('Dokument ist leer')
	process(request.responseText)
}
