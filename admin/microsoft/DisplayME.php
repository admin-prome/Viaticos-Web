<?php
    //Enable the option to display any parsing errors.
    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 0); 
    //Require other files.
    require_once 'GraphServiceAccessHelper.php';
    require_once 'Settings.php';
    require_once 'AuthorizationHelperForGraph.php';  
?>
<HTML>
    <head>
        <title>
        </title>
    </head>

    <BODY>
        <h1>
        </h1>  
        <br/><br/>
        <table border="0">
            <?php
                if ($_SESSION['access_token'] == NULL)
				{
                	header( 'Location:Authorize.php' ) ;
                }
                //display a set of user properties
                $user = GraphServiceAccessHelper::getMeEntry();  
				
//				var_dump($user);
                echo('<tr><td>Display Name:</td>');
                echo('<td>'. $user->{'displayName'}.'</td>');
                echo('</tr><tr><td>User Principal Name:</td>');
                echo('<td>'. $user->{'userPrincipalName'}.'</td>');
                echo('</tr><tr><td>Object ID:</td>');
                echo('<td>'. $user->{'objectId'}.'</td>');
                echo('</tr><tr><td>Immutable ID:</td>');
                echo('<td>'. $user->{'immutableId'}.'</td>');
                echo('</tr><tr><td>Street:</td>');
                echo('<td>'. $user->{'streetAddress'}.'</td>');
                echo('</tr><tr><td>Delivery Location:</td>');
                echo('<td>'. $user->{'physicalDeliveryOfficeName'}.'</td>');
                echo('</tr><tr><td>Usage Location:</td>');
                echo('<td>'. $user->{'usageLocation'}.'</td>');
                echo('</tr><tr><td>City:</td>');
                echo('<td>'. $user->{'city'}.'</td>');
                echo('</tr><tr><td>Country:</td>');
                echo('<td>'. $user->{'country'}.'</td>');
                echo('</tr><tr><td>Department:</td>');
                echo('<td>'. $user->{'department'}.'</td>');
                echo('</tr><tr><td>Job Title:</td>');
                echo('<td>'. $user->{'jobTitle'}.'</td>');
                echo('</tr><tr><td>Mail:</td>');
                echo('<td>'. $user->{'mail'}.'</td>');
                
                // proxyAddresses property is a collection
                echo('</tr><tr><td>Proxy Addresses: </td>');
                if (!is_null($user->{'proxyAddresses'}))
                {
                    foreach ($user->{'proxyAddresses'} as $proxy)
                    { 
                            echo('<td>'. $proxy.'</td>');
                    }
                 }
                
                // otherMails property is a collection
                echo('</tr><tr><td>Other Email Addresses:</td>');                
                if (!is_null($user->{'otherMails'}))
                {
                    foreach($user->{'otherMails'} as $address)
                    {
                         echo('<td>'. $address.'</td>');
                    }
                }

                // assignedLicenses property is a collection
                echo('</tr><tr><td>Licenses </td>');                
                if (!is_null($user->{'assignedLicenses'}))
                {
                    foreach($user->{'assignedLicenses'} as $userLicense)
                    {
                         echo('</tr><tr><td>Sku ID: </td>');
                         echo('<td>'. $userLicense->{'skuId'}.'</td>');
                                               
                         if (!is_null($userLicense->{'disabledPlans'}))
                         {
                             echo('</tr><tr><td>Disabled Plans: </td>');
                             foreach($userLicense->{'disabledPlans'} as $disabledPlans)
                             {
                              echo('<td>'. $disabledPlans.'</td>');
                             }
                         }
                         
                    }
                }

                echo('</tr><tr><td>Mobile:</td>');
                echo('<td>'. $user->{'mobile'}.'</td>');
                echo('</tr><tr><td>Password Policies:</td>');
                echo('<td>'. $user->{'passwordPolicies'}.'</td>');
                echo('</tr><tr><td>Surname:</td>');
                echo('<td>'. $user->{'surname'}.'</td>');
                echo('</tr><tr><td>telephone Number:</td>');
                echo('<td>'. $user->{'telephoneNumber'}.'</td>');
                echo('</tr><tr><td>Account Enabled:</td>');
                echo ('<td>'. $user->{'accountEnabled'}.'</td>'); 
                echo('<tr><td>User Type:</td>');
                echo('<td>'. $user->{'userType'}.'</td></tr>');
                $editLinkValue = "EditUser.php";
                echo('<tr/><tr><td><a href=\''.$editLinkValue.'\'>'. 'Edit User' . '</a></td></tr>');
            ?>
        </table>
    </BODY>
</HTML>