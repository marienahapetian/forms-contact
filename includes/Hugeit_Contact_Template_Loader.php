<?php


class Hugeit_Contact_Template_Loader {
    public static function render($html_path, $params = array(), $css_path='') {
        ob_start();
        ob_implicit_flush(false);

        extract($params, EXTR_OVERWRITE);

        require $html_path;
        if ( $css_path ) {
            require $css_path;
        }

        return ob_get_clean();
    }

}