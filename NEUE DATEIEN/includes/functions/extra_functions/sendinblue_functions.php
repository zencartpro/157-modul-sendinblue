<?php
/**
* Package Sendinblue
* Copyright 2023 webchills (www.webchills.at)
* Copyright 2003-2023 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @copyright Portions Copyright 2003 osCommerce
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: sendinblue_functions.php 2023-01-30 19:08:16Z webchills $
*/

if (defined('SENDINBLUE_ENABLED') && SENDINBLUE_ENABLED === 'true') {
function add_contact_to_sendinblue($sendinblue_email, $sendinblue_firstname, $sendinblue_lastname, $sendinblue_list_id, $sendinblue_template_id,$sendinblue_redirection_url) {
// define config values
$sendinblue_apikey = SENDINBLUE_API_KEY;
$sendinblue_firstname_variable = SENDINBLUE_ATTRIBUTE_FIRSTNAME;
$sendinblue_lastname_variable = SENDINBLUE_ATTRIBUTE_LASTNAME;
// Request parameters for doubleoptin
$request = array(
'email' => $sendinblue_email,
'attributes' => array(
            $sendinblue_firstname_variable => $sendinblue_firstname,
            $sendinblue_lastname_variable => $sendinblue_lastname,
),
'includeListIds' => [$sendinblue_list_id],
'templateId' => $sendinblue_template_id,         
'redirectionUrl' => "$sendinblue_redirection_url", 
);      

// API call parameters
$curl_options = array(
CURLOPT_URL => 'https://api.sendinblue.com/v3/contacts/doubleOptinConfirmation',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 10,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_POSTFIELDS => json_encode($request),
CURLOPT_HTTPHEADER => array(
'accept: application/json',
'api-key: '.$sendinblue_apikey,
'content-type: application/json',
),
);

// Create a Curl instance.
$ch = curl_init();

// Configure the Curl instance.
curl_setopt_array($ch, $curl_options);

// Make the actual API call.
$response = curl_exec($ch);

// write debug log if enabled
if (defined('SENDINBLUE_DEBUG') && SENDINBLUE_DEBUG === 'true') {
// HTTP response code from the Sendinblue server:
$response_message ='';
$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response_code === 400 ) {
$response_message = 'FEHLER - Pr??fen Sie Ihre Einstellungen unter Konfiguration > Sendinblue';
} else if ($response_code === 401 ) {
$response_message = 'FEHLER - Pr??fen Sie Ihre Einstellungen unter Konfiguration > Sendinblue';
} else if ($response_code === 403 ) {
$response_message = 'FEHLER - Pr??fen Sie Ihre Einstellungen unter Konfiguration > Sendinblue';
} else if ($response_code === 405 ) {
$response_message = 'FEHLER - Pr??fen Sie Ihre Einstellungen unter Konfiguration > Sendinblue';
} else if ($response_code === 201){
	$response_message = 'ERFOLGREICH - Email wurde als neue Email an Sendinblue ??bermittelt und bekommt nun das Double-Optin Best??tigungsmail. Erst nach Anclicken des Best??tigungslinks wird die Email bei Sendinblue eingetragen und ist dann unter Kontakte ersichtlich!';
} else if ($response_code === 204 ) {
	$response_message = 'ERFOLGREICH - Email wurde als bestehende Email an Sendinblue zur Aktualisierung ??bermittelt und bekommt nun das Double-Optin Best??tigungsmail. Erst nach Anclicken des Best??tigungslinks wird die Email bei Sendinblue eingetragen und ist dann unter Kontakte ersichtlich!';
} else {
$response_message = 'FEHLER - keine R??ckmeldung erhalten';	
}

$date = new DateTime();
$date = $date->format("d.m.Y - H:i:s");
        $debugMessage = "Datum/Uhrzeit:" . print_r($date, true) . "\n".
           "\tEmail: " . print_r($sendinblue_email, true) . "\n".
           "\tVorname: " . print_r($sendinblue_firstname, true) . "\n".
           "\tNachname: " . print_r($sendinblue_lastname, true) . "\n".
           "\tListe ID: " . print_r($sendinblue_list_id, true) . "\n".
           "\tTemplate ID: " . print_r($sendinblue_template_id, true) . "\n".
           "\tRedirection URL: " . print_r($sendinblue_redirection_url, true) . "\n".
           "\tStatuscode: " . print_r($response_code, true) . "\n".
           "\tStatus: " . print_r($response_message, true) . "\n".
           "\tZusatzinfo: " . print_r($response, true) . "\n\n";
          $file = DIR_FS_LOGS . '/' . 'Sendinblue.log';
          if ($fp = @fopen($file, 'a')) {
            fwrite($fp, $debugMessage);
            fclose($fp);
          }
}
// Close Curl
curl_close($ch);
}
}