<?php
/**
 * checkout_success header_php.php
 *
 * @package page
 * @copyright Copyright 2003-2015 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Modified in v1.5.5 $
 *
 * Stock by Attributes 1.5.4
 */

// This should be first line of the script:
$zco_notifier->notify('NOTIFY_HEADER_START_SBA_CHECKOUT_SUCCESS');

// if the customer is not logged on, redirect them to the shopping cart page
if (!$_SESSION['customer_id']) {
  zen_redirect(zen_href_link(FILENAME_TIME_OUT));
}

if (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action'] != 'confirm')) {
/*
$notify_string='';
if (isset($_GET['action']) && ($_GET['action'] == 'update')) {
  $notify_string = 'action=notify&';
  $notify = $_POST['notify'];

  if (is_array($notify)) {
    for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
      $notify_string .= 'notify[]=' . $notify[$i] . '&';
    }
    if (strlen($notify_string) > 0) $notify_string = substr($notify_string, 0, -1);
  }
  if ($notify_string == 'action=notify&') {
      zen_redirect(zen_href_link(FILENAME_CHECKOUT_SUCCESS, '', 'SSL'));
  } else {
    zen_redirect(zen_href_link(FILENAME_DEFAULT, $notify_string));
  }
}

require(DIR_WS_MODULES . zen_get_module_directory('require_languages.php'));
$breadcrumb->add(NAVBAR_TITLE_1);
$breadcrumb->add(NAVBAR_TITLE_2);

// find out the last order number generated for this customer account
$orders_query = "SELECT * FROM " . TABLE_ORDERS . "
                 WHERE customers_id = :customersID
                 ORDER BY date_purchased DESC LIMIT 1";
$orders_query = $db->bindVars($orders_query, ':customersID', $_SESSION['customer_id'], 'integer');
$orders = $db->Execute($orders_query);
$orders_id = $orders->fields['orders_id'];

// use order-id generated by the actual order process
// this uses the SESSION orders_id, or if doesn't exist, grabs most recent order # for this cust (needed for paypal-standard and other offsite payment methods).
// Needs reworking in future checkout-rewrite
$zv_orders_id = (isset($_SESSION['order_number_created']) && $_SESSION['order_number_created'] >= 1) ? $_SESSION['order_number_created'] : $orders_id;
$_GET['order_id'] = $orders_id = $zv_orders_id;
$order_summary = $_SESSION['order_summary'];
unset($_SESSION['order_summary']);
unset($_SESSION['order_number_created']);

$additional_payment_messages = '';
if (isset($_SESSION['payment_method_messages']) && $_SESSION['payment_method_messages'] != '') {
  $additional_payment_messages = $_SESSION['payment_method_messages'];
  unset($_SESSION['payment_method_messages']);
}

$statuses_query = "SELECT os.orders_status_name, osh.date_added, osh.comments
                   FROM   " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh
                   WHERE      osh.orders_id = :ordersID
                   AND        osh.orders_status_id = os.orders_status_id
                   AND        os.language_id = :languagesID
                   AND        osh.customer_notified >= 0
                   ORDER BY   osh.date_added";
$statuses_query = $db->bindVars($statuses_query, ':ordersID', $orders_id, 'integer');
$statuses_query = $db->bindVars($statuses_query, ':languagesID', $_SESSION['languages_id'], 'integer');
$statuses = $db->Execute($statuses_query);
foreach ($statuses as $status) {
  $statusArray[] = array('date_added'=>$status['date_added'],
                         'orders_status_name'=>$status['orders_status_name'],
                         'comments'=>$status['comments']);
}
// get order details
require(DIR_WS_CLASSES . 'order.php');
$order = new order($orders_id);


// prepare list of product-notifications for this customer
$global_query = "SELECT global_product_notifications
                 FROM " . TABLE_CUSTOMERS_INFO . "
                 WHERE customers_info_id = :customersID";

$global_query = $db->bindVars($global_query, ':customersID', $_SESSION['customer_id'], 'integer');
$global = $db->Execute($global_query);
$flag_global_notifications = $global->fields['global_product_notifications'];*/

$notificationsArray = array();
if ($flag_global_notifications != '1') {
  $products_array = array();
  $counter = 0;
  //mc12345678 2014-09-16 modified to prevent duplicate SBA tracked items on checkout_success
  $products_query = "SELECT DISTINCT products_id, products_name
                     FROM " . TABLE_ORDERS_PRODUCTS . "
                     WHERE orders_id = :ordersID
                     ORDER BY products_name";
  $products_query = $db->bindVars($products_query, ':ordersID', $orders_id, 'integer');
  $products = $db->Execute($products_query);

  foreach ($products as $product) {
    $notificationsArray[] = array('counter'=>$counter,
                                  'products_id'=>$product['products_id'],
                                  'products_name'=>$product['products_name']);
    $counter++;
  }
}

}

// This should be last line of the script:
$zco_notifier->notify('NOTIFY_HEADER_END_SBA_CHECKOUT_SUCCESS');
