<?php 

class MigrationRunner {
	public static function runMigrations(mysqli $mysqli, string $directory, string $prefix = ''): array {
	   $log = [];
	   foreach (glob("$directory/*.php") as $file) {
	      try {
	         $queries = require $file;
	         foreach ($queries as $sql) {
	            if (!$mysqli->query($sql)) {
	               throw new Exception($mysqli->error);
	            }
	         }
	         $log[] = [
	            'file' => basename($file),
	            'status' => 'success',
	            'date' => date('Y-m-d')
	         ];
	      } catch (Throwable $e) {
	         $log[] = [
	            'file' => basename($file),
	            'status' => 'error',
	            'error' => $e->getMessage()
	         ];
	      }
	   }
	   return $log;
	}

	public static function saveLog(array $log, string $filename): void {
		file_put_contents($filename, json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
	}
}