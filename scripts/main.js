"use strict";
var gWarenkorb = null

window.addEventListener('load', _ => {
	const gesamtPreisElem = document.getElementById('GesamtPreis')
	const warenkorbElem = document.getElementById('Warenkorb')
	const bestellenBtn = document.getElementById('BestellenButton')
	if(gesamtPreisElem && warenkorbElem) {
		const gesamtPreis = new GesamtPreis(gesamtPreisElem)
		gWarenkorb = new Warenkorb(warenkorbElem, gesamtPreis, bestellenBtn)
	}
})

document.addEventListener("DOMContentLoaded", _ => {
	var scrollpos = localStorage.getItem('scrollpos');
	if (scrollpos) window.scrollTo(0, scrollpos);
});

window.addEventListener('beforeunload', _ => {
	localStorage.setItem('scrollpos', window.scrollY);
})

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
	constructor(warenkorbElem, gesamtPreis, bestellenBtn) {
		this.elem = warenkorbElem
		this.gesamtPreis = gesamtPreis
		this.bestellenBtn = bestellenBtn
		this.addressValue = ""
		this.validateBestellenBtn()
	}

	add(name, preis) {
		this.elem.add(Warenkorb.createOptionElement(name, preis))
		if (this.elem.size <10)
			this.elem.size++
		this.gesamtPreis.add(preis)
		this.validateBestellenBtn()
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
		if(options.length < 10)
			this.elem.size = options.length
		else
			this.elem.size=10
		this.validateBestellenBtn()
	}

	removeAll() {
		let length = this.elem.options.length
		for(let i = 0; i < length; i++) {
			this.gesamtPreis.preis = 0
			this.elem.options.remove(0)
		}
		this.elem.size = 0
		this.validateBestellenBtn()
	}

	addressInput(value) {
		this.addressValue = value
		this.validateBestellenBtn()
	}

	validateBestellenBtn() {
		if(this.addressValue && this.elem.options.length > 0)
			this.bestellenBtn.disabled = false
		else
			this.bestellenBtn.disabled = true
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

function sendForm(event) {
	event.target.form.submit()
}

function expandNavbar(event) {
	let navList = document.getElementById('nav-list')
	if(window.innerWidth > 530) {
		navList.style.display = 'flex'
		return
	}
	if (!navList.style.display || navList.style.display == "none") {
		navList.style.display = 'flex'
	}
	else {
		navList.style.removeProperty('display')
	}

}

function p(msg) {
	console.log(msg)
}

function d(obj) {
	console.dir(obj)
}
