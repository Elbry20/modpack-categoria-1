<?php
// URL del archivo INI remoto
$iniUrl = 'https://heavynightlauncher.com/Launcher-Categorias/Categoria2/Category-Config.ini';

// Función para descargar y leer el archivo INI
function getServerConfig($url) {
    $iniContent = @file_get_contents($url);
    if ($iniContent === false) {
        return false;
    }

    $config = parse_ini_string($iniContent, true);
    if ($config === false) {
        return false;
    }

    return $config;
}

// Obtener la configuración del servidor
$config = getServerConfig($iniUrl);
if ($config === false) {
    die("No se pudo obtener la configuración del servidor.");
}

// Extraer la IP y el puerto del archivo INI
$serverHost = $config['General']['ip'];
$serverPort = isset($config['General']['puerto']) ? $config['General']['puerto'] : null;

// Construir la URL para consultar la API
if ($serverPort) {
    $apiUrl = "https://api.mcsrvstat.us/2/{$serverHost}:{$serverPort}";
} else {
    $apiUrl = "https://api.mcsrvstat.us/2/{$serverHost}";
}

// Obtener los datos del servidor desde la API
$serverData = @file_get_contents($apiUrl);
if ($serverData === false) {
    die("No se pudo obtener la información del servidor.");
}

$serverData = json_decode($serverData, true);
if ($serverData === null) {
    die("Error al decodificar la respuesta de la API.");
}

// Verificar si el servidor está en línea
if (isset($serverData['online']) && $serverData['online'] === true) {
    echo "En linea";
} else {
    echo "Apagado";
}
?>
