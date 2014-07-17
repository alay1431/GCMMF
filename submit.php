<?php 
@$flag=$_POST['flag'];
@$zptyp_txt=$_POST['zptyp_txt'];
@$zprod_txt=$_POST['zprod_txt'];
@$comp_value=$_POST['composition_value'];
@$prod_app=$_POST['prod_app'];
@$prod_features=$_POST['prod_features'];
require 'connect.php';
/*---Creates a function to insert data into the database---*/

function insertdata($zptyp_txt,$zprod_txt,$comp_value,$prod_app,$prod_features) {	
	//query for inserting product_features
	$prod_features_query="INSERT INTO `product_features_master` (`zptyp_txt`,`zprod_txt`,`prod_app`,`prod_features`) VALUES ('".$zptyp_txt."','".$zprod_txt."','".$prod_app."','".$prod_features."')";
	mysql_query($prod_features_query);
	//query for updating composition_value
	$prod_comp_query="INSERT INTO `product_composition` (`zprod_txt`,`composition_value`) VALUES ('".$zprod_txt."','".$comp_value."')";
	mysql_query($prod_comp_query);
	
	/*---Query For Dynamic Content---*/
	
	//query to get the nutritional fields from database
	
	$fields_query='SELECT `Sr_no`,`Fields` FROM `nutrition_fields`';
	$query_run=mysql_query($fields_query);
	while($fields_array=mysql_fetch_assoc($query_run)) {
		$n_id=$fields_array['Sr_no'];
		$fields_name=$fields_array['Fields'];
		$fields_name=str_replace(' ','',$fields_name);
		if(!empty($_POST[$fields_name])) {
			$values=$_POST[$fields_name];
			// query for inserting product_nutrition with nutritional values
			$values_query="INSERT INTO `product_nutrition` (`n_id`,`zprod_txt`,`n_value`) VALUES ('".$n_id."','".$zprod_txt."','".$values."')";	
			mysql_query($values_query);
		}
	}
}
if($flag==1) {
	
	// query to delete inserted data
	$delete_features_query= "DELETE FROM `product_features_master` WHERE `zptyp_txt` ='".$zptyp_txt."' AND `zprod_txt` = '".$zprod_txt."'";
	$delete_composition_query="DELETE FROM `product_composition` WHERE `zprod_txt` = '".$zprod_txt."'";
	$delete_n_values="DELETE FROM `product_nutrition` WHERE `zprod_txt` = '".$zprod_txt."'";
	
	mysql_query($delete_features_query);
	mysql_query($delete_composition_query);
	mysql_query($delete_n_values);
	
	insertdata( $zptyp_txt, $zprod_txt, $comp_value, $prod_app, $prod_features);
	
	
} else {
	insertdata( $zptyp_txt,$zprod_txt,$comp_value,$prod_app,$prod_features );
}
?>
