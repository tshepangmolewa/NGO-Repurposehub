<?php
// Load the Twilio SDK
 require 'C:\xampp\htdocs\Second attempt\twilio-sdk\twilio-php-8.3.7\src\Twilio\autoload.php';
 use Twilio\Rest\Client;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Twilio credentials
    $sid = 'AC4df933f6a3748ad35560b9f09f50074d';
    $token = '24426273c2aac2004e880a0e89e2a046';
    $twilioNumber = '+16162025438';

    // Initialize Twilio client
    $client = new Client($sid, $token);

    // Get the user's phone number from the form
    $userPhoneNumber = '+27' . $_POST['number'];

    // Send SMS function
    function sendRegistrationSMS($userPhoneNumber) {
        global $client, $twilioNumber;
        try {
            $message = $client->messages->create(
                $userPhoneNumber,
                [
                    'from' => $twilioNumber,
                    'body' => 'Thank you for registering with us! We’re excited to have you on board.'
                ]
            );
            return $message->sid;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Send SMS after registration
    sendRegistrationSMS($userPhoneNumber);
} 

?> 