<?php
    //Require other files.
    require_once 'Settings.php';
    require_once 'AuthorizationHelperForGraph.php';

    class GraphServiceAccessHelper
    {
        // Constructs a Http GET request to fetch the entry for the current user.
        // Returns the json decoded respone as the objects that were recieved in feed.
        public static function getMeEntry(){
            // initiaze curl which is used to make the http request
            $ch = curl_init();
            
            // Add authorization and other headers. Also set some common settings.
            self::AddRequiredHeadersAndSettings($ch);
            
            // Create url for the entry based on the feedname and the key value
            $feedURL = "https://graph.windows.net/".Settings::$appTenantDomainName."/me/";
        	//$feedURL = "https://graph.windows.net/me";
            $feedURL = $feedURL."?".Settings::$apiVersion;
			
			//echo "<br><br>feedURL: ".$feedURL;
			
            curl_setopt($ch, CURLOPT_URL, $feedURL);             
            
            //Enable fiddler to capture request
            
            // $output contains the output string 
			//echo "<br><br>CURLOPT_URL: ".CURLOPT_URL;


            $output = curl_exec($ch);
            //echo "<br><br>output".$output;
            // close curl resource to free up system resources 
            curl_close($ch);      
            $jsonOutput = json_decode($output);
            return $jsonOutput;
        }

        // Constructs a HTTP PATCH request for updating current user entry.
        public static function updateMeEntry($entry){
            //initiaze curl which is used to make the http request
            $ch = curl_init();
            self::AddRequiredHeadersAndSettings($ch);
            // set url
            $feedURL = "https://graph.windows.net/me"."?".Settings::$apiVersion;
            curl_setopt($ch, CURLOPT_URL, $feedURL); 
            // Mark as Patch request
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            $data = json_encode($entry);
            // Set the data for the request
            curl_setopt($ch, CURLOPT_POSTFIELDS,  $data);
            // read the output from the request
            $output = curl_exec($ch); 
            // close curl resource to free up system resources
            curl_close($ch);      
            // decode the response json decoder
            $udpatedEntry = json_decode($output);
            return $udpatedEntry;
        }

        // Add required headers like Authorization, Accept, Content-Type etc.
        public static function AddRequiredHeadersAndSettings($ch)
        {
            //Generate the authentication header
            $authHeader = 'Authorization:' . $_SESSION['token_type'].' '.$_SESSION['access_token'];
            curl_setopt($ch, CURLOPT_HTTPHEADER, array($authHeader, 'Accept:application/json;odata=minimalmetadata',
                                                       'Content-Type:application/json'));           
            // Set the option to recieve the response back as string.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            // By default https does not work for CURL.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }

    }
?>