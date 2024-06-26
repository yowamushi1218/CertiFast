<?php
include 'server/server.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["username"];

$sql = "SELECT *, r.id AS id FROM tblpurok_records AS r JOIN tbl_user_admin AS a ON r.purok = a.purok WHERE a.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$resident = array();
while ($row = $result->fetch_assoc()) {
    $resident[] = $row;
}

if (!empty($resident)) {
    $_SESSION['purok'] = $resident[0]['purok'];
}

$query1 = "SELECT * FROM tblpurok";
$result1 = $conn->query($query1); 

$purok = array();
while($row2 = $result1->fetch_assoc()){
    $purok[] = $row2; 
}

$purokrecords = $_SESSION["username"];
$purokquery = "SELECT COUNT(*) AS resident FROM tblpurok_records AS r
          JOIN tbl_user_admin AS a ON r.purok = a.purok
          WHERE a.username = ?";
$stmt = $conn->prepare($purokquery);
$stmt->bind_param("s", $purokrecords);
$stmt->execute();
$purokresult = $stmt->get_result();

$purokrow = $purokresult->fetch_assoc();
$purokcount = $purokrow["resident"];

$voterusername = $_SESSION["username"];
$voterquery = "SELECT COUNT(*) AS voter FROM tblpurok_records AS r
          JOIN tbl_user_admin AS a ON r.purok = a.purok
          WHERE a.username = ? AND r.voterstatus = 'Yes'";
$stmt = $conn->prepare($voterquery);
$stmt->bind_param("s", $voterusername);
$stmt->execute();
$voterresult = $stmt->get_result();

$voterrow = $voterresult->fetch_assoc();
$votercount = $voterrow["voter"];

$purokusername = $_SESSION["username"];
$reqquery = "SELECT COUNT(*) AS id FROM tblresident AS r
          JOIN tbl_user_admin AS a ON r.purok = a.purok
          WHERE a.username = ? AND r.residency_status = 'on hold'";
$stmt = $conn->prepare($reqquery);
$stmt->bind_param("s", $purokusername);
$stmt->execute();
$reqresult = $stmt->get_result();

