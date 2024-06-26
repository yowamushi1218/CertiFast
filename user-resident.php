<?php include 'server/server.php' ?>
<?php 
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'staff') {
        $off_q = "SELECT * FROM tbl_user_resident WHERE `account_status`='verified'";
    } else {
        $off_q = "SELECT * FROM tbl_user_resident ORDER BY `created_at` ASC";
    }
} else {
    $off_q = "SELECT * FROM tbl_user_resident WHERE `account_status`='verified'";
}

$result = $conn->query($off_q);

$users = array();
while ($row = $result->fetch_assoc()) {
    $row['account_badge'] = $row['account_status'] == 'verified' ? '<span class="badge badge-info">verified</span>' : '<span class="badge badge-warning">unverified</span>';
    $row['active_badge'] = $row['is_active'] == 'active' ? '<span class="badge badge-primary">active</span>' : '<span class="badge badge-danger">inactive</span>';
    $users[] = $row;
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
		<div class="main-panel">
			<div class="content">
				<div class="panel-header">
					<div class="page-inner">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-black fw-bold" style = "font-size: 300%;">Resident Account</h2>
							</div>
						</div>
					</div>
				</div>
				<div class="page-inner">
					<div class="row mt--2">
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
								<div class="card-header">
									<div class="card-head-row">
                                        <div class="card-title"></div>
                                            <?php if(isset($_SESSION['username'])):?>
                                            <div class="card-tools">
                                                <a class="btn btn-light btn-border btn-sm dropdown-toggle" type="button" id="filterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Filter
                                                </a>
                                                <div class="dropdown-menu mt-3 mr-3" aria-labelledby="filterDropdown">
                                                    <div class="dropdown-item">
                                                        <label>Status</label>
                                                        <select class="form-control" id="filterStatus" name="status_name" onclick="event.stopPropagation();">
                                                            <option value="">All</option>
                                                            <option value="active">Active</option>
                                                            <option value="inactive">Inactive</option>
                                                        </select>
                                                    </div>
                                                    <div class="dropdown-item">
                                                        <label>Account</label>
                                                        <select class="form-control" id="filterCert" name="cert_name" onclick="event.stopPropagation();">
                                                            <option value="">All</option>
                                                            <option value="verified">Verified</option>
                                                            <option value="unverified">Unverified</option>
                                                        </select>
                                                    </div>
                                                    <div class="dropdown-item">
                                                        <label>From Date:</label>
                                                        <input type="date" class="form-control datepicker" id="fromDate" placeholder="Select date range">
                                                    </div>
                                                    <div class="dropdown-item">
                                                        <label>To Date:</label>
                                                        <input type="date" class="form-control datepicker" id="toDate" placeholder="Select date range">
                                                    </div>
                                                    <div class="dropdown-item">
                                                        <button type="button" id="clearFilters" class="form-control btn btn-outline-primary">Clear Filters</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif ?>
										</div>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table id="residenttable" class="table">
												<thead>
													<tr class="text-center">
                                                        <th scope="col">Date</th>
														<th scope="col">Name</th>
														<th scope="col">Email</th>
                                                        <th scope="col">Purok</th>
                                                        <th scope="col">Address</th>
														<?php if(isset($_SESSION['username'])):?>
															<?php if($_SESSION['role']=='administrator'):?>
															<th class="text-center" scope="col">Account</th>
                                                            <th class="text-center" scope="col">Status</th>
															<?php endif ?>
														<?php endif?>
                                                        <th scope="col">Reasons</th>
														<?php if(isset($_SESSION['username'])):?>
															<?php if($_SESSION['role']=='administrator'):?>
															<?php endif ?>
															<th class="text-center">Action</th>
														<?php endif?>
													</tr>
												</thead>
												<tbody>
													<?php if(!empty($users)): ?>
														<?php $no=1; foreach($users as $row): ?>
															<tr class="text-center">
                                                                <td><?= $row['created_at'] ?></td>
																<td><?= $row['fullname'] ?></td>
																<td><?= $row['user_email'] ?></td>
                                                                <td><?= $row['purok'] ?></td>
                                                                <td><?= $row['address'] ?></td>
																<td class="text-center"><?= $row['account_badge'] ?></td>	
                                                                <td class="text-center"><?= $row['active_badge'] ?></td>
                                                                <td><?= $row['reason'] ?></td>                                                               														
																<td class="text-center">
																	<div class="form-button-action">
                                                                        <a type="button" class="btn btn-link btn-primary" data-toggle="modal" href="" data-target="#DeactivateDeleteModal<?= $row['id'] ?>" data-original-title="Edit">
																			<i class="fa-solid fa-edit"></i>
																		</a>
																		<a type="button" class="btn btn-link btn-danger" data-toggle="modal" data-target="#confirmDeleteModal<?= $row['id'] ?>" data-original-title="Remove">
																			<i class="fa-solid fa-trash"></i>
																		</a>
																	</div>
																</td>													
															</tr>
														<?php endforeach ?>
													<?php else: ?>
														<tr>
															<td colspan="8" class="text-center">No Available Data</td>
														</tr>
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
			<?php include 'templates/main-footer.php' ?>
		</div>
	</div>
	<?php include 'templates/footer.php' ?>
	<?php foreach ($users as $row) { ?>
        <div class="modal fade" id="confirmDeleteModal<?= $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Message</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center" style="font-size: 15px;">
                        Are you certain you want to permanently delete this user account ?
                    </div>
                    <div class="modal-footer mt-2 d-flex justify-content-center">
                        <form method="post" action="model/remove_user_resident.php">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <button type="button" class="btn btn-danger text-center">No</button>
                            <button type="submit" class="btn btn-primary text-center">Yes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
<?php foreach ($users as $row) { ?>
<div id="DeactivateDeleteModal<?= $row['id'] ?>" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Deactivating user's account.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body">
                <form method="POST" action="model/deactivate_account.php">
                        <div class="col-md-12">
                            <div class="form-group">
                                <span>Select a reason to deactivate this user account.</span>
                            </div>
                            <div class="form-group">
                                <label>Reason's</label>
                                <select class="form-control" name="reason" id="reason" required>
                                    <option value="" disabled selected>Select a reason</option>
                                    <option value="problem solved">Problem Solved</option>
                                    <option value="take a break">Take a break</option>
                                    <option value="personal reasons">Personal reasons</option>
                                    <option value="privacy concern">Privacy concerns</option>
                                    <option value="transfer residency">Transfer Residency</option>
                                    <option value="someone has a case">This user has a file case</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" rows="5" placeholder="Additional comments" name="message" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Account Status</label>
                                <select class="form-control" name="is_active" id="is_active" required>
                                    <option value="" disabled selected>Select status account</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <!--<div class="form-group">
                                <label>Profiling Status</label>
                                <select class="form-control" name="status" id="status" required>
                                    <option value="" disabled selected>Select Status Profiling</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>-->
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <input type="hidden" name="email" value="<?= $row["user_email"] ?>">
                        <button class="btn btn-outline-secondary" type="button" data-dismiss="modal">
                            <span class="link-collapse">Cancel</span>
                        </button>
                        <button class="btn btn-primary mr-2" type="submit" name="submit">
                            <span class="link-collapse">Submit</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const filterStatus = document.getElementById("filterStatus");
  const filterCert = document.getElementById("filterCert");
  const fromDate = document.getElementById("fromDate");
  const toDate = document.getElementById("toDate");
  const clearFiltersBtn = document.getElementById("clearFilters");
  
  const tableRows = document.querySelectorAll("#residenttable tbody tr");
  
  function rowMatchesFilter(row) {
    const certValue = filterCert.value.toLowerCase();
    const statusValue = filterStatus.value.toLowerCase();
    const rowStatus = row.querySelector("td:nth-child(7)").textContent.toLowerCase();
    const rowCert = row.querySelector("td:nth-child(6)").textContent.toLowerCase();
    const rowDate = new Date(row.querySelector("td:nth-child(1)").textContent);
    const from = new Date(fromDate.value);
    const to = new Date(toDate.value);

    return (certValue === "" || rowCert.includes(certValue)) &&
            (statusValue === "" || rowStatus.includes(statusValue)) &&
           (isNaN(from) || rowDate >= from) &&
           (isNaN(to) || rowDate <= to);
  }
  
  function applyFilter() {
    tableRows.forEach(row => {
      const shouldDisplay = rowMatchesFilter(row);
      row.style.display = shouldDisplay ? "table-row" : "none";
    });
  }

  function clearFilters() {
    filterCert.value = "";
    filterStatus.value = "";
    fromDate.value = "";
    toDate.value = "";
    applyFilter();
  }
  filterStatus.addEventListener("change", applyFilter);
  filterCert.addEventListener("change", applyFilter);
  fromDate.addEventListener("change", applyFilter);
  toDate.addEventListener("change", applyFilter);
  clearFiltersBtn.addEventListener("click", clearFilters);
});
</script>
</body>
</html>