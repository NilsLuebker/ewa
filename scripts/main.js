// Globals
let gWarenkorb = null

window.onload = () => {
	const gesamtPreisElem = document.getElementById('GesamtPreis')
	const gesamtPreis = new GesamtPreis(gesamtPreisElem)
	const warenkorbElem = document.getElementById('Warenkorb')
	gWarenkorb = new Warenkorb(warenkorbElem, gesamtPreis)
}

class GesamtPreis {
	constructor(gesamtPreisElem) {
		this.elem = gesamtPreisElem
		this.preis = 0;
	}

	add(preis) {
		this.preis = this.preis + preis
	}

	set preis(preis) {
		this.elem.dataset.value = preis
		this.elem.innerHTML = GesamtPreis.formatCurrency(preis)
	}

	get preis() {
		return Number(this.elem.dataset.value)
	}

	static formatCurrency(preis) {
		return `${preis.toFixed(2)} &euro;`
	}
}

class Warenkorb {
	constructor(warenkorbElem, gesamtPreis) {
		this.elem = warenkorbElem
		this.elem.style.display = 'none'
		this.gesamtPreis = gesamtPreis
	}

	add(name, preis) {
		p(`adding ${name}`)
		this.elem.add(Warenkorb.createOptionElement(name, preis))
		this.elem.size++
		this.elem.style.display = 'inline-block'
		this.gesamtPreis.add(preis)
	}

	static createOptionElement(name, preis) {
		const optionElem = document.createElement('option')
		optionElem.text = name
		optionElem.value = name.toLowerCase()
		optionElem.dataset.preis = preis
		return optionElem
	}
}


function p(msg) {
	console.log(msg)
}

function d(obj) {
	console.dir(obj)
}
