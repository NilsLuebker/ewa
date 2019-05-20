// Globals
var gWarenkorb = null

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

	remove(preis) {
		this.preis = this.preis - preis
	}

	set preis(preis) {
		this.elem.dataset.value = preis
		this.elem.textContent = GesamtPreis.formatCurrency(preis)
	}

	get preis() {
		return Number(this.elem.dataset.value)
	}

	static formatCurrency(preis) {
		return `${preis.toFixed(2)} €`
	}
}

class Warenkorb {
	constructor(warenkorbElem, gesamtPreis) {
		this.elem = warenkorbElem
		this.gesamtPreis = gesamtPreis
	}

	add(name, preis) {
		this.elem.add(Warenkorb.createOptionElement(name, preis))
		this.elem.size++
		this.gesamtPreis.add(preis)
	}

	selectAll() {
		for(let i = 0; i < this.elem.options.length; i++) {
			this.elem.options[i].selected = true
		}
	}

	deleteSelected() {
		while(this.elem.options.selectedIndex != -1) {
			this.gesamtPreis.remove(this.elem.options[this.elem.options.selectedIndex].dataset.preis)
			this.elem.options.remove(this.elem.options.selectedIndex)
		}
		this.elem.size = this.elem.options.length
	}

	deleteAll() {
		this.elem.options.length = 0
		this.elem.size = 0
		this.gesamtPreis.preis = 0
	}

	static createOptionElement(name, preis) {
		const optionElem = document.createElement('option')
		optionElem.text = name
		optionElem.value = name.toLowerCase()
		optionElem.dataset.preis = preis
		return optionElem
	}
}

function sendForm(event) {
	event.target.form.submit()
}


function p(msg) {
	console.log(msg)
}

function d(obj) {
	console.dir(obj)
}
