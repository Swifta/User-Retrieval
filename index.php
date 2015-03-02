<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
        
<title>User Retrieval</title>



</head>

<body style="background: url(back.jpg) ;
    border: 0.5px solid black;">
    
    <div style="background-color:#ffffff; margin-left: 250px;padding: 10px; margin-right: 250px;margin-bottom: 100px;margin-top: 65px;padding-bottom: 20px;
         filter: alpha(Opacity=50);
         opacity: 0.5;">
            
        
        
       <?php
            
                                                session_start();
                                                         

if(isset($_GET['code'])){


$code =  $_GET['code'];

}
else{
//authorization                         
                        $data2= "https://accounts.google.com/o/oauth2/auth?"; 

                        $data2 .="response_type=code&";
                        $data2 .="redirect_uri=" . urlencode("http://localhost/admintool/index.php") ."&";

                        $data2 .="client_id=983059794307-u3049j1c383mlb7p6amqc3nl6j441tfu.apps.googleusercontent.com" ."&";

                        $data2 .="scope=" . urlencode("https://www.googleapis.com/auth/admin.directory.user") ."&"; #orgunit user
                    

                        $data2 .="approval_prompt=force&";
                        $data2 .="state=kay&";
                        $data2 .="access_type=offline&"; 

                        $data2 .="include_granted_scopes=true"; #true
                        $datea = $data2;
                        
                        header("Location: $datea");

}


$url = 'https://accounts.google.com/o/oauth2/token';
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
curl_setopt($ch, CURLOPT_FAILONERROR, false);  
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); 

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/x-www-form-urlencoded'
));

curl_setopt($ch, CURLOPT_POSTFIELDS,
    'code=' . urlencode($code) . '&' .
    'client_id=' . urlencode('983059794307-u3049j1c383mlb7p6amqc3nl6j441tfu.apps.googleusercontent.com') . '&' .
    'client_secret=' . urlencode('OPeYNZotVTATKPUHsKjJ53f5') . '&' .
    'redirect_uri=' . urlencode('http://localhost/admintool/index.php') . '&' .
    'grant_type=authorization_code'
);


// Send the request & save response to $resp
$resp = curl_exec($ch);

        //$_SESSION['access_token'] ;
	//$_SESSION['refresh_token'] ;


$json = json_decode($resp, true);

if(!isset($json['access_token'])){
    $access_token = $_SESSION['access_token'];
    $refresh_token = $_SESSION['refresh_token'];
}else{
    $access_token = $json['access_token'];
    $refresh_token = $json['refresh_token'];
}
// Close request to clear up some resources
curl_close($ch);


