<?php
function mi_tema_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary' => 'Menú Principal'
    ));
}
add_action('after_setup_theme', 'mi_tema_setup');

function mi_tema_scripts() {
    // Bootstrap CSS desde CDN con fallback
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css', array(), '5.3.7');
    
    // Bootstrap Icons para iconos adicionales
    wp_enqueue_style('bootstrap-icons', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css', array(), '1.10.0');
    
    // Estilos del tema con ruta absoluta para mejor compatibilidad
    wp_enqueue_style('theme-style', get_template_directory_uri() . '/style.css', array('bootstrap-css'), '1.0.0');
    
    // Estilos específicos para Fiborti Analytics (solo en la página principal)
    if (is_home() || is_front_page()) {
        wp_enqueue_style('fiborti-analytics-css', get_template_directory_uri() . '/fiborti-analytics.css', array('bootstrap-css', 'bootstrap-icons'), filemtime(get_template_directory() . '/fiborti-analytics.css'));
    }
    
    // Bootstrap JS desde CDN
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.7', true);
    
    // Font Awesome para iconos con fallback
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
    
    // JavaScript específico para Fiborti Analytics (solo en la página principal)
    if (is_home() || is_front_page()) {
        $js_file_path = get_template_directory() . '/js/fiborti-analytics.js';
        if (file_exists($js_file_path)) {
            wp_enqueue_script('fiborti-analytics-js', get_template_directory_uri() . '/js/fiborti-analytics.js', array('jquery', 'bootstrap-js'), filemtime($js_file_path), true);
        }
    }
    
    // Agregar versión para cache busting en Hostinger
    wp_enqueue_style('theme-custom', get_template_directory_uri() . '/style.css', array('bootstrap-css', 'fontawesome'), filemtime(get_template_directory() . '/style.css'));
    
    // Forzar recarga de estilos si hay problemas de caché (solo en modo debug)
    if (WP_DEBUG === true) {
        wp_enqueue_style('theme-debug', get_template_directory_uri() . '/style.css', array('bootstrap-css', 'fontawesome'), time());
    }
}
add_action('wp_enqueue_scripts', 'mi_tema_scripts');

function mi_tema_widgets_init() {
    register_sidebar(array(
        'name' => 'Sidebar Principal',
        'id' => 'sidebar-1',
        'before_widget' => '<div class="card mb-3"><div class="card-body">',
        'after_widget' => '</div></div>',
        'before_title' => '<h5 class="card-title">',
        'after_title' => '</h5>',
    ));
}
add_action('widgets_init', 'mi_tema_widgets_init');

// Desactivar Gutenberg y usar editor clásico
function mi_tema_disable_gutenberg() {
    // Desactivar para posts
    add_filter('use_block_editor_for_post_type', '__return_false', 10);
    
    // Desactivar para widgets
    add_filter('use_widgets_block_editor', '__return_false');
    
    // Remover estilos CSS de Gutenberg del frontend
    add_action('wp_enqueue_scripts', function() {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style'); // WooCommerce blocks
        wp_dequeue_style('global-styles'); 
    }, 100);
}
add_action('after_setup_theme', 'mi_tema_disable_gutenberg');

// Soporte para templates personalizados
function mi_tema_page_templates($templates) {
    $templates['calfiborti.php'] = 'Calendario Fiborti';
    return $templates;
}
add_filter('theme_page_templates', 'mi_tema_page_templates');

// Cargar template personalizado
function mi_tema_load_custom_template($template) {
    global $post;
    
    if ($post) {
        $page_template = get_post_meta($post->ID, '_wp_page_template', true);
        
        if ($page_template == 'calfiborti.php') {
            $template = get_template_directory() . '/calfiborti.php';
        }
    }
    
    return $template;
}
add_filter('template_include', 'mi_tema_load_custom_template');

// Agregar clases CSS específicas al body para páginas especiales
function mi_tema_body_classes($classes) {
    global $post;
    
    if (is_page() && $post) {
        $page_template = get_post_meta($post->ID, '_wp_page_template', true);
        
        if ($page_template == 'calfiborti.php') {
            $classes[] = 'fiborti-calendar-page';
        }
        
        // Clase para páginas del calendario Fiborti
        if ($page_template == 'calfiborti.php') {
            $classes[] = 'fiborti-calendar-page';
        }
    }
    
    return $classes;
}
add_filter('body_class', 'mi_tema_body_classes');

// Mejorar la seguridad del sitio
function mi_tema_security_headers() {
    if (!is_admin()) {
        // Prevenir clickjacking
        header('X-Frame-Options: SAMEORIGIN');
        
        // Prevenir MIME type sniffing
        header('X-Content-Type-Options: nosniff');
        
        // Habilitar XSS protection
        header('X-XSS-Protection: 1; mode=block');
    }
}
add_action('send_headers', 'mi_tema_security_headers');

// Optimizar carga de jQuery para mejor compatibilidad
function mi_tema_jquery_optimization() {
    if (!is_admin()) {
        // Asegurar que jQuery esté disponible para Bootstrap
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'mi_tema_jquery_optimization', 1);

// Agregar meta tags para mejor SEO en páginas especiales
function mi_tema_custom_meta_tags() {
    global $post;
    
    if (is_page() && $post) {
        $page_template = get_post_meta($post->ID, '_wp_page_template', true);
        
        if ($page_template == 'calfiborti.php') {
            echo '<meta name="description" content="Sistema de calendario y agendamiento de citas Fiborti. Consulta disponibilidad y agenda citas fácilmente.">' . "\n";
            echo '<meta name="keywords" content="calendario, citas, agendamiento, Fiborti, disponibilidad">' . "\n";
            echo '<meta name="robots" content="index, follow">' . "\n";
        }
    }
}
add_action('wp_head', 'mi_tema_custom_meta_tags');

// Personalizar el excerpt length
function mi_tema_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'mi_tema_excerpt_length');

// Personalizar el "read more" del excerpt
function mi_tema_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'mi_tema_excerpt_more');

