<?php
/**
* Package Sendinblue
* Copyright 2023 webchills (www.webchills.at)
* Copyright 2003-2023 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @copyright Portions Copyright 2003 osCommerce
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: header_php.php 2023-01-29 13:47:16Z webchills $
*/

$_SESSION['navigation']->remove_current_page();
require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
// include template specific file name defines
$define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', FILENAME_DEFINE_NEWSLETTER_CONFIRM, 'false');
$breadcrumb->add(NAVBAR_TITLE);