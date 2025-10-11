<?php

class Uploader {
    private $allowedTypes = [
        'foto' => ['jpg', 'jpeg', 'png', 'webp'],
        'video' => ['mp4', 'avi', 'mov', 'mkv'],
        'audio' => ['mp3', 'wav', 'ogg']
    ];

    private $maxSize = 10 * 1024 * 1024; // 10 MB

    public function subirMultiples($files, $subdir = '') {
        $rutas = [];
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $tmp = $files['tmp_name'][$i];
                $name = $files['name'][$i];
                $ruta = $this->subirArchivo($tmp, $name, $subdir);
                if ($ruta) {
                    $rutas[] = $ruta;
                }
            }
        }
        return $rutas;
    }

    public function subirArchivo($tmp, $originalName, $subdir = '') {
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $type = $this->detectarTipo($ext);

        if (!$type) {
            throw new Exception("Tipo de archivo no permitido: $ext");
        }

        if (!in_array($ext, $this->allowedTypes[$type])) {
            throw new Exception("Extensión no permitida para $type: $ext");
        }

        $uploadDir = __DIR__ . '/../public/uploads/' . trim($subdir, '/');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid() . '.' . $ext;
        $rutaCompleta = $uploadDir . '/' . $filename;
        $rutaRelativa = 'uploads/' . trim($subdir, '/') . '/' . $filename;

        if (move_uploaded_file($tmp, $rutaCompleta)) {
            return $rutaRelativa;
        } else {
            throw new Exception("Error al mover el archivo a $rutaCompleta");
        }
    }

    private function detectarTipo($ext) {
        foreach ($this->allowedTypes as $tipo => $extensiones) {
            if (in_array($ext, $extensiones)) {
                return $tipo;
            }
        }
        return null;
    }
}
?>