// Función helper para logging (útil para debug general)
function mi_tema_log($message, $level = 'info') {
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            $message = print_r($message, true);
        }
        error_log('[MI_TEMA_' . strtoupper($level) . '] ' . $message);
    }
}

// Función para debug de carga de estilos (solo en modo debug)
function mi_tema_debug_estilos() {
    if (WP_DEBUG === true && current_user_can('administrator')) {
        $estilos_cargados = wp_styles()->done;
        $scripts_cargados = wp_scripts()->done;
        
        echo "<!-- DEBUG ESTILOS CARGADOS: " . implode(', ', $estilos_cargados) . " -->\n";
        echo "<!-- DEBUG SCRIPTS CARGADOS: " . implode(', ', $scripts_cargados) . " -->\n";
        
        // Verificar archivos críticos
        $archivos_criticos = array(
            'style.css' => get_template_directory() . '/style.css',
            'calfiborti.js' => get_template_directory() . '/js/calfiborti.js',
            'fiborti-analytics.css' => get_template_directory() . '/fiborti-analytics.css',
            'fiborti-analytics.js' => get_template_directory() . '/js/fiborti-analytics.js'
        );
        
        foreach ($archivos_criticos as $nombre => $ruta) {
            if (file_exists($ruta)) {
                echo "<!-- DEBUG: $nombre existe en $ruta -->\n";
            } else {
                echo "<!-- ERROR: $nombre NO existe en $ruta -->\n";
            }
        }
    }
}
add_action('wp_footer', 'mi_tema_debug_estilos');

// Agregar soporte para uploads de archivos adicionales (por si necesitas logos, etc.)
function mi_tema_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'mi_tema_mime_types');

// Optimización para carga de imágenes
function mi_tema_add_image_lazy_loading($content) {
    if (is_singular() && in_the_loop() && is_main_query()) {
        $content = str_replace('<img ', '<img loading="lazy" ', $content);
    }
    return $content;
}
add_filter('the_content', 'mi_tema_add_image_lazy_loading');

// Función para mostrar notificaciones del tema
function mi_tema_admin_notices() {
    // Aquí puedes agregar notificaciones del tema si las necesitas
}
add_action('admin_notices', 'mi_tema_admin_notices');

// Deshabilitar completamente el chatbot
function mi_tema_disable_chatbot() {
    // Eliminar cualquier opción del chatbot
    delete_option('fiborti_chatbot_enabled');
    delete_option('fiborti_chatbot_settings');
    
    // Asegurar que no se cargue
    wp_dequeue_script('fiborti-chatbot');
    wp_dequeue_style('fiborti-chatbot');
}
add_action('init', 'mi_tema_disable_chatbot');

// Limpiar elementos de WordPress en la página principal de Fiborti Analytics
function mi_tema_clean_fiborti_page() {
    if (is_home() || is_front_page()) {
        // Remover estilos de WordPress que no necesitamos
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('wc-blocks-style');
        wp_dequeue_style('global-styles');
        wp_dequeue_style('classic-theme-styles');
        
        // Remover scripts de WordPress que no necesitamos
        wp_dequeue_script('wp-embed');
        wp_dequeue_script('comment-reply');
        
        // Ocultar la barra de administración
        add_filter('show_admin_bar', '__return_false');
        
        // Remover meta tags innecesarios
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }
}
add_action('wp_enqueue_scripts', 'mi_tema_clean_fiborti_page', 100);

// Agregar estilos adicionales para mejor integración
function mi_tema_inline_styles() {
    if (is_page_template('calfiborti.php')) {
        ?>
        <style>
        .fiborti-calendar-page .content-area {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .fiborti-calendar-page .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        
        .fiborti-calendar-page .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }
        
        .fiborti-calendar-page .nav-tabs .nav-link.active {
            font-weight: 600;
            color: #007bff;
        }
        
        /* Mejorar la apariencia en móviles */
        @media (max-width: 768px) {
            .fiborti-calendar-page .nav-tabs .nav-link {
                font-size: 0.9rem;
                padding: 0.5rem 0.75rem;
            }
            
            .fiborti-calendar-page .card-body {
                padding: 1rem;
            }
        }
        
        /* Ocultar completamente cualquier elemento del chatbot */
        .fiborti-chatbot-container,
        .fiborti-chatbot-toggle,
        .fiborti-chatbot-window,
        #fibortiChatbotToggle,
        #fibortiChatbotWindow,
        [id*="chatbot"],
        [class*="chatbot"],
        [id*="chat"],
        [class*="chat-widget"],
        .chat-button,
        .chat-toggle,
        .widget-chat,
        .chat-widget,
        .chat-container {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
            position: absolute !important;
            left: -9999px !important;
            top: -9999px !important;
        }
        </style>
        <?php
    }
}
add_action('wp_head', 'mi_tema_inline_styles');

// Opcional: Remover completamente Gutenberg para usuarios específicos
function mi_tema_disable_gutenberg_for_users($can_edit, $post_type) {
    // Si quieres desactivarlo solo para ciertos tipos de usuario
    // Descomenta y modifica según necesites:
    
    // if (current_user_can('administrator')) {
    //     return true; // Los administradores sí pueden usar Gutenberg
    // }
    
    return false; // Todos los demás usan editor clásico
}
// add_filter('use_block_editor_for_post_type', 'mi_tema_disable_gutenberg_for_users', 10, 2);
?>