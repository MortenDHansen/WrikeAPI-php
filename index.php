<?php
    define('angus',TRUE); // setting a constant in order to prevent direct access. Low practical - i know...
    require 'api_handler.php';
    $api = new wrikeapi();
    // Look to the api_handler.php - method token_init can help you make the access token.
    // you should be able to recieve the token.
    $access_token = '<the access token you got>';
?>
<!DOCTYPE html>
<!--
 Morten D. Hansen
 2015 morten@visia.dk
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Wrike API</title>
    </head>
    <body>
        <p>running</p>
        <?php
            // check that the API is running - this gets the version
            $version = $api->api_version($access_token);
            $version = json_decode($version);
            echo "<p>The api version you are requesting is: " . $version->data[0]->major . "." . $version->data[0]->minor . "<p>";
            echo "That went well!";
        ?>
    </body>
</html>
