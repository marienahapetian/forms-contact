<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( function_exists( 'current_user_can' ) ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		die( 'Access Denied' );
	}
}
if ( ! function_exists( 'current_user_can' ) ) {
	die( 'Access Denied' );
}

function hugeit_contact_show_contact() {

	global $wpdb;

	if ( isset( $_POST['search_events_by_title'] ) ) {
		$_POST['search_events_by_title'] = esc_html( stripslashes( $_POST['search_events_by_title'] ) );
	}
	if ( isset( $_POST['asc_or_desc'] ) ) {
		$_POST['asc_or_desc'] = esc_js( $_POST['asc_or_desc'] );
	}
	if ( isset( $_POST['order_by'] ) ) {
		$_POST['order_by'] = esc_js( $_POST['order_by'] );
	}
	$where                 = '';
	$sort["custom_style"]  = "manage-column column-autor sortable desc";
	$sort["default_style"] = "manage-column column-autor sortable desc";
	$sort["sortid_by"]     = 'id';
	$sort["1_or_2"]        = 1;
	$order                 = '';

	if ( isset( $_POST['page_number'] ) ) {

		if ( $_POST['asc_or_desc'] ) {
			$sort["sortid_by"] = $_POST['order_by'];
			if ( $_POST['asc_or_desc'] == 1 ) {
				$sort["custom_style"] = "manage-column column-title sorted asc";
				$sort["1_or_2"]       = "2";
				$order                = "ORDER BY " . $sort["sortid_by"] . " ASC";
			} else {
				$sort["custom_style"] = "manage-column column-title sorted desc";
				$sort["1_or_2"]       = "1";
				$order                = "ORDER BY " . $sort["sortid_by"] . " DESC";
			}
		}
		if ( $_POST['page_number'] ) {
			$limit = ( $_POST['page_number'] - 1 ) * 20;
		} else {
			$limit = 0;
		}
	} else {
		$limit = 0;
	}
	if ( isset( $_POST['search_events_by_title'] ) ) {
		$search_tag = esc_html( stripslashes( $_POST['search_events_by_title'] ) );
	} else {
		$search_tag = "";
	}

	if ( isset( $_GET["catid"] ) ) {
		$cat_id = esc_html( $_GET["catid"] );
	} else {
		if ( isset( $_POST['cat_search'] ) ) {
			$cat_id = esc_html( $_POST['cat_search'] );
		} else {
			$cat_id = 0;
		}
	}

	if ( $search_tag ) {
		$where = " WHERE name LIKE '%" . $search_tag . "%' ";
	}
	if ( $where ) {
		if ( $cat_id ) {
			$where .= " AND hc_width=" . $cat_id;
		}

	} else {
		if ( $cat_id ) {
			$where .= " WHERE hc_width=" . $cat_id;
		}

	}

	$cat_row_query = "SELECT id,name FROM " . $wpdb->prefix . "huge_it_contact_contacts WHERE hc_width=0";
	$cat_row       = $wpdb->get_results( $cat_row_query );

	// get the total number of records
	$query = "SELECT COUNT(*) FROM " . $wpdb->prefix . "huge_it_contact_contacts" . $where;

	$total            = $wpdb->get_var( $query );
	$pageNav['total'] = $total;
	$pageNav['limit'] = $limit / 20 + 1;

	if ( $cat_id ) {
		$query = "SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM " . $wpdb->prefix . "huge_it_contact_contacts  AS a LEFT JOIN " . $wpdb->prefix . "huge_it_contact_contacts AS b ON a.id = b.hc_width LEFT JOIN (SELECT  " . $wpdb->prefix . "huge_it_contact_contacts.ordering as ordering," . $wpdb->prefix . "huge_it_contact_contacts.id AS id, COUNT( " . $wpdb->prefix . "huge_it_contact_contacts_fields.hugeit_contact_id ) AS prod_count
FROM " . $wpdb->prefix . "huge_it_contact_contacts_fields, " . $wpdb->prefix . "huge_it_contact_contacts
WHERE " . $wpdb->prefix . "huge_it_contact_contacts_fields.hugeit_contact_id = " . $wpdb->prefix . "huge_it_contact_contacts.id
GROUP BY " . $wpdb->prefix . "huge_it_contact_contacts_fields.hugeit_contact_id) AS c ON c.id = a.id LEFT JOIN
(SELECT " . $wpdb->prefix . "huge_it_contact_contacts.name AS par_name," . $wpdb->prefix . "huge_it_contact_contacts.id FROM " . $wpdb->prefix . "huge_it_contact_contacts) AS g
 ON a.hc_width=g.id WHERE  a.name LIKE '%" . $search_tag . "%' group by a.id " . $order . " " . " LIMIT " . $limit . ",20";
	} else {
		$query = "SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM " . $wpdb->prefix . "huge_it_contact_contacts  AS a LEFT JOIN " . $wpdb->prefix . "huge_it_contact_contacts AS b ON a.id = b.hc_width LEFT JOIN (SELECT  " . $wpdb->prefix . "huge_it_contact_contacts.ordering as ordering," . $wpdb->prefix . "huge_it_contact_contacts.id AS id, COUNT( " . $wpdb->prefix . "huge_it_contact_contacts_fields.hugeit_contact_id ) AS prod_count
FROM " . $wpdb->prefix . "huge_it_contact_contacts_fields, " . $wpdb->prefix . "huge_it_contact_contacts
WHERE " . $wpdb->prefix . "huge_it_contact_contacts_fields.hugeit_contact_id = " . $wpdb->prefix . "huge_it_contact_contacts.id
GROUP BY " . $wpdb->prefix . "huge_it_contact_contacts_fields.hugeit_contact_id) AS c ON c.id = a.id LEFT JOIN
(SELECT " . $wpdb->prefix . "huge_it_contact_contacts.name AS par_name," . $wpdb->prefix . "huge_it_contact_contacts.id FROM " . $wpdb->prefix . "huge_it_contact_contacts) AS g
 ON a.hc_width=g.id WHERE a.name LIKE '%" . $search_tag . "%'  group by a.id " . $order . " " . " LIMIT " . $limit . ",20";
	}

	$rows = $wpdb->get_results( $query );
	global $glob_ordering_in_cat;
	if ( isset( $sort["sortid_by"] ) ) {
		if ( $sort["sortid_by"] == 'ordering' ) {
			if ( $_POST['asc_or_desc'] == 1 ) {
				$glob_ordering_in_cat = " ORDER BY ordering ASC";
			} else {
				$glob_ordering_in_cat = " ORDER BY ordering DESC";
			}
		}
	}
	$rows      = open_cat_in_tree( $rows );
	$query     = "SELECT  " . $wpdb->prefix . "huge_it_contact_contacts.ordering," . $wpdb->prefix . "huge_it_contact_contacts.id, COUNT( " . $wpdb->prefix . "huge_it_contact_contacts_fields.hugeit_contact_id ) AS prod_count
