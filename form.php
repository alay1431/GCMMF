<?php

require 'connect.php';
/*---Checks if the product selected is already entered---*/

$check_query = "SELECT `zptyp_txt`,`zprod_txt`,`prod_app`,`prod_features` FROM `product_features_master` WHERE `zptyp_txt` = '".$zptyp_txt."' AND `zprod_txt` = '".$zprod_txt."'";
$query_run=mysql_query($check_query);
if(mysql_num_rows($query_run)!=0) {
	$flag=1;
	// query to get the values of existing data
	while($prod_values = mysql_fetch_assoc($query_run)) {
		$prod_app=$prod_values['prod_app'];
		$prod_features=$prod_values['prod_features'];
	}
	/*--- QUERY to get product nutrition values ---*/
	$nutval_query="SELECT `n_id`, `zprod_txt`,`n_value` FROM `product_nutrition` WHERE `zprod_txt`='".$zprod_txt."'";
	$query_run=mysql_query($nutval_query);
	$values_array=array();
	$i=0;
	while($values=mysql_fetch_assoc($query_run)) {
		$values_array[$i]=$values;
		$i++;
	}
	// Query to get Product Composition 
	$compo_query = "SELECT `composition_value` FROM `product_composition` WHERE `zprod_txt` ='".$zprod_txt."'";
	$query_run=mysql_query($compo_query);
	$values=mysql_fetch_assoc($query_run);
	$composition_value=$values['composition_value'];
} else {
	$flag=0;
}


?>	
	<h2>Fill in the following details:</h2>
	<p id="empty_message"></p>
	<form action="submit.php" method="POST" onSubmit="return validation()" >
	<label>Product Details</label><br/><br/>
	<table>
	<tr >
		<td class="fields" >Product Category:</td>
		<td class="values"><input type="text" id="zptyp_txt" name="zptyp_txt" value="<?php echo $zptyp_txt;?>"></td>
		<td id="empty"><p id="empty_zptyp_txt"></p></td>
	</tr>
	
	<tr >
		<td class="fields">Product Name:</td>
		<td class="values"><input type="text" id="zprod_txt" name="zprod_txt" value="<?php echo $zprod_txt;?>"></td>
		<td id="empty"><p id="empty_zprod_txt"></p></td>
	</tr>
	
	
	
	
	<input type="hidden" name="flag" <?php if(@$flag==1) { echo 'value="1"' ;} else { echo 'value="0"'; }?> > 
	
	
	
	
	
	</table><br/>
	<label>Product Specification</label><br/><br/><br/>
	<table>
	<tr>
				<td class="fields" id="text_area">Product Composition:</td>
				<td class="values"><textarea name="composition_value" id="composition_value" rows="5" ><?php if(@$flag==1){ echo $composition_value; }?></textarea></td>
				<td id="empty"><p id="empty_composition_value"></p></td>
	</tr>
	</table>
	<b>Nutritional Information</b> (*Fill Data along with Units of measurement)<b>:</b><br/><br/>
	<?php
	
	if(connect('nutriational_info')){
		$query = 'SELECT `Sr_no`,`fields` FROM `nutrition_fields`';
		$query_run=mysql_query($query);
	?>
	<table id="nutritional_info">		
		<?php
		while ($feild_rows=mysql_fetch_assoc($query_run)) {
			$temp_n_id=$feild_rows['Sr_no'];
		;?>
			<tr>
				<td class="fields"><?php echo  $feild_rows['fields'].":";?></td>
				<td class="values"><input type = "text" name="<?php $temp=$feild_rows['fields']; $temp=str_replace(' ','',$temp); echo $temp?>"   id="<?php echo $temp;?>" 
				<?php 
					if(@$flag==1) { 
						/*$echo_query="SELECT `n_id`,`n_value` FROM `product_nutrition` WHERE `zprod_txt`= '".$zprod_txt."' AND `n_id`= '".$temp_n_id."'";
						$echo_query_run=mysql_query($echo_query);
						$value=mysql_fetch_assoc($echo_query_run);
						$echo_value=$value['n_value'];*/
						echo 'value = "'.$values_array[$temp_n_id-1]['n_value'].'"';
					}
				?>
				></td>
			</tr>
		<?php	
			}
		}
		?>
	</table><br/>
	<table>
	<tr>
		<td class="fields" id="text_area">Product Features:</td>
		<td class="values"><textarea type="text" name="prod_features" id="prod_features" rows="6"><?php if(@$flag==1){ echo $prod_features; }?></textarea></td>
		<td id="empty"><p id="empty_prod_features"></p></td>
	</tr>
	<tr>
		<td class="fields" id="text_area">Product Application:</td>
		<td class="values"><textarea type="text" name="prod_app" id="prod_app" rows="6"><?php if(@$flag==1){ echo $prod_app; }?></textarea></td>
		<td id="empty"><p id="empty_prod_app"></p></td>
	</tr>
	</table>
	</html>
	<input type="submit" value="Submit" id="submit">
	</form>
