<?php include 'server/server.php' ?>
<?php 
    $id = $_GET['id'];
	$query = "SELECT * FROM tblresident WHERE id='$id'";
    $result = $conn->query($query);
    $resident = $result->fetch_assoc();

    $query1 = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblposition.position AND `status`='Active'";
    $result1 = $conn->query($query1);
    $officials = array();
	while($row = $result1->fetch_assoc()){
		$officials[] = $row; 
	}

    $c = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblposition.position='Kapitan'";
    $captain = $conn->query($c)->fetch_assoc();
    $s = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblposition.position='Secretary'";
    $sec = $conn->query($s)->fetch_assoc();
    $t = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblposition.position='Treasurer'";
    $treasurer = $conn->query($t)->fetch_assoc();
    $skc = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblposition.position='SK Chairman'";
    $skchairman = $conn->query($skc)->fetch_assoc();
    $k1 = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblofficials.id='21'";
    $kagawad1 = $conn->query($k1)->fetch_assoc();
    $k2 = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblofficials.id='22'";
    $kagawad2 = $conn->query($k2)->fetch_assoc();
    $k3 = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblofficials.id='23'";
    $kagawad3 = $conn->query($k3)->fetch_assoc();
    $k4 = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblofficials.id='24'";
    $kagawad4 = $conn->query($k4)->fetch_assoc();
    $k5 = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblofficials.id='25'";
    $kagawad5 = $conn->query($k5)->fetch_assoc();
    $k6 = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblofficials.id='26'";
    $kagawad6 = $conn->query($k6)->fetch_assoc();
    $k7 = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblofficials.id='27'";
    $kagawad7 = $conn->query($k7)->fetch_assoc();
    $k8 = "SELECT * FROM tblofficials JOIN tblposition ON tblofficials.position=tblposition.id WHERE tblofficials.id='28'";
    $kagawad8 = $conn->query($k8)->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include 'templates/header.php' ?>
	<title>Certificate of Indigency</title>
    <style>
    .footer-content {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: forestgreen;
        color: white;
        padding: 5px;
        display: flex;
        justify-content: space-between;;
      }
      .footer-names {
        display: inline-block;
      } 
      .text-left {
        text-align: right;  
        margin-right: 2%;
        margin-top: 10px;     
      }

      ul, ol {
        list-style: none;
      }   
      .footer-names .fw-bold {
        margin-top: 10px;
        margin: 0px;
        padding: 0px;
        color: yellow;
    }
    /* Media query for laptops and computers */
    @media (min-width: 992px) {
        .footer-names {
            margin-bottom: 0;
        }

        .personal-info {
            margin-left: 150px;
        }

        .family-info {
            margin-left: 100px;
        }
    }

    /* Media query for phones */
    @media (max-width: 767px) {
        .footer-content {
            flex-direction: column;
        }
        
        .footer-names {
            margin-bottom: 20px;
        }

        .personal-info,
        .family-info {
            margin-left: 0;
        }
    }
    </style>
