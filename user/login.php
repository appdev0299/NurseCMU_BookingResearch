<meta http-equiv="Content-Type" content="text/html; charset=utf8">

<?php session_start();

$client_id = 'kJ4G50AGcH6kegNvpyWjXPERVHpH1mxWjs577Mrk';      
$client_secret = 'gaNYS3AD8xZcyA5GdpYxM3R2kmaYnRxPgMcXbT81';  
$redirect_uri = 'https://app.nurse.cmu.ac.th/booking/user/login.php';

$oauth_scope = "cmuitaccount.basicinfo"; // Scopes with space-delimited
$oauth_auth_url = "https://oauth.cmu.ac.th/v1/Authorize.aspx";
$oauth_token_url = "https://oauth.cmu.ac.th/v1/GetToken.aspx";
$wsapi_get_basicinfo_url = "https://misapi.cmu.ac.th/cmuitaccount/v1/api/cmuitaccount/basicinfo";

if (isset($_GET['error']) != null) {
    echo "error: " . $_GET['error'];
    echo "<br>";
    echo "error_description: " . $_GET['error_description'];
} else {
    if (isset($_GET['code'])) {
        $accessToken = get_oauth_token($_GET['code'], $oauth_token_url);

        //Show Debug Code / Access Token
        //echo "code: " . $_GET['code'] . "<br>";
        //echo "accessToken: " .$accessToken . "<br>";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $wsapi_get_basicinfo_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $accessToken,
                "Cache-Control: no-cache"
            )
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $json = json_decode($response, true);
            // Response model in Json format  
            // ========================================
            //"cmuitaccount_name": "XXXXX",
            //"cmuitaccount": "XXXXX@cmu.ac.th",
            //"student_id": "",
            //"prename_id": "MR",
            //"prename_TH": "นาย",
            //"prename_EN": "Mr.",
            //"firstname_TH": "XXXXX",
            //"firstname_EN": "XXXXX",
            //"lastname_TH": "XXXXX",
            //"lastname_EN": "XXXXX",
            //"organization_code": "53",
            //"organization_name_TH": "สำนักบริการเทคโนโลยีสารสนเทศ",
            //"organization_name_EN": "Information Technology Services Center",
            //"itaccounttype_id": "MISEmpAcc",
            //"itaccounttype_TH": "บุคลากร",
            //"itaccounttype_EN": "MIS Employee"
            // ===========================================
            session_start();
            $_SESSION['login_info'] = $json;
            // Show Result Text
            // echo "Name:" . $json['firstname_EN'] . "<br>";
            // echo "Surname:" . $json['lastname_EN'] . "<br>";
            // echo "organization:" . $json['organization_name_EN'] . "<br>";
            // echo "cmuitaccount:" . $json['cmuitaccount'] . "<br>";
            // echo "itaccounttype_TH:" . $json['itaccounttype_TH'] . "<br>";
            header("Location: index.php");
            exit;
        }
    } else {
        header("Location:" . $oauth_auth_url . "?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri&scope=$oauth_scope");
        exit;
    }
}




// Get access_token to call webservices api
function get_oauth_token($code, $oauth_url)
{
    global $client_id;
    global $client_secret;
    global $redirect_uri;


    $client_post = array(
        "code" => $code,
        "client_id" => $client_id,
        "client_secret" => $client_secret,
        "redirect_uri" => $redirect_uri,
        "grant_type" => "authorization_code"
    );

    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $oauth_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('application/x-www-form-urlencoded'));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $client_post);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);


    $json_response = curl_exec($curl);

    curl_close($curl);

    $authObj = json_decode($json_response);

    if (isset($authObj->refresh_token)) {
        global $refreshToken;
        $refreshToken = $authObj->refresh_token;
    }

    $accessToken = $authObj->access_token;
    return $accessToken;
}


?>