<?php
include 'server/db_connection.php';

if (!isset($_SESSION["fullname"])) {
    header("Location: login.php");
    exit;
}

$fullname = $_SESSION["fullname"];

$sql = "SELECT *, tblresident.id AS id, tblresident.purok FROM tblresident JOIN tbl_user_resident ON tblresident.email = tbl_user_resident.user_email WHERE tbl_user_resident.fullname = ? AND tblresident.residency_status IN ('on hold', 'operating','rejected')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $fullname);
$stmt->execute();
$result = $stmt->get_result();

$resident = array();

while ($row = $result->fetch_assoc()) {
    $status = $row['residency_status'];
    $statusBadge = '';

    if ($status == 'on hold') {
        $statusBadge = '<span class="badge badge-warning">On Hold</span>';
    }elseif ($status == 'approved') {
            $statusBadge = '<span class="badge badge-success">Approved</span>';
    } elseif ($status == 'rejected') {
        $statusBadge = '<span class="badge badge-danger">Rejected</span>';
    }

    $row['residency_badge'] = $statusBadge;

    if ($status == 'on hold') {
        $resident[] = $row;
    } elseif ($status == 'rejected') {
        $resident[] = $row;
    }
}

$user = $_SESSION["user_email"];

$query1 = "SELECT * FROM tbl_user_resident WHERE tbl_user_resident.user_email=?";
$stmt = $conn->prepare($query1);
$stmt->bind_param("s", $user);
$stmt->execute();
$result1 = $stmt->get_result();

