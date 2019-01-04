<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$logged_in = false;

$content_param = isset($_GET['go']) ? $_GET['go'] : 'home';
$content_parts = explode(':', $content_param);
$content_module = count($content_parts) > 0 ? $content_parts[0] : $content_param;

if ( $content_module ) {    
    $content_action = count($content_parts) > 1 ? $content_parts[1] : '';
    $content = $content_module;
    if ( $content_action ) {
        $content .= '/' . $content_action;
    }
}

$content_path = dirname( __FILE__ ) . '/views/' . $content . '.php';
$content_exists = file_exists($content_path);
$module_path = $content_module == 'home' ? '/' : '?go=' . $content_module;

include 'views/fragments/header.php';

// Load requested page (and create breadcrumbs) if it exists; if not, load the 404 page.
if ( $content_exists ) {

    echo '<nav class="aria-label="breadcrumb">';
    echo '<ol class="breadcrumb">';
    
    // $meta_title = getPageMeta( $content_param, 'title' );
    $meta_title = empty($meta_title) ? $content_action : $meta_title;

    if ( ucwords($content_action) == '' ) {        
        echo '<li class="breadcrumb-item">' . ucwords($content_module) . '</li>';
    } else {            
        echo '<li class="breadcrumb-item"><a href="' . $module_path . '">' . ucwords($content_module) . '</a></li>';
        echo '<li class="breadcrumb-item active" aria-current="page">' . ucwords($meta_title) . '</li>';
    }

    echo '</ol>';
    echo '</nav>';

  include $content_path;

} else {
  include 'views/error.php';
}

include 'views/fragments/footer.php';

?>