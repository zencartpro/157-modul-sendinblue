<?php
/**
 * @package Bestellen ohne Kundenkonto (COWOA)
 * Zen Cart German Specific
 * @copyright Copyright 2003-2023 Zen Cart Development Team
 * Zen Cart German Version - www.zen-cart-pro.at
 * @copyright Portions Copyright 2003 osCommerce
 * @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
 * @version $Id: no_account.php for Brevo 2024-04-12 20:00:16Z webchills $
 */
// This should be first line of the script:
$zco_notifier->notify('NOTIFY_MODULE_START_NO_ACCOUNT');

if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
/**
 * Set some defaults
 */
  $process = false;
  $zone_name = '';
  $entry_state_has_zones = '';
  $error_state_input = false;
  $state = '';
  $zone_id = 0;
  $error = false;
  $email_format = (ACCOUNT_EMAIL_PREFERENCE == '1' ? 'HTML' : 'TEXT');
  $newsletter = (ACCOUNT_NEWSLETTER_STATUS == '1' || ACCOUNT_NEWSLETTER_STATUS == '0' ? false : true);
  $antiSpamFieldName = isset($_SESSION['antispam_fieldname']) ? $_SESSION['antispam_fieldname'] : 'should_be_empty';
/**
 * Process form contents
 */