$res = array();
while($row2 = $result1->fetch_assoc()){
	$res[] = $row2; 
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include 'templates/header.php' ?>
    <link rel="stylesheet" href="assets/css/menu-style.css">
	<title>CertiFast Portal</title>
</head>
<body>
	<div class="wrapper">
		<?php include 'templates/main-header-resident.php' ?>
		<?php include 'templates/sidebar-resident.php' ?>
		<div class="main-panel mt-2">
			    <div class="content">
                    <h1 class="text-center fw-bold mt-4" style="font-size: 300%;">Barangay Certificates</h1>
                    <h3 class="text-center">Please fill out your personal information before submitting any of the requested certifications in Barangay Los Amigos.</h3>
                    <h4 class="text-center">(Palihog pun-a ang inyong personal nga impormasyon sa wala pa magpasa sa mga gikinahanglang sertipikasyon sa Barangay Los Amigos.)</h4>
                    <div class="page-inner">
                            <?php if(isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?php echo $_SESSION['success']; ?> <?= $_SESSION['success']=='danger' ? 'bg-danger text-light' : null ?>" role="alert" style="font-size: 21px;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?php echo $_SESSION['message']; ?>
                                </div>
                            <?php unset($_SESSION['message']); ?>
                            <?php endif ?>
                        <div class="container col-sm-12 col-md-12">
                            <form method="GET" class="col-sm-12 col-md-12">
                                <div class="input-group">
                                    <select class="form-control" id="certType" name="search">
                                        <option value="" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Default') echo 'selected'; ?> disabled>Select Types of Certificates</option>
                                        <option value="Show All" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Show All') echo 'selected'; ?>><b>Show All</b></option>
                                        <option value="Barangay Clearance" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Barangay Clearance') echo 'selected'; ?>>Barangay Clearance</option>
                                        <option value="Business Permit" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Business Permit') echo 'selected'; ?>>Business Permit</option>
                                        <option value="Barangay Identification" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Barangay Identification') echo 'selected'; ?>>Barangay Identification (ID)</option>
                                        <option value="Certificate of Residency" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Certificate of Residency') echo 'selected'; ?>>Certificate of Residency</option>
                                        <option value="Certificate of Indigency" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Certificate of Indigency') echo 'selected'; ?>>Certificate of Indigency</option>
                                        <option value="First Time Jobseekers" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'First Time Jobseekers') echo 'selected'; ?>>First Time Jobseekers</option>
                                        <option value="Certificate of Oath Taking" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Certificate of Oath Taking') echo 'selected'; ?>>Certificate of Oath Taking</option>
                                        <option value="Certificate of Death" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Certificate of Death') echo 'selected'; ?>>Certificate of Death</option>
                                        <option value="Certificate of Birth" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Certificate of Birth') echo 'selected'; ?>>Certificate of Birth</option>
                                        <option value="Certificate of Good Moral" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Certificate of Good Moral') echo 'selected'; ?>>Certificate of Good Moral</option>
                                        <option value="Certificate of Live In" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Certificate of Live In') echo 'selected'; ?>>Certificate of Live In</option>
                                        <option value="Family Home Estate" <?php if (isset($_POST['certType']) && $_POST['certType'] === 'Family Home Estate') echo 'selected'; ?>>Family Home Estate</option>
                                    </select>  
                                    <button type="submit" class="btn btn-danger">Confirm</button>                           
                                </div>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="p-1 bg-danger text-white container">
                                    <h4 class="text-left mt-2"><i class="fas fa-exclamation-circle"></i>  Palihug siguruha nga ang imong Profiling naaprobahan na sa imong respetado nga Purok Leader, sa wala ka pa mangayo ug bisan unsang mga sertipikasyon.</h4>
                                </div>
                                <div class="container">
                                    <?php
                                    include 'server/db_connection.php';
                                    $sql = "SELECT * FROM tblcertificates";
                                    $result = mysqli_query($conn, $sql);
                                    $boxes = array();
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $boxes[] = array(
                                            'image' => 'assets/img/brgyLA.png',
                                            'name' => $row['list_certificates'],
                                            'description' => $row['description'],
                                            'link' => $row['link'],
                                        );
                                    }

                                    function filterBoxes($boxes, $searchTerm) {
                                        if ($searchTerm === 'Show All') {
                                            return $boxes;
                                        }

                                        $filteredBoxes = array();
                                        foreach ($boxes as $box) {
                                            if (stripos($box['name'], $searchTerm) !== false) {
                                                $filteredBoxes[] = $box;
                                            }
                                        }
                                        return $filteredBoxes;
                                    }

                                    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
                                    if (!empty($searchTerm)) {
                                        $filteredBoxes = filterBoxes($boxes, $searchTerm);
                                    } else {
                                        $filteredBoxes = $boxes;
                                    }

                                    foreach ($filteredBoxes as $box):
                                    ?>
                                    <div class="d-flex flex-column align-items-center box mb-3">
                                        <div class="image">
                                            <img src="<?php echo $box['image']; ?>">
                                        </div>
                                        <div class="name_job"><?php echo ucwords($box['name']); ?></div>
                                        <p><?php echo $box['description']; ?></p>
                                        <div class="btns text-center">
                                            <a type="button" href="<?php echo $box['link']; ?>" data-toggle="modal" class="p-2 text-white">Request</a>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="modal fade" id="addgoodmoral" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Certificate of Good Moral Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_good_moral.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5><b>Fill out the given information and follow the example correctly. Put N/A if not applicable.</b></h5>
                                                <span>Punan ang Ibinigay na Impormasyon. Lagyan ng N/A kung hindi naaangkop.</span>
                                            </div> 
                                            <div class="form-group">
                                                <label>Fullname </label>
                                                <span>(Boung Pangalan)</span>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" placeholder="Ex: Juan G. Luna" name="fullname" value="<?= $_SESSION["fullname"] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Purok</label>
                                                <?php foreach($res as $row2):?>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" name="purok" value="<?= $row2["purok"] ?>">
                                                <?php endforeach ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Tax no. </label>
                                                <span>(Tax ID number)</span>
                                                <input type="number" class="form-control" placeholder="Ex: 000-000-000-000" min="6" name="taxno" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Reason for requesting </label>
                                                <span>(Rason sa paghanyo)</span>
                                                <textarea class="form-control" name="requirement" required placeholder="Ex: 4ps Requirements"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Copies:</label>
                                                <span>(Pila ka kopya ang kinahanglan nimo?)</span> &nbsp
                                                <select class=" w-25 p-2" id="quantity" name="quantity">
                                                <option value disabled>Please select a number</option>
                                                <?php
                                                    for ($i = 1; $i <= 10; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 d-flex justify-content-center">
                                        <input type="hidden" name="certificate_name" value="certificate of good moral" required>
                                        <input type="hidden" name="fname" value="<?= $_SESSION["fullname"]; ?>" required>
                                        <input type="hidden" name="email" value="<?= $_SESSION["user_email"]; ?>" required>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="modal fade" id="adddeath" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content"> 
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Death Certificate Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_death.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5><b>Fill out the given information and follow the example correctly. Put N/A if not applicable.</b></h5>
                                                <span>Punan ang Ibinigay na Impormasyon. Lagyan ng N/A kung hindi naaangkop.</span>
                                            </div> 
                                            <div class="form-group">
                                                <label>Full name of the deceased</label>
                                                <span>(Buong pangalan ng namatay)</span>
                                                <input type="text" class="form-control" placeholder="Ex: Juan G. Luna" name="dead_person" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <span>(Araw ng Kapanganakan)</span>
                                                <input type="date" class="form-control" name="death_bdate" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Age</label>
                                                <span>(Edad bago namatay)</span>
                                                <input type="number" class="form-control" placeholder="Age" min="1" name="age" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Purok</label>
                                                <?php foreach($res as $row2):?>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" name="purok" value="<?= $row2["purok"] ?>">
                                                <?php endforeach ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Date of Death</label>
                                                <span>(Araw ng Kamatayan)</span>
                                                <input type="date" class="form-control" name="death_date" required>
                                            </div>
                                            <div class="form-group mt-2">
                                                <h5><b>Personal Information of the Families/Relatives: </b></h5>
                                                <span>(Personal na Impormasyon ng pamilya/kamag-anak)</span>
                                            </div>                                           
                                            <div class="form-group">
                                                <label>Name of the informant</label>
                                                <span>(Buong pangalan ng taong nagbibigay ng impormasyon tungkol sa namatay)</span>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" placeholder="Name" name="guardian" value="<?= $_SESSION["fullname"]; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Relationship to the deceased</label>
                                                <span>(Relasyon sa namatay)</span>
                                                <select class="form-control" required name="relationship">
                                                    <option disabled selected>Select Relationship</option>
                                                        <option value="Wife">Wife (Asawa)</option>
                                                        <option value="Husband">Husband (Bana)</option>
                                                        <option value="Mother">Mother (Inahan)</option>
                                                        <option value="Father">Father (Amahan)</option>
                                                        <option value="Uncle">Uncle (Uyoan)</option>
                                                        <option value="Antie">Antie (Iyaan)</option>
                                                        <option value="Grandfather">Grandmother (Lola)</option>
                                                        <option value="Grandfather">Grandfather (Lolo)</option>
                                                        <option value="Brother">Brother (Igsoon)</option>
                                                        <option value="Sister">Sister (Igsoon)</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Cause of Death</label>
                                                <span>(Dahilan ng kamatayan)</span>
                                                <textarea class="form-control" name="requirement" required placeholder="Ex: High Blood"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Copies:</label>
                                                <span>(Pila ka kopya ang kinahanglan nimo?)</span> &nbsp
                                                <select class=" w-25 p-2" id="quantity" name="quantity">
                                                <option value disabled>Please select a number</option>
                                                <?php
                                                    for ($i = 1; $i <= 10; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 d-flex justify-content-center">
                                        <input type="hidden" name="certificate_name" value="certificate of death" required>
                                        <input type="hidden" name="fullname" value="<?= $_SESSION["fullname"]; ?>" required>
                                        <input type="hidden" name="email" value="<?= $_SESSION["user_email"]; ?>" required>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="modal fade" id="addbrgyId" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Barangay Identification Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_brgy_id.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5><b>Fill out the given information and follow the example correctly. Put N/A if not applicable.</b></h5>
                                                <span>Punan ang Ibinigay na Impormasyon. Lagyan ng N/A kung hindi naaangkop.</span>
                                            </div> 
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <span>(Buong Pangalan)</span>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" placeholder="Ex: Juan G. Luna" name="fullname" value="<?= $_SESSION["fullname"] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Purok</label>
                                                <?php foreach($res as $row2):?>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" name="purok" value="<?= $row2["purok"] ?>">
                                                <?php endforeach ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Birthdate</label>
                                                <span>(Araw ng kapanganakan)</span>
                                                <input type="date" class="form-control" name="birthdate" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Contact Number</label>
                                                <input type="number" class="form-control" placeholder="Contact Number (e.g., 09)" maxlength="11" name="phone" id="phone" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Precint Number</label>
                                                <span>(Numero ng Presinto)</span>
                                                <input type="number" class="form-control" placeholder="Precint number" name="precintno" required>
                                            </div>
                                            <div class="form-group mt-2">
                                                <h5><b>In case of emergency </b></h5>
                                                <span>Taong kinukuntak pag may importanteng kailangan</span>
                                            </div>                                        
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <span>(Buong Pangalan)</span>
                                                <input type="text" class="form-control" placeholder="Ex: Juan G. Luna" name="guardian" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Relationship to the person</label>
                                                <span>(Relasyon sa tao)</span>
                                                <select class="form-control" required name="relationship">
                                                    <option disabled selected>Select Relationship</option>
                                                        <option value="Wife">Wife (Asawa)</option>
                                                        <option value="Husband">Husband (Bana)</option>
                                                        <option value="Mother">Mother (Inahan)</option>
                                                        <option value="Father">Father (Amahan)</option>
                                                        <option value="Uncle">Uncle (Uyoan)</option>
                                                        <option value="Antie">Antie (Iyaan)</option>
                                                        <option value="Grandfather">Grandmother (Lola)</option>
                                                        <option value="Grandfather">Grandfather (Lolo)</option>
                                                        <option value="Brother">Brother (Igsoon)</option>
                                                        <option value="Sister">Sister (Igsoon)</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Contact Number</label>
                                                <input type="number" class="form-control" placeholder="Contact Number (e.g., 09)" maxlength="11" name="contact_number" id="contact_number" required>
                                            </div>   
                                            <div class="form-group">
                                                <label>Reason for requesting</label>
                                                <span>(Rason sa Paghangyo)</span>
                                                <textarea class="form-control" name="requirement" required placeholder="Ex: 4ps Requirements"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Copies:</label>
                                                <span>(Pila ka kopya ang kinahanglan nimo?)</span> &nbsp
                                                <select class=" w-25 p-2" id="quantity" name="quantity">
                                                <option value disabled>Please select a number</option>
                                                <?php
                                                    for ($i = 1; $i <= 10; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 d-flex justify-content-center">
                                        <input type="hidden" name="certificate_name" value="barangay identification" required>
                                        <input type="hidden" name="fname" value="<?= $_SESSION["fullname"]; ?>" required>
                                        <input type="hidden" name="email" value="<?= $_SESSION["user_email"]; ?>" required>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    var phoneWarningShown = false;
                    var contactNumberWarningShown = false;

                    document.getElementById("phone").addEventListener("input", function () {
                        var phoneNumber = this.value.trim();

                        if (/^\+63\d{10}$/.test(phoneNumber) && phoneNumber.length === 13) {
                            this.maxLength = 13;
                            if (phoneWarningShown) {
                                phoneWarningShown = false;
                            }
                        } else {
                            this.maxLength = 13;
                            if (!phoneWarningShown) {
                                alert("Invalid contact number format. Please enter number that start 09-000-000-000.");
                                phoneWarningShown = true; 
                            }
                        }
                    });

                    document.getElementById("contact_number").addEventListener("input", function () {
                        var contactNumber = this.value.trim();

                        if (/^\+63\d{10}$/.test(contactNumber) && contactNumber.length === 13) {
                            this.maxLength = 13;
                            if (contactNumberWarningShown) {
                                contactNumberWarningShown = false;
                            }
                        } else {
                            this.maxLength = 13;
                            if (!contactNumberWarningShown) {
                                alert("Invalid contact number format. Please enter number that start 09-000-000-000.");
                                contactNumberWarningShown = true;
                            }
                        }
                    });
                </script>
            <div class="modal fade" id="addpermit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Business Permit Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_permit_user.php" >
                                <div class="form-group">
                                    <label>Business Owner Name</label>
                                    <input type="text" class="form-control mb-2 btn btn-light btn-info disabled" placeholder="Ex: Juan G. Luna" name="owner1" value="<?= $_SESSION["fullname"] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Business Email Address</label>
                                    <input type="text" class="form-control mb-2 btn btn-light btn-info disabled" placeholder="Ex: sample@gmail.com" name="email" value="<?= $_SESSION["user_email"] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Nature of Business Name</label>
                                    <input type="text" class="form-control" placeholder="Ex: Sari-Sari Store" name="business_name"  required>
                                </div>
								<div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" placeholder="Address" name="address" required>
                                </div>
                                <div class="form-group">
                                    <label>Business Location</label>
                                    <input type="text" class="form-control" placeholder="Business Location" name="location" required>
                                </div>
								<div class="form-group">
                                    <label>Date Applied</label>
                                    <input type="date" class="form-control" name="applied" value="<?= date('Y-m-d'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Reasons for applying</label>
                                    <input type="text" class="form-control" name="requirement" id="requirement" placeholder="Reason" required>
                                </div>
                                <div class="form-group">
                                    <label for="quantity">Copies:</label>
                                    <span>(Pila ka kopya ang kinahanglan nimo?)</span> &nbsp
                                    <select class=" w-25 p-2" id="quantity" name="quantity">
                                    <option value disabled>Please select a number</option>
                                    <?php
                                        for ($i = 1; $i <= 10; $i++) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                    ?>
                                    </select>
                                </div>
                       		</div>
							<div class="modal-footer mt-2 d-flex justify-content-center">
                                <input type="hidden" name="certificate_name" value="business permit" required>
                                <input type="hidden" name="req_email" value="<?= $_SESSION["user_email"]; ?>" required>
                                <input type="hidden" name="fname" value="<?= $_SESSION["fullname"]; ?>" required>
								<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-danger">Submit</button>
							</div>
                        </form>
                    </div>
                </div>
            </div>
        <div class="modal fade" id="addoath" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Certificate of Oath Undertaking Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_oath.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5><b>Fill out the given information and follow the example correctly. Put N/A if not applicable.</b></h5>
                                                <span>Punan ang Ibinigay na Impormasyon. Lagyan ng N/A kung hindi naaangkop.</span>
                                            </div> 
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <span>(Buong Pangalan)</span>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" placeholder="Ex: Juan G. Luna" name="fullname" value="<?= $_SESSION["fullname"] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Purok</label>
                                                <?php foreach($res as $row2):?>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" name="purok" value="<?= $row2["purok"] ?>">
                                                <?php endforeach ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Age</label>
                                                <span>(Edad)</span>
                                                <input type="number" class="form-control" placeholder="Enter Age" min="1" name="age" required>
                                                <span style="color: red;">Note: Ang imong edad kinahanglan nga sa legal, 18 pataas ang allowed mo register.</span>
                                            </div>
                                           <div class="form-group">
                                                <label>Reason for requesting</label>
                                                <span>(Rason sa Paghangyo)</span>
                                                <textarea class="form-control" name="requirement" required placeholder="Ex: Employment Requirements"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Copies:</label>
                                                <span>(Pila ka kopya ang kinahanglan nimo?)</span> &nbsp
                                                <select class=" w-25 p-2" id="quantity" name="quantity">
                                                <option value disabled>Please select a number</option>
                                                <?php
                                                    for ($i = 1; $i <= 10; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 d-flex justify-content-center">
                                        <input type="hidden" name="certificate_name" value="certificate of oath taking" required>
                                        <input type="hidden" name="fname" value="<?= $_SESSION["fullname"]; ?>" required>
                                        <input type="hidden" name="email" value="<?= $_SESSION["user_email"]; ?>" required>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="modal fade" id="addclearance" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Barangay Clerance Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_clearance.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5><b>Fill out the given information and follow the example correctly. Put N/A if not applicable.</b></h5>
                                                <span>Punan ang Ibinigay na Impormasyon. Lagyan ng N/A kung hindi naaangkop.</span>
                                            </div> 
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <span>(Buong Pangalan)</span>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" placeholder="Ex: Juan G. Luna" name="fullname" value="<?= $_SESSION["fullname"] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Purok</label>
                                                <?php foreach($res as $row2):?>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" name="purok" value="<?= $row2["purok"] ?>">
                                                <?php endforeach ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Tax No</label>
                                                <span>(Tax ID number)</span>
                                                <input type="number" class="form-control" placeholder="Ex: 000-000-000-000" min="6" name="taxno" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Reason for requesting</label>
                                                <span>(Rason sa Paghangyo )</span>
                                                <textarea class="form-control" name="requirement" required placeholder="Ex: 4ps Requirements "></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Copies:</label>
                                                <span>(Pila ka kopya ang kinahanglan nimo?)</span> &nbsp
                                                <select class=" w-25 p-2" id="quantity" name="quantity">
                                                <option value disabled>Please select a number</option>
                                                <?php
                                                    for ($i = 1; $i <= 10; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 d-flex justify-content-center">
                                        <input type="hidden" name="certificate_name" value="barangay clearance" required>
                                        <input type="hidden" name="fname" value="<?= $_SESSION["fullname"]; ?>" required>
                                        <input type="hidden" name="email" value="<?= $_SESSION["user_email"]; ?>" required>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="modal fade" id="addlivein" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Live in Certificate Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_live_in.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5><b>Fill out the given information and follow the example correctly. Put N/A if not applicable.</b></h5>
                                                <span>Punan ang Ibinigay na Impormasyon. Lagyan ng N/A kung hindi naaangkop.</span>
                                            </div> 
                                            <div class="form-group">
                                                <label>Husband Full Name</label>
                                                <span>(Bana Kompleto nga Pangalan)</span>
                                                <input type="text" class="form-control" placeholder="Husband's Name" name="husband" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Age</label>
                                                <span>(Edad)</span>
                                                <input type="number" class="form-control" placeholder="Husband's Age" min="1" name="husband_age" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Wife Full Name</label>
                                                <span>(Asawa Kompleto nga Pangalan)</span>
                                                <input type="text" class="form-control" placeholder="Wife's Name" name="wife" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Age</label>
                                                <span>(Edad)</span>
                                                <input type="number" class="form-control" placeholder="Wife's age" min="1" name="wife_age" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Duration of Cohabitation</label>
                                                <span>(Tagal ng pagsasama)</span>
                                                <input type="text" class="form-control" placeholder="Ex: 2 years" min="1" name="living_year" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Purok</label>
                                                <?php foreach($res as $row2):?>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" name="purok" value="<?= $row2["purok"] ?>">
                                                <?php endforeach ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Reason for requesting</label>
                                                <span>(Rason sa Paghangyo)</span>
                                                <textarea class="form-control" name="requirements" required placeholder="Ex: 4ps Requirements"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Copies:</label>
                                                <span>(Pila ka kopya ang kinahanglan nimo?)</span> &nbsp
                                                <select class=" w-25 p-2" id="quantity" name="quantity">
                                                <option value disabled>Please select a number</option>
                                                <?php
                                                    for ($i = 1; $i <= 10; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 d-flex justify-content-center">
                                        <input type="hidden" name="certificate_name" value="certificate of live in" required>
                                        <input type="hidden" name="fname" value="<?= $_SESSION["fullname"]; ?>" required>
                                        <input type="hidden" name="email" value="<?= $_SESSION["user_email"]; ?>" required>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="modal fade" id="addfamilytax" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Family Tax Estate Certificate Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_family_tax.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5><b>Fill out the given information and follow the example correctly. Put N/A if not applicable.</b></h5>
                                                <span>Punan ang Ibinigay na Impormasyon. Lagyan ng N/A kung hindi naaangkop.</span>
                                            </div>                                        
                                            <div class="form-group">
                                                <label>Wife Name</label>
                                                <span>(Asawa Boung Pangalan)</span>
                                                <input type="text" class="form-control" placeholder="Name" name="fam_1" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Husband Name</label>
                                                <span>(Bana Boung Pangalan)</span>
                                                <input type="text" class="form-control" placeholder="Name" name="fam_2" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Purok</label>
                                                <?php foreach($res as $row2):?>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" name="purok" value="<?= $row2["purok"] ?>">
                                                <?php endforeach ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Children Name's</label>
                                                <span></span>
                                                <input type="text" class="form-control mb-2" placeholder="First child name" name="fam_3" required>
                                                <input type="text" class="form-control mb-2" placeholder="Second child name" name="fam_4" required>
                                                <input type="text" class="form-control mb-2" placeholder="Third child name" name="fam_5" required>
                                                <h6 class="text-danger"><b>Note: In case you had a lot of children's, we advise you to proceed to Barangay Los Amigos Office. </b></h6>
                                                <span>Kung higit sa (3) ang inyong anak, maaaring pumunta na lamang sa Barangay Los Amigos Office para sa dagdag na impormasyon</span>
                                            </div>
                                            <div class="form-group">
                                                <label>Transfer Certificate of Title (TCT) number</label>
                                                <span>(Sertipiko ng paglilipat ng titulo)</span>
                                                <input class="form-control" name="tctno" required placeholder="TCT number">
                                            </div>
                                            <div class="form-group">
                                                <label>Reason for requesting</label>
                                                <span>(Rason sa Paghangyo)</span>
                                                <textarea class="form-control" name="requirements" required placeholder="Ex: Loan Requirements"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Copies:</label>
                                                <span>(Pila ka kopya ang kinahanglan nimo?)</span> &nbsp
                                                <select class="w-25 p-2" id="quantity" name="quantity">
                                                <option value disabled>Please select a number</option>
                                                <?php
                                                    for ($i = 1; $i <= 10; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 d-flex justify-content-center">
                                        <input type="hidden" name="certificate_name" value="family home estate" required>
                                        <input type="hidden" name="fullname" value="<?= $_SESSION["fullname"]; ?>" required>
                                        <input type="hidden" name="email" value="<?= $_SESSION["user_email"]; ?>" required>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="modal fade" id="addbirth" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Birth Certificate Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_birth.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5><b>Fill out the given information and follow the example correctly. Put N/A if not applicable.</b></h5>
                                                <span>Punan ang Ibinigay na Impormasyon. Lagyan ng N/A kung hindi naaangkop.</span>
                                            </div> 
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <span>(Buong Pangalan ng bata)</span>
                                                <input type="text" class="form-control" placeholder="Ex: Juan G. Luna" name="fullname" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <label>What is the birthday?</label>
                                                <span>(Kailan pinangak / petsa ng kapanganakan)</span>
                                                <input type="date" class="form-control" placeholder="Birthdate" name="bdate" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Age</label>
                                                <span>(Edad)</span>
                                                <input type="number" class="form-control" placeholder="Age" min="1" name="age" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Sex</label>
                                                <span>(Kasarian)</span>
                                                <select class="form-control" required name="gender">
                                                    <option disabled selected value="">Select Gender</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Birthplace</label>
                                                <span>(Saan pinanganak)</span>
                                                <input type="text" class="form-control" placeholder="Ex: Tugbok, Los Amigos" name="birthplace" value="" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Purok</label>
                                                <?php foreach($res as $row2):?>
                                                <input type="text" class="form-control  btn btn-light btn-info disabled" name="purok" value="<?= $row2["purok"] ?>">
                                                <?php endforeach ?>
                                            </div>
                                            <div class="form-group mt-2">
                                                <h5><b>Parents Information’s: </b></h5>
                                            </div>                                           
                                            <div class="form-group">
                                                <label>Mother’s Full Name</label>
                                                <span>(Buong Pangalan ng Ina)</span>
                                                <input type="text" class="form-control" placeholder="Mother's name" name="mother" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Father’s Full Name</label>
                                                <span>(Buong Pangalan ng Ama)</span>
                                                <input type="text" class="form-control" placeholder="Father's name" name="father" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Reason for requesting</label>
                                                <span>(Rason sa Paghangyo)</span>
                                                <textarea class="form-control" name="requirement" required placeholder="Ex: School Requirements"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Copies:</label>
                                                <span>(Pila ka kopya ang kinahanglan nimo?)</span> &nbsp
                                                <select class=" w-25 p-2" id="quantity" name="quantity">
                                                <option value disabled>Please select a number</option>
                                                <?php
                                                    for ($i = 1; $i <= 10; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 d-flex justify-content-center">
                                        <input type="hidden" name="certificate_name" value="certificate of birth" required>
                                        <input type="hidden" name="fname" value="<?= $_SESSION["fullname"]; ?>" required>
                                        <input type="hidden" name="email" value="<?= $_SESSION["user_email"]; ?>" required>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="modal fade" id="addresidency" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Certificate of Residency Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_residency.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5><b>Fill out the given information and follow the example correctly. Put N/A if not applicable.</b></h5>
                                                <span>Punan ang Ibinigay na Impormasyon. Lagyan ng N/A kung hindi naaangkop.</span>
                                            </div> 
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <span>(Buong Pangalan)</span>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" placeholder="Ex: Juan G. Luna" name="fullname" value="<?= $_SESSION["fullname"] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Purok</label>
                                                <?php foreach($res as $row2):?>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" name="purok" value="<?= $row2["purok"] ?>">
                                                <?php endforeach ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Age</label>
                                                <span>(Edad)</span>
                                                <input type="number" class="form-control" placeholder="Age" min="6" name="age" required>
                                                <span style="color: red;">Note: Ang imong edad kinahanglan nga sa legal, 18 pataas ang allowed mo register.</span>
                                            </div>
                                            <div class="form-group">
                                                <label>Reason for requesting</label>
                                                <span>(Rason sa Paghangyo)</span>
                                                <textarea class="form-control" name="requirement" required placeholder="Ex: Loan Requirements"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Years of resident of Los Amigos</label>
                                                <span>(Ilan taon nakatira sa Barangay/ Pila ka tuig nagpuyo sa Barangay)</span>
                                                <input type="text" class="form-control" name="resident_years" placeholder="Ex: 1 year or 2 years" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Copies:</label>
                                                <span>(Pila ka kopya ang kinahanglan nimo?)</span> &nbsp
                                                <select class=" w-25 p-2" id="quantity" name="quantity">
                                                <option value disabled>Please select a number</option>
                                                <?php
                                                    for ($i = 1; $i <= 10; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 d-flex justify-content-center">
                                        <input type="hidden" name="certificate_name" value="certificate of residency">
                                        <input type="hidden" name="fname" value="<?= $_SESSION["fullname"]; ?>">
                                        <input type="hidden" name="email" value="<?= $_SESSION["user_email"]; ?>">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="modal fade" id="addindigency" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Certificate of Indigency Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_indigency.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5><b>Fill out the given information and follow the example correctly. Put N/A if not applicable.</b></h5>
                                                <span>Punan ang Ibinigay na Impormasyon. Lagyan ng N/A kung hindi naaangkop.</span>
                                            </div> 
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <span>(Buong Pangalan)</span>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" placeholder="Ex: Juan G. Luna" name="fullname" value="<?= $_SESSION["fullname"] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Purok</label>
                                                <?php foreach($res as $row2):?>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" name="purok" value="<?= $row2["purok"] ?>">
                                                <?php endforeach ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Reason for requesting</label>
                                                <span>(Rason sa Paghangyo)</span>
                                                <textarea class="form-control" name="requirements" required placeholder="Ex: 4ps Requirements"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Copies:</label>
                                                <span>(Pila ka kopya ang kinahanglan nimo?)</span> &nbsp
                                                <select class=" w-25 p-2" id="quantity" name="quantity">
                                                <option value disabled>Please select a number</option>
                                                <?php
                                                    for ($i = 1; $i <= 10; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 d-flex justify-content-center">
                                        <input type="hidden" name="certificate_name" value="certificate of indigency" required>
                                        <input type="hidden" name="fname" value="<?= $_SESSION["fullname"]; ?>" required>
                                        <input type="hidden" name="email" value="<?= $_SESSION["user_email"]; ?>" required>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="modal fade" id="addjobseekers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Certificate of First Time Jobseekers Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_job.php" enctype="multipart/form-data">
                                <input type="hidden" name="size" value="1000000">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5><b>Fill out the given information and follow the example correctly. Put N/A if not applicable.</b></h5>
                                                <span>Punan ang Ibinigay na Impormasyon. Lagyan ng N/A kung hindi naaangkop.</span>
                                            </div> 
                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <span>(Buong Pangalan)</span>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" placeholder="Ex: Juan G. Luna" name="fullname" value="<?= $_SESSION["fullname"] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Purok</label>
                                                <?php foreach($res as $row2):?>
                                                <input type="text" class="form-control btn btn-light btn-info disabled" name="purok" value="<?= $row2["purok"] ?>">
                                                <?php endforeach ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Age</label>
                                                <span>(Edad)</span>
                                                <input type="number" class="form-control" placeholder="Age" min="1" name="age" value="" required>
                                                <span style="color: red;">Note: Ang imong edad kinahanglan nga sa legal, 18 pataas ang allowed mo register.</span>
                                            </div>
                                            <div class="form-group">
                                                <label>Reason for requesting</label>
                                                <span>(Rason sa Paghangyo)</span>
                                                <textarea class="form-control" name="requirement" required placeholder="Ex: Jobseeking Requirements"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="quantity">Copies:</label>
                                                <span>(Pila ka kopya ang kinahanglan nimo?)</span> &nbsp
                                                <select class=" w-25 p-2" id="quantity" name="quantity">
                                                <option value disabled>Please select a number</option>
                                                <?php
                                                    for ($i = 1; $i <= 10; $i++) {
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer mt-2 d-flex justify-content-center">
                                        <input type="hidden" name="certificate_name" value="first time jobseekers" required>
                                        <input type="hidden" name="fname" value="<?= $_SESSION["fullname"]; ?>" required>
                                        <input type="hidden" name="email" value="<?= $_SESSION["user_email"]; ?>" required>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
			<?php include 'templates/main-footer.php' ?>		
		</div>
	</div>
	<?php include 'templates/footer.php' ?>
<script>
function editResident(that) {
    var id = $(that).attr('data-id');
    var pic = $(that).attr('data-img');
    var nat_id = $(that).attr('data-national');
    var fname = $(that).attr('data-fname');
    var mname = $(that).attr('data-mname');
    var lname = $(that).attr('data-lname');
    var address = $(that).attr('data-address');
    var bplace = $(that).attr('data-bplace');
    var bdate = $(that).attr('data-bdate');
    var age = $(that).attr('data-age');
    var cstatus = $(that).attr('data-cstatus');
    var gender = $(that).attr('data-gender');
    var purok = $(that).attr('data-purok');
    var vstatus = $(that).attr('data-vstatus');
    var email = $(that).attr('data-email');
    var number = $(that).attr('data-number');
    var taxno = $(that).attr('data-taxno');
    var citi = $(that).attr('data-citi');
    var occu = $(that).attr('data-occu');
    var dead = $(that).attr('data-dead');
    var remarks = $(that).attr('data-remarks');
    var purpose = $(that).attr('data-purpose');

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

    if (dead == 1) {
        $("#alive").prop("checked", true);
    } else {
        $("#dead").prop("checked", true);
    }

    var str = pic;
    var n = str.includes("data:image");
    if (!n) {
        pic = 'assets/uploads/resident_profile/' + pic;
    }
    $('#image').attr('src', pic);
}
    </script>
</body>
</html>