</head>
<body>
<?php include 'templates/loading_screen.php' ?>
	<div class="wrapper">
		<!-- Main Header -->
		<?php include 'templates/main-header.php' ?>
		<!-- End Main Header -->

		<!-- Sidebar -->
		<?php include 'templates/sidebar.php' ?>
		<!-- End Sidebar -->

		<div class="main-panel">
			<div class="content">
				<div class="panel-header" style = "background-color: #E42654">
					<div class="page-inner">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white fw-bold">Generate Certificate</h2>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner">
					<div class="row mt--2">
						<div class="col-md-12">
                            <?php if(isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?php echo $_SESSION['success']; ?> <?= $_SESSION['success']=='danger' ? 'bg-danger text-light' : null ?>" role="alert">
                                    <?php echo $_SESSION['message']; ?>
                                </div>
                            <?php unset($_SESSION['message']); ?>
                            <?php endif ?>
                            <div class="card">
								<div class="card-header">
									<div class="card-head-row">
										<div class="card-title">Certificate of Indigency</div>
										<div class="card-tools">
											<button class="btn btn-info btn-border btn-round btn-sm" onclick="printDiv('printThis')">
												<i class="fa fa-print"></i>
												Print Certificate
											</button>
										</div>
									</div>
								</div>
								<div class="card-body" id="printThis">
                                    <div class="card-header" style="background-color:forestgreen;">
                                        <div class="card-head">
                                            <div class="d-flex flex-wrap justify-content-around">
                                                <div class="text-center">
                                                    <img src="assets/uploads/<?= $city_logo ?>" class="img-fluid" width="150">
                                                </div>
                                                    <div class="text-center" style="color: white;">
                                                        <h2 class="mb-1">Republic of the Philippines</h2>
                                                        <h2 class="mb-1">City of <?= ucwords($province) ?></h2>
                                                        <h1 class="fw-bold mb-1"><?= ucwords($brgy) ?></i></h1>
                                                        <h3 class="mb-2"><?= ucwords($town) ?></h3>				
                                                        <h3><i class="fas fa-phone" style="color: yellow;"></i> <?= $number ?> &nbsp  <i class="fa fa-envelope" style="color: yellow;"></i> <?= $b_email ?></h3>
                                                    </div>
                                                <div class="text-center">
                                                    <img src="assets/uploads/<?= $brgy_logo ?>" class="img-fluid" width="150">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4" style="margin: 50px 10px 50px 50px;">
                                        <div class="col-md-12">
                                            <div class="text-center">
                                                <h1 class="mt-4 fw-bold mb-5" style="font-size:100px;">BARANGAY CERTIFICATION</h1>
                                            </div>
                                            <h2 class="mt-5 fw-bold">TO WHOM IT MAY CONCERN:</h2>
                                            <h2 class="mt-3" style="text-indent: 40px;">This is to certify that <span class="fw-bold" style="font-size:25px"><?= ucwords($resident['firstname'].' '.$resident['middlename'].' '.$resident['lastname']) ?></span>, 
                                            of legal age, and is a bona fide resident of <span class="fw-bold" style="font-size:20px"> Purok <?= ucwords($resident['purok']) ?></span>, <span class="fw-bold" style="font-size:20px"><?= ucwords($town) ?></span>, Davao City.
                                            <h2 class="mt-3" style="text-indent: 40px;">This further certifies that the above mentioned belongs to an indigent family in this Barangay.</h2>
                                            <h2 class="mt-3" style="text-indent: 40px;">This certification is issued upon the request of aforementioned for <span class="fw-bold" style="font-size:20px"><?= ucwords($resident['remarks']) ?></span> 
                                            or for whatever legal purpose/s that may serve her/him best. </h2> <h2 class="mt-3" style="text-indent: 40px;">Done this <span class="fw-bold" style="font-size:20px">
                                            <?= date('jS F, Y') ?></span> at <span class="fw-text" style="font-size:20px"><?= ucwords($town) ?></span>, Davao City.</h2>    
                                        </div>
                                        <div class="col-md-12" style="margin-top: 100px;">
                                            <div class="p-3 text-right mt-2" style="margin-bottom: 300px;">
                                                <h2 class="fw-bold mb-6"><u><?= ucwords($captain['fullname']) ?></u></h2>
                                                <p class="text-right mr-4">PUNONG BARANGAY</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="footer-content">
                                        <div class="footer-names text-left">                                                       
                                            <ul>
                                                <li><h1 class="fw-bold mb-6" style="margin-top: 90px;"><?= ucwords($captain['fullname']) ?></h1></li>
                                                <li><h6 class="text mr-5">PUNONG BARANGAY</h6></li>
                                            </ul>                                                                                                  
                                        </div>
                                        <div class="footer-names text-left">                                                        
                                            <ul>
                                                <h2 class="text-bold"><u>Barangay Kagawad</u></h2>
                                                <li><h3 class="fw-bold"><?= ucwords($kagawad1['fullname']) ?></h3></li>
                                                <li><h3 class="fw-bold"><?= ucwords($kagawad2['fullname']) ?></h3></li>
                                                <li><h3 class="fw-bold"><?= ucwords($kagawad3['fullname']) ?></h3></li>
                                                <li><h3 class="fw-bold"><?= ucwords($kagawad4['fullname']) ?></h3></li>
                                                <li><h3 class="fw-bold"><?= ucwords($kagawad5['fullname']) ?></h3></li>
                                                <li><h3 class="fw-bold"><?= ucwords($kagawad6['fullname']) ?></h3></li>
                                                <li><h3 class="fw-bold"><?= ucwords($kagawad7['fullname']) ?></h3></li>
                                            </ul>                                                       
                                        </div>
                                        <div class="footer-names text-left">                                                       
                                            <ul>
                                                <li><h3 class="fw-bold"><?= ucwords($kagawad8['fullname']) ?></h3></li>
                                                <li><h6 class="text">IPMR</h6></li>
                                                <li><h3 class="fw-bold"><?= ucwords($skchairman['fullname']) ?></h3></li>
                                                <li><h6 class="text">SK Chairman</h6></li>
                                                <li><h3 class="fw-bold"><?= ucwords($sec['fullname']) ?></h3></li>
                                                <li><h6 class="text">Barangay Secretary</h6></li>
                                                <li><h3 class="fw-bold"><?= ucwords($treasurer['fullname']) ?></h3></li>
                                                <li><h6 class="text">Barangay Treasurery</h6></li>
                                            </ul>                                                       
                                        </div>
                                    </div>                                                                                                                                                                            
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
            <!-- Modal -->
           <div class="modal fade" id="pment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Create Payment</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="model/save_pment.php" >
                                <div class="form-group">
                                    <label>Amount</label>
                                    <input type="number" class="form-control" name="amount" placeholder="Enter amount to pay" required>
                                </div>
                                <div class="form-group">
                                    <label>Date Issued</label>
                                    <input type="datetime" class="form-control" name="date" value="<?= date('Y-m-d H:i:s') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Payment Details(Optional)</label>
                                    <textarea class="form-control" placeholder="Enter Payment Details" name="details">Certificate of Indigency Payment</textarea>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" class="form-control" name="name" value="<?= ucwords($resident['firstname'].' '.$resident['middlename'].' '.$resident['lastname']) ?>">
                            <input type="hidden" name="email" value="<?= ucwords($resident['email']) ?>">
                            <button type="button" class="btn btn-danger" onclick="goBack()">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
			<!-- Main Footer -->
			<?php include 'templates/main-footer.php' ?>
			<!-- End Main Footer -->
			<?php if(!isset($_GET['closeModal'])){ ?>
            
                <script>
                    setTimeout(function(){ openModal(); }, 1000);
                </script>
            <?php } ?>
		</div>
		
	</div>
	<?php include 'templates/footer.php' ?>
    <script>
            function openModal(){
                $('#pment').modal('show');
            }
            function printDiv(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;
            }
    </script>
</body>
</html>