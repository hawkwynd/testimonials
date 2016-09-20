<?php 
require('config.php'); 

// add and remove users here //////////////////////////////////
$vu = array('hawkwynd' => 'scootre','admin' =>'password'); 
////////////////////////////////////////////////////////////////
if(!checkUser()) doAuth();				

function doAuth() {
	header('WWW-Authenticate: Basic realm="Protected Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Valid username / password required.';
    exit;	
}

function checkUser() {
	global $vu;
	$b = false;
	if($_SERVER['PHP_AUTH_USER']!='' && $_SERVER['PHP_AUTH_PW']!='') {
		if($vu[$_SERVER['PHP_AUTH_USER']] == $_SERVER['PHP_AUTH_PW']) $b = true;
	}
	return $b;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">  
  <head>  
   <link rel="icon" href="../favicon.ico" type="images/x-icon" />  
    <meta name="google-site-verification" content="u7GaQazuOH54mLqTJjc6zr0P1KKeq8iitGEjcs2aH5o" />    
    <title>Website Hosting, Website Design and Custom Programming</title>    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="en" />
    <meta name="keywords" content="Website development,Custom Programming, Website design, Website Hosting, Custom Wordpress" />    
    <meta name="description" content="Affordable Website development, design and Website Hosting Services, custom wordpress and dynamic content management."/>                  
    <link href="../css/styles.css" rel="stylesheet" type="text/css" />    
    <!--[if IE 5]>
        <style type="text/css"> 
        /* place css box model fixes for IE 5* in this conditional comment */
        .twoColFixLt #sidebar1 { width: 230px; }
        .twoColFixLt #rightsidepanel{ width: 230px; }
        </style>
        <![endif]-->    
    <!--[if IE]>
        <style type="text/css"> 
        /* place css fixes for all versions of IE in this conditional comment */
        .twoColFixLt #sidebar1 { padding-top: 30px; }
        .twoColFixLt #mainContent { zoom: 1; }
         body{font-size: 95%;}
        /* the above proprietary zoom property gives IE the hasLayout it needs to avoid several bugs */
        </style>
        <![endif]-->    
  </head>  
  <body class="twoColFixLt">    
    <div id="container">         
    

      </div>                 
      <div id="sidebar1">    
      	<?php
      	
      	if(checkUser()) echo '<div class="menuitem"><a href="index.php?f=1">Add new</a></div>';
      	

      	?>
      	
      </div>                          
      
      <!-- // Right Side Panel -->         
      <div id="rightsidepanel">                 
         
      </div>         
      <!--// End Right Side Panel -->                       

      <div id="mainContent">             
    	   <h2 class="menuhead">Testimonials Management Tool  </h2>
    	                    
        <div class="contentBox">                               
		
        <!-- Begin Testimonials Management display -->

		<?php 

			// load form for new record
			if($_GET['f']==1) echo createEmptyForm(); // display open form
				// submitting a new testimonial
				
			if(isset($_GET['r'])) {
				echo loadRecordForEdit($_GET['r']);
			}
			
			// Post a new record

			if($_POST['action'] == "insert") {
			 

			if (filter_has_var(INPUT_POST, 'date_added')) {
			try {    
		    $required = array('name' , 'email' , 'comments');
		    $val = new Validator($required);
		    $val->checkTextLength('name', 3);
		    $val->removeTags('name');
		    $val->isEmail('email');
		    $val->checkTextLength('comments', 10, 500);
		    $val->useEntities('comments');
		    $filtered = $val->validateInput();
			 $missing = $val->getMissing();
			 $errors = $val->getErrors();
			
					if(!$missing && !$errors) 
					{
					//passed validation
					// the validated input is store in $filtered
					 echo insertNewTestimonial($filtered);
					}
				} catch (Exception $e) 
					{ 
					echo $e;
					} 
			}	
 			} // if POST
 	


			// Update Record
			if($_POST['action'] == "update") {
				echo updateTestimonial($_POST);
				}
				
			
			// List all records
			if((!$_GET) && (!$_POST)) {
				
				echo listAllRecs(); // display open recs
				}
			
			if(isset($_GET['d'])) {

				echo deleteRecord($_GET['d']);
				}
				
			
		?>
										
        
               
        
        
        

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