FROM " . $wpdb->prefix . "huge_it_contact_contacts_fields, " . $wpdb->prefix . "huge_it_contact_contacts
WHERE " . $wpdb->prefix . "huge_it_contact_contacts_fields.hugeit_contact_id = " . $wpdb->prefix . "huge_it_contact_contacts.id
GROUP BY " . $wpdb->prefix . "huge_it_contact_contacts_fields.hugeit_contact_id ";
	$prod_rows = $wpdb->get_results( $query );

	foreach ( $rows as $row ) {
		foreach ( $prod_rows as $row_1 ) {
			if ( $row->id == $row_1->id ) {
				$row->ordering   = $row_1->ordering;
				$row->prod_count = $row_1->prod_count;
			}
		}

	}


	$query       = "SELECT * FROM " . $wpdb->prefix . "huge_it_contact_styles order by id ASC";
	$form_styles = $wpdb->get_results( $query );


	$cat_row    = open_cat_in_tree( $cat_row );
	$postsbycat = '';
	html_showhugeit_contacts( $rows, $pageNav, $sort, $cat_row, $postsbycat, $form_styles );
}

function open_cat_in_tree($catt,$tree_problem='',$hihiih=1){

global $wpdb;
global $glob_ordering_in_cat;
static $trr_cat=array();
if(!isset($search_tag))
$search_tag='';
if($hihiih)
$trr_cat=array();
foreach($catt as $local_cat){
	$local_cat->name=$tree_problem.$local_cat->name;
	array_push($trr_cat,$local_cat);
	$new_cat_query=	"SELECT  a.* ,  COUNT(b.id) AS count, g.par_name AS par_name FROM ".$wpdb->prefix."huge_it_contact_contacts  AS a LEFT JOIN ".$wpdb->prefix."huge_it_contact_contacts AS b ON a.id = b.hc_width LEFT JOIN (SELECT  ".$wpdb->prefix."huge_it_contact_contacts.ordering as ordering,".$wpdb->prefix."huge_it_contact_contacts.id AS id, COUNT( ".$wpdb->prefix."huge_it_contact_contacts_fields.hugeit_contact_id ) AS prod_count
FROM ".$wpdb->prefix."huge_it_contact_contacts_fields, ".$wpdb->prefix."huge_it_contact_contacts
WHERE ".$wpdb->prefix."huge_it_contact_contacts_fields.hugeit_contact_id = ".$wpdb->prefix."huge_it_contact_contacts.id
GROUP BY ".$wpdb->prefix."huge_it_contact_contacts_fields.hugeit_contact_id) AS c ON c.id = a.id LEFT JOIN
(SELECT ".$wpdb->prefix."huge_it_contact_contacts.name AS par_name,".$wpdb->prefix."huge_it_contact_contacts.id FROM ".$wpdb->prefix."huge_it_contact_contacts) AS g
 ON a.hc_width=g.id WHERE a.name LIKE '%".$search_tag."%' AND a.hc_width=".$local_cat->id." group by a.id  ".$glob_ordering_in_cat; 
 $new_cat=$wpdb->get_results($new_cat_query);
 open_cat_in_tree($new_cat,$tree_problem. "— ",0);
}
return $trr_cat;

}