if (isset($access_token)){
	
	$_SESSION['access_token'] = $access_token;
	$_SESSION['refresh_token'] = $refresh_token;

//echo "Access token generated";
        ?>
    
    <div style="height:auto; margin-left: 150px;margin-right:50px;width: auto;">
            
        
           <form action="" method="POST" enctype="multipart/form-data" style="margin-left:3px;width:auto ;padding:7px;margin-top:1px;margin-right:2px;border: 1px solid #fff;border-radius: 3px; font-family:HelveticaNeue-Light, Helvetica Neue Light, Helvetica Neue, Lucida Grande;font-weight:300px;text-align: left;text-decoration: none;">
            <div >
                <h1>User Retrieval</h1>    </br>
    
        <h3>This application can only be used by Google Apps Administrators. </h3>
        </br>
    </br>
               
            </div>
                
            <label style="float: left;width: 100px;">Domain name: </label>
            <input type="text" name="dom"style="float: left; margin-left: 20px;width: 440px;"/>
            <br><br>
            <label style="float: left;width: 100px;">Query: </label>
                <select id="menu" name="field" style="float: left; margin-left: 20px;width: 100px;"/>
                    <option>None</option>
                    <option>name</option>
                    <option>givenName</option>
                    <option>familyName</option>
                    <option>email</option>
                    <option>orgUnitPath</option>
                    
                </select>
            <label  style="float: left; margin-left: 20px;width: 60px;">Contains</label>
                    
                <input type="text" name="value"style="float: left; margin-left: 20px;width: 240px;"/>
            <br><br>
                <input type="submit" name="submit" value="Submit">
        </form>
      
    
<?php
    if(isset($_REQUEST['submit'])){
        
               $dom = $_POST['dom'];
               $field = $_POST['field'];
              // $operator = $_POST['operator'];
               $value = $_POST['value'];
               
             $access = $_SESSION['access_token'];
             
                                        //$urla = 'https://apps-apis.google.com/a/feeds/calendar/resource/2.0/'.$dom.'?alt=json';
                                        $urla ="https://www.googleapis.com/admin/directory/v1/users";
                                        
                                        $urla2 ="?domain=$dom";
                                        $urla2 .="&maxResults=50";
                                        $urla2 .="&orderBy=familyName";
                                        $urla2 .= "&sortOrder=ascending";
                                        $urla2 .="&query=".urlencode("$field='$value'");
                                        $urla .= $urla2;
                                       
                                        $cha = curl_init($urla);
                                         //echo $urla;
                                        curl_setopt($cha, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt ( $cha , CURLOPT_VERBOSE , 1 );
                                        curl_setopt ( $cha , CURLOPT_HEADER , 1 );
                                        curl_setopt($cha, CURLOPT_FAILONERROR, false);
                                        curl_setopt($cha, CURLOPT_SSL_VERIFYPEER, false);
                                        curl_setopt($cha, CURLOPT_CUSTOMREQUEST, 'GET');
                                        curl_setopt($cha, CURLOPT_CONNECTTIMEOUT ,0);
                                        curl_setopt($cha, CURLOPT_TIMEOUT, 400);
                                        curl_setopt($cha, CURLOPT_HTTPHEADER, array(
                                                        'Authorization:Bearer '.$access

                                        ));


                                      // curl_setopt($cha, CURLOPT_POSTFIELDS, $data3);

                                           $response = curl_exec($cha);
                                           //echo $response;
                                         
                    $error = curl_error($cha);
                    $result = array( 'header' => '', 
                                     'body' => '', 
                                     'curl_error' => '', 
                                     'http_code' => '',
                                     'last_url' => '');


                    if ( $error != "" )
                    {
                        $result['curl_error'] = $error;
                        echo $result['curl_error'];
                    }

                    $header_size = curl_getinfo($cha,CURLINFO_HEADER_SIZE);
                    $result['header'] = substr($response, 0, $header_size);
                    $result['body'] = substr( $response, $header_size );
                    $result['http_code'] = curl_getinfo($cha,CURLINFO_HTTP_CODE);
                    $result['last_url'] = curl_getinfo($cha,CURLINFO_EFFECTIVE_URL);
                   
                    
                    $xmll = json_decode($result['body'], true);
                    //print_r($xmll);
                       // echo $xmll['user'][0]['primaryEmail'];

                    if($result['http_code']=="200"){
                        $csv_hdr = "S/N, Email, Firstname, Lastname, Organization";
                        $csv_output="";
                        
                        ?>
                            <br>
                            <table align="center" border="1" cellpadding="0" cellspacing="0" width="100%">
                            <tr class="dataTableRow">
                                    <td class="main" width="3%"><b>S/N</b></td>
                                    <td class="main" width="10%"><b>Email</b></td>
                                    <td class="main" width="10%"><b>Firstname</b></td>
                                    <td class="main" width="10%"><b>Lastname</b></td>
                                    <td class="main" width="10%"><b>Organization</b></td>
                                                               
                                   
                            </tr>
                        <?Php

                                                    
                       $val =  count($xmll['users']);
                       for($i=0;$i<$val;$i++){
                           
                           ?>
                               <tr>
                                   
            <td align="left" valign="center">
            <br><?php echo $i . "</b>"; 
            $csv_output .= $i. ", "; //ensure the last column entry starts a new line ?>
            </td>
                                   
            <td align="left" valign="center">
            <br><?php echo $xmll['users'][$i]['primaryEmail']; //here we are displaying the contents of the field or column in our rows array for a particular row.
            //while we're at it we might as well store the data in comma separated values (csv) format in the csv_output variable for later use.
            $csv_output .= $xmll['users'][$i]['primaryEmail'] . ", ";?>
            </td>
            <td align="left" valign="center">
            <br><?php echo $xmll['users'][$i]['name']['givenName']; //repeat for all remaining fields or columns we have headings for...
            $csv_output .= $xmll['users'][$i]['name']['givenName'] . ", ";?>
            </td>
            <td align="left" valign="center">
            <br><?php echo $xmll['users'][$i]['name']['familyName']; //repeat for all remaining fields or columns we have headings for...
            $csv_output .= $xmll['users'][$i]['name']['familyName'] . ", ";?>
            </td>
               
            <td align="left" valign="center">
            <br><?php 
                    $organ = preg_replace('/\//',' ',$xmll['users'][$i]['orgUnitPath']);
                    echo $organ; //repeat for all remaining fields or columns we have headings for...
            $csv_output .= $organ . "\n";?>
            </td>
            
            
        </tr>
                                
         <?php
                           
                          //echo $xmll['users'][$i]['primaryEmail'].','.$xmll['users'][$i]['name']['givenName'].','.$xmll['users'][$i]['name']['familyName'].'<br>';
                                                       
                       }
                       
                       
                       ?>
                                </table> 
                                
                                <br>
                                <center>
                                <form name="export" action="export.php" method="post">
                                    <input type="submit" value="Export table to CSV">
                                    <input type="hidden" value="<?php echo $csv_hdr; ?>" name="csv_hdr">
                                    <input type="hidden" value="<?php echo $csv_output; ?>" name="csv_output">
                                </form>
                                </center>
                                 </br>
                                
                        <?php
                          
                         //echo $val;    
                        }else{

                            echo "An error occurred";
                        }

                                           curl_close($cha);

            
         
      }

}else{echo "Access NOT granted";}

?> 
    
        

      </div>       
        

    
    
   </div>
   
</body>
</html>