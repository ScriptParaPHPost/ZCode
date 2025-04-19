<?php

namespace app\traits;

/**
 * Trait con funciones auxiliares del sistema
 */
trait System {
	// 🔹 1. Obtener contenido de una URL
	public function getUrlContent(string $tsUrl): ?string {
		if (function_exists('curl_init')) {
			$useragent = $_SERVER['HTTP_USER_AGENT'] ?? 'Mozilla/5.0';
			$ch = curl_init();
			curl_setopt_array($ch, [
				CURLOPT_URL => $tsUrl,
				CURLOPT_USERAGENT => $useragent,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_RETURNTRANSFER => true,
			]);
			$result = curl_exec($ch);
			curl_close($ch);
		} else {
			$result = @file_get_contents($tsUrl);
		}

		return $result ?: null;
	}

	// 🔹 2. Expresión "Hace X tiempo"
	public function setHace(int $fecha = 0, bool $show = false): string {
		if ($fecha <= 0) return "Nunca";
		$tiempo = time() - $fecha;

		$unidades = [
			31536000 => ["a&ntilde;o", "a&ntilde;os"],
			2678400  => ["mes", "meses"],
			604800   => ["semana", "semanas"],
			86400    => ["d&iacute;a", "d&iacute;as"],
			3600     => ["hora", "horas"],
			60       => ["minuto", "minutos"],
		];

		foreach ($unidades as $segundos => $nombre) {
			if ($tiempo <= 60) {
				return ($show ? "Hace " : "") . "instantes";
			}

			$round = round($tiempo / $segundos);
			if ($round > 0) {
				return ($show ? "Hace " : "") . "{$round} {$nombre[($round > 1 ? 1 : 0)]}";
			}
		}

		return "Hace un momento";
	}

	// 🔹 3. Genera una cadena SQL "campo = valor"
	public function buildSqlUpdateFields(array $fields, string $prefix = ''): string {
		$sets = [];

		foreach ($fields as $field => $value) {
			$safeValue = is_numeric($value) ? $value : "'" . str_replace("'", "''", $value) . "'";
			$sets[] = "{$prefix}{$field} = $safeValue";
		}

		return implode(', ', $sets);
	}

	// 🔹 4. Convierte texto a slug
	public function slugify(string $text): string {
		$text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
		$text = preg_replace('/[^a-zA-Z0-9\/_|+ -]/', '', $text);
		$text = strtolower(trim($text, '-'));
		$text = preg_replace('/[\/_|+ -]+/', '-', $text);
		return $text;
	}

	// 🔹 5. Genera un token aleatorio
	public function generateToken(int $length = 32): string {
		return bin2hex(random_bytes($length / 2));
	}

	// 🔹 6. Limpia HTML (seguro contra XSS básico)
	public function sanitizeHtml(string $input): string {
		return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
	}

	// 🔹 7. Genera una cadena alfanumérica aleatoria
	public function randomString(int $length = 16): string {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$output = '';
		for ($i = 0; $i < $length; $i++) {
			$output .= $characters[random_int(0, strlen($characters) - 1)];
		}
		return $output;
	}

	// 🔹 8. Valida si una cadena es una URL válida
	public function isValidUrl(string $url): bool {
		return (bool) filter_var($url, FILTER_VALIDATE_URL);
	}
}