<?php 
/*
* Music City Guru Main page
* Include Wordpress functions
*/
define('WP_USE_THEMES', false);
require('../wp/wp-load.php');
require('../config.php'); 
require('../images/get_logo.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">  
  <head>  
    <link href="../css/styles.css" rel="stylesheet" type="text/css" />    
  </head>  
  <body class="twoColFixLt">    
    <div id="container">         
      <div id="banner">
        <a href="../.">
          <img src="../images/<?php echo get_logo(); ?>.png" alt="Music City Guru Logo image" alt="Music City Guru" width="259" height="173" class="logo" id="logo" /></a>
      </div>
      
      <!-- // Right Side Panel -->         
      <div id="rightsidepanel">                 
      </div>         
      <!--// End Right Side Panel -->                       

      <div id="mainContent">             
        <h2 class="menuhead">Add A Testimonials</h2> 
        <div class="contentBox">                               
		    <p>Music City Guru always welcomes feedback, especially positive news 
					from our customers. Please feel free to add your experience, or if you have 
					something to share about the service you've received we are gratefully willing 
					to share your experiences with our customers and website visitors. </p>
					 
        <!-- Begin Testimonials display -->
        <?php 

				 	mysql_connect($dbhost, $dbuser, $dbpassword);
					@mysql_select_db($dbname) or die( "Unable to select database");
					
					$query = "SELECT * FROM testimonials_manager where 1";
					$result = mysql_query($query) or die ("Could Not execute Query");

					echo '<div class="testimonials_form">';
				
				  	echo '<form action="addnewtestimonial.php" name="addnew" method="post">';
				  	echo '<input type="hidden" name="testimonials_status" value="0" />';
				  	
					
					$num_fields = mysql_num_fields($result);
					$x = 1;
					
					while($x < $num_fields){				   				

          	list($dump, $fieldname) = explode("_", mysql_field_name($result, $x)); // truncate field name

          	switch($x){
						
						case 8: /* Textarea */
							
							echo '<div id="field">'.ucfirst($fieldname).'</div>';							
							echo '<div class="input"><textarea name="'.mysql_field_name($result, $x).'" cols="70" rows="6" /></textarea></div>';
							break;						

						case 9:
						case 10:
						break;

						case 11: // ratings select
						
							echo '<div id="field">Please rate your experience (1=Poor 10=Best) ';
							echo '<select name="testimonials_'. mysql_field_name($result, $x). '">';
							for ($count =1; $count <=10; $count++){
								echo '<option value="'.$count. '"';
								if($count == 5) echo ' selected ';
								echo ' >'.$count.'</option>';
							}
							echo '</select></div>';
							break;
														
						
						default:
							echo '<div id="field">'.ucfirst($fieldname).'</div>';
							echo '<div class="input"><input type="text" size="40" name="'.mysql_field_name($result, $x).'" /></div>';
							break;
														
    				}
    				
						$x++;
						

					}
					
					?>
					<div id="field" align="right">	<input type="submit"></div>
					</form>   
                
				   
     
        

          
        <!--//end Contentbox-->                                                                        
      </div> 
      <!--// end mainContent -->                
    </div>
    <!--// end container -->                                                   
    <!-- 
          This clearing element should immediately 
          follow the #mainContent div in order to force the 
          #container div to contain all child floats 
        --><br class="clearfloat" />  
        
   </body>
</html>