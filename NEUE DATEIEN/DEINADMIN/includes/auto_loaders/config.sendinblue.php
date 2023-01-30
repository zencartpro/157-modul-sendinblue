<?php
/**
* Package Sendinblue
* Copyright 2023 webchills (www.webchills.at)
* Copyright 2003-2023 Zen Cart Development Team
* Zen Cart German Version - www.zen-cart-pro.at
* @copyright Portions Copyright 2003 osCommerce
* @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
* @version $Id: config.sendinblue.php 2023-01-29 13:47:16Z webchills $
*/

if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
} 

$autoLoadConfig[999][] = array(
  'autoType' => 'init_script',
  'loadFile' => 'init_sendinblue.php'
);