function hugeit_contact_edit_hugeit_contact($id){
	if ( isset( $_POST['csrf_token_hugeit_forms'] ) && ( ! isset( $_SESSION["csrf_token_hugeit_forms"] ) || $_SESSION["csrf_token_hugeit_forms"] != @$_POST['csrf_token_hugeit_forms'] ) ) {
		exit;
	}

	global $wpdb;

	if ( isset( $_GET["removeslide"] ) && $_GET["removeslide"] != '' ) {
		$remove_slide = sanitize_text_field($_GET["removeslide"]);

		$query = $wpdb->prepare( "DELETE FROM " . $wpdb->prefix . "huge_it_contact_contacts_fields  WHERE id = %d", $remove_slide );
		$wpdb->query( $query );

	}

	if(isset($_GET["dublicate"])){
		if(is_int((int)$_GET["dublicate"])){
			$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts_fields WHERE id=%d",$_GET["dublicate"]);
			$rowduble=$wpdb->get_row($query);

			$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts WHERE id=%d",$id);
			$row=$wpdb->get_row($query);
			$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts_fields where hugeit_contact_id = %d order by id ASC", $row->id);
			$rowplusorder=$wpdb->get_results($query);

			foreach ($rowplusorder as $key=>$rowplusorders){
				if($rowplusorders->ordering > $rowduble->ordering){
					$rowplusorderspl=$rowplusorders->ordering+1;
					$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_contact_contacts_fields SET ordering = %d WHERE id = %d ", $rowplusorderspl,$rowplusorders->id));
				}
			}

			$inserttexttype = $wpdb->prefix . "huge_it_contact_contacts_fields";
			$rowdubleorder=$rowduble->ordering+1;
			$wpdb->insert(
				$inserttexttype,
				array(
					'name' => $rowduble->name,
					'hugeit_contact_id' => $rowduble->hugeit_contact_id,
					'description' => $rowduble->description,
					'conttype' => $rowduble->conttype,
					'hc_field_label' => $rowduble->hc_field_label,
					'hc_other_field' => $rowduble->hc_other_field,
					'field_type' => $rowduble->field_type,
					'hc_required' => $rowduble->hc_required,
					'ordering' => $rowdubleorder,
					'published' => $rowduble->published,
					'hc_input_show_default' => $rowduble->hc_input_show_default,
					'hc_left_right' => $rowduble->hc_left_right,
				),
				array('%s','%d','%s','%s','%s','%s','%s','%s','%s','%d','%s','%s',)
			);

			header('Location: admin.php?page=hugeit_forms_main_page&id='.$id.'&task=apply');
		}

	}

	if ( isset( $_GET["inputtype"] ) ) {
		$_GET["inputtype"] = esc_html( $_GET["inputtype"] );
		$query             = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_it_contact_contacts WHERE id=%d", $id );
		$row               = $wpdb->get_row( $query );
		$inputtype         = esc_html( $_GET["inputtype"] );
		$query             = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_it_contact_contacts WHERE id= %d", $id );
		$row               = $wpdb->get_row( $query );
		$query             = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_it_contact_contacts_fields WHERE hugeit_contact_id = %d ORDER BY id ASC", $row->id );
		$rowplusorder      = $wpdb->get_results( $query );

		foreach ( $rowplusorder as $key => $rowplusorders ) {
			$rowplusorderspl = $rowplusorders->ordering + 1;
			$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_it_contact_contacts_fields SET ordering = %d WHERE id = %d ", $rowplusorderspl, $rowplusorders->id ) );
		}

		switch ($inputtype){
			case 'custom_text':  //7
				$inserttexttype = $wpdb->prefix . "huge_it_contact_contacts_fields";
				$sql_type_text = "
				INSERT INTO 
				`" . $inserttexttype . "` ( `name`, `hugeit_contact_id`, `description`, `conttype`, `hc_field_label`, `hc_other_field`, `field_type`,`hc_required`, `ordering`, `published`, `hc_input_show_default`, `hc_left_right`) VALUES
				( 'Placeholder', '".$row->id."', 'on', '".$_GET["inputtype"]."', 'Label', '80','on','on', 'par_TV', 2, '1', 'left' )";


				$wpdb->query($sql_type_text);
		}
	}

	   $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts WHERE id=%d",$id);
	   $row=$wpdb->get_row($query);

	   if(!isset($row->hc_yourstyle))
	   return 'id not found';
       $images=explode(";;;",$row->hc_yourstyle);
	   $par=explode('	',$row->param);
	   $count_ord=count($images);
	   $cat_row=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts WHERE id!=" .$id." and hc_width=0");
       $cat_row=open_cat_in_tree($cat_row);
	   	  $query="SELECT name,ordering FROM ".$wpdb->prefix."huge_it_contact_contacts WHERE hc_width=".$row->hc_width."  ORDER BY `ordering` ";
	   $ord_elem=$wpdb->get_results($query);

	    $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts_fields where hugeit_contact_id = %d order by ordering DESC",$row->id);
			   $rowim=$wpdb->get_results($query);

			   if(isset($_GET["addslide"])&&$_GET["addslide"] == 1){

$table_name = $wpdb->prefix . "huge_it_contact_contacts_fields";
    $sql_2 = "
INSERT INTO

`" . $table_name . "` ( `name`, `hugeit_contact_id`, `description`, `hc_field_label`, `hc_other_field`, `ordering`, `published`, `hc_input_show_default`) VALUES
( '', '".$row->id."', '', '', '', 'par_TV', 2, '1' )";

    $wpdb->query($sql_huge_it_contact_contacts_fields);


      $wpdb->query($sql_2);

	   }



       $tablename = $wpdb->prefix . "huge_it_contact_contacts";
	   $query=$wpdb->prepare("SELECT * FROM %s order by id ASC",$tablename);
	   $query=str_replace("'","",$query);
	   $rowsld=$wpdb->get_results($query);

	$query = "SELECT *  from " . $wpdb->prefix . "huge_it_contact_general_options ";
    $rowspar = $wpdb->get_results($query);
    $paramssld = array();
    foreach ($rowspar as $rowpar) {
        $key = $rowpar->name;
        $value = $rowpar->value;
        $paramssld[$key] = $value;
    }
	 $tablename = $wpdb->prefix . "posts";
	 $query=$wpdb->prepare('SELECT * FROM %s where post_type = "post" and post_status = "publish" order by id ASC',$tablename);
	 $query=str_replace("'","",$query);
			   $rowsposts=$wpdb->get_results($query);
	 if(!isset($_POST["iframecatid"])){
	 	$_POST["iframecatid"]='';
	 }
	 	  $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."term_relationships where term_taxonomy_id = %d order by object_id ASC",$_POST["iframecatid"]);
		$rowsposts8=$wpdb->get_results($query);




			   foreach($rowsposts8 as $rowsposts13){
	 $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."posts where post_type = 'post' and post_status = 'publish' and ID = '".$rowsposts13->object_id."'  order by ID ASC",$id);
			   $rowsposts1=$wpdb->get_results($query);
			   $postsbycat = $rowsposts1;

	 }

	$query="SELECT * FROM ".$wpdb->prefix."huge_it_contact_styles order by id ASC";
	$form_styles=$wpdb->get_results($query);
	$themeId=$row->hc_yourstyle;
	$query = "SELECT *  from " . $wpdb->prefix . "huge_it_contact_style_fields where options_name = '".$row->hc_yourstyle."' ";
    $rows = $wpdb->get_results($query);
    $style_values = array();
    foreach ($rows as $row) {
        $key = $row->name;
        $value = $row->value;
        $style_values[$key] = $value;
    }

	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts WHERE id=%d",$id);
	$row=$wpdb->get_row($query);
	if(!isset($postsbycat)){
		$postsbycat='';
	}
    hugeit_contact_html_edithugeit_contact($id, $ord_elem, $count_ord, $images, $row, $cat_row, $rowim, $rowsld, $paramssld, $rowsposts, $rowsposts8, $postsbycat, $form_styles,$style_values,$themeId);
}
  