$reqrow = $reqresult->fetch_assoc();
$reqcount = $reqrow["id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include 'templates/header.php' ?>
	<title>CertiFast Portal</title>
</head>
<body>
<?php include 'templates/loading_screen.php' ?>
	<div class="wrapper">
		<?php include 'templates/main-header.php' ?>
		<?php include 'templates/sidebar.php' ?>
		<div class="main-panel mt-2">
			<div class="content">
				<div class="panel-header">
                    <h1 class="text-center fw-bold mt-3" style="font-size: 300%;">Barangay Los Amigos - CertiFast Portal</h1>
                    <h3 class="text-center mt-2" style="font-size: 150%;">The records of Purok <?php echo isset($_SESSION['purok']) ? ucwords($_SESSION['purok']) : ''; ?> that have been confirmed are mentioned below.</h3>
                    <div class="page-inner mt-4">
                        <div class="row">
                            <div class="page-inner mt-2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card card-stats card card-round">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="icon-big text-center">
                                                            <i class="fas fa-user fa-2x" style="color: gray;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-2 col-stats">
                                                    </div>
                                                    <div class="col-2 col-stats">
                                                        <div class="numbers mt-2">
                                                            <h2 class="text-uppercase" style="font-size: 16px;">Resident</h2>
                                                            <h3 class="fw-bold text-uppercase" style="font-size: 30px; color: #C77C8D;"><?= number_format($purokcount) ?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="text-uppercase" style="font-size: 14px;">Total Purok Resident</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card card-stats card card-round">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="icon-big text-center">
                                                            <i class="fas fa-user-check fa-2x" style="color: gray;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-2 col-stats">
                                                    </div>
                                                    <div class="col-2 col-stats">
                                                        <div class="numbers mt-2">
                                                            <h2 class="text-uppercase" style="font-size: 16px;">Voters</h2>
                                                            <h3 class="fw-bold" style="font-size: 30px; color: #C77C8D;"><?= number_format($votercount) ?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <h5 class="text-uppercase" style="font-size: 14px;">Total Purok Voters</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card card-stats card card-round">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="icon-big text-center">
                                                            <i class="fas fa-user-times fa-2x" style="color: gray;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-2 col-stats">
                                                    </div>
                                                    <div class="col-2 col-stats">
                                                        <div class="numbers mt-2">
                                                            <h2 class="text-uppercase" style="font-size: 16px;">Request</h2>
                                                            <h3 class="fw-bold text-uppercase" style="font-size: 30px; color: #C77C8D;"><?= number_format($reqcount) ?></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">          
                                                    <h5 class="text-uppercase" style="font-size: 14px;">Total Request</h5>                          
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                            <?php if(isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?php echo $_SESSION['success']; ?> <?= $_SESSION['success']=='danger' ? 'bg-danger text-light' : null ?>" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?php echo $_SESSION['message']; ?>
                                </div>
                            <?php unset($_SESSION['message']); ?>
                            <?php endif ?>
                                <div class="card">
                                    <div class="card-header md-2">
                                        <div class="card-head-row">
                                            <div class="card-title fw-bold"></div>
                                            <?php if(isset($_SESSION['username'])):?>
                                                <div class="card-tools">
                                                    <a href="#add" data-toggle="modal" class="btn btn-light btn-border btn-sm">
                                                        <i class="fa fa-plus"></i>
                                                        Profiling
                                                    </a>
                                                    <a href="model/export_purok.php" class="btn btn-light btn-border btn-sm">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            <?php endif?>
                                        </div>                                    
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="residenttable" class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Barangay ID</th>
                                                        <th scope="col">Fullname</th>
                                                        <th scope="col">Address</th>
                                                        <th scope="col">Birthdate</th>
                                                        <th scope="col">Age</th>
                                                        <th scope="col">Gender</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Purok</th>
                                                        <?php if(isset($_SESSION['username'])):?>
                                                            <?php if($_SESSION['role']=='purok leader'):?>
                                                        <?php endif ?>
                                                        <th class="text-center" scope="col">Action</th>
                                                        <?php endif ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php if (!empty($resident)): ?>
                                                    <?php $no = 1; foreach ($resident as $row): ?>
                                                    <tr>
                                                        <td><?= $row['national_id'] ?></td>
                                                        <td>
                                                            <?= ucwords($row['lastname'].', '.$row['firstname'].' '.$row['middlename']) ?>
                                                        </td>
                                                        <td><?= $row['address'] ?></td>
                                                        <td><?= $row['birthdate'] ?></td>
                                                        <td><?= $row['age'] ?></td>
                                                        <td><?= $row['gender'] ?></td>
                                                        <td><?= $row['email'] ?></td>
                                                        <td><?= $row['purok'] ?></td>
                                                        <?php if(isset($_SESSION['username'])):?>
                                                        
                                                        <?php if($_SESSION['role']=='purok leader'):?>                                                           
                                                        <?php endif ?>
                                                        <td class="text-center">
                                                            <div class="form-button-action">
                                                                <a type="button" href="#edit" data-toggle="modal" class="btn btn-link btn-primary" title="View Resident" onclick="editResident(this)" 
                                                                    data-id="<?= $row['id'] ?>" data-national="<?= $row['national_id'] ?>" data-fname="<?= $row['firstname'] ?>" data-mname="<?= $row['middlename'] ?>" data-lname="<?= $row['lastname'] ?>" data-address="<?= $row['address'] ?>" data-bplace="<?= $row['birthplace'] ?>" data-bdate="<?= $row['birthdate'] ?>" data-age="<?= $row['age'] ?>"
                                                                    data-cstatus="<?= $row['civilstatus'] ?>" data-gender="<?= $row['gender'] ?>"data-purok="<?= $row['purok'] ?>" data-vstatus="<?= $row['voterstatus'] ?>" data-taxno="<?= $row['taxno'] ?>" data-number="<?= $row['phone'] ?>" data-email="<?= $row['email'] ?>" data-occu="<?= $row['occupation'] ?>" 
                                                                     data-citi="<?= $row['citizenship'];?>" data-dead="<?= $row['resident_type'];?>">
                                                                    <?php if(isset($_SESSION['username'])): ?>
                                                                        <i class="fas fa-edit"></i>
                                                                    <?php else: ?>
                                                                        <i class="fa fa-eye"></i>
                                                                    <?php endif ?>
                                                                </a>
                                                                <?php if(isset($_SESSION['username']) && $_SESSION['role']=='purok leader'):?>
                                                                    <button type="button" class="btn btn-link btn-danger" data-toggle="modal" data-target="#confirmDeleteModal<?= $row['id'] ?>" data-original-title="Remove">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                <?php endif ?>
                                                            </div>
                                                        </td>
                                                        <?php endif ?>														
                                                    </tr>
                                                    <?php $no++; endforeach ?>
                                                <?php endif ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>	
            </div>
        </div>
        <?php foreach ($resident as $row) { ?>
        <div class="modal fade" id="confirmDeleteModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Message</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center" style="font-size: 16px">
                        Are you sure you want to removed this resident?
                    </div>
                    <div class="modal-footer mt-2 d-flex justify-content-center">
                        <form method="post" action="model/restore_purok_records.php">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">No</button>
                            <button type="submit" class="btn btn-primary">Yes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
                <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">New Resident Registration Form</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="model/save_purok_resident.php" enctype="multipart/form-data">
                                    <input type="hidden" name="size" value="1000000">
                                    <div class="row">
                                        <div class="col-md-12">
                                        <h4 class="mt-2"><b>Kindly update your purok assignment with the latest <br> resident information.</b></h4>
                                            <div class="form-group text-center mt-3">                                               
                                                <div class="selectgroup selectgroup-secondary selectgroup-pills">
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="deceased" value="1" class="selectgroup-input" checked="">
                                                        <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-walking"></i></span>
                                                    </label><p class="mt-1 mr-3"><b>Alive</b></p>
                                                    <label class="selectgroup-item">
                                                        <input type="radio" name="deceased" value="0" class="selectgroup-input" id="dead">
                                                        <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-people-carry"></i></span>
                                                    </label><p  class="mt-1 mr-3"><b>Deceased</b></p>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Barangay ID No.</label>
                                                <input type="text" class="form-control" name="national" placeholder="Barangay ID No." required>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>First name</label>
                                                        <input type="text" class="form-control" placeholder="Ex: Juan" name="fname" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Middle name</label>
                                                        <input type="text" class="form-control" placeholder="Ex: G." name="mname" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Last name</label>
                                                        <input type="text" class="form-control" placeholder="Ex: Luna" name="lname" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Address</label>
                                                        <input type="text" class="form-control" placeholder="Ex: 1-A Los Amigos" name="address" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Place of Birth</label>
                                                        <input type="text" class="form-control" placeholder="Ex: Tugbok, Los Amigos" name="bplace" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Birthdate</label>
                                                        <input type="date" class="form-control" placeholder="Enter Birthdate" name="bdate" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Age</label>
                                                        <input type="number" class="form-control" placeholder="Enter Age" min="1" name="age" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Civil Status</label>
                                                        <select class="form-control" name="cstatus">
                                                            <option disabled selected>Select Civil Status</option>
                                                            <option value="Single">Single</option>
                                                            <option value="Married">Married</option>
                                                            <option value="Widow">Widow</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Gender</label>
                                                        <select class="form-control" required name="gender">
                                                            <option disabled selected value="">Select Gender</option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Purok</label>
                                                        <select class="form-control" required name="purok">
                                                            <option disabled selected>Select Purok Name</option>
                                                            <?php foreach($purok as $row):?>
                                                                <option value="<?= ucwords($row['purok']) ?>"><?= $row['purok'] ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Voters Status</label>
                                                        <select class="form-control vstatus" required name="vstatus">
                                                            <option disabled selected>Select Voters Status</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>                               
                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="text" class="form-control" placeholder="Ex: no-sample@gmail.com" name="email" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Contact Number</label>
                                                        <input type="text" class="form-control" placeholder="Ex: +63 000-000-0000" name="number" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Occupation</label>
                                                        <input type="text" class="form-control" placeholder="Ex: Teacher" name="occupation" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Update Resident Registration Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="model/edit_purok_resident.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                <div class="row">
                                <h3 class="text-center mt-2"><b>Kindly check the resident information.</b></h3>
                                    <div class="col-md-12">
                                        <div class="form-group text-center mt-3">
                                            <div class="selectgroup selectgroup-secondary selectgroup-pills">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="deceased" value="1" class="selectgroup-input" checked="" id="alive">
                                                    <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-walking"></i></span>
                                                </label><p class="mt-1 mr-3"><b>Alive</b></p>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="deceased" value="0" class="selectgroup-input" id="dead">
                                                    <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-people-carry"></i></span>
                                                </label><p  class="mt-1 mr-3"><b>Deceased</b></p>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Barangay ID No.</label>
                                            <input type="text" class="form-control" name="national" placeholder="Enter Barangay ID No." id="nat_id" readonly>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>First name</label>
                                                    <input type="text" class="form-control" placeholder="Enter First name" name="fname"  id="fname" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Middle name</label>
                                                    <input type="text" class="form-control" placeholder="Enter Middle name" name="mname" id="mname" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Last name</label>
                                                    <input type="text" class="form-control" placeholder="Enter Last name" name="lname" id="lname" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <input type="text" class="form-control" placeholder="Enter Address" name="address" id="address" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Place of Birth</label>
                                                    <input type="text" class="form-control" placeholder="Enter Birthplace" name="bplace"  id="bplace" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Birthdate</label>
                                                    <input type="date" class="form-control" placeholder="Enter Birthdate" name="bdate" id="bdate" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Age</label>
                                                    <input type="number" class="form-control" placeholder="Enter Age" min="1" name="age" id="age" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Civil Status</label>
                                                    <select class="form-control" name="cstatus" id="cstatus">
                                                        <option disabled selected>Select Civil Status</option>
                                                        <option value="Single">Single</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Widow">Widow</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Gender</label>
                                                    <select class="form-control" required name="gender" id="gender">
                                                        <option disabled selected value="">Select Gender</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Purok</label>
                                                    <select class="form-control" required name="purok" id="purok">
                                                        <option disabled selected>Select Purok Name</option>
                                                        <?php foreach($purok as $row):?>
                                                            <option value="<?= ucwords($row['purok']) ?>"><?= $row['purok'] ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Voters Status</label>
                                                    <select class="form-control vstatus" required name="vstatus" id="vstatus">
                                                        <option disabled selected>Select Voters Status</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>                               
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" class="form-control" placeholder="Ex: no-sample@gmail.com" name="email" id="email" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Contact Number</label>
                                                    <input type="text" class="form-control" placeholder="Enter Contact Number" value="+63 000-000-000-00" name="number" id="number" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Occupation</label>
                                                    <input type="text" class="form-control" placeholder="Enter Occupation" name="occupation" id="occupation" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer mt-2 d-flex justify-content-center">
                                    <input type="hidden" name="id" id="res_id">
                                    <?php if(isset($_SESSION['username'])): ?>
                                    <button type="submit" class="btn btn-primary">Change</button>
                                    <?php endif ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php include 'templates/main-footer.php' ?>
    </div>
<?php include 'templates/footer.php' ?>
<script>
    function editResident(that){
        id          = $(that).attr('data-id');
        pic         = $(that).attr('data-img');
        nat_id 		= $(that).attr('data-national');
        fname 		= $(that).attr('data-fname');
        mname 		= $(that).attr('data-mname');
        lname 		= $(that).attr('data-lname');
        address 	= $(that).attr('data-address');
        bplace 	    = $(that).attr('data-bplace');
        bdate 		= $(that).attr('data-bdate');
        age 		= $(that).attr('data-age');
        cstatus 	= $(that).attr('data-cstatus');
        gender 	    = $(that).attr('data-gender');
        purok 		= $(that).attr('data-purok');
        vstatus 	= $(that).attr('data-vstatus');
        email 	    = $(that).attr('data-email');
        number 	    = $(that).attr('data-number');
        taxno 	    = $(that).attr('data-taxno');
        citi 	    = $(that).attr('data-citi');
        occu 	    = $(that).attr('data-occu');
        dead 	    = $(that).attr('data-dead');
        remarks 	= $(that).attr('data-remarks');
        purpose 	= $(that).attr('data-purpose');

        $('#res_id').val(id);
        $('#nat_id').val(nat_id);
        $('#fname').val(fname);
        $('#mname').val(mname);
        $('#lname').val(lname);
        $('#address').val(address);
        $('#bplace').val(bplace);
        $('#bdate').val(bdate);
        $('#age').val(age);
        $('#cstatus').val(cstatus);
        $('#gender').val(gender);
        $('#purok').val(purok);
        $('#vstatus').val(vstatus);
        $('#taxno').val(taxno);
        $('#email').val(email);
        $('#number').val(number);
        $('#occupation').val(occu);
        $('#citizenship').val(citi);
        $('#remarks').val(remarks);
        $('#purpose').val(purpose);

        if(dead==1){
            $("#alive").prop("checked", true);
        }else{
            $("#dead").prop("checked", true);
        }

        var str = pic;
        var n = str.includes("data:image");
        if(!n){
            pic = 'assets/uploads/resident_profile/'+pic;
        }
        $('#image').attr('src', pic);
    }
</script>
</body>
</html>