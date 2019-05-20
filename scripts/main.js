// Globals
var gWarenkorb = null

window.onload = () => {
	const gesamtPreisElem = document.getElementById('GesamtPreis')
	const warenkorbElem = document.getElementById('Warenkorb')
	if(gesamtPreisElem && warenkorbElem) {
		const gesamtPreis = new GesamtPreis(gesamtPreisElem)
		gWarenkorb = new Warenkorb(warenkorbElem, gesamtPreis)
	}
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

	removeSelected() {
		let options = this.elem.options
		let index;
		while((index = options.selectedIndex) != -1) {
			this.gesamtPreis.remove(options[index].dataset.preis)
			options.remove(index)
		}
		this.elem.size = options.length
	}

	removeAll() {
		let length = this.elem.options.length
		for(let i = 0; i < length; i++) {
			this.gesamtPreis.preis = 0
			this.elem.options.remove(0)
		}
		this.elem.size = 0
	}

	validate(event) {
		if(this.elem.options.length == 0) {
			event.preventDefault()
		}
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