if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
  $process = true;
  $antiSpam = !empty($_POST[$antiSpamFieldName]) ? 'spam' : '';
  if (!empty($_POST['firstname']) && preg_match('~https?://?~', $_POST['firstname'])) $antiSpam = 'spam';
  if (!empty($_POST['lastname']) && preg_match('~https?://?~', $_POST['lastname'])) $antiSpam = 'spam';

  $zco_notifier->notify('NOTIFY_NO_ACCOUNT_CAPTCHA_CHECK');
  
     if (ACCOUNT_GENDER == 'true') {
    if (isset($_POST['gender'])) {
      $gender = zen_db_prepare_input($_POST['gender']);
    } else {
      $gender = false;
    }
  }


  if (ACCOUNT_COMPANY == 'true') $company = zen_db_prepare_input($_POST['company']);
  $firstname = zen_db_prepare_input(zen_sanitize_string($_POST['firstname']));
  $lastname = zen_db_prepare_input(zen_sanitize_string($_POST['lastname']));
  $nick = (isset($_POST['nick']) ? zen_db_prepare_input($_POST['nick']) : '');
  if (ACCOUNT_DOB == 'true') $dob = (isset($_POST['dob']) ? zen_db_prepare_input($_POST['dob']) : ''); 
 
  
  $street_address = zen_db_prepare_input($_POST['street_address']);
  if (ACCOUNT_SUBURB == 'true') $suburb = zen_db_prepare_input($_POST['suburb']);
  $postcode = zen_db_prepare_input($_POST['postcode']);
  $city = zen_db_prepare_input($_POST['city']);
  if (ACCOUNT_STATE == 'true') {
    $state = zen_db_prepare_input(isset($_POST['state']) ? $_POST['state'] : '');
    if (isset($_POST['zone_id'])) {
      $zone_id = zen_db_prepare_input($_POST['zone_id']);
    } else {
      $zone_id = false;
    }
  }
  $country = zen_db_prepare_input($_POST['zone_country_id']);
  $telephone = zen_db_prepare_input($_POST['telephone']);
  if (ACCOUNT_FAX_NUMBER == 'true') $fax = zen_db_prepare_input($_POST['fax']);
  $email_format = zen_db_prepare_input($_POST['email_format']);
  $customers_authorization = (int)CUSTOMERS_APPROVAL_AUTHORIZATION;
  $customers_referral = (isset($_POST['customers_referral']) ? zen_db_prepare_input($_POST['customers_referral']) : '');

  if (ACCOUNT_NEWSLETTER_STATUS == '1' || ACCOUNT_NEWSLETTER_STATUS == '2') {
    $newsletter = 0;
  if (isset($_POST['newsletter'])) {
    $newsletter = zen_db_prepare_input($_POST['newsletter']);
    }
  }


  if (DISPLAY_PRIVACY_CONDITIONS == 'true') {
    if (!isset($_POST['privacy_conditions']) || ($_POST['privacy_conditions'] != '1')) {
      $error = true;
      $messageStack->add('no_account', ERROR_PRIVACY_STATEMENT_NOT_ACCEPTED, 'error');
    }
  }

  if (ACCOUNT_GENDER == 'true') {
    if ( ($gender != 'm') && ($gender != 'f') && ($gender != 'd') ) {
      $error = true;
      $messageStack->add('no_account', ENTRY_GENDER_ERROR);
    }
  }

  if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;
    $messageStack->add('no_account', ENTRY_FIRST_NAME_ERROR);
  }

  if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;
    $messageStack->add('no_account', ENTRY_LAST_NAME_ERROR);
  }


  if (ACCOUNT_COMPANY == 'true') {
    if ((int)ENTRY_COMPANY_MIN_LENGTH > 0 && strlen($company) < ENTRY_COMPANY_MIN_LENGTH) {
      $error = true;
      $messageStack->add('no_account', ENTRY_COMPANY_ERROR);
    }
  }

  if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
    $error = true;
    $messageStack->add('no_account', ENTRY_STREET_ADDRESS_ERROR);
  }

  if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
    $error = true;
    $messageStack->add('no_account', ENTRY_CITY_ERROR);
  }

  if (ACCOUNT_STATE == 'true') {
    $check_query = "SELECT count(*) AS total
                    FROM " . TABLE_ZONES . "
                    WHERE zone_country_id = :zoneCountryID";
    $check_query = $db->bindVars($check_query, ':zoneCountryID', $country, 'integer');
    $check = $db->Execute($check_query);
    $entry_state_has_zones = ($check->fields['total'] > 0);
    if ($entry_state_has_zones == true) {
      $zone_query = "SELECT distinct zone_id, zone_name, zone_code
                     FROM " . TABLE_ZONES . "
                     WHERE zone_country_id = :zoneCountryID
                     AND " . 
                     ((trim($state) != '' && $zone_id == 0) ? "(upper(zone_name) like ':zoneState%' OR upper(zone_code) like '%:zoneState%') OR " : "") .
                    "zone_id = :zoneID
                     ORDER BY zone_code ASC, zone_name";

      $zone_query = $db->bindVars($zone_query, ':zoneCountryID', $country, 'integer');
      $zone_query = $db->bindVars($zone_query, ':zoneState', strtoupper($state), 'noquotestring');
      $zone_query = $db->bindVars($zone_query, ':zoneID', $zone_id, 'integer');
      $zone = $db->Execute($zone_query);

      //look for an exact match on zone ISO code
      $found_exact_iso_match = ($zone->RecordCount() == 1);
      if ($zone->RecordCount() > 1) {
        while (!$zone->EOF && !$found_exact_iso_match) {
          if (strtoupper($zone->fields['zone_code']) == strtoupper($state) ) {
            $found_exact_iso_match = true;
            continue;
          }
          $zone->MoveNext();
        }
      }

      if ($found_exact_iso_match) {
        $zone_id = $zone->fields['zone_id'];
        $zone_name = $zone->fields['zone_name'];
      } else {
        $error = true;
        $error_state_input = true;
        $messageStack->add('no_account', ENTRY_STATE_ERROR_SELECT);
      }
    } else {
      if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
        $error = true;
        $error_state_input = true;
        $messageStack->add('no_account', ENTRY_STATE_ERROR);
      }
    }
  }

  if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
    $error = true;
    $messageStack->add('no_account', ENTRY_POST_CODE_ERROR);
  }

  if ( (is_numeric($country) == false) || ($country < 1) ) {
    $error = true;
    $messageStack->add('no_account', ENTRY_COUNTRY_ERROR);
  }

  if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;
    $messageStack->add('no_account', ENTRY_TELEPHONE_NUMBER_ERROR);
  }

 
 
  $email_address = zen_db_prepare_input($_POST['email_address']);
  
  if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;
    $messageStack->add('no_account', ENTRY_EMAIL_ADDRESS_ERROR);
  } elseif (zen_validate_email($email_address) == false) {
    $error = true;
    $messageStack->add('no_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
  } else {
    $check_email_query = "select count(*) as total
                            from " . TABLE_CUSTOMERS . "
                            where customers_email_address = '" . zen_db_input($email_address) . "'
                            and COWOA_account != 1";
    $check_email = $db->Execute($check_email_query);

    if ($check_email->fields['total'] > 0) {
      $error = true;
      $messageStack->add('no_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
    }
  }
  

 
  $password=zen_create_random_value(15, 'mixed');

  if ($error == true) {
    // hook notifier class
    $zco_notifier->notify('NOTIFY_FAILURE_DURING_NO_ACCOUNT');
  } else {

    $_SESSION['COWOA'] = true;

    $sql_data_array = array('customers_firstname' => $firstname,
                            'customers_lastname' => $lastname,
                            'customers_email_address' => $email_address,
                            'customers_nick' => $nick,
                            'customers_telephone' => $telephone,
                            
                            'customers_newsletter' => (int)$newsletter,
                            'customers_email_format' => $email_format,
                            'customers_default_address_id' => 0,
                            'customers_password' => zen_encrypt_password($password),
                            'COWOA_account' => 1,
                            'customers_authorization' => (int)CUSTOMERS_APPROVAL_AUTHORIZATION
    );
    // Begin check if COWOA account exists for email_address adapted from FEC by Numinix
    $cowoa_accounts = 0;
    
      $cowoa_account = $db->Execute("SELECT customers_id, customers_default_address_id FROM " . TABLE_CUSTOMERS . " 
                                     WHERE customers_email_address = '" . $email_address . "'
                                     ORDER BY customers_id DESC
                                     LIMIT 1;");
      $cowoa_accounts = $cowoa_account->RecordCount();
    if ($cowoa_accounts > 0) {
      // cowoa account exists, use that don't create new
      
      // update last login and number of logins for existing cowoa account
        $existing = "UPDATE " . TABLE_CUSTOMERS_INFO . "
              SET customers_info_date_of_last_logon = now(),
                  customers_info_number_of_logons = IF(customers_info_number_of_logons, customers_info_number_of_logons+1, 1)
              WHERE customers_info_id = :cowoaID";

        $existing = $db->bindVars($existing, ':cowoaID',  $cowoa_account->fields['customers_id'], 'integer');
        $db->Execute($existing);
      
            
      $db_action = 'update';
      $sql_data_array['customers_id'] = $_SESSION['customer_id'] = $cowoa_account->fields['customers_id'];
      $sql_data_array['customers_default_address_id'] = $address_id = $cowoa_account->fields['customers_default_address_id'];
      $sql_data_array['COWOA_account'] = 1;
      $db_customers_where = 'customers_id = "' . $cowoa_account->fields['customers_id'] . '"'; 
      
    } else {  
    //don't exist, create new COWOA
      $db_action = 'insert';
      $db_customers_where = '';
     
    }
    //end modified

    if ((CUSTOMERS_REFERRAL_STATUS == '2' and $customers_referral != '')) $sql_data_array['customers_referral'] = $customers_referral;
    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
    if (ACCOUNT_FAX_NUMBER == 'true') $sql_data_array['customers_fax'] = $fax;
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = (empty($_POST['dob']) || $dob_entered == '0001-01-01 00:00:00' ? zen_db_prepare_input('0001-01-01 00:00:00') : zen_date_raw($_POST['dob']));

      zen_db_perform(TABLE_CUSTOMERS, $sql_data_array, $db_action, $db_customers_where); //added for modified cowoa
      
          if ($db_action == 'insert') {
      $_SESSION['customer_id'] = $db->Insert_ID();
    }

    $zco_notifier->notify('NOTIFY_MODULE_NO_ACCOUNT_ADDED_CUSTOMER_RECORD', array_merge(array('customer_id' => $_SESSION['customer_id']), $sql_data_array));
    $sql_data_array = array('customers_id' => $_SESSION['customer_id'],
                            'entry_firstname' => $firstname,
                            'entry_lastname' => $lastname,
                            'entry_street_address' => $street_address,
                            'entry_postcode' => $postcode,
                            'entry_city' => $city,
                            'entry_country_id' => $country);

    if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
    if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
    if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
    if (ACCOUNT_STATE == 'true') {
      if ($zone_id > 0) {
        $sql_data_array['entry_zone_id'] = $zone_id;
        $sql_data_array['entry_state'] = '';
      } else {
        $sql_data_array['entry_zone_id'] = '0';
        $sql_data_array['entry_state'] = $state;
      }
    }
    if ($db_action == 'update') {
      $sql_data_array['address_book_id'] = $address_id;
      $db_address_table_where = 'address_book_id = ' . $address_id; 
    } else {
      $db_address_table_where = '';
    }

    zen_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, $db_action, $db_address_table_where);

    if ($db_action == 'insert') { 
    $address_id = $db->Insert_ID();
    $zco_notifier->notify('NOTIFY_MODULE_NO_ACCOUNT_ADDED_ADDRESS_BOOK_RECORD', array_merge(array('address_id' => $address_id), $sql_data_array));
    $sql = "update " . TABLE_CUSTOMERS . "
              set customers_default_address_id = '" . (int)$address_id . "'
              where customers_id = '" . (int)$_SESSION['customer_id'] . "'";

    $db->Execute($sql);

    $sql = "insert into " . TABLE_CUSTOMERS_INFO . "
                          (customers_info_id, customers_info_number_of_logons,
                           customers_info_date_account_created, customers_info_date_of_last_logon)
              values ('" . (int)$_SESSION['customer_id'] . "', '1', now(), now())";

    $db->Execute($sql);
  }
    // End check if COWOA account exists for email_address

    if (SESSION_RECREATE == 'True') {
      zen_session_recreate();
    }

    $_SESSION['customer_first_name'] = $firstname;
    $_SESSION['customer_last_name'] = $lastname;
    $_SESSION['customer_default_address_id'] = $address_id;
    $_SESSION['customer_country_id'] = $country;
    $_SESSION['customer_zone_id'] = $zone_id;
    $_SESSION['customers_authorization'] = $customers_authorization;

    // restore cart contents
    $_SESSION['cart']->restore_contents();

    // hook notifier class
    $zco_notifier->notify('NOTIFY_LOGIN_SUCCESS_VIA_NO_ACCOUNT');
    
   // BOF submit data to brevo
if (defined('SENDINBLUE_ENABLED') && SENDINBLUE_ENABLED === 'true') {
if (isset($_POST['newsletter'])) {
    $sendinblue_email = $email_address;
    $sendinblue_firstname = $firstname;
    $sendinblue_lastname = $lastname; 
    $sendinblue_list_id = (int)SENDINBLUE_LIST_ID;
    $sendinblue_template_id = (int)SENDINBLUE_TEMPLATE_ID;
    $sendinblue_redirection_url = SENDINBLUE_REDIRECTION_URL;
    add_contact_to_sendinblue($sendinblue_email,$sendinblue_firstname,$sendinblue_lastname,$sendinblue_list_id,$sendinblue_template_id,$sendinblue_redirection_url);       
  }
}
 //  EOF submit data to brevo

    if ($_SESSION['cart']->count_contents() > 0)
      zen_redirect(zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    else
      zen_redirect(zen_href_link(FILENAME_SHOPPING_CART)); 

  } //endif !error
}


/*
 * Set flags for template use:
 */
  $selected_country = (isset($_POST['zone_country_id']) && $_POST['zone_country_id'] != '') ? $country : SHOW_CREATE_ACCOUNT_DEFAULT_COUNTRY;
  $flag_show_pulldown_states = ((($process == true || $entry_state_has_zones == true) && $zone_name == '') || ACCOUNT_STATE_DRAW_INITIAL_DROPDOWN == 'true' || $error_state_input) ? true : false;
  $state = ($flag_show_pulldown_states) ? ($state == '' ? '&nbsp;' : $state) : $zone_name;
  $state_field_label = ($flag_show_pulldown_states) ? '' : ENTRY_STATE;

  if (!isset($email_format)) $email_format = (ACCOUNT_EMAIL_PREFERENCE == '1' ? 'HTML' : 'TEXT');
  if (!isset($newsletter))   $newsletter = (ACCOUNT_NEWSLETTER_STATUS == '1' ? false : true);

// This should be last line of the script:
$zco_notifier->notify('NOTIFY_MODULE_END_NO_ACCOUNT');