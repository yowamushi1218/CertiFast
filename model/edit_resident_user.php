<?php 
	include '../server/db_connection.php';

	if(!isset($_SESSION['fullname'])){
		if (isset($_SERVER["HTTP_REFERER"])) {
			header("Location: " . $_SERVER["HTTP_REFERER"]);
		}
	}
	
    $id 		= $conn->real_escape_string($_POST['id']);
	$national_id= $conn->real_escape_string($_POST['national']);
	$fname 		= $conn->real_escape_string($_POST['fname']);
	$mname 		= $conn->real_escape_string($_POST['mname']);
    $lname 		= $conn->real_escape_string($_POST['lname']);
	$address 	= $conn->real_escape_string($_POST['address']);
    $bplace 	= $conn->real_escape_string($_POST['bplace']);
	$bdate 		= $conn->real_escape_string($_POST['bdate']);
    $age 		= $conn->real_escape_string($_POST['age']);
    $cstatus 	= $conn->real_escape_string($_POST['cstatus']);
	$gender 	= $conn->real_escape_string($_POST['gender']);
    $purok 		= $conn->real_escape_string($_POST['purok']);
	$vstatus 	= $conn->real_escape_string($_POST['vstatus']);
    $taxno		= $conn->real_escape_string($_POST['taxno']);
    $email 	    = $conn->real_escape_string($_POST['email']);
	$number 	= $conn->real_escape_string($_POST['number']);
    $remarks 	= $conn->real_escape_string($_POST['remarks']);
	$occu 		= $conn->real_escape_string($_POST['occupation']);
    $citi 		= $conn->real_escape_string($_POST['citizenship']);
	$deceased 	= $conn->real_escape_string($_POST['deceased']);
    $purpose 	= $conn->real_escape_string($_POST['purpose']);
	$profile 	= $conn->real_escape_string($_POST['profileimg']); 
	$profile2 	= $_FILES['img']['name'];

	$newName = date('dmYHis').str_replace(" ", "", $profile2);

  	$target = "../assets/uploads/resident_profile/".basename($newName);
	$check = "SELECT id FROM tblresident WHERE national_id='$national_id'";
	$nat = $conn->query($check)->fetch_assoc();	
	if($nat['id'] == $id || count($nat) <= 0){
		if(!empty($id)){

			if(!empty($profile) && !empty($profile2)){

				$query = "UPDATE tblresident SET national_id='$national_id',citizenship='$citi',`picture`='$profile', `firstname`='$fname', `middlename`='$mname', `lastname`='$lname', `address`='$address', `birthplace`='$bplace', `birthdate`='$bdate', 
						age=$age, `civilstatus`='$cstatus', `gender`='$gender', `purok`='$purok', `voterstatus`='$vstatus', `taxno`='$taxno', `phone`='$number', `email`='$email',`occupation`='$occu', `remarks`='$remarks',
						`resident_type`='$deceased', `purpose`='$purpose'
						WHERE id=$id;";

				if($conn->query($query) === true){

					$_SESSION['message'] = 'Resident Information has been updated!';
					$_SESSION['success'] = 'success';
				}
			}else if(!empty($profile) && empty($profile2)){

				$query = "UPDATE tblresident SET national_id='$national_id',citizenship='$citi',`picture`='$profile', `firstname`='$fname', `middlename`='$mname', `lastname`='$lname', `address`='$address', `birthplace`='$bplace', `birthdate`='$bdate', 
						age=$age, `civilstatus`='$cstatus', `gender`='$gender', `purok`='$purok', `voterstatus`='$vstatus', `taxno`='$taxno', `phone`='$number', `email`='$email',`occupation`='$occu', `remarks`='$remarks',
						`resident_type`='$deceased', `purpose`='$purpose'
						WHERE id=$id;";
				
				if($conn->query($query) === true){

					$_SESSION['message'] = 'Resident Information has been updated!';
					$_SESSION['success'] = 'success';
				}

			}else if(empty($profile) && !empty($profile2)){

				$query = "UPDATE tblresident SET national_id='$national_id',citizenship='$citi',`picture`='$newName', `firstname`='$fname', `middlename`='$mname', `lastname`='$lname', `address`='$address', `birthplace`='$bplace', `birthdate`='$bdate', 
							age=$age, `civilstatus`='$cstatus', `gender`='$gender', `purok`='$purok', `voterstatus`='$vstatus', `taxno`='$taxno', `phone`='$number', `email`='$email',`occupation`='$occu', `remarks`='$remarks',
							`resident_type`='$deceased', `purpose`='$purpose'
							WHERE id=$id;";

				if($conn->query($query) === true){

					$_SESSION['message'] = 'Resident Information has been updated!!';
					$_SESSION['success'] = 'success';

					if(move_uploaded_file($_FILES['img']['tmp_name'], $target)){

						$_SESSION['message'] = 'Resident Information has been updated!!';
						$_SESSION['success'] = 'success';
					}
				}

			}else{
				$query = "UPDATE tblresident SET national_id='$national_id',citizenship='$citi',`firstname`='$fname', `middlename`='$mname', `lastname`='$lname', `address`='$address', `birthplace`='$bplace', `birthdate`='$bdate', 
							age=$age, `civilstatus`='$cstatus', `gender`='$gender', `purok`='$purok', `voterstatus`='$vstatus', `taxno`='$taxno', `phone`='$number', `email`='$email',`occupation`='$occu', `remarks`='$remarks',
							`resident_type`='$deceased', `purpose`='$purpose'
							WHERE id=$id;";
				
				if($conn->query($query) === true){

					$_SESSION['message'] = 'Resident Information has been updated!';
					$_SESSION['success'] = 'success';
				}
			}

		}else{

			$_SESSION['message'] = 'Please complete the form!';
			$_SESSION['success'] = 'danger';
		}
	}else{
		$_SESSION['message'] = 'ID is already taken. Please enter a unique ID!';
		$_SESSION['success'] = 'danger';
	}
    header("Location: ../resident_profiling.php");

	$conn->close();