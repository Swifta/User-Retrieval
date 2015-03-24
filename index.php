<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">

  <script src="bower_components/webcomponentsjs/webcomponents.js"></script>
  
  

<!-- Import element -->


  <link rel="import" href="bower_components/core-icons/core-icons.html">
      <link rel="import" href="bower_components/core-label/core-label.html">
          <link rel="import" href="bower_components/core-selector/core-selector.html">
  <link rel="import" href="bower_components/core-toolbar/core-toolbar.html">
      <link rel="import" href="bower_components/core-menu/core-menu.html">
      <link rel="import" href="bower_components/paper-input/paper-input-decorator.html">
  <link rel="import" href="bower_components/font-roboto/roboto.html">
  <link rel="import" href="bower_components/paper-button/paper-button.html">
  <link rel="import" href="bower_components/paper-checkbox/paper-checkbox.html">
  <link rel="import" href="bower_components/paper-icon-button/paper-icon-button.html">
  <link rel="import" href="bower_components/paper-fab/paper-fab.html">
  <link rel="import" href="bower_components/paper-tabs/paper-tabs.html">
  <link rel="import" href="bower_components/paper-toast/paper-toast.html">
  <link rel="import" href="bower_components/paper-dropdown-menu/paper-dropdown-menu.html">
  <link rel="import" href="bower_components/paper-dropdown/paper-dropdown.html">
  <link rel="import" href="bower_components/paper-item/paper-item.html">
 
      
      <link rel="stylesheet" href="styles.css">
        
<title>User Retrieval</title>



</head>

<body unresolved>
    
     <core-toolbar>
         <paper-icon-button icon="cloud-download"></paper-icon-button>
    <span flex>User Retrieval</span>
    
  </core-toolbar>

  
      
      
    
  
  
  
    
    <div style="background-color:#ffffff; margin-left: 250px;padding: 10px; margin-right: 250px;margin-bottom: 100px;margin-top: 65px;padding-bottom: 20px;
         //filter: alpha(Opacity=70);
         //opacity: 0.7;
         ">
            
        
        
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

                        $data2 .="client_id=867271592456-aa9mhjt0vkpor0f89efim1k4la2ucm7j.apps.googleusercontent.com" ."&";

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
    'client_id=' . urlencode('867271592456-aa9mhjt0vkpor0f89efim1k4la2ucm7j.apps.googleusercontent.com') . '&' .
    'client_secret=' . urlencode('_ZBtB4Bv1n0KOw-pQTvxC6_m') . '&' .
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
    
    <div style="height:auto; margin-left: 50px;margin-right:50px;width: 1000px;">
            
        
        <form id="myForm"  action="" method="POST"  style="margin-left:3px;width:725px ;padding:7px;margin-top:1px;margin-right:2px;border: 1px solid #fff;border-radius: 3px;">
            <div >
                
    
        <p>This application can only be used by Google Apps Administrators. </p>
      
        </br>
    </br>
               
            </div>
            <paper-input-decorator label="Your domain" >
                <input is="core-input" name="dom">
            </paper-input-decorator>
                  
            
            <br><br>
                                      
                    
                    <label style="float: left;width: 100px;padding-top: 22.5px;height:50px;background-color: transparent;">Query:</label>
                  
              <paper-input-decorator  style="float: left; margin-left: 10px;width: 250px;">
                <select id="menu"  name="field" style="float: left; margin-left: 0px;width: 250px; border:0px; font: inherit;
  color: inherit;
  background-color: transparent;
  border: none;
  outline: none;"/>
                <option value='' disabled selected style='display:none;'>Select an item</option>
                    <option value="givenName">Given Name</option>
                    <option value="familyName">Family Name</option>
                    <option value="email">Email</option>
                    <option value="orgUnitPath">Org Unit Path</option>
                    
                </select>           
              </paper-input-decorator >    
                
                <label style="float: left;margin-left: 25px;width: 65px;padding-top: 22.5px;height:50px;background-color: transparent;">Equals</label>
                  
                <paper-input-decorator label="value"style="float: left; margin-top: 3px;margin-left:0px;width: 275px;">
                 <input is="core-input" name="value" />
            </paper-input-decorator>
                
            <br><br><br><br>
                    
             <paper-submit-button-decorator style="width: 100px;margin-top: 10px;margin-bottom: 100px">
                        
                        <button type="submit" name="submit">Submit</button>
                    </paper-submit-button-decorator>       
                <br><br>
                <br><br><br><br>
                               
        </form>
        <style shim-shadowdom>
  paper-submit-button-decorator {
    padding:0;
  }
  paper-submit-button-decorator::shadow .button-content {
    padding:0;
  }
  paper-submit-button-decorator button {
    //padding:1em;
    //background-color: transparent;
    //border-color: transparent;
    
    //background-color: #5677fc;
    //color: #fff;
    
    text-transform: inherit;
        padding: 0.7em 0.57em;
        
        
        display: inline-block;
        position: relative;
        box-sizing: border-box;
        min-width: 5.14em;
        max-width: 6.00em;
        margin: 0 0.29em;
        background: transparent;
        text-align: center;
        font: inherit;
        text-transform: uppercase;
        outline: none;
        border-radius: 3px;
        -moz-user-select: none;
        -ms-user-select: none;
        -webkit-user-select: none;
        user-select: none;
        cursor: pointer;
        z-index: 0;
      
    
    margin: 0.5em 1em 0.5em 0;
  width: 10em;
      background-color: #5677fc;
  color: #fff;
  }
  paper-submit-button-decorator button::-moz-focus-inner {
    border: 0;
  }
