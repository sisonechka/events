<?php
require_once('mail.php');

function random_string($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return strtoupper($randomString);
}

function checkFDUser()
{
	if (!isset($_SESSION['calendar_fd_user'])) {
		header('Location: ' . WEB_ROOT . 'login.php');
		exit;
	}
	if (isset($_GET['logout'])) {
		doLogout();
	}
}

function doLogin()
{
	$name 	= $_POST['name'];
	$pwd 	= $_POST['pwd'];
    $errorMessage = '';
    $sql 	= "SELECT * FROM tbl_users WHERE name = '$name' AND pwd = '$pwd'";
	$result = dbQuery($sql);
	
	if (dbNumRows($result) == 1) {
		$row = dbFetchAssoc($result);
		$_SESSION['calendar_fd_user'] = $row;
		$_SESSION['calendar_fd_user_name'] = $row['username'];
		header('Location: index.php');
		exit();
	}
	else {
		$errorMessage = 'Invalid username / passsword. Please try again or contact to support.';
	}
	return $errorMessage;
}
function doLogout()
{
	if (isset($_SESSION['calendar_fd_user'])) {
		unset($_SESSION['calendar_fd_user']);
	}
	header('Location: index.php');
	exit();
}
function getBookingRecords(){
	$per_page = 10;
	$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : 1;
	$start 	= ($page-1)*$per_page;
	$sql 	= "SELECT u.id AS uid, u.name, u.phone, u.email,
			   r.ucount, r.rdate, r.status, r.comments   
			   FROM tbl_users u, tbl_reservations r 
			   WHERE u.id = r.uid  
			   ORDER BY r.id DESC LIMIT $start, $per_page";
	$result = dbQuery($sql);
	$records = array();
	while($row = dbFetchAssoc($result)) {
		extract($row);
		$records[] = array("user_id" => $uid,
							"user_name" => $name,
							"user_phone" => $phone,
							"user_email" => $email,
							"count" => $ucount,
							"res_date" => $rdate,
							"status" => $status,
							"comments" => $comments);	
	}
	return $records;
}
function getUserRecords(){
	$per_page = 20;
	$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : 1;
	$start 	= ($page-1)*$per_page;
	
	$type = $_SESSION['calendar_fd_user']['type'];
	if($type == 'student') {
		$id = $_SESSION['calendar_fd_user']['id'];
		$sql = "SELECT  * FROM tbl_users u WHERE type != 'admin' AND id = $id ORDER BY u.id DESC";
	}
	else {
		$sql = "SELECT  * FROM tbl_users u WHERE type != 'admin' ORDER BY u.id DESC LIMIT $start, $per_page";
	}
    $result = dbQuery($sql);
	$records = array();
	while($row = dbFetchAssoc($result)) {
		extract($row);
		$records[] = array("user_id" => $id,
			"user_name" => $name,
			"user_phone" => $phone,
			"user_email" => $email,
			"type" => $type,
			"status" => $status,
			"bdate" => $bdate
		);	
	}
	return $records;
}
function getHolidayRecords() {
	$per_page = 10;
	$page 	= (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : 1;
	$start 	= ($page-1)*$per_page;
	$sql 	= "SELECT * FROM tbl_holidays ORDER BY id DESC LIMIT $start, $per_page";
	$result = dbQuery($sql);
	$records = array();
	while($row = dbFetchAssoc($result)) {
		extract($row);
		$records[] = array("hid" => $id, "hdate" => $date,"hreason" => $reason);	
	}
	return $records;
}
function generateHolidayPagination() {
	$per_page = 10;
	$sql 	= "SELECT * FROM tbl_holidays";
	$result = dbQuery($sql);
	$count 	= dbNumRows($result);
	$pages 	= ceil($count/$per_page);
	$pageno = '<ul class="pagination pagination-sm no-margin pull-right">';
	for($i=1; $i<=$pages; $i++)	{
		$pageno .= "<li><a href=\"?v=HOLY&page=$i\">".$i."</a></li>";
	}
	$pageno .= 	"</ul>";
	return $pageno;
}
function generatePagination(){
	$per_page = 10;
	$sql 	= "SELECT * FROM tbl_users";
	$result = dbQuery($sql);
	$count 	= dbNumRows($result);
	$pages 	= ceil($count/$per_page);
	$pageno = '<ul class="pagination pagination-sm no-margin pull-right">';
	for($i=1; $i<=$pages; $i++)	{
		$pageno .= "<li><a href=\"?v=USER&page=$i\">".$i."</a></li>";
	}
	$pageno .= 	"</ul>";
	return $pageno;
}
?>