function hugeit_contact_add_hugeit_contact()
{
	global $wpdb;
	
	$query="SELECT name,ordering FROM ".$wpdb->prefix."huge_it_contact_contacts WHERE hc_width=0 ORDER BY `ordering`";
	$ord_elem=$wpdb->get_results($query); ///////ordering elements list
	$cat_row=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts where hc_width=0");
	$cat_row=open_cat_in_tree($cat_row);
	
	$table_name = $wpdb->prefix . "huge_it_contact_contacts";
	$wpdb->insert(
		$table_name,
		array(
			'name' => 'New Form',
			'hc_acceptms' => '500',
			'hc_width' => '300',
			'hc_userms' => 'true',
			'hc_yourstyle' => '1',
			'description' => '2900',
			'param' => '1000',
			'ordering' => '1',
			'published' => '300',
		),
		array('%s', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s')
	);

	$query    = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_it_contact_contacts ORDER BY id ASC", $id );
	$rowsldcc = $wpdb->get_results( $query );

	header( 'Location: admin.php?page=hugeit_forms_main_page&id=' . $rowsldcc[count($rowsldcc) - 1]->id . '&task=apply' );

	ob_flush();

}

function hugeit_contact_remove_contact($id){

	global $wpdb;

	$r = $wpdb->delete(
		$wpdb->prefix."huge_it_contact_contacts",
		array('id' => $id),
		array('%d')
	);

	if ( $r ) {
		?>
		<div class="updated"><p><strong><?php _e( 'Item Deleted.' ); ?></strong></p></div>
		<?php
	}
    $row=$wpdb->get_results($wpdb->prepare( 'UPDATE '.$wpdb->prefix.'huge_it_contact_contacts SET hc_width="0"   WHERE hc_width=%d',$id));
	$rows=$wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'huge_it_contact_contacts  ORDER BY `ordering` ASC ');
	
	$count_of_rows=count($rows);
	$ordering_values=array();
	$ordering_ids=array();
	for($i=0;$i<$count_of_rows;$i++)
	{		
	
		$ordering_ids[$i]=$rows[$i]->id;
		if(isset($_POST["ordering"]))
		$ordering_values[$i]=$i+1+$_POST["ordering"];
		else
		$ordering_values[$i]=$i+1;
	}

		for($i=0;$i<$count_of_rows;$i++)
	{	
			$wpdb->update($wpdb->prefix.'huge_it_contact_contacts', 
			  array('ordering'      =>$ordering_values[$i]), 
              array('id'			=>$ordering_ids[$i]),
			  array('%s'),
			  array( '%s' )
			  );
	}

}

