<?php 

function generateThemeColors($nameColor, $lightColor, $darkColor) {
	function lightenDarkenColor($col, $amt) {
		$usePound = false;
		if ($col[0] === "#") {
			$col = substr((string) $col, 1);
			$usePound = true;
		}
		
		$num = hexdec((string) $col);
		// R - Red | Rojo
		$r = (($num >> 16) & 0xFF) + $amt;
		if ($r > 255) $r = 255;
		else if ($r < 0) $r = 0;
		
		// G - Green | Verde
		$g = (($num >> 8) & 0xFF) + $amt;
		if ($g > 255) $g = 255;
		else if ($g < 0) $g = 0;
		
		// B - Blue | Azul
		$b = ($num & 0xFF) + $amt;
		if ($b > 255) $b = 255;
		else if ($b < 0) $b = 0;
		
		return ($usePound ? "#" : "") . str_pad(dechex($r), 2, '0', STR_PAD_LEFT) . str_pad(dechex($g), 2, '0', STR_PAD_LEFT) . str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
	}

	function hexToRgb($hex) {
		$hex = str_replace("#", "", $hex);
		$r = hexdec(substr($hex, 0, 2));
		$g = hexdec(substr($hex, 2, 2));
		$b = hexdec(substr($hex, 4, 2));
		return "$r, $g, $b";
	}

	$lightHover = lightenDarkenColor($lightColor, 20);
	$lightActive = lightenDarkenColor($lightColor, -20);
	$darkHover = lightenDarkenColor($darkColor, 20);
	$darkActive = lightenDarkenColor($darkColor, -20);

	return "<style id=\"customizer_style\">[data-theme-color=\"$nameColor\"]{--main-bg:$lightColor;--main-bg-hover:$lightHover;--main-bg-active:$lightActive;--main-bg-rgb:rgba(" . hexToRgb($lightColor) . ",var(--opacity));}[data-theme=\"dark\"][data-theme-color=\"$nameColor\"]{color-scheme:dark;--main-bg:$darkColor;--main-bg-hover:$darkHover;--main-bg-active:$darkActive;--main-bg-rgb:rgba(" . hexToRgb($darkColor) . ",var(--opacity));}</style>";
}