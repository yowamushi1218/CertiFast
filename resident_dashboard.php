<?php 
include 'server/db_connection.php';
if (!isset($_SESSION["fullname"])) {
    header("Location: login.php");
    exit;
}
$fullname = $_SESSION["fullname"];
$query = "SELECT *,p.cert_id,p.status FROM tblresident_requested AS p JOIN tbl_user_resident AS u ON p.resident_name=u.fullname WHERE u.fullname=? AND p.status IN ('on hold')";       

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $fullname);
$stmt->execute();
$result = $stmt->get_result();

$resident = array();

while ($row = $result->fetch_assoc()) {
    $status = $row['status'];
    $statusBadge = '';

    if ($status == 'on hold') {
        $statusBadge = '<span class="badge badge-warning btn-sm">On Hold</span>';
    } elseif ($status == 'approved') {
        $statusBadge = '<span class="badge badge-success btn-sm">Approved</span>';
    } elseif ($status == 'rejected') {
        $statusBadge = '<span class="badge badge-danger btn-sm">Rejected</span>';
    }

    $resident[] = $row;
}
$stmt->close();

?>
<?php
include 'model/requested_status.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>  
	<?php include 'templates/header.php' ?>
	<title>Certifast Portal</title>
</head>
  <body>
  <?php include 'templates/loading_screen.php' ?>
	<div class="wrapper">
		<?php include 'templates/main-header-resident.php' ?>
		<?php include 'templates/sidebar-resident.php' ?>
			<div class="main-panel">
				<div class="content">
					<section class="main-content">
						<div class="container">
							<h1 class="text-center fw-bold mb-4" style="font-size: 400%;">Dashboard</h1>
							<div class="row mt-2">
								<div class="col-12 col-sm-6 col-md-4">
									<div class="card card-stats card card-round">
										<div class="card-body">
											<div class="row">
												<div class="col-4">
													<div class="icon-big text-center">
														<i class="fas fa-user-clock fa-2x" style="color: gray;"></i>
													</div>
												</div>
												<div class="col-2 col-stats">
												</div>
												<div class="col-2 col-stats">
													<div class="numbers mt-2">
														<h2 class="text-uppercase" style="font-size: 16px;">Pending</h2>
														<h3 class="fw-bold" style="font-size: 30px; color: #C77C8D;"><?= number_format($row['pending']) ?></h3>
													</div>
												</div>
											</div>
											<div class="card-body">
												<a href="purok_info.php?state=purok" class="card-link text" style="color: gray;"></a>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-6 col-md-4">
									<div class="card card-stats card card-round" >
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
														<h2 class="text-uppercase" style="font-size: 16px;">Approved</h2>
														<h3 class="fw-bold" style="font-size: 30px; color: #C77C8D;"><?= number_format($row['approved'])?></h3>
														</div>
													</div>
												</div>
												<div class="card-body">
													<a href="revenue.php?state=revenue" class="card-link text" style="color: gray;"></a>
												</div>
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-6 col-md-4">
										<div class="card card-stats card-round">
											<div class="card-body">
												<div class="row">
													<div class="col-4">
														<div class="icon-big text-center">
															<i class="fas fa-user-alt-slash fa-2x" style="color: gray;"></i>
														</div>
													</div>
													<div class="col-2 col-stats">
													</div>
													<div class="col-2 col-stats">
														<div class="numbers mt-2">
															<h2 class="text-uppercase" style="font-size: 16px;">Rejected</h2>
															<h3 class="fw-bold text-uppercase" style="font-size: 30px; color: #C77C8D;"><?= number_format($row['rejected']) ?></h3>
														</div>
													</div>
												</div>
												<div class="card-body">
													<a href="purok_info.php?state=purok" class="card-link text" style="color: gray;"></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<div class="container">
						<?php if(isset($_SESSION['message'])): ?>
                                <div class="alert alert-<?php echo $_SESSION['success']; ?> <?= $_SESSION['success']=='danger' ? 'bg-danger text-light' : null ?>" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <?php echo $_SESSION['message']; ?>
                                </div>
                            <?php unset($_SESSION['message']); ?>
                            <?php endif ?>
							<div class="content">
								<div class="card">
									<div class="card-header">
										<h4 class="card-title"></h4>
										<div class="card-head-row">
                                            <div class="card-title fw-bold"></div>
                                            <?php if(isset($_SESSION['fullname'])):?>
                                                <div class="card-tools">
                                                    <a href="resident_certificates.php" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-eye"></i>
                                                        View All
                                                    </a>
                                                </div>
                                            <?php endif?>
                                        </div> 
									</div>
									<div class="card-body">
										<div class="table-responsive mt-3">
											<table class="residenttable table">
												<thead>
													<tr>
														<th scope="col">Date Applied</th>
														<th scope="col">Name of Resident</th>
														<th scope="col">Name of Certificate</th>
														<th class="text-center" scope="col">Status</th>
													</tr>
												</thead>
												<tbody>
												<?php if (!empty($resident)): ?>
													<?php foreach ($resident as $row): ?> 
														<tr>
															<td><?= $row['date_applied'] ?></td>
															<td><?= $row['resident_name'] ?></td>
															<td><?= ucwords($row['certificate_name']) ?></td>
															<td class="text-center status-cell"><?= $row['residency_badge'] = $statusBadge; ?></td>
														</tr>
														<?php endforeach ?>
															<?php else: ?>
														<tr>
															<td colspan="4" class="text-center">No data history available</td>
														</tr>
													<?php endif ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<?php include 'templates/main-footer.php' ?>
			</div>
  		</div>
	<?php include 'templates/footer.php' ?>
</body>
</html>
