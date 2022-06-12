<?php 

require_once 'Booking.php';
require_once '../library/config.php';
require_once '../library/mail.php';

$cmd = isset($_GET['cmd']) ? $_GET['cmd'] : '';

switch($cmd) {
	
	case 'book':
		bookCalendar();
	break;

	case 'calview':
		calendarView();
	break;

	case 'regConfirm':
		regConfirm();
	break;
			
	case 'delete':
		regDelete();
	break;
	
	case 'user':
		userDetails();
	break;
	
	default :
	break;
}

function bookCalendar() {
	$name 		= $_POST['name'];
	$userId		= (int)$_POST['userId'];
	$address 	= $_POST['address'];
	$phone 		= $_POST['phone'];
	$email 		= $_POST['email'];
	$rdate		= $_POST['rdate'];
	$rtime		= $_POST['rtime'];
	$bkdate		= $rdate. ' '. $rtime;
	$ucount		= $_POST['ucount'];
	
	//TODO first check if that date has a holiday
	$hsql	= "SELECT * FROM tbl_holidays WHERE date = '$rdate'";
	$hresult = dbQuery($hsql);
	if (dbNumRows($hresult) > 0) {
		$errorMessage = 'You can not book any event on Holiday. Please try another day.';
		header('Location: ../views/?v=DB&err=' . urlencode($errorMessage));
		exit();
	}

	
	$sql = "INSERT INTO tbl_reservations (uid, ucount, rdate, status, comments, bdate) 
			VALUES ($userId, $ucount, '$bkdate', 'PENDING', '', NOW())";
	dbQuery($sql);
	
	$bodymsg = "User $name booked the date slot on $bkdate. Requesting you to please take further action on user booking.<br/>Mbr/>Tousif Khan";
	$data = array('to' => 'tousifkhan510@gmail.com', 'sub' => 'Booking on $rdate.', 'msg' => $bodymsg);
	header('Location: ../index.php?msg=' . urlencode('User successfully registered.'));
	exit();
}

function regConfirm() {
	$userId		= $_GET['userId'];
	$action 	= $_GET['action'];
	$stat		= ($action == 'approve') ? 'APPROVED' : 'DENIED';
	
	$sql		= "UPDATE tbl_reservations SET status = '$stat' WHERE uid = $userId";
	dbQuery($sql);
	
	$data = array();
	
	header('Location: ../views/?v=DB&msg=' . urlencode('Reservation status successfully changed.'));
	exit();
}

function regDelete() {
	$userId	= $_GET['userId'];
	$sql1	= "DELETE FROM tbl_reservations WHERE uid = $userId";
	dbQuery($sql1);
	$sql2	= "DELETE FROM tbl_users WHERE id = $userId";
	dbQuery($sql2);
	
	header('Location: ../views/?v=LIST&msg=' . urlencode('User record successfully deleted.'));
	exit();
}


function calendarView() {
	$start 	= $_POST['start'];
	$end 	= $_POST['end'];
	$bookings = array();
	$sql	= "SELECT u.name AS u_name, u.id AS user_id, r.rdate, r.status 
			   FROM tbl_users u, tbl_reservations r 
			   WHERE u.id = r.uid  
			   AND (r.rdate BETWEEN '$start' AND '$end')";
	$result = dbQuery($sql);
	while($row = dbFetchAssoc($result)) {
		extract($row);
		$book = new Booking();
		$book->title = $u_name;
		$book->start = $rdate; 
		$bgClr = '#f39c12';
		if($status == 'DENIED') {$bgClr = '#ff0000';}
		else if($status == 'APPROVED') {$bgClr = '#00cc00';}
		$book->backgroundColor = $bgClr;
        $book->borderColor = $bgClr;
		$book->url = WEB_ROOT . 'views/?v=USER&ID='.$user_id;
		$bookings[] = $book; 
	}

	echo json_encode($bookings);
}

function userDetails() {
	$userId	= $_GET['userId'];
	$hsql	= "SELECT * FROM tbl_users WHERE id = $userId";
	$hresult = dbQuery($hsql);
	$user = array();
	while($hrow = dbFetchAssoc($hresult)) {	
		extract($hrow);
		$user['user_id'] = $id;
		$user['address'] = $address;
		$user['phone_no'] = $phone;
		$user['email'] = $email;
	}
	echo json_encode($user);
}


?>