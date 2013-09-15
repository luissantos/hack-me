<?php
/**
 * This file is part of "hack me, a simple vulnerable application"
 *
 * WARNING!! this application has security issues, don't install
 * this on a public server!
 */

define('ITEMS_BY_PAGE',3);

$db = mysql_connect('localhost','username','password');
$ret = mysql_select_db('datax');


