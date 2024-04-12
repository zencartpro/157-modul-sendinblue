<?php
/**
* Package Brevo
* Copyright 2023-2024 webchills (www.webchills.at)
* Copyright 2003-2024 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @copyright Portions Copyright 2003 osCommerce
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: 1.1.0.php 2024-04-12 19:44:16Z webchills $
*/

$db->Execute("REPLACE INTO ".TABLE_CONFIGURATION_LANGUAGE." (configuration_title, configuration_key, configuration_description, configuration_language_id) VALUES
('Brevo - Verbindung aktivieren?', 'SENDINBLUE_ENABLED', 'Stellen Sie auf true, um die Verbindung mit dem Brevo Newslettersystem zu aktivieren. Wenn aktiviert, bekommen Kunden, die bei der Shopregistrierung die Checkbox Newsletter abonnieren ankreuzen, ein Double Optin Bestätigungsmail und werden nach Anclicken des Bestätigungslinks in Ihre Brevo Newsletterliste eingetragen. Übermittelt werden Email, Vorname und Nachname.', 43),
('Brevo - Newsletter Define Pages', 'SENDINBLUE_DEFINE_PAGES', 'Stellen Sie auf true, um die drei mitgelieferten Define Pages zu aktivieren. Auf der Seite define_newsletter können Sie dann Ihr Brevo Newsletteranmeldeformular einfügen, so dass auch Shopbesucher die Möglichkeit habe, Ihren Newsletter zu abonnieren.', 43),
('Brevo - Newsletter Link in der Sidebox', 'SENDINBLUE_SIDEBOX_INFORMATION', 'Stellen Sie auf true, um in Ihrer Information Sidebox einen Link zu Ihrer Newsletteranmeldeseite zu aktivieren.', 43),
('Brevo - Debug?', 'SENDINBLUE_DEBUG', 'Wenn Sie das Debugging auf true stellen, wird jede Neukundenregistrierung mit Newsletter im Ordner logs im Debug Logfile Brevo.log mit Details zur Brevo CURL Antwort protokolliert. Nützlich für Troubleshooting.', 43),
('Brevo - API Key', 'SENDINBLUE_API_KEY', 'Tragen Sie hier Ihren Brevo API Key ein.', 43),
('Brevo - Attribut für Vorname', 'SENDINBLUE_ATTRIBUTE_FIRSTNAME', 'Geben Sie hier den Attributnamen für den Vornamen ein, wie er in Ihrer Brevo Administration ersichtlich ist. Achten Sie auf exakte Schreibweise und Gross- und Kleinschreibung<br>z.B. VORNAME', 43),
('Brevo - Attribut für Nachname', 'SENDINBLUE_ATTRIBUTE_LASTNAME', 'Geben Sie hier den Attributnamen für den Nachnamen ein, wie er in Ihrer Brevo Administration ersichtlich ist. Achten Sie auf exakte Schreibweise und Gross- und Kleinschreibung<br>z.B. NACHNAME', 43),
('Brevo - ID der Liste', 'SENDINBLUE_LIST_ID', 'Geben Sie hier die ID Ihrer Brevo Liste ein, in die die Newsletterabonnenten eingetragen werden sollen.', 43),
('Brevo - ID des Templates', 'SENDINBLUE_TEMPLATE_ID', 'Geben Sie hier die ID Ihres Brevo Standard Templates für Double-Optin Bestätigungen ein.', 43),
('Brevo - URL zur Bestätigungsseite', 'SENDINBLUE_REDIRECTION_URL', 'Geben Sie die URL zu der Seite ein, zu der der User geleitet werden soll, nachdem er den Bestätigungslink im Double-Optin Email angeclickt hat. Verwenden Sie hier am besten die von diesem Modul mitgelieferte define page index.php?main_page=newsletter_success', 43)");
$messageStack->add('Brevo Konfiguration erfolgreich aktualisiert.', 'success');  