</style>

                    </div> 
        
    <div style="height:auto; margin-left: 0px;margin-right:100px;width: 920px;">
<?php

        
    if(isset($_REQUEST['submit'])){
        //echo "I entered here";
        
               $dom = $_POST['dom'];
               
               $field = $_POST['field'];
              // $operator = $_POST['operator'];
               $value = $_POST['value'];
               
               $k = 0;
               
             $access = $_SESSION['access_token'];
             
                                        //$urla = 'https://apps-apis.google.com/a/feeds/calendar/resource/2.0/'.$dom.'?alt=json';
                                        $urla ="https://www.googleapis.com/admin/directory/v1/users";
                                        
                                        $urla2 ="?domain=$dom";
                                        $urla2 .="&maxResults=1";
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
                                    <td class="main"  width="3%"><b>S/N</b></td>
                                    <td class="main" width="10%"><b>Email</b></td>
                                    <td class="main" width="10%"><b>Firstname</b></td>
                                    <td class="main" width="10%"><b>Lastname</b></td>
                                    <td class="main" width="10%"><b>Organization Path</b></td>
                                                               
                                   
                            </tr>
                        <?Php
                      if(isset($xmll['users'])){
                                                                             
                       $val =  count($xmll['users']);
                       for($i=0;$i<$val;$i++){
                           
                           ?>
                               <tr>
                                   
            <td align="left" valign="center">
            <br><?php echo $i+1 . "</b>"; 
            $csv_output .= $i+1 . ", "; //ensure the last column entry starts a new line ?>
            </td>
                                   
            <td align="left" valign="center">
            <br><?php echo $xmll['users'][$i]['primaryEmail']; //here we are displaying the contents of the field or column in our rows array for a particular row.
            //while we're at it we might as well store the data in comma separated values (csv) format in the csv_output variable for later use.
            $csv_output .= $xmll['users'][$i]['primaryEmail'] . ", ";?>
            </td>
            <td align="left" valign="center">
                <br><?php echo strtoupper($xmll['users'][$i]['name']['givenName']); //repeat for all remaining fields or columns we have headings for...
            $csv_output .= strtoupper($xmll['users'][$i]['name']['givenName']) . ", ";?>
            </td>
            <td align="left" valign="center">
                <br><?php echo strtoupper($xmll['users'][$i]['name']['familyName']); //repeat for all remaining fields or columns we have headings for...
            $csv_output .= strtoupper($xmll['users'][$i]['name']['familyName']) . ", ";?>
            </td>
               
            <td align="left" valign="center">
            <br><?php 
                    $organ = preg_replace('/\//',' ',$xmll['users'][$i]['orgUnitPath']);
                    echo strtoupper($organ); //repeat for all remaining fields or columns we have headings for...
            $csv_output .= strtoupper($organ) . "\n";?>
            </td>
            
            
        </tr>
                                
         <?php
                           
                          //echo $xmll['users'][$i]['primaryEmail'].','.$xmll['users'][$i]['name']['givenName'].','.$xmll['users'][$i]['name']['familyName'].'<br>';
                       }
                       
                      
                       
                       // if(isset($xmll['nextPageToken'])){
                            
                            
                       // }
                              while(isset($xmll['nextPageToken'])){
                                  
                                  $k++;
                              //nextpagetoken method
                                            $nextpagetoken = $xmll['nextPageToken'];
                                            unset($urla);
                                            unset($urla2);
                                            
                                        $urla ="https://www.googleapis.com/admin/directory/v1/users";
                                        
                                        $urla2 ="?domain=$dom";
                                        $urla2 .="&maxResults=1";
                                        $urla2 .="&orderBy=familyName";
                                        $urla2 .= "&sortOrder=ascending";
                                        $urla2 .="&query=".urlencode("$field='$value'");
                                        $urla .= $urla2;
                                            
                                            $urla .= "&pageToken=$nextpagetoken";
                                            $cha = curl_init($urla);
                                                      //echo $urla;
                                                     curl_setopt($cha, CURLOPT_RETURNTRANSFER, true);
                                                     curl_setopt ( $cha , CURLOPT_VERBOSE , 1 );
                                                     curl_setopt ( $cha , CURLOPT_HEADER , 1 );
                                                     curl_setopt($cha, CURLOPT_FAILONERROR, false);
                                                     curl_setopt($cha, CURLOPT_SSL_VERIFYPEER, false);
                                                     curl_setopt($cha, CURLOPT_CUSTOMREQUEST, 'GET');
                                                     curl_setopt($cha, CURLOPT_CONNECTTIMEOUT ,0);
                                                     curl_setopt($cha, CURLOPT_TIMEOUT, 4000);
                                                     curl_setopt($cha, CURLOPT_HTTPHEADER, array(
                                                                     'Authorization:Bearer '.$access

                                                     ));


                                                   // curl_setopt($cha, CURLOPT_POSTFIELDS, $data3);

                                                        $response2 = curl_exec($cha);
                                                        unset($result);
                                                        
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
                                                           
                                                        
                                                        
                                                        
                                                        
                                                       // unset($nextpagetoken);
                                                        unset($xmll);                                        
                                                        
                                                        
                                                        $header_size = curl_getinfo($cha,CURLINFO_HEADER_SIZE);
                                                        $result['header'] = substr($response2, 0, $header_size);
                                                        $result['body'] = substr( $response2, $header_size );
                                                        $result['http_code'] = curl_getinfo($cha,CURLINFO_HTTP_CODE);
                                                        $result['last_url'] = curl_getinfo($cha,CURLINFO_EFFECTIVE_URL);
                                                        
                                                        

                                                        $xml = json_decode($result['body'], true);
                                                        
                                                        $xmll = json_decode($result['body'], true);
                                                        //$xmll = $xml;
                                                        $val =  count($xml['users']);
                                                        curl_close($cha);
                       for($i=0;$i<$val;$i++){
                           
                           ?>
                               <tr>
                                   
            <td align="left" valign="center">
            <br><?php echo $k+$i+1 . "</b>"; 
            $csv_output .= $k+$i+1 . ", "; //ensure the last column entry starts a new line ?>
            </td>
                                   
            <td align="left" valign="center">
                <br><?php echo strtolower($xml['users'][$i]['primaryEmail']); //here we are displaying the contents of the field or column in our rows array for a particular row.
            //while we're at it we might as well store the data in comma separated values (csv) format in the csv_output variable for later use.
            $csv_output .= strtolower($xml['users'][$i]['primaryEmail']) . ", ";?>
            </td>
            <td align="left" valign="center">
                <br><?php echo strtoupper($xml['users'][$i]['name']['givenName']); //repeat for all remaining fields or columns we have headings for...
            $csv_output .= strtoupper($xml['users'][$i]['name']['givenName']) . ", ";?>
            </td>
            <td align="left" valign="center">
                <br><?php echo strtoupper($xml['users'][$i]['name']['familyName']); //repeat for all remaining fields or columns we have headings for...
            $csv_output .= strtoupper($xml['users'][$i]['name']['familyName']) . ", ";?>
            </td>
               
            <td align="left" valign="center">
            <br><?php 
                    $organ = preg_replace('/\//',' ',$xml['users'][$i]['orgUnitPath']);
                    echo strtoupper($organ); //repeat for all remaining fields or columns we have headings for...
            $csv_output .= strtoupper($organ) . "\n";?>
            </td>
            
            
        </tr>
                                
         <?php
                           
                          //echo $xmll['users'][$i]['primaryEmail'].','.$xmll['users'][$i]['name']['givenName'].','.$xmll['users'][$i]['name']['familyName'].'<br>';
                       }
                                                    
                          // if(isset($xml['nextPageToken'])){
                               /*if(isset($xmll['nextPageToken'])== $nextpagetoken){
                                     echo "Same Pagetoken";                                                                  
                                    break;
                                    
                                } else{
                                
                                }*/
                                                    
                                                   
                                                        
                                                        
                              }                  

                          //}
                       }
                       
                       
                       ?>
                                </table> 
                                
                                <br>
                                <center>
                                <form name="export" action="export.php" method="post">
                                    <input type="submit" value="CSV Export" class="hi">
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

                                          

            
         
      }

}else{echo "Access NOT granted";}

?> 
    
        

            
        

    </div>
    
   </div>
    
</body>
</html>