function hugeit_contact_captcha_keys( $id ) {
	global $wpdb;
	$idsave       = esc_html( $_GET["id"] );
	$query        = $wpdb->prepare( "SELECT * FROM " . $wpdb->prefix . "huge_it_contact_contacts_fields WHERE hugeit_contact_id=%d", $idsave );
	$rowall       = $wpdb->get_results( $query );
	$leftRightPos = 'left';
	foreach ( $rowall as $value ) {
		if ( $value->hc_left_right == 'right' ) {
			$leftRightPos = 'right';
		}
	}
	$queryMax  = $wpdb->prepare( "SELECT MAX(ordering) AS res FROM " . $wpdb->prefix . "huge_it_contact_contacts_fields WHERE hugeit_contact_id=%d AND hc_left_right=%s", $idsave, $leftRightPos );
	$row8      = $wpdb->get_results( $queryMax );
	$finRes    = $row8[0]->res;
	$queryType = $wpdb->prepare( "SELECT conttype FROM " . $wpdb->prefix . "huge_it_contact_contacts_fields WHERE hugeit_contact_id=%d AND ordering=%d AND hc_left_right=%s", $idsave, $finRes, $leftRightPos );
	$rowType   = $wpdb->get_results( $queryType );
	$toCheck   = $rowType[0]->conttype;
	$resOfMax  = $row8[0]->res;
	$resOfMax  = (int) $resOfMax;
	if ( $toCheck != 'buttons' ) {
		$resOfMax = $resOfMax + 1;
	} else {
		$resOfMax3 = (int)$resOfMax + 1;
		$wpdb->query( $wpdb->prepare( "UPDATE " . $wpdb->prefix . "huge_it_contact_contacts_fields SET ordering = %d WHERE hugeit_contact_id = %d AND ordering=%d", $resOfMax3, $idsave, $resOfMax ) );
	}
	/////////////////////////
	$query        = "SELECT * FROM " . $wpdb->prefix . "huge_it_contact_general_options ";
	$rows         = $wpdb->get_results( $query );
	$param_values = array();
	foreach ( $rows as $row ) {
		$key                  = $row->name;
		$value                = $row->value;
		$param_values[ $key ] = $value;
	}

	if ( isset( $_POST['params'] ) ) {
		$params = $_POST['params'];
		foreach ( $params as $key => $value ) {
			$wpdb->update( $wpdb->prefix . 'huge_it_contact_general_options', array( 'value' => $value ), array( 'name' => $key ), array( '%s' ) );
		}


		$inserttexttype = $wpdb->prefix . "huge_it_contact_contacts_fields";
		$sql_type_text  = "
	INSERT INTO 
	`" . $inserttexttype . "` ( `name`, `hugeit_contact_id`, `description`, `conttype`, `hc_field_label`, `hc_other_field`, `field_type`,`hc_required`, `ordering`, `published`, `hc_input_show_default`, `hc_left_right`) VALUES
	( 'image', '" . $idsave . "', '', 'captcha', '', '','', 'light', '" . $resOfMax . "', 2, '1', '" . $leftRightPos . "' )";


		$wpdb->query( $sql_type_text );

	}
	html_captcha_keys( $param_values );
}

