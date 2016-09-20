<?php
require('database.class.php');

// Loads form, accepts POST to add new record to database

/** MySQL database username */
define('DB_USERNAME', 'guru_hawkwynd');
/** MySQL database password */
define('DB_PW', 'scootre');
/** MySQL hostname */
define('DB_HOST', 'localhost');
/* MySQL DB name */
define('DB_DATABASE','guru_testimony');
define('TABLE_TESTIMONIALS','testimonials_manager');



// load form for new record
			if($_GET['f']==1) echo createEmptyForm(); // display open form
				// submitting a new testimonial
			
			// Post a new record
			if($_POST['action'] == "insert") echo insertNewTestimonial($_POST);
			
			
			



function insertNewTestimonial($testimonial) {

if ($_POST) {
	array_shift($_POST); // pop the action
		
  	$kv = array();
			foreach ($_POST as $key => $value) {
    		$kv[] = "$key=$value";
    		//$fields .= $key.',';
				
				switch($key){
					case "testimonials_comments":
					$value = nl2br(strip_tags($value));
				}
				// build the query  				
  				$data[$key] = strip_tags($value, $allowable_tags = null);
				
			}
  		}


	//$mysqldate = date( 'Y-m-d H:i:s');
	$data['testimonials_date_added'] = date( 'Y-m-d H:i:s');
	
	// execute 
	$db = new Database(DB_HOST, DB_USERNAME, DB_PW, DB_DATABASE);
	$db->connect();

	//obtain id	
	$primary_id = $db->query_insert(testimonials_manager, $data);
	
	$message = "A new testimonial has been added to the database on musiccityguru.com and requires approval.\n";
	$message .= "http://www.musiccityguru.com/testimonials/index.php?r=". $primary_id. "\n";
			
	mail('scott@musiccityguru.com','New Testimonial' .$primary_id. ' Added', $message, null);
	
	header('location: ../news.php?page_id=390', $replace = null, $http_response_code = null);
	
	
	
}

function createEmptyForm() {

		//DEBUG		
		//print (DB_HOST . '-'. DB_USERNAME .'-'. DB_PW .'-'. DB_DATABASE);
		
		$db = new Database(DB_HOST, DB_USERNAME, DB_PW, DB_DATABASE);
		$db->connect();
		$sql = "SELECT * FROM ". TABLE_TESTIMONIALS ." where 1";
		$rows = $db->query($sql);

		$num_fields = mysql_num_fields($rows);

		$x = 1;

  			   echo '<input type="hidden" name="action" value="insert" />';			
			  	echo '<input type="hidden" name="testimonials_status" value="0" />';
			
						
		
		while($x < $num_fields){				   				

          	list($dump, $fieldname) = explode("_", mysql_field_name($rows, $x)); // truncate field name

          	switch($x){
						
						case 8: /* Textarea */
							
							echo '<div id="field">'.ucfirst($fieldname).'</div>';							
							echo '<div class="input"><textarea name="'.mysql_field_name($rows, $x).'" cols="70" rows="6" /></textarea></div>';
							break;						

						case 9:
						case 10:
						break;

						case 11: // ratings select
						
							echo '<div id="field">Please rate your experience (1=Poor 10=Best) ';
							echo '<select name="'. mysql_field_name($rows, $x). '">';
							for ($count =1; $count <=10; $count++){
								echo '<option value="'.$count. '"';
								if($count == 5) echo ' selected ';
								echo ' >'.$count.'</option>';
							}
							echo '</select></div>';
							break;
														
						
						default:
							echo '<div id="field">'.ucfirst($fieldname).'</div>';
							echo '<div class="input"><input type="text" size="40" name="'.mysql_field_name($rows, $x).'" /></div>';
							break;
														
    				}
    				
						$x++;
						

					}
				
				echo '<div id="field" align="right"><input type="submit"></div></form>';   


}		
			