<?php

class Hugeit_Contact_WP_Settings extends WPDEV_Settings_API
{
    public $plugin_id = 'forms_contact';

    public $tablename = 'huge_it_contact_general_options';

    public function __construct()
    {
        $config = array(
            'menu_slug' => 'hugeit_forms_general_options',
            'parent_slug' => 'hugeit_forms_main_page',
            'page_title' => __('', 'hugeit_contact'),
            'title' => __('Forms Contact General Options', 'hugeit_contact'),
            'menu_title' => __('General Options', 'hugeit_contact'),
        );
        $this->init();
        $this->init_panels();
        $this->init_sections();
        $this->init_controls();


        parent::__construct($config);

        $this->add_css('wpdev-custom-styles', plugins_url('../vendor/wpdev-settings/assets/css/wpdev-settings.css',__FILE__) );
        $this->add_js('wpdev-custom-js',  plugins_url('../vendor/wpdev-settings/assets/js/wpdev-settings.js',__FILE__));

    }

    /**
     * Initialize user defined variables
     */
    public function init()
    {
        $this->init_general_options();
    }

    /**
     *
     */
    public function init_panels()
    {
        $this->panels = array(
            'form_settings' => array(
                'title' => __('General Options', 'hugeit_contact'),
            ),
        );
    }

    public function init_sections()
    {
        $this->sections = array(
            'form_general_settings' => array(
                'panel' => 'form_settings',
                'title' => __('Form Settings', 'hugeit_contact'),
            ),

            'form_messages' => array(
                'panel' => 'form_settings',
                'title' => __('Form Messages', 'hugeit_contact'),
            ),
            'email_admin' => array(
                'panel' => 'form_settings',
                'title' => __('Email To Administrator', 'hugeit_contact'),
            ),
            'email_user' => array(
                'panel' => 'form_settings',
                'title' => __('Email To User', 'hugeit_contact'),
            ),

        );
    }

    /**
     * Display the admin page
     */
    public function init_controls()
    {
        $this->controls = array();
        $controls_forms_general_options = $this->controls_general_options();

        foreach ($controls_forms_general_options as $control_id => $control) {
            $this->controls[$control_id] = $control;
        }


    }

    private function init_general_options()
    {
        $this->form_adminstrator_user_name = $this->get_option_from_table("form_adminstrator_user_name", '');
        $this->form_adminstrator_user_mail = $this->get_option_from_table("form_adminstrator_user_mail", '');
        $this->form_save_reply_to_user = $this->get_option_from_table("form_save_form_save_reply_to_user", 'off');
        $this->form_captcha_public_key = $this->get_option_from_table("form_captcha_public_key", '');
        $this->form_captcha_private_key = $this->get_option_from_table("form_captcha_private_key", '');
        $this->form_save_to_database = $this->get_option_from_table("form_save_to_database", '');

        $this->msg_send_success = $this->get_option_from_table("msg_send_success", 'Message is sent successfully');
        $this->msg_send_false = $this->get_option_from_table("msg_send_false", 'Message failed to be sent');
        $this->msg_refered_spam = $this->get_option_from_table("msg_refered_spam", 'Submission was referred to as Spam');
        $this->msg_captcha_error = $this->get_option_from_table("msg_captcha_error", 'Please tick on Captcha box');
        $this->required_empty_field = $this->get_option_from_table("required_empty_field", 'Please Fill This Field');
        $this->msg_invalid_email = $this->get_option_from_table("msg_invalid_email", 'Incorrect Email');
        $this->msg_fail_failed = $this->get_option_from_table("msg_fail_failed", 'Error on file upload');
        $this->msg_file_format = $this->get_option_from_table("msg_file_format", 'Unacceptable file type');
        $this->msg_large_file = $this->get_option_from_table("msg_large_file", 'Exceeds limits on uploaded file');
        $this->msg_simple_captcha_error = $this->get_option_from_table("msg_simple_captcha_error", 'Incorrect Input');

        $this->form_send_email_for_each_submition = $this->get_option_from_table("form_send_email_for_each_submition", 'on');
        $this->form_adminstrator_email = $this->get_option_from_table("form_adminstrator_email", '');
        $this->form_message_subject = $this->get_option_from_table("form_message_subject", 'Form Submitted');
        $this->form_adminstrator_message = $this->get_option_from_table("form_adminstrator_message", '');
        $this->form_send_to_email_user = $this->get_option_from_table("form_send_to_email_user", 'on');
        $this->form_user_message_subject = $this->get_option_from_table("form_user_message_subject", 'Form Submitted');
        $this->form_user_message = $this->get_option_from_table("form_user_message", '');
    }

