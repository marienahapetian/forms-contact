<?php
if(! defined( 'ABSPATH' )) exit;
function hugeit_contact_show_published_contact_1($id){
	global $wpdb;    
	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts_fields where hugeit_contact_id = %d order by ordering DESC",$id);
	$rowim=$wpdb->get_results($query);
	$tablename = $wpdb->prefix . "huge_it_contact_general_options";
	$query=$wpdb->prepare("SELECT * FROM %s order by id ASC",$tablename);
	$query=str_replace("'","",$query);
	$huge_it_gen_opt=$wpdb->get_results($query);

    $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts where id = %d order by id ASC",$id);
    $hugeit_contact=$wpdb->get_results($query);
    $hugeit_contacteffect=$hugeit_contact[0]->hc_yourstyle;

    $strquery = "SELECT * from " . $wpdb->prefix . "huge_it_contact_general_options";

    $rowspar = $wpdb->get_results($strquery);

    $paramssld = array();
    foreach ($rowspar as $rowpar) {
        $key = $rowpar->name;
        $value = $rowpar->value;
        $paramssld[$key] = $value;
    }
	$frontendformid = $id;

	$query = "SELECT *  FROM " . $wpdb->prefix . "huge_it_contact_style_fields WHERE options_name = '".$hugeit_contacteffect."' ";
    $rows = $wpdb->get_results($query);
    $style_values = array();
    foreach ($rows as $row) {
        $key = $row->name;
        $value = $row->value;
        $style_values[$key] = $value;
    }
  
  $queryMessage="SELECT * FROM ".$wpdb->prefix."huge_it_contact_submission where id = '".$id."'  order by id ASC";
  $messageInArrayFront = $wpdb->get_results($queryMessage); 
  return hugeit_contact_front_end_hugeit_contact($rowim, $paramssld, $hugeit_contact, $frontendformid,$style_values,$huge_it_gen_opt,$rowspar,$messageInArrayFront);
}


function hugeit_contact_is_single_column($rows) {
	foreach ( $rows as $row ) {
		if ($row->hc_left_right === 'right') {
				return false;
		}
	}

	return true;
}


function hugeit_contact_show_contact_submissions($args){
    global $wpdb;

    $submissions_query="SELECT *  FROM " . $wpdb->prefix . "huge_it_contact_submission";


    if(!empty($args)){
        $id=$args['id'];
    }
    if($id){
        $submissions_query.=" WHERE contact_id=$id";
        $fields_names_query="SELECT hc_field_label FROM " . $wpdb->prefix . "huge_it_contact_contacts_fields WHERE hugeit_contact_id=$id order by ordering";
        $fields=$wpdb->get_results($fields_names_query,'ARRAY_A');
    }

    $tablename = $wpdb->prefix . "huge_it_contact_general_options";
    $query_options="SELECT * FROM " . $tablename . " WHERE name='hf_submission_view' || name='hf_submission_per_page'";
    $huge_it_gen_opt=$wpdb->get_results($query_options);
    foreach ($huge_it_gen_opt as $key=>$option){
        if($option->name=='hf_submission_view'){
            $view=$option->value;
        }
        if($option->name=='hf_submission_per_page'){
            $submissions_per_page=$option->value;
        }
    }


    $submissions=$wpdb->get_results($submissions_query,'ARRAY_A');
    if($submissions){
        if(!$id){$view='default'; }

        if($fields){
            foreach ($fields as $key=>$field){
                $fieldsArray[]=$field['hc_field_label'];
            }
        }
        return hugeit_contact_front_end_submissions($submissions,$view,$submissions_per_page,$fieldsArray);
    }

}
