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
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css');
    wp_enqueue_style('theme-style', get_stylesheet_uri(), array('bootstrap-css'));
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js', array(), '5.3.7', true);
    
    // Font Awesome para iconos (usado por el chatbot)
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
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
        
        // Agregar clase para páginas con chatbot
        if (get_option('fiborti_chatbot_enabled', '1') === '1') {
            $classes[] = 'has-fiborti-chatbot';
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
        // Asegurar que jQuery esté disponible para el chatbot
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

// Función helper para logging (útil para debug del chatbot)
function mi_tema_log($message, $level = 'info') {
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            $message = print_r($message, true);
        }
        error_log('[MI_TEMA_' . strtoupper($level) . '] ' . $message);
    }
}

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

// Hook para cuando se activa el plugin del chatbot
function mi_tema_chatbot_activation_notice() {
    if (class_exists('FibortiChatbot')) {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p><strong>¡Chatbot Fiborti activado!</strong> Visita <a href="' . admin_url('options-general.php?page=fiborti-chatbot') . '">Configuración → Fiborti Chatbot</a> para personalizar el chat.</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'mi_tema_chatbot_activation_notice');

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