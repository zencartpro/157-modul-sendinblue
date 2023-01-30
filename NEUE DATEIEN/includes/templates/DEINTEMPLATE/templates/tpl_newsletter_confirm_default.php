<?php
/**
 * Page Template
 *
 * Package Sendinblue
 * Copyright 2023 webchills (www.webchills.at)
 * @copyright Copyright 2003-2023 Zen Cart Development Team
 * Zen Cart German Version - www.zen-cart-pro.at
 * @copyright Portions Copyright 2003 osCommerce
 * @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
 * @version $Id: tpl_newsletter_confirm_default.php 2023-01-29 06:17:16Z webchills $
 */



?>

<div class="centerColumn" id="newsletterConfirm">
<h1 id="newsletterConfirmHeading"><?php echo HEADING_TITLE; ?></h1>
<?php if (defined('SENDINBLUE_DEFINE_PAGES') && SENDINBLUE_DEFINE_PAGES === 'true') { ?>
<div id="newsletterConfirmContent" class='content'>
<?php
/**
* require the html_define for the newsletter_confirm page
*/
require($define_page);
?>
</div>
<?php } ?>

</div>

