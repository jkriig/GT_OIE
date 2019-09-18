<?php
// If 1, display things to debug.
$debug="0";

// You must configure the url and key in the array below.

$config = array(
        'url'=>'https://XXXX.gatech.edu/api/http.php/tickets.json',  // URL to site.tld/api/tickets.json
		'key'=>' '  // API Key goes here
);
# NOTE: some people have reported having to use "https://kiosk.oie.gatech.edu/api/http.php/tickets.json" instead.

if($config['url'] === 'https://XXXXX.gatech.edu/api/tickets.json') {
  echo "<p style=\"color:red;\"><b>Error: No URL</b><br>You have not configured this script with your URL!</p>";
  echo "Please edit this file ".__FILE__." and add your URL at line 18.</p>";
  die();  
}		
//print_r($_POST);


require_once("GTED.php");
$gted = new GTED();
if(isset($_POST['gtid']))
            {
                if($_POST['gtid'] != 0)
				{	
					$gtedInfo = $gted->getUser($_POST['gtid']);
					$name = $gtedInfo["displayname"]["0"];
					$email = $gtedInfo["gtprimarygtaccountusername"]["0"];
					$gtid = $gtedInfo["gtgtid"]["0"];  					
				}
				else
				{
					$name = $_POST['name'];
					$gtid = $_POST['gtid'];
					$email = $_POST['emailadd'];
				}					
				$sub=stripslashes($_POST['subject']);
				//check email for @ symbol
				$real_email = strstr($email, '@');
				//if the "email" is just a gtusername then add the rest of the email.
				if ($real_email === false) 
					{
						$email=$email . "@gatech.edu";
						//echo $email;
					}           
                
				$topic_sub = $sub;
				if ($topic_sub == "International Student Walk-in") {
					$topicId = 13;
				} 
				elseif ($topic_sub == "Scholar or Student Intern Walk-in") {
					$topicId = 14;
				}
				elseif ($topic_sub == "Travel Signature") {
					$topicId = 15;
				}
				elseif ($topic_sub == "New Student Check-in") {
					$topicId = 16;
				}
				elseif ($topic_sub == "Appointment") {
					$topicId = 17;
				}
                
                # Fill in the data for the new ticket, this will likely come from $_POST.
                # NOTE: your variable names in osT are case sensiTive. 
                # So when adding custom lists or fields make sure you use the same case
                # For examples on how to do that see Agency and Site below.
                $data = array(
                    'name'      =>      $name,  // from name aka User/Client Name
                    'email'     =>      $email,  // from email aka User/Client Email
                	'phone' 	=>		$gtid,  // phone number aka User/Client Phone Number
                    'subject'   =>      $sub,  // test subject, aka Issue Summary
                    'message'   =>      'New walkup. GTID: '.$gtid,  // test ticket body, aka Issue Details.
                    'ip'        =>      $_SERVER['REMOTE_ADDR'], // Should be IP address of the machine thats trying to open the ticket.
                	'topicId'   =>      $topicId,
                	//'Agency'  =>		'58', //this is an example of a custom list entry. This should be the number of the entry.
                	//'Site'	=>		'Bermuda'; // this is an example of a custom text field.  You can push anything into here you want.	
                    //'attachments' => array()
                );
			
				if ($email == '@gatech.edu')
				{
					$message = "Your email address didn't come through. Please try manually entering your email or See the Frontdesk to check in.";
  					echo "<script type='text/javascript'>alert('$message'); window.location = 'https://kiosk.oie.gatech.edu/submitticket.php';</script>";
					
				}
				if($debug =='1') {
					//echo 'Debug: <br />';
					var_dump($data);					
					//die();
					//echo ': <br />';
					//echo ': <br />';							
				}
}

#pre-checks
function_exists('curl_version') or die('CURL support required');
function_exists('json_encode') or die('JSON support required');

#set timeout
set_time_limit(60);


#curl post
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $config['url']);
//curl_setopt($ch, CURLOPT_SSLVERSION, 3);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.8');
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);

//Enable if cert errors
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
$result = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

//echo $result;
//echo $code;
$ticket_id = (int) $result;
if ($ticket_id)
{
	echo $ticket_id;
}
else
{
	echo 0;
}

?>

