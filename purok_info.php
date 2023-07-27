<?php include 'server/server.php' ?>
<?php
    $state = $_GET['state'];
    $purok = array();

    if($state=='purok'){
        $query = "SELECT * FROM tblpurok ORDER BY purok";
        $result = $conn->query($query);

        while($row = $result->fetch_assoc()){
            $purok[] = $row; 
        }
    }else{
        $query = "SELECT * FROM tblprecinct";
        $result = $conn->query($query);

        while($row = $result->fetch_assoc()){
            $purok[] = $row; 
        }
    }
    
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
		<div class="main-panel mt-5">
			<div class="container">
				<div class="panel-header" style = "background-color: #E42654">
					<div class="page-inner">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white fw-bold"><?= $state=='purok' ? 'Purok' : 'Precint' ?></h2>
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
										<div class="card-title"><?= $state=='purok' ? 'Purok Information' : 'Precint Information' ?></div>
									</div>
								</div>
								<div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr class="text-center">
                                                    <th scope="col">No.</th>
                                                    <th scope="col"><?= $state=='purok' ? 'Purok Name' : 'Precint No.' ?></th>
                                                    <th scope="col">No. of Residents</th>
                                                    <th scope="col">No. of Households</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(!empty($purok)): ?>
                                                    <?php $no=1; foreach($purok as $row): ?>
                                                    <tr class="text-center">
                                                        <td><?= $no ?></td>
                                                        <td><?= $state!='purok' ? $row['precinct'] : $row['purok'] ?></td>
                                                        <td><?= $row['total_residents'] ?></td>
                                                        <td><?= $row['total_households'] ?></td>
                                                    </tr>
                                                    <?php $no++; endforeach ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">No Available Data</td>
                                                    </tr>
                                                <?php endif ?>
                                            </tbody>
                                        </table>
                                    </div>
								</div>
							</div>
						</div>
                        <div class="col-md-3">
                            <div class="card card-stats card-round" style="<?= $state=='purok' ? 'background-color:#E42654; color:#fff' : 'background-color:#E42654; color:#fff' ?>">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-3">
                                                <div class="icon-big text-center">
                                                    <i class="fas fa-route fa-2x"></i>
                                                </div>
                                            </div>
                                            <div class="col-3 col-stats">
                                            </div>
                                            <div class="col-6 col-stats ">
                                                <div class="numbers">
                                                    <p class="card-category text-light"><?= $state=='purok' ? 'Total Purok' : 'Total Precint' ?></p>
                                                    <h4 class="card-title text-light"><?= number_format(count($purok)) ?></h4>
                                                </div>
                                            </div>
                                        </div>
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