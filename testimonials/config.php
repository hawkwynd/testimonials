<?php

require('database.class.php');

/** MySQL database username */
define('DB_USERNAME', 'database_username_here');
/** MySQL database password */
define('DB_PW', 'database_password_here');
/** MySQL hostname */
define('DB_HOST', 'localhost');
/* MySQL DB name */
define('DB_DATABASE','guru_testimony');
define('TABLE_TESTIMONIALS','testimonials_manager');
define('URL_LINK', "http://www.example.com/testimonials/index.php?r=");
define('EMAIL_LINK', "{email address here}");


function insertNewTestimonial($testimonial) {

if ($_POST) {
	array_shift($_POST); // pop the action
		
  	$kv = array();
			foreach ($_POST as $key => $value) {
				if(strlen($value) >0 ){
    				$kv[] = "$key=$value";
    			}else {
    				$kv[] = "$key=$value" ."..."; // cant have empty cells
    			}
    		
    		//$fields .= $key.',';
				
				switch($key){
					case "testimonials_comments":
					$value = nl2br(strip_tags($value));
				}
				// build the query  				
  				$data[$key] = strip_tags($value);
				
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
	$message .= URL_LINK . $primary_id. "\n";
			
	mail(EMAIL_LINK,'New Testimonial' .$primary_id. ' Added', $message, null);
	
	echo 'New record added. <a href="index.php">Return to listing</a>';
	
	
	
}

/* Show all records */

function listAllRecs () {
	$results ="";

// execute connection to db
	$db = new Database(DB_HOST, DB_USERNAME, DB_PW, DB_DATABASE);
	$db->connect();
	

	$sql = "select * from ". TABLE_TESTIMONIALS;
	$rows = $db->fetch_all_array($sql);

	foreach($rows as $key => $value) {
		foreach($value as $key2 => $value2){
			list($dump, $fieldname) = explode("_", $key2);

			// reload the array with keys			
			$data[$key2] = $value2; 
		
			switch($fieldname) {
				case "id":
					break;
				case "status":
					$results .= ucfirst($fieldname). ': '. ($value2 == 1 ? "approved" : "unapproved"). '<br/>';
					break;
				default:		
				$results .= ucfirst($fieldname). ": ". $value2. "<br/>";
				break;
				}
		}
		
		$results .= '<a href="index.php?r='. $data['testimonials_id'].'">Edit</a> | ';
		$results .= '<a href="index.php?d='. $data['testimonials_id'].'" onclick="return confirm(\'Are you sure you really want to delete?\');">Delete</a>';
		$results .="<hr>";
	}

	return $results;
}
	
function WriteXMLFile () {
	
// execute connection to db
	$db = new Database(DB_HOST, DB_USERNAME, DB_PW, DB_DATABASE);
	$db->connect();
	$xml = new SimpleXMLElement('<channel></channel>');
	
	
	$sql = "select * from ". TABLE_TESTIMONIALS. " where testimonials_status = 1"; // only approved records
	$rows = $db->fetch_all_array($sql);

	foreach($rows as $key => $value) {

		$make = $xml->addChild('item');
		
		foreach($value as $key2 => $value2){
			list($dump, $fieldname) = explode("_", $key2);

			// reload the array with keys			
			$data[$key2] = $value2; 
	
			switch($fieldname) {
				case "name":
					$make ->addChild('title', $value2);
					break;
				case "website":
					$make->addChild('link', $value2);
					break;
				case "comments":
					$make->addChild('description', $value2);
					break;
				case "date":
					$make->addChild('pubDate', $value2);
					break;
				case "city":
					$city = $value2;
					break;
				case "state":
					$make->addChild('region', $city. ' '. $value2 );
					break;
				}
		} //foreach value
		
	} // foreach row

	return $xml->asXML('test_file.xml'). ' file has been written. <a href="index.php">Listings</a><br/><a href="test_file.xml">View XML</a>';
	//return $results;
}	
	
	

function createEmptyForm() {

		
		$db = new Database(DB_HOST, DB_USERNAME, DB_PW, DB_DATABASE);
		$db->connect();
		$sql = "SELECT * FROM ". TABLE_TESTIMONIALS ." where 1";
		$rows = $db->query($sql);

		$num_fields = mysql_num_fields($rows);

		$x = 1;
			echo '<div class="testimonials_form">';
			  	echo '<form action="index.php" name="addnew" method="post">';
  			   echo '<input type="hidden" name="action" value="insert" />';			
			  	echo '<input type="hidden" name="testimonials_status" value="0" />';
			
						
		
		while($x < $num_fields){				   				

          	list($dump, $fieldname) = explode("_", mysql_field_name($rows, $x)); // truncate field name

          	switch($x){
						
						case 8: /* Textarea */
							
							echo '<div id="field">'.ucfirst($fieldname).'</div>';							
							echo '<div class="input"><textarea name="'.mysql_field_name($rows, $x).'" cols="40" rows="4" /></textarea></div>';
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

function loadRecordForEdit($recordid) {
	
	mysql_connect(DB_HOST, DB_USERNAME,DB_PW);
	@mysql_select_db(DB_DATABASE) or die( "Unable to select database");
		
		$sql = "SELECT * FROM ". TABLE_TESTIMONIALS ." where testimonials_id =" . $recordid; 
		$result = mysql_query($sql);
		$fields=mysql_num_fields($result);

		$form = '<div class="testimonials_form"><form action="index.php" name="addnew" method="post">'. "\n";
		$form .= '<input type="hidden" name="action" value="update">';
		$form .= '<input type="hidden" name="testimonials_id" value ="'. mysql_result($result, $i, 0) .'" >';
	
		$row = mysql_fetch_row($result);
  
	for ($i = 1; $i < mysql_num_fields($result); $i++){	
			list($dump, $fieldname) = explode("_", mysql_field_name($result, $i));
			
			$form .= '<div id="field">'. ucfirst($fieldname) . '</div>';
			
			switch (mysql_field_name($result,$i)){
		 	 case 'testimonials_id':
			 	break;
			 case 'testimonials_comments':
				$form .= '<div><textarea cols=70 rows=4 name='. mysql_field_name($result,$i). '>'. mysql_result($result, 0, $i). '</textarea></div>';
				break;

			case 'testimonials_status':
			
				$form .= '<div><select name="'. mysql_field_name($result, $i) . '">';
				$form .= '<option value = '. mysql_result($result, 0, $i). '>';						
				$form .= (mysql_result($result, 0, $i) == 1 ? "approved" : "unapproved") .'</option>';
				
				$form .= '<option value = '. (mysql_result($result, 0, $i) == 0 ? "1" : "0") . '>'  .(mysql_result($result, 0, $i) == 0 ? "approved" : "unapproved") . '</option>';

				$form .= '</select></div>';
				break;			 
			 
			 default:
				 $form .= '<div><input type="text" size=40 maxlength=100 name="'. mysql_field_name($result,$i). '"  value="'. mysql_result($result, 0, $i). '"></div>';
				 break;
		  }
	}
	
	$form .= '<div align="right"><input type="submit" value="update">';
	$form .= '</div>';
	mysql_close();
	
return $form;
	
}		

/*
* Update changes to an existing record 
*
*/

function updateTestimonial($updateData) {
	
if ($_POST) {
	array_shift($_POST); // pop the action
	$record_id = array_shift($_POST); // pop the testimonial_id
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

		
	// execute UPDATE 
	$db = new Database(DB_HOST, DB_USERNAME, DB_PW, DB_DATABASE);
	$db->connect();
	$db->query_update('testimonials_manager', $data, "testimonials_id=" .$record_id );
		
	return 'Successful update to the database. <a href=./>Return to listing</a><br/><a href="writeXML.php">Write the XML</a>';
		
	
}

	// Delete a record 
	
	function deleteRecord($recordid) {
	$db = new Database(DB_HOST, DB_USERNAME, DB_PW, DB_DATABASE);
	$db->connect();

	$sql = "delete from testimonials_manager where testimonials_id = ". $recordid; 
	$db->query($sql);
	

	return 'Record ID:' .$recordid . ' has been deleted. <a href="index.php"> Back to List</a> ';
		
	}
	


/* Validation Class */

class Validator {
public $_errors;
public $_filterArgs;


	protected $_inputType;
	protected $_submitted;
	protected $_required;
	//protected $_filterArgs;
	protected $_filtered;
	protected $_missing;
//	protected $_errors;
	
	public function  __construct($required = array(), $inputType = 'post')
	{ 
		if(!function_exists('filter_list')) {
			throw new Exception('The Validator class requires the Filter Functions in >= PHP 5.2 or PECL.');
			}
		if(!is_null($required) && !is_array($required)) {
			throw new Exception('The names of required fields must be an arrray, even if only one field is required');
			}
		
		$this->_required = $required;
		$this->setInputType($inputType);	
		if($this->_required) {
			$this->checkRequired();
		}

		$this->_filterArgs = array();
		$this->_errors = array();
		$this->_booleans = array();
				
	
	}

	protected function setInputType($type)	{
		switch(strtolower($type)) {
			case 'post' :
				$this->_inputType = INPUT_POST;
				$this->_submitted = $_POST;
				break;
			case 'get':
				$this->_inputType = INPUT_GET;
				$this->_submitted = $_GET;
				break;
			default:
				throw new Exception('Invalid input type. Valid types are "post" and "get". ');
		}	
	}
	
	protected function checkRequired()
	{
		$OK = array();
		foreach ($this->_submitted as $name => $value) {
			$value = is_array($value) ? $value : trim($value);
			if(!empty($value)) {
				$OK[] = $name;
			}
		}
		$this->_missing = array_diff($this->_required, $OK);
		
		// DEBUG
	   //print_r($this->_missing);
	}
	
	protected function checkDuplicateFilter($fieldName) 
	{
		if (isset($this->_filterArgs[$fieldName])) {
			throw new Exception("A filter has already been set for the following field: $fieldName.");
			}
	}
	
	// Verify integer input type
	public function isInt($fieldName, $min = null, $max = null) 
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName] = array('filter' => FILTER_VALIDATE_INT);
		if(is_int($min)) {
			$this->_filterArgs[$fieldName]['options']['min_range'] = $min; 
		}
		if(is_int($max)) {
			$this->_filterArgs[$fieldName]['options']['max_range'] = $max; 
		}
	}

	// Verify Float
	public function isFloat($fieldName, $decimalPoint = '.', $allowThousandSeperator = true) 
	{
		$this->checkDuplicateFilter($fieldName);
		if($decmialPoint != '.' && $decimalPoint != ',') {
			throw new Exception('Decimal point must be a comma or a period in isFloat().');
		}
		$this->_filterArgs[$fieldName] = array(
			'filter' => FILTER_VALIDATE_FLOAT, 
			'options' => array('decimal' => $decimalPoint)
			);
			if($allowThousandSeperator) {
				$this->_filterArgs[$fieldName]['flags'] = FILTER_FLAG_ALLOW_THOUSAND;
			}
	}
	
	public function isNumericArray($fieldName, $allowDecimalFractions = true, $decimalPoint = '.', $allowThousandSeperator = true) 
	{
		$this->checkDuplicateFilter($fieldName);
		if($decmialPoint != '.' && $decimalPoint != ',') {
			throw new Exception('Decimal point must be a comma or a period in isFloat().');
		}
		$this->_filterArgs[$fieldName] = array(
			'filter' => FILTER_VALIDATE_FLOAT,
			'flags' => FILTER_REQUIRE_ARRAY,
			'options' => array('decimal' => $decimalPoint)
			);
			if($allowDecimalFractions) {
				$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ALLOW_FRACTION;
			}
			if($allowThousandSeperator) {
				$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ALLOW_THOUSAND;
			}
		}

	// VALIDATE EMAIL 		
	public function isEmail($fieldName)
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName] = FILTER_VALIDATE_EMAIL;
	}

	// VERIFY FULL URL (HTTP OR FTP)					
	public function isFullURL($fieldName, $queryStringRequired = false) 
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName] = array(
			'filter' => FILTER_VALIDATE_URL,
			'flags' =>	FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED | FILTER_FLAG_PATH_REQUIRED);
			if($queryStringRequired) {
				$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_QUERY_REQUIRED;
			}
	}

	// VALIDATE URL, OMIT REQUIREMENTS FOR SCHEME AND HOST
	public function isURL($fieldName, $queryStringRequired = false) 
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName]['filter'] = FILTER_VALIDATE_URL;
		if($queryStringRequired) {
			$this->_filterArgs[$fieldName]['flags'] = FILTER_FLAG_QUERY_REQUIRED;
		}
	}
				
	// VALIDATE BOOLEAN VALUES
	public function isBool($fieldName, $nullOnFailure = false)
	{
		$this->checkDuplicateFilter($fieldName); 
		$this->_booleans[] = $fieldName;
		$this->_filterArgs[$fieldName]['filter'] = FILTER_VALIDATE_BOOLEAN;
		if($nullOnFailure) {
			$this->_filterArgs[$fieldName]['flags'] = FILTER_VALIDATE_ON_FAILURE;
		}
	}
										
	// VALIDATE AGAINST REGULAR EXPRESSIONS
	public function matches($fieldName, $pattern) 
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName] = array(
			'filter' => FILTER_VALIDATE_REGEXP,
			'options' => array('regexp' => $pattern)
			);
	}
	
	//SANITIZE STRING REMOVING TAGS
	public function removeTags($fieldName, $encodeAmp = false, $preserveQuotes = false, $encodeLow = false, $stripLow = false, $stripHigh = false) 
	{
		$this->checkDuplicateFilter($fieldName); 
		$this->_filterArgs[$fieldName]['filter'] = FILTER_SANITIZE_STRING;
		$this->_filterArgs[$fieldName]['flags'] = FILTER_REQUIRE_ARRAY;
		if($encodeAmp) {
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_AMP;
		}
		if($preserveQuotes){
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_NO_ENCODE_QUOTES;
		}
		if($encodeLow) {
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_LOW;
		}
		if($encodeHigh) {
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_HIGH; 
		}
		if($stripLow) {
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_LOW;
		}
		if($stripHigh) {
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_HIGH;
		}
	}
	
	// sanitize a string by removing special characters to entities
	public function useEntities($fieldName, $isArray = false, $encodeHigh = false, $stripLow = false, $stripHigh = false) 
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName]['filter'] = FILTER_SANITIZE_SPECIAL_CHARS;
		$this->_filterArgs[$fieldName]['flags'] = 0;
		
		if($isArray) {
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_REQUIRE_ARRAY;
		}
		if($encodeHigh) {
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_HIGH;
		}
		if($stripLow) {
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_LOW;
		}
		if($stripHigh) {
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_STRIP_HIGH;
		}
	}
	
	// CHECK THE LENGTH OF TEXT FIELDS
	public function checkTextLength($fieldName, $min, $max = null) 
	{
		$this->checkDuplicateFilter($fieldName);
		$text = trim($this->_submitted[$fieldName]);

		// make sure it is a string value
		if(!is_string($text)) {
				throw new Exception("The checkTextLength() method can only be applied to strings; $fieldName is the wrong data type.");
		}
				 
		// Make sure the 2nd argument is a number
		if(!is_numeric($min)) {
			throw new Exception("The checkTextLength() method expects a number as the second argument (field name: $fieldName)" );
		}
		
		// If the string length is shorter than the minimum, create an error message
		if(strlen($text) < $min) {
			// check for a maximum value
			if(is_numeric($max)){
				$this->_errors[] = ucfirst($fieldName) . " must be between $min and $max characters.";
			} else {
				$this->_errors[] = ucfirst($fieldName) . " must be a minimim of $min characters.";
			}
		}
		// If maximum is set, and the string is too long
		if(is_numeric($max) && strlen($text) > $max) {
			if($min == 0) {
				$this->_errors[] = ucfirst($fieldName) . " must be no more than $max characters.";
				} else {
					$this->_errors[] = ucfirst($fieldName) . " must be between $min and $max characters." ;
				}
		}
		
	}	
	
	// Filter input that requires special handling
	public function noFilter($fieldName, $isArray = false, $encodeAmp = false) 
	{
		$this->checkDuplicateFilter($fieldName);
		$this->_filterArgs[$fieldName]['filter'] = FILTER_UNSAFE_RAW;
		$this->_filterArgs[$fieldName]['flags'] = 0;
		if($isArray) {
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_REQUIRE_ARRAY;
		}
		if($encodeAmp) {
			$this->_filterArgs[$fieldName]['flags'] |= FILTER_FLAG_ENCODE_AMP;
		}
	}
	
	public function validateInput() 
	{
		//Initialize array for required items that haven't been vaidated
		$notFiltered = array();
		//Get names of all fields that have been validated
		$tested = array_keys($this->_filterArgs);
		// Loop through the required fields
		// Add any missing ones to the $notFiltered array
		foreach($this->_required as $field) {
			if (!in_array($field, $tested)) {
				$notFiltered[] = $field;
			}
		}
		//If any items have been added to the $notFiltered array, it means a 
		// required item hasn't been validated, so throw an exception
		if ($notFiltered) {
			throw new Exception('No filter has been set for the following required item(s): ' . implode(',', $notFiltered));
		}
		// Apply the validation tests using filter_input_array()
		$this->_filtered = filter_input_array($this->_inputType, $this->_filterArgs);
		
		//Now find items that failed validation
		foreach($this->_filtered as $key => $value) {
			//skip items that used the isBool() method
			//Also skip any that either missing or not required
			if(in_array($key, $this->_booleans) || in_array($key, $this->_missing) || !in_array($key, $this->_required)) 
			{
				continue;
				}
				//If the filtered value is false, it failed validation,
				//so add it to the $errors array
				elseif($value === false) {
					$this->_errors[$key] = ucfirst($key) . ': invalid data supplied';
				}
			}
			// Return validated input as array
			return $this->_filtered;
	}			
	
	public function getMissing() 
	{
			return $this->_missing;
	}
	
	public function getFiltered() 
	{
		return $this->_filtered;
	}
	
	public function getErrors() {
		return $this->_errors;
	}
	
	
} // end class




?>
