<?php

class TChildDecorator
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyles']);
        $this->enqueueMobileUpd();
        $this->decorateAdminBar();

    }

    public function enqueueStyles()
    {
        wp_enqueue_style('parent-theme', THEME . '/style.css', [], THEME_VER);
        wp_enqueue_style('child-theme', CHILD_THEME . '/style.css', ['parent-theme'], THEME_VER);

    }

    public function enqueueMobileUpd()
    {
        if (IS_MOBILE) {
            $theme_color = '#ffca93';

            add_action('wp_head', function () use ($theme_color) {
                ?>
                <!-- Chrome, Firefox OS and Opera -->
                <meta name="theme-color" content="<?php echo $theme_color; ?>">
                <!-- Windows Phone -->
                <meta name="msapplication-navbutton-color" content="<?php echo $theme_color; ?>">
                <!-- iOS Safari -->
                <meta name="apple-mobile-web-app-status-bar-style" content="<?php echo $theme_color; ?>">

                <?php
            }, 20);
        }

    }

    public function decorateAdminBar()
    {
        add_action('set_current_user', function () {
            if (is_user_logged_in()) {
                $user = wp_get_current_user();
                if ($user->user_email === 'wptest@elementor.com') {
                    show_admin_bar(FALSE);
                }
            }
        });
    }
}


add_action('after_setup_theme', function () {
    $decorator = new TChildDecorator();
});