    private function controls_general_options()
    {
        return array(
            'form_adminstrator_user_name' => array(
                'section' => 'form_general_settings',
                'type' => 'text',
                'default' => $this->form_adminstrator_user_name,
                'label' => __('Send Emails From Name', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'form_adminstrator_user_mail' => array(
                'section' => 'form_general_settings',
                'type' => 'email',
                'default' => $this->form_adminstrator_user_mail,
                'label' => __('Send Emails From Email', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'form_save_reply_to_user' => array(
                'section' => 'form_general_settings',
                'type' => 'checkbox',
                'default' => $this->form_save_reply_to_user,
                'label' => __('Reply To User', 'hugeit_contact'),
                'help' => __('Choose whether to get the emails from the user email address', 'hugeit_contact')
            ),
            'form_captcha_public_key' => array(
                'section' => 'form_general_settings',
                'type' => 'text',
                'default' => $this->form_captcha_public_key,
                'label' => __('Captcha Public Key', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'form_captcha_private_key' => array(
                'section' => 'form_general_settings',
                'type' => 'text',
                'default' => $this->form_captcha_private_key,
                'label' => __('Captcha Private Key', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'form_save_to_database' => array(
                'section' => 'form_general_settings',
                'type' => 'checkbox',
                'default' => $this->form_save_to_database,
                'label' => __('Save Submissions To Database', 'hugeit_contact'),
                'help' => __('Uncheck this if you don\'t want submissions to be saved in database', 'hugeit_contact')
            ),
            'msg_send_success' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_send_success,
                'label' => __('Sender\'s message was sent successfully', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'msg_send_false' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_send_false,
                'label' => __('Sender\'s message was failed to send', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'msg_refered_spam' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_refered_spam,
                'label' => __('Submission was referred to as spam', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'msg_captcha_error' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_captcha_error,
                'label' => __('Captcha is Not Validated', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'required_empty_field' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->required_empty_field,
                'label' => __('Required Field Is Empty', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'msg_invalid_email' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_invalid_email,
                'label' => __('Email address that the sender entered is invalid', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'msg_fail_failed' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_fail_failed,
                'label' => __('Uploading a file fails for any reason', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'msg_file_format' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_file_format,
                'label' => __('Uploaded file is not allowed file type', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'msg_large_file' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_large_file,
                'label' => __('Uploaded file is too large', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'msg_simple_captcha_error' => array(
                'section' => 'form_messages',
                'type' => 'text',
                'default' => $this->msg_simple_captcha_error,
                'label' => __('Simple Captcha Code Incorrect', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'form_send_email_for_each_submition' => array(
                'section' => 'email_admin',
                'type' => 'checkbox',
                'default' => $this->form_send_email_for_each_submition,
                'label' => __('Send Email For Each Submission', 'hugeit_contact'),
                'help' => __('Whether to Send an Email to Admin for each Submission', 'hugeit_contact')
            ),
            'form_adminstrator_email' => array(
                'section' => 'email_admin',
                'type' => 'textarea',
                'default' => $this->form_adminstrator_email,
                'label' => __('Administrator Email', 'hugeit_contact'),
                'help' => __('Add multiple emails,separate them with commas', 'hugeit_contact')
            ),
            'form_message_subject' => array(
                'section' => 'email_admin',
                'type' => 'text',
                'default' => $this->form_message_subject,
                'label' => __('Message Subject', 'hugeit_contact'),
                'help' => __('If you leave this field empty, the name of the submitted form will be used as the subject of the email', 'hugeit_contact')
            ),
            'form_adminstrator_message' => array(
                'section' => 'email_admin',
                'type' => 'editor',
                'editorId' => 'hugeit_contact_adminmessage',
                'default' => $this->form_adminstrator_message,
                'label' => __('Message Content', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),
            'form_send_to_email_user' => array(
                'section' => 'email_user',
                'type' => 'checkbox',
                'default' => $this->form_send_to_email_user,
                'label' => __('Send Email For Each Submission', 'hugeit_contact'),
                'help' => __('Whether to Send an Email to Admin for each Submission', 'hugeit_contact')
            ),
            'form_user_message_subject' => array(
                'section' => 'email_user',
                'type' => 'text',
                'default' => $this->form_user_message_subject,
                'label' => __('Message Subject', 'hugeit_contact'),
                'help' => __('If you leave this field empty, the name of the submitted form will be used as the subject of the email', 'hugeit_contact')
            ),
            'form_user_message' => array(
                'section' => 'email_user',
                'type' => 'editor',
                'editorId' => 'hugeit_contact_usermessage',
                'default' => $this->form_user_message,
                'label' => __('Message Content', 'hugeit_contact'),
                'help' => __('', 'hugeit_contact')
            ),

        );
    }




    /**
     * @param $id
     * @param $control
     */
    protected function control_editor( $id, $control ) {
        $default = ( isset( $control['default'] ) ? $control['default'] : "" );

        $html_class = isset( $control['html_class'] ) ? $control['html_class'] : array();

        if ( is_string( $html_class ) ) {
            explode( ' ', $html_class );
        }
        $html_class_str  = implode( ' ', $html_class );
        $label_str       = ( isset( $control['label'] ) ? '<label for="'.$id.'" > ' . $control['label'] : '' );
        $label_str      .= isset( $control['help'] ) ? '<div class="wpdev_settings_help">&#63;<div class="wpdev_settings_help_block"><span class="pnt"></span><p>'. $control['help'] .'</p></div></div></label>' : '</label>';
        $description     = isset( $control['description'] ) ? $control['description'] : "";
        $description_str = $description != "" ? '<p class="description">' . $description . '</p>' : '';

        $attrs = array();
        if ( isset( $control['attrs'] ) && ! empty( $control['attrs'] ) ) {
            foreach ( $control['attrs'] as $k => $attr ) {
                $attrs[] = $k . '=' . $attr;
            }
        }

        $editorId   = ( isset( $control['editorId'] )) ? $control['editorId'] : '';

        echo $label_str;
        ?>

        <?php wp_editor( html_entity_decode(stripslashes($default)), $editorId ); ?>
        <?php
        echo $description_str;
    }


    /**
     * @param $key
     * @param bool $default
     * @param bool $concat
     *
     * @return mixed|void
     */
    public function get_option_from_table( $key, $default = false, $concat = true  ) {
        global $wpdb;
        $value = $wpdb->get_var('SELECT `value` FROM '.$wpdb->prefix.$this->tablename.' WHERE `name`="'.$key.'"');

        if(!$value) $value = $default;

        return $value;
    }

    /**
     * @param $key
     * @param $value
     *
     */
    public function update_option_in_table( $key, $value ) {
        global $wpdb;
        $wpdb->update( $this->tablename, array(
            'value'=>$value
        ), array(
            'name'=>$key
        ));
    }

    public function save_options(){
        $ajax = false;
        if (defined('DOING_AJAX') && DOING_AJAX){
            $ajax = true;

        }

        if( ! isset( $_REQUEST['wpdev_settings_current_plugin'] ) || $_REQUEST['wpdev_settings_current_plugin'] !== $this->plugin_id ){
            return false;
        }

        if( !isset( $_REQUEST['action'] ) || $_REQUEST['action'] !== 'wpdev_save_settings' ){

            return false;
        }

        if( !check_admin_referer( 'wpdev_settings_save_options', 'wpdev_settings_save_nonce' ) ){

            if( $ajax ){
                echo json_encode( array( 'errorMsg' => __( "Wrong nonce parameter" ) ) );die;
            }

            return false;
        }

        if( isset( $_REQUEST['wpdev_options'] ) && is_array( $_REQUEST['wpdev_options'] ) && !empty( $_REQUEST['wpdev_options'] ) ){
            foreach( $_REQUEST['wpdev_options'] as $name => $value ){
                if( method_exists( $this, 'set_'.$name ) ){
                    call_user_func( array( $this, 'set_'.$name ), $value );
                }else{
                    $this->update_option_in_table( $name, $value );
                }

            }

            if( $ajax ){
                echo json_encode( array( 'successMsg' => __( "Saved Successfully" ) ) );die;
            }
            return true;
        }

        if( $ajax ){
            echo json_encode( array( 'errorMsg' => __( "Something went wrong" ) ) );die;
        }

        return true;

    }


}

