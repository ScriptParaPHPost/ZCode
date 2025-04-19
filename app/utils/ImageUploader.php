<?php

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package     ZCode
 * @author      Miguel92
 * @copyright   2024 - 2025
 * @version     3.1.18
 * @link        https://zcodev.alwaysdata.net/ (DEMO)
 * @link        https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link        https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

namespace app\utils;

class ImageUploader
{
    protected array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    protected array $sizes = [];
    protected string $uploadDir = '';

    public function upload($input, string $destination, array $sizes): array
    {
        $this->uploadDir = rtrim($destination, '/') . '/';
        $this->sizes = $sizes;

        // Crear carpeta si no existe
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        // Detectar si es URL o archivo
        $originalPath = $this->getImage($input);
        if (!$originalPath) {
            throw new Exception('No se pudo obtener la imagen.');
        }

        // Obtener info
        $info = pathinfo($originalPath);
        $baseName = $info['filename'];

        $result = [];

        foreach ($this->sizes as $width => $name) {
            $target = $this->uploadDir . $name . '.webp';
            $this->resizeToWebp($originalPath, $width, $target);
            $result[$name] = $target;
        }

        // Borrar imagen temporal si fue descargada
        if (strpos($originalPath, sys_get_temp_dir()) === 0) {
            @unlink($originalPath);
        }

        return $result;
    }

    protected function getImage($input): ?string
    {
        if (is_array($input) && isset($input['tmp_name'])) {
            // Archivo subido
            $ext = strtolower(pathinfo($input['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $this->allowedExtensions)) {
                return null;
            }
            return $input['tmp_name'];
        }

        if (filter_var($input, FILTER_VALIDATE_URL)) {
            // URL remota
            $tempFile = tempnam(sys_get_temp_dir(), 'img_');
            $imageData = @file_get_contents($input);
            if ($imageData) {
                file_put_contents($tempFile, $imageData);
                return $tempFile;
            }
        }

        return null;
    }

    protected function resizeToWebp(string $source, int $targetWidth, string $destination): void
    {
        [$width, $height, $type] = getimagesize($source);
        $ratio = $height / $width;
        $newHeight = intval($targetWidth * $ratio);

        $srcImage = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($source),
            IMAGETYPE_PNG  => imagecreatefrompng($source),
            IMAGETYPE_GIF  => imagecreatefromgif($source),
            IMAGETYPE_WEBP => imagecreatefromwebp($source),
            default        => null,
        };

        if (!$srcImage) {
            throw new Exception("No se pudo crear imagen desde fuente.");
        }

        $dstImage = imagecreatetruecolor($targetWidth, $newHeight);
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $targetWidth, $newHeight, $width, $height);

        imagewebp($dstImage, $destination, 85); // 85 = calidad alta

        imagedestroy($srcImage);
        imagedestroy($dstImage);
    }
}

/*
# EJEMPLO
$uploader = new ImageUploader();

try {
    $result = $uploader->upload(
        $_FILES['imagen'], // o una URL como 'https://...'
        'uploads/posts/',
        [
            1920 => 'cover-lg',
            1280 => 'cover-md',
            768  => 'cover-sm',
            480  => 'cover-xs',
            160  => 'thumb',
        ]
    );

    print_r($result);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

*/