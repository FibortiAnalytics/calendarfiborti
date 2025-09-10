<?php
/**
 * Script de verificación para problemas de estilos en Hostinger
 * Ejecutar desde: tu-sitio.com/wp-content/themes/opergali/verificar-estilos.php
 */

// Verificar que estamos en el directorio correcto
$tema_dir = __DIR__;
$wp_content_dir = dirname(dirname($tema_dir));

echo "<h1>🔍 Verificación de Estilos - Tema Opergali</h1>";
echo "<p><strong>Directorio del tema:</strong> $tema_dir</p>";
echo "<p><strong>Directorio wp-content:</strong> $wp_content_dir</p>";

// Verificar archivos críticos
$archivos_criticos = [
    'style.css' => $tema_dir . '/style.css',
    'functions.php' => $tema_dir . '/functions.php',
    'calfiborti.js' => $tema_dir . '/js/calfiborti.js',
    'calfiborti.php' => $tema_dir . '/calfiborti.php',
    'fiborti-analytics.css' => $tema_dir . '/fiborti-analytics.css',
    'fiborti-analytics.js' => $tema_dir . '/js/fiborti-analytics.js',
    'header.php' => $tema_dir . '/header.php',
    'footer.php' => $tema_dir . '/footer.php',
    'index.php' => $tema_dir . '/index.php'
];

echo "<h2>📁 Verificación de Archivos</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>Archivo</th><th>Existe</th><th>Tamaño</th><th>Permisos</th><th>Última Modificación</th></tr>";

foreach ($archivos_criticos as $nombre => $ruta) {
    $existe = file_exists($ruta);
    $tamaño = $existe ? filesize($ruta) : 'N/A';
    $permisos = $existe ? substr(sprintf('%o', fileperms($ruta)), -4) : 'N/A';
    $modificacion = $existe ? date('Y-m-d H:i:s', filemtime($ruta)) : 'N/A';
    
    $color = $existe ? 'green' : 'red';
    echo "<tr>";
    echo "<td><strong>$nombre</strong></td>";
    echo "<td style='color: $color'>" . ($existe ? '✅ Sí' : '❌ No') . "</td>";
    echo "<td>$tamaño bytes</td>";
    echo "<td>$permisos</td>";
    echo "<td>$modificacion</td>";
    echo "</tr>";
}
echo "</table>";

// Verificar directorio js
echo "<h2>📂 Verificación de Directorio JS</h2>";
$js_dir = $tema_dir . '/js';
if (is_dir($js_dir)) {
    echo "<p>✅ Directorio js existe</p>";
    $archivos_js = scandir($js_dir);
    echo "<p><strong>Archivos en js/:</strong></p><ul>";
    foreach ($archivos_js as $archivo) {
        if ($archivo != '.' && $archivo != '..') {
            echo "<li>$archivo</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>❌ Directorio js no existe</p>";
}

// Verificar URLs de CDN
echo "<h2>🌐 Verificación de CDN</h2>";
$cdns = [
    'Bootstrap CSS' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css',
    'Bootstrap JS' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js',
    'Font Awesome' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'
];

foreach ($cdns as $nombre => $url) {
    $headers = @get_headers($url);
    $accesible = $headers && strpos($headers[0], '200') !== false;
    $color = $accesible ? 'green' : 'red';
    echo "<p style='color: $color'>" . ($accesible ? '✅' : '❌') . " $nombre: $url</p>";
}

// Verificar configuración de WordPress
echo "<h2>⚙️ Configuración de WordPress</h2>";
if (file_exists($wp_content_dir . '/../wp-config.php')) {
    echo "<p>✅ wp-config.php encontrado</p>";
    
    // Intentar leer configuración
    $wp_config = file_get_contents($wp_content_dir . '/../wp-config.php');
    if (strpos($wp_config, 'WP_DEBUG') !== false) {
        echo "<p>✅ WP_DEBUG configurado</p>";
    } else {
        echo "<p>⚠️ WP_DEBUG no encontrado en wp-config.php</p>";
    }
} else {
    echo "<p>❌ wp-config.php no encontrado</p>";
}

// Verificar permisos del directorio
echo "<h2>🔐 Verificación de Permisos</h2>";
$permisos_tema = substr(sprintf('%o', fileperms($tema_dir)), -4);
echo "<p><strong>Permisos del directorio del tema:</strong> $permisos_tema</p>";

if ($permisos_tema >= '755') {
    echo "<p>✅ Permisos correctos (755 o superior)</p>";
} else {
    echo "<p>⚠️ Permisos pueden ser insuficientes. Recomendado: 755</p>";
}

// Sugerencias
echo "<h2>💡 Sugerencias para Hostinger</h2>";
echo "<ol>";
echo "<li><strong>Limpiar caché:</strong> Ve al panel de Hostinger y limpia el caché del sitio</li>";
echo "<li><strong>Verificar .htaccess:</strong> Asegúrate de que el archivo .htaccess esté en la raíz del sitio</li>";
echo "<li><strong>Permisos de archivos:</strong> Los archivos CSS y JS deben tener permisos 644</li>";
echo "<li><strong>Directorio del tema:</strong> Debe tener permisos 755</li>";
echo "<li><strong>CDN:</strong> Si los CDN no funcionan, considera descargar Bootstrap y Font Awesome localmente</li>";
echo "</ol>";

echo "<h2>🔧 Comandos para Terminal (si tienes acceso SSH)</h2>";
echo "<pre>";
echo "# Cambiar permisos del tema\n";
echo "chmod -R 755 $tema_dir\n";
echo "chmod 644 $tema_dir/*.css\n";
echo "chmod 644 $tema_dir/*.js\n";
echo "chmod 644 $tema_dir/js/*.js\n";
echo "\n# Verificar propietario\n";
echo "ls -la $tema_dir\n";
echo "</pre>";

echo "<hr>";
echo "<p><small>Script generado el " . date('Y-m-d H:i:s') . "</small></p>";
?>
