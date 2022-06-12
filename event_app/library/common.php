<?php

require_once 'config.php';
require_once 'database.php';

function checkRequiredPost($requiredField) {
	$numRequired = count($requiredField);
	$keys        = array_keys($_POST);
	
	$allFieldExist  = true;
	for ($i = 0; $i < $numRequired && $allFieldExist; $i++) {
		if (!in_array($requiredField[$i], $keys) || $_POST[$requiredField[$i]] == '') {
			$allFieldExist = false;
		}
	}
	
	return $allFieldExist;
}

function getShopConfig()
{
	$sql = "SELECT sc_name, sc_address, sc_phone, sc_email, sc_shipping_cost, sc_order_email, cy_symbol 
			FROM tbl_shop_config sc, tbl_currency cy
			WHERE sc_currency = cy_id";
	$result = dbQuery($sql);
	$row    = dbFetchAssoc($result);

    if ($row) {
        extract($row);
	
        $shopConfig = array('name'           => $sc_name,
                            'address'        => $sc_address,
                            'phone'          => $sc_phone,
                            'email'          => $sc_email,
				    		'sendOrderEmail' => $sc_order_email,
                            'shippingCost'   => $sc_shipping_cost,
                            'currency'       => $cy_symbol);
    } else {
        $shopConfig = array('name'           => '',
                            'address'        => '',
                            'phone'          => '',
                            'email'          => '',
				    		'sendOrderEmail' => '',
                            'shippingCost'   => '',
                            'currency'       => '');    
    }

	return $shopConfig;						
}

function displayAmount($amount)
{
	global $shopConfig;
	return $shopConfig['currency'] . number_format($amount);
}


function queryString()
{
	$qString = array();
	
	foreach($_GET as $key => $value) {
		if (trim($value) != '') {
			$qString[] = $key. '=' . trim($value);
		} else {
			$qString[] = $key;
		}
	}
	
	$qString = implode('&', $qString);
	
	return $qString;
}

function setError($errorMessage)
{
	if (!isset($_SESSION['plaincart_error'])) {
		$_SESSION['plaincart_error'] = array();
	}
	
	$_SESSION['plaincart_error'][] = $errorMessage;

}

function displayError()
{
	if (isset($_SESSION['plaincart_error']) && count($_SESSION['plaincart_error'])) {
		$numError = count($_SESSION['plaincart_error']);
		
		echo '<table id="errorMessage" width="550" align="center" cellpadding="20" cellspacing="0"><tr><td>';
		for ($i = 0; $i < $numError; $i++) {
			echo '&#8226; ' . $_SESSION['plaincart_error'][$i] . "<br>\r\n";
		}
		echo '</td></tr></table>';
		
		$_SESSION['plaincart_error'] = array();
	}
}

function getPagingQuery($sql, $itemPerPage = 10)
{
	if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
		$page = (int)$_GET['page'];
	} else {
		$page = 1;
	}
    $offset = ($page - 1) * $itemPerPage;
	return $sql . " LIMIT $offset, $itemPerPage";
}


function getPagingLink($sql, $itemPerPage = 10, $strGet = '')
{
	$result        = dbQuery($sql);
	$pagingLink    = '';
	$totalResults  = dbNumRows($result);
	$totalPages    = ceil($totalResults / $itemPerPage);
	$numLinks      = 10;
	if ($totalPages > 1) {
		$self = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ;
		if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
			$pageNumber = (int)$_GET['page'];
		} else {
			$pageNumber = 1;
		}
		if ($pageNumber > 1) {
			$page = $pageNumber - 1;
			if ($page > 1) {
				$prev = " <a href=\"$self?page=$page&$strGet/\">[Prev]</a> ";
			} else {
				$prev = " <a href=\"$self?$strGet\">[Prev]</a> ";
			}
			$first = " <a href=\"$self?$strGet\" class=\"pagBox\" >[First]</a> ";
		} else {
			$prev  = '';
			$first = '';
		}
		if ($pageNumber < $totalPages) {
			$page = $pageNumber + 1;
			$next = " <a href=\"$self?page=$page&$strGet\" class=\"pagBox\">[Next]</a> ";
			$last = " <a href=\"$self?page=$totalPages&$strGet\" class=\"pagBox\">[Last]</a> ";
		} else {
			$next = '';
			$last = '';
		}
		$start = $pageNumber - ($pageNumber % $numLinks) + 1;
		$end   = $start + $numLinks - 1;
		$end   = min($totalPages, $end);
		$pagingLink = array();
		for($page = $start; $page <= $end; $page++)	{
			if ($page == $pageNumber) {
				$pagingLink[] = " $page ";
			} else {
				if ($page == 1) {
					$pagingLink[] = " <a href=\"$self?$strGet\" class=\"pagBox\">$page</a> ";
				} else {	
					$pagingLink[] = " <a href=\"$self?page=$page&$strGet\" class=\"pagBox\">$page</a> ";
				}	
			}
		}
		$pagingLink = implode(' | ', $pagingLink);
        $pagingLink = $first . $prev . $pagingLink . $next . $last;
	}
	return $pagingLink;
}
?>