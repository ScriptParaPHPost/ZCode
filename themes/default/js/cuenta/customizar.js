export function handleChangeColor() {
	const inputColor = $('input[type="color"]');
	let customizar = {
		light: $('input[name="light"]').val(),
		dark: $('input[name="dark"]').val()
	}

	const updateCustomizer = (type, value) => {
		customizar[type] = value;
		const custom = generateThemeColors('customizer', customizar.light, customizar.dark, type);
		$('#customizer_style').html(custom);
	};

	inputColor.on('input', function(e) {
		updateCustomizer(e.target.name, $(this).val());
	}).on('change', function() {
		$.post(`${ZCodeApp.url}/cuenta-customizer.php`, customizar);
	});
}

function generateThemeColors(nameColor, lightColor = '#212121', darkColor = '#F4F4F4', from) {
	const lightenDarkenColor = (col, amt) => {
		let usePound = false;
		if (col[0] === "#") {
			col = col.slice(1);
			usePound = true;
		}
		const num = parseInt(col, 16);
		let r = Math.max(0, Math.min(255, (num >> 16) + amt));
		let g = Math.max(0, Math.min(255, (num >> 8 & 0x00FF) + amt));
		let b = Math.max(0, Math.min(255, (num & 0x0000FF) + amt));

		return (usePound ? "#" : "") + [r, g, b].map(c => c.toString(16).padStart(2, '0')).join('');
	};

	const hexToRgb = hex => {
		const bigint = parseInt(hex.slice(1), 16);
		return `${(bigint >> 16) & 255}, ${(bigint >> 8) & 255}, ${bigint & 255}`;
	};

	const lightHover = lightenDarkenColor(lightColor, 20);
	const lightActive = lightenDarkenColor(lightColor, -25);
	const darkHover = lightenDarkenColor(darkColor, 20);
	const darkActive = lightenDarkenColor(darkColor, -25);

	const normal = (from === 'light') ? lightColor : darkColor;
	$(`.box-${from}.normal`).css({ background: normal });
	$(`.box-${from}.hover`).css({ background: (from === 'light') ? lightHover : darkHover });
	$(`.box-${from}.active`).css({ background: (from === 'light') ? lightActive : darkActive });
	$(`.box-${from}.transparent`).css({ background: `rgba(${hexToRgb(normal)}, .5)` });

	return `
[data-theme-color="${nameColor}"] {
	color-scheme: light;
  --main-bg: ${lightColor};
  --main-bg-hover: ${lightHover};
  --main-bg-active: ${lightActive};
  --main-bg-rgb: rgba(${hexToRgb(lightColor)}, var(--opacity));
}
[data-theme="dark"][data-theme-color="${nameColor}"] {
	color-scheme: dark;
  --main-bg: ${darkColor};
  --main-bg-hover: ${darkHover};
  --main-bg-active: ${darkActive};
  --main-bg-rgb: rgba(${hexToRgb(darkColor)}, var(--opacity));
}
`;
}
