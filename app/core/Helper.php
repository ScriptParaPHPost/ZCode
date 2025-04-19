<?php

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 3.1.18
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

namespace app\core;

class Helper {
    public static function isInstallerNeeded(): bool {
        return !file_exists(__DIR__ . '/../../.env') || ($_ENV['ZCODE_DB_HOST'] ?? '') === 'dbhost';
    }

    public static function ensureLogDirectoryExists(string $path): void {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }
}
