<?php

//$xml = new SimpleXMLElement('<channel></channel>');
//$xml = dom_import_simplexml($simpleXml)->ownerDocument;
//$make = $xml->addChild('item');
//$make->addChild('title',htmlspecialchars(utf8_encode('A entry in the mix!')));
//$make->addChild('link', 'something in the new linke');
//$make->addChild('description', 'THis is a description');
//make->addChild('pubDate', 'Sun Jun 18, 2010 10:30 PM');

//echo $xml->asXML('test_file.xml'). ' file has been written';
// This will save the XML to a file called test_file.xml

require('config.php');

echo WriteXMLFile();

?>