function hugeit_contact_apply_cat($id){
	global $wpdb;	 

    if(isset($_POST["name"])){
		if($_POST["name"] != ''){
			$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_contact_contacts SET  name = %s  WHERE id = %d ", $_POST["name"], $id));
			$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_contact_contacts SET  hc_yourstyle = %s  WHERE id = %d ", $_POST["select_form_theme"], $id));
		}
	}

		
	$query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts WHERE id=%d",$id);
	   $row=$wpdb->get_row($query);

			    $query=$wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_contact_contacts_fields where hugeit_contact_id = %d order by id ASC",$row->id);
			   $rowim=$wpdb->get_results($query);
			   
   foreach ($rowim as $key=>$rowimages){
	   if(isset($_POST)&&isset($_POST["hc_left_right".$rowimages->id.""])){
		   if($_POST["hc_left_right".$rowimages->id.""]){
		   	$id=$rowimages->id;
				if(isset($_POST["field_type".$rowimages->id.""]))$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_contact_contacts_fields SET  field_type = %s WHERE id = %d",$_POST["field_type".$rowimages->id.""],$id));
				if(isset($_POST["hc_other_field".$rowimages->id.""]))$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_contact_contacts_fields SET  hc_other_field = %s WHERE id = %d",$_POST["hc_other_field".$rowimages->id.""],$id));
				if(isset($_POST["titleimage".$rowimages->id.""]))$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_contact_contacts_fields SET  name = %s  WHERE id = %d",stripslashes($_POST["titleimage".$rowimages->id.""]),$id));
				if(isset($_POST["im_description".$rowimages->id.""]))$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_contact_contacts_fields SET  description = %s  WHERE id = %d",$_POST["im_description".$rowimages->id.""],$id));
				if(isset($_POST["hc_required".$rowimages->id.""]))$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_contact_contacts_fields SET  hc_required = %s WHERE id = %d",$_POST["hc_required".$rowimages->id.""],$id));
				if(isset($_POST["imagess".$rowimages->id.""]))$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_contact_contacts_fields SET  hc_field_label = %s  WHERE id = %d",stripslashes($_POST["imagess".$rowimages->id.""]),$id));
				if(isset($_POST["hc_left_right".$rowimages->id.""]))$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_contact_contacts_fields SET  hc_left_right = %s  WHERE id = %d",$_POST["hc_left_right".$rowimages->id.""],$id));
				if(isset($_POST["hc_ordering".$rowimages->id.""]))$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_contact_contacts_fields SET  ordering = %s  WHERE id = %d",$_POST["hc_ordering".$rowimages->id.""],$id));
				if(isset($_POST["hc_input_show_default".$rowimages->id.""]))$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix."huge_it_contact_contacts_fields SET  hc_input_show_default = %s  WHERE id = %d",$_POST["hc_input_show_default".$rowimages->id.""],$id));
			}
		}
	}

