<?php include 'server/server.php' ?>
<?php 

	$query = "SELECT * FROM tblresident WHERE resident_type=1";
    $result = $conn->query($query);
	$total = $result->num_rows;

	$query1 = "SELECT * FROM tbl_users WHERE user_type='staff'";
    $result1 = $conn->query($query1);
	$staff = $result1->num_rows;

	$query2 = "SELECT * FROM tblresident WHERE gender='Female' AND resident_type=1";
    $result2 = $conn->query($query2);
	$female = $result2->num_rows;

	$query3 = "SELECT * FROM tblresident WHERE voterstatus='Yes' AND resident_type=1";
    $result3 = $conn->query($query3);
	$totalvoters = $result3->num_rows;

	$query4 = "SELECT * FROM tblresident WHERE voterstatus='No' AND resident_type=1";
	$non = $conn->query($query4)->num_rows;

	$query5 = "SELECT * FROM tblpurok";
	$purok = $conn->query($query5)->num_rows;

	$query8 = "SELECT SUM(amounts) as am FROM tblpayments";
	$revenue = $conn->query($query8)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include 'templates/header.php' ?>
	<title>Dashboard</title>
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
								<h2 class="text-white fw-bold">Dashboard</h2>
							</div>
						</div>
					</div>
				</div>
					<div class="page-inner mt-2">
						<?php if(isset($_SESSION['message'])): ?>
								<div class="alert alert-<?= $_SESSION['success']; ?> <?= $_SESSION['success']=='danger' ? 'bg-danger text-light' : null ?>" role="alert">
									<?php echo $_SESSION['message']; ?>
								</div>
							<?php unset($_SESSION['message']); ?>
						<?php endif ?>
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-header">
										<div class="card-head-row">
											<div class="card-title fw-bold"><h1><strong>Barangay Los Amigos</strong></h1></div>
										</div>
									</div>
									<div class="card-body">
										<!--<p><?= !empty($db_txt) ? $db_txt : 'Los Amigos is a barangay in Davao City.' ?></p>-->
										<div class="text-center">
											<img class="img-fluid" src="<?= !empty($db_img) ? 'assets/uploads/'.$db_img : 'assets/img/bg-abstract.png' ?>" />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="col-md-3">
									<div class="card card-stats card-round">
										<div class="card-body">
											<canvas id="myChart2" width="400" height="300">
											<script src="homepage/js/half-donut-chart.js"></script>
											</canvas>
										</div>
									</div>
								</div>
								<div class="col-md-9">
									<div class="card card-stats card-round">
										<div class="card-body">
											<canvas id="myChart3" width="800" max-width="1000">
												<script src="homepage/js/group-bar-chart.js"></script>
											</canvas>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="col-md-3">
									<div class="card card-stats card-round">
										<div class="card-body">
											<canvas id="myChart1" width="400" max-width="300">
												<script src="homepage/js/pie-chart.js"></script>
											</canvas>
										</div>
									</div>
								</div>
								<div class="col-md-9">
									<div class="card card-stats card-round">
										<div class="card-body">
											<canvas id="myChart" width="800" max-width="1000">
												<script src="homepage/js/bar-chart.js"></script>
											</canvas>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php if(isset($_SESSION['username']) && $_SESSION['role']=='administrator'):?>
						<?php endif ?>
					</div>
				</div>
				<!-- Main Footer -->
				<?php include 'templates/main-footer.php' ?>
				<!-- End Main Footer -->
		</div>
	</div>
	<?php include 'templates/footer.php' ?>
</body>
</html>