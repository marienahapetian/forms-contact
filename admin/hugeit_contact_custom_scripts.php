<?php
if(! defined( 'ABSPATH' )) exit;

require_once("hugeit_free_version.php");


/* Check if option exists in general options table */
function hugeit_exists_in_gen_op_table($option){
    global $wpdb;
    $result = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM '.$wpdb->prefix.'huge_it_contact_general_options WHERE name= %s',$option));
    return $result;
}

/* Save Custom CSS and JS */
function hugeit_contact_save_custom_scripts(){
    global $wpdb;

    $scriptsArray=array('css'=>'hugeit_custom_css','js'=>'hugeit_custom_js');

    foreach ($scriptsArray as $key=>$script){
        $newvalue=trim($_REQUEST[$script],' ');

        if(isset($newvalue) && strlen($newvalue)>0 ){
            if(hugeit_exists_in_gen_op_table($script)){
                $wpdb->update($wpdb->prefix.'huge_it_contact_general_options',array('value'=>sanitize_text_field($newvalue)),array('name'=>sanitize_text_field($script)));
            }
            else{
                $wpdb->insert($wpdb->prefix.'huge_it_contact_general_options',
                    array(
                        'name'=>sanitize_text_field($script),
                        'value'=>sanitize_text_field($newvalue)
                    ),
                    array('%s','%s'));
            }
        }
    }
    return true;
}


/* Get option row from general_options table by name */
function hugeit_get_option($option){
    global $wpdb;

    $result = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$wpdb->prefix.'huge_it_contact_general_options WHERE name= %s',$option));
    return $result[0]->value;
}

/* Generate Page html */
function hugeit_generate_custom_scripts_page(){
$path_site = plugins_url("Front_images", __FILE__); ?>
    <div class="wrap">
        <?php hugeit_contact_drawFreeBanner();?>
        <div id="poststuff">
            <div id="post-body-content">

                <div class="scripts-block">
                    <form action="admin.php?page=hugeit_forms_custom_scripts" method="post" id="adminForm" name="adminForm">
                        <div id="post-body-heading">
                            <input type="submit" value="Save" class="button-primary">
                        </div>
                        <div class="hugeit-contact-custom-scripts-column hugeit-contact-custom-scripts-left">
                            <div class="hugeit-contact-custom-scripts-block">
                                <div>
                                    <label for="hugeit_custom_css"><?php _e('Custom CSS','hugeit_contact');?></label>
                                    <textarea  wrap="soft" name="hugeit_custom_css"><?php echo (hugeit_get_option('hugeit_custom_css'))?stripslashes(hugeit_get_option('hugeit_custom_css')):'/* Write Your CSS Here */';?></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php }