if (isset($_POST['params'])) {
      $params = $_POST['params'];
      foreach ($params as $key => $value) {
          $wpdb->update($wpdb->prefix . 'huge_it_contact_params',
              array('value' => $value),
              array('name' => $key),
              array('%s')
          );
      }
     
    }
	
				   if(isset($_POST["imagess"])&&$_POST["imagess"] != ''){
	
$table_name = $wpdb->prefix . "huge_it_contact_contacts_fields";
    $sql_2 = "
INSERT INTO 

`" . $table_name . "` ( `name`, `hugeit_contact_id`, `description`, `hc_field_label`, `hc_other_field`, `ordering`, `published`, `hc_input_show_default`) VALUES
( '', '".$row->id."', '', '".$_POST["imagess"]."', '', 'par_TV', 2, '1' )";

    $wpdb->query($sql_huge_it_contact_contacts_fields);
	

      $wpdb->query($sql_2);
	
	   }
	   
	
	 if(!isset($_POST["posthuge-it-description-length"])){
	 	$_POST["posthuge-it-description-length"]='';
	 	
	 }
	 $_GET['id']=esc_html($_GET['id']);
	 $wpdb->query("UPDATE ".$wpdb->prefix."huge_it_contact_contacts SET  published = '".$_POST["posthuge-it-description-length"]."' WHERE id = ".$_GET['id']." ");

	?>
	<!-- <div class="updated"><p><strong><?php _e('Item Saved'); ?></strong></p></div> -->
	<?php
	
    return true;
	
}
