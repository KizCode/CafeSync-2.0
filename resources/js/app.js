const menuCards = document.querySelectorAll('[data-menu-card]');
const selectedCount = document.querySelector('#selected-count');
const orderTotal = document.querySelector('#order-total');
const cartLines = document.querySelector('[data-cart-lines]');
const sidebar = document.querySelector('[data-sidebar]');
const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
const sidebarOverlay = document.querySelector('[data-sidebar-overlay]');
const menuSearch = document.querySelector('[data-menu-search]');
const categoryButtons = document.querySelectorAll('[data-category-filter]');
const paidInput = document.querySelector('[data-pay-total]');
const changeOutput = document.querySelector('[data-pay-change]');

const setSidebarOpen = (isOpen) => {
	if (!sidebar || !sidebarToggle || !sidebarOverlay) {
		return;
	}

	sidebar.classList.toggle('is-open', isOpen);
	sidebarOverlay.classList.toggle('is-visible', isOpen);
	sidebarToggle.setAttribute('aria-expanded', String(isOpen));
};

sidebarToggle?.addEventListener('click', () => {
	setSidebarOpen(!sidebar.classList.contains('is-open'));
});
sidebarOverlay?.addEventListener('click', () => setSidebarOpen(false));
sidebar?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setSidebarOpen(false)));

const formatCurrency = (amount) => `Rp ${new Intl.NumberFormat('id-ID').format(amount)}`;

const updateOrderSummary = () => {
	let itemCount = 0;
	let total = 0;
	const lines = [];

	menuCards.forEach((card) => {
		const input = card.querySelector('[data-quantity-input]');
		const quantity = Number(input.value) || 0;
		const price = Number(card.dataset.price);

		itemCount += quantity;
		total += quantity * price;
		card.classList.toggle('is-selected', quantity > 0);

		if (quantity > 0) {
			lines.push({
				name: card.dataset.name,
				quantity,
				total: quantity * price,
			});
		}
	});

	if (selectedCount) {
		selectedCount.textContent = `${itemCount} item`;
	}

	if (orderTotal) {
		orderTotal.textContent = formatCurrency(total);
	}

	if (cartLines) {
		cartLines.replaceChildren();

		if (lines.length === 0) {
			const empty = document.createElement('li');
			empty.className = 'kasir-empty';
			empty.textContent = 'Keranjang masih kosong.';
			cartLines.append(empty);
		} else {
			lines.forEach((line) => {
				const item = document.createElement('li');
				const label = document.createElement('span');
				const amount = document.createElement('strong');
				label.textContent = `${line.name} x${line.quantity}`;
				amount.textContent = formatCurrency(line.total);
				item.append(label, amount);
				cartLines.append(item);
			});
		}
	}
};

menuCards.forEach((card) => {
	const input = card.querySelector('[data-quantity-input]');
	const decreaseButton = card.querySelector('[data-quantity-button="decrease"]');
	const increaseButton = card.querySelector('[data-quantity-button="increase"]');

	const setQuantity = (quantity) => {
		input.value = Math.min(Math.max(quantity, 0), Number(input.max));
		updateOrderSummary();
	};

	decreaseButton.addEventListener('click', () => setQuantity(Number(input.value) - 1));
	increaseButton.addEventListener('click', () => setQuantity(Number(input.value) + 1));
	input.addEventListener('input', () => setQuantity(Number(input.value)));
});

const applyMenuFilters = () => {
	const query = menuSearch?.value.trim().toLowerCase() ?? '';
	const activeCategory = document.querySelector('[data-category-filter].is-active')?.getAttribute('data-category-filter') ?? '';

	menuCards.forEach((card) => {
		const matchesName = (card.dataset.name ?? '').toLowerCase().includes(query);
		const matchesCategory = activeCategory === '' || card.dataset.category === activeCategory;
		card.hidden = !(matchesName && matchesCategory);
	});
};

categoryButtons.forEach((button) => {
	button.addEventListener('click', () => {
		categoryButtons.forEach((item) => item.classList.toggle('is-active', item === button));
		applyMenuFilters();
	});
});

menuSearch?.addEventListener('input', applyMenuFilters);

const productSearch = document.querySelector('#product-search');

productSearch?.addEventListener('input', () => {
	const query = productSearch.value.trim().toLowerCase();

	document.querySelectorAll('[data-product-row]').forEach((row) => {
		const haystack = (row.dataset.search ?? row.textContent ?? '').toLowerCase();
		row.hidden = query !== '' && !haystack.includes(query);
	});
});

const updateChange = () => {
	if (!paidInput || !changeOutput) {
		return;
	}

	const total = Number(paidInput.getAttribute('data-pay-total'));
	const paid = Number(paidInput.value) || 0;
	changeOutput.textContent = formatCurrency(Math.max(paid - total, 0));
};

paidInput?.addEventListener('input', updateChange);
document.querySelectorAll('[data-pay-amount]').forEach((button) => {
	button.addEventListener('click', () => {
		if (!paidInput) {
			return;
		}

		paidInput.value = button.getAttribute('data-pay-amount');
		updateChange();
	});
});

updateOrderSummary();
updateChange();

const clock = document.querySelector('[data-clock]');

if (clock) {
	const tick = () => {
		clock.textContent = new Intl.DateTimeFormat('id-ID', {
			weekday: 'short',
			day: '2-digit',
			month: 'short',
			hour: '2-digit',
			minute: '2-digit',
		}).format(new Date());
	};

	tick();
	setInterval(tick, 30000);
}
