<?php
define('THEME_VER', 1.3);

if (!defined('THEME')) {
    define('THEME', get_template_directory_uri());
}
if (!defined('CHILD_THEME')) {
    define('CHILD_THEME', get_stylesheet_directory_uri());
}

if (!defined('IS_MOBILE')) {
    define('IS_MOBILE', wp_is_mobile());
}

if (!defined('THEME_DIR')) {
    define('THEME_DIR', get_stylesheet_directory());
}

define('TEXT_DOM', 'twenty-theme');