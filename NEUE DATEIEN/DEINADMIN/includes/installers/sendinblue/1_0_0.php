<?php
/**
* Package Sendinblue
* Copyright 2023 webchills (www.webchills.at)
* Copyright 2003-2023 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @copyright Portions Copyright 2003 osCommerce
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: 1.0.0.php 2023-01-29 13:47:16Z webchills $
*/


$db->Execute(" SELECT @gid:=configuration_group_id
FROM ".TABLE_CONFIGURATION_GROUP."
WHERE configuration_group_title= 'Sendinblue'
LIMIT 1;");

$db->Execute("INSERT IGNORE INTO ".TABLE_CONFIGURATION." (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES
('Sendinblue - Enable connection', 'SENDINBLUE_ENABLED', 'false', 'Set to true to enable the Sendinblue newsletter connection. If enabled customers who check the newsletter subscribe checkbox during the creation of a new account get submitted to your Sendinblue newsletter and receive a double optin confirmation email. After clicking the confirmation link they will be subscribed to your Sendinblue newsletter list.', @gid, 1, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Sendinblue - Newsletter Define Pages', 'SENDINBLUE_DEFINE_PAGES', 'false', 'Set to true to enable the three included define pages. On the define_newsletter page you can then insert your Sendinblue newsletter subscription form so that store visitors also have the option to subscribe to your newsletter.', @gid, 2, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Sendinblue - Newsletter Link', 'SENDINBLUE_SIDEBOX_INFORMATION', 'false', 'Set to true to enable a link to your newsletter subscribe page in your information sidebox.', @gid, 3, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Sendinblue - Debug', 'SENDINBLUE_DEBUG', 'false', 'If set to true a debug logfile with details about the Sendinblue CURL response is written to the logs folder for new customer registrations. Useful for troubleshooting', @gid, 4, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),'),
('Sendinblue - API Key', 'SENDINBLUE_API_KEY', 'xkeysib-', 'Enter your Sendinblue API Key', @gid, 5, NOW(), NULL, NULL),
('Sendinblue - Attribute Firstname', 'SENDINBLUE_ATTRIBUTE_FIRSTNAME', 'VORNAME', 'Enter here the attribute name for the first name as it is visible in your Sendinblue administration. Pay attention to exact spelling and upper and lower case<br>e.g. VORNAME', @gid, 6, NOW(), NULL, NULL),
('Sendinblue - Attribut Lastname', 'SENDINBLUE_ATTRIBUTE_LASTNAME', 'NACHNAME', 'Enter here the attribute name for the last name as it is visible in your Sendinblue administration. Pay attention to exact spelling and upper and lower case<br>e.g. NACHNAME', @gid, 7, NOW(), NULL, NULL),
('Sendinblue - List ID', 'SENDINBLUE_LIST_ID', '2', 'Enter the ID of the Sendinblue list in which the newsletter subscribers should be added.', @gid, 8, NOW(), NULL, NULL),
('Sendinblue - Template ID', 'SENDINBLUE_TEMPLATE_ID', '2', 'Enter the ID of your Sendinblue standard template for double opt-in confirmations', @gid, 9, NOW(), NULL, NULL),
('Sendinblue - Redirection URL', 'SENDINBLUE_REDIRECTION_URL', 'https://www.meinshop.de/index.php?main_page=newsletter_success', 'Enter the URL of the site to which the customer should be redirected after clicking the double optin confirmation link in the email. Use the define page index.php?main_page=newsletter_success', @gid, 10, NOW(), NULL, NULL);");

$db->Execute("REPLACE INTO ".TABLE_CONFIGURATION_LANGUAGE." (configuration_title, configuration_key, configuration_description, configuration_language_id) VALUES
('Sendinblue - Verbindung aktivieren?', 'SENDINBLUE_ENABLED', 'Stellen Sie auf true, um die Verbindung mit dem Sendinblue Newslettersystem zu aktivieren. Wenn aktiviert, bekommen Kunden, die bei der Shopregistrierung die Checkbox Newsletter abonnieren ankreuzen, ein Double Optin Bestätigungsmail und werden nach Anclicken des Bestätigungslinks in Ihre Sendinblue Newsletterliste eingetragen. Übermittelt werden Email, Vorname und Nachname.', 43),
('Sendinblue - Newsletter Define Pages', 'SENDINBLUE_DEFINE_PAGES', 'Stellen Sie auf true, um die drei mitgelieferten Define Pages zu aktivieren. Auf der Seite define_newsletter können Sie dann Ihr Sendinblue Newsletteranmeldeformular einfügen, so dass auch Shopbesucher die Möglichkeit habe, Ihren Newsletter zu abonnieren.', 43),
('Sendinblue - Newsletter Link in der Sidebox', 'SENDINBLUE_SIDEBOX_INFORMATION', 'Stellen Sie auf true, um in Ihrer Information Sidebox einen Link zu Ihrer Newsletteranmeldeseite zu aktivieren.', 43),
('Sendinblue - Debug?', 'SENDINBLUE_DEBUG', 'Wenn Sie das Debugging auf true stellen, wird jede Neukundenregistrierung mit Newsletter im Ordner logs im Debug Logfile Sendinblue.log mit Details zur Sendinblue CURL Antwort protokolliert. Nützlich für Troubleshooting.', 43),
('Sendinblue - API Key', 'SENDINBLUE_API_KEY', 'Tragen Sie hier Ihren Sendinblue API Key ein.', 43),
('Sendinblue - Attribut für Vorname', 'SENDINBLUE_ATTRIBUTE_FIRSTNAME', 'Geben Sie hier den Attributnamen für den Vornamen ein, wie er in Ihrer Sendinblue Administration ersichtlich ist. Achten Sie auf exakte Schreibweise und Gross- und Kleinschreibung<br>z.B. VORNAME', 43),
('Sendinblue - Attribut für Nachname', 'SENDINBLUE_ATTRIBUTE_LASTNAME', 'Geben Sie hier den Attributnamen für den Nachnamen ein, wie er in Ihrer Sendinblue Administration ersichtlich ist. Achten Sie auf exakte Schreibweise und Gross- und Kleinschreibung<br>z.B. NACHNAME', 43),
('Sendinblue - ID der Liste', 'SENDINBLUE_LIST_ID', 'Geben Sie hier die ID Ihrer Sendinblue Liste ein, in die die Newsletterabonnenten eingetragen werden sollen.', 43),
('Sendinblue - ID des Templates', 'SENDINBLUE_TEMPLATE_ID', 'Geben Sie hier die ID Ihres Sendinblue Standard Templates für Double-Optin Bestätigungen ein.', 43),
('Sendinblue - URL zur Bestätigungsseite', 'SENDINBLUE_REDIRECTION_URL', 'Geben Sie die URL zu der Seite ein, zu der der User geleitet werden soll, nachdem er den Bestätigungslink im Double-Optin Email angeclickt hat. Verwenden Sie hier am besten die von diesem Modul mitgelieferte define page index.php?main_page=newsletter_success', 43)");

$admin_page = 'configSendinblue';
// delete configuration menu
$db->Execute("DELETE FROM " . TABLE_ADMIN_PAGES . " WHERE page_key = '" . $admin_page . "' LIMIT 1;");
// add configuration menu
if (!zen_page_key_exists($admin_page)) {
$db->Execute(" SELECT @gid:=configuration_group_id
FROM ".TABLE_CONFIGURATION_GROUP."
WHERE configuration_group_title= 'Sendinblue'
LIMIT 1;");

$db->Execute("INSERT IGNORE INTO " . TABLE_ADMIN_PAGES . " (page_key,language_key,main_page,page_params,menu_key,display_on_menu,sort_order) VALUES 
('configSendinblue','BOX_CONFIGURATION_SENDINBLUE','FILENAME_CONFIGURATION',CONCAT('gID=',@gid),'configuration','Y',@gid)");
$messageStack->add('Sendinblue Konfiguration erfolgreich hinzugefügt.', 'success');  
}