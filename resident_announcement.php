<?php include 'server/db_connection.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include 'templates/header.php' ?>                  
	<title>Announcement</title>
</head>
<body>
	<?php include 'templates/loading_screen.php' ?>
	<div class="wrapper">
    <?php include 'templates/main-header-resident.php' ?>
		<?php include 'templates/sidebar-resident.php' ?>
		<div class="main-panel">
			<div class="content">
                    <div>
                        <h1 class="text-center fw-bold mt-5" style="font-size: 300%;">Announcement</h1>
                    </div>
					<div class="page-inner mt-2">
						<?php if(isset($_SESSION['message'])): ?>
								<div class="alert alert-<?= $_SESSION['success']; ?> <?= $_SESSION['success']=='danger' ? 'bg-danger text-light' : null ?>" role="alert">
									<?php echo $_SESSION['message']; ?>
								</div>
							<?php unset($_SESSION['message']); ?>
						<?php endif ?>
					</div>
                    <div class="page-inner">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Announcement</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive mt-3">
                                            <table id="revenuetable" class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Recipient</th>
                                                        <th scope="col">Details</th>
                                                        <th scope="col">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $user_name = $_SESSION['fullname'];

                                                    $paymentHistoryQuery = "SELECT p.date, u.fullname, p.details, p.amounts
                                                    FROM tblpayments AS p
                                                    INNER JOIN tbl_user_resident AS u ON p.name = u.fullname
                                                    WHERE u.fullname = ?
                                                    ORDER BY p.date DESC;";

                                                    $stmt = $conn->prepare($paymentHistoryQuery);
                                                    $stmt->bind_param("s", $user_name);
                                                    $stmt->execute();
                                                    $paymentHistoryResult = $stmt->get_result();

                                                    if ($paymentHistoryResult->num_rows > 0) {
                                                        while ($row = $paymentHistoryResult->fetch_assoc()) {
                                                            ?>
                                                            <tr>
                                                                <td><?= $row['date'] ?></td>
                                                                <td><?= $row['fullname'] ?></td>
                                                                <td><?= $row['details'] ?></td>
                                                                <td><i class="fas fa-peso-sign"></i> <?= number_format($row['amounts'], 2) ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="4" class="text-center">No announcement available</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php include 'templates/main-footer.php' ?>
	        </div>
	    </div>
	<?php include 'templates/footer.php' ?>
</body>
</html>