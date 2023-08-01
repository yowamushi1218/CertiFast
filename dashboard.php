<?php include 'server/server.php';
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}
$fullname = $_SESSION["username"];
$query = "SELECT *, r.id AS id 
          FROM tblresident AS r 
          JOIN tbl_user_admin AS a ON r.purok = a.purok 
          WHERE a.username = ? AND r.residency_status IN ('on hold', 'approved', 'rejected') 
          ORDER BY r.id DESC"; 

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $fullname);
$stmt->execute();
$result = $stmt->get_result();

$resident = array();
$approvedResidents = array();

while ($row = $result->fetch_assoc()) {
    $status = $row['residency_status'];
    $statusBadge = '';

    if ($status == 'on hold') {
        $statusBadge = '<span class="badge badge-warning">On Hold</span>';
    }elseif ($status == 'approved') {
        $statusBadge = '<span class="badge badge-success">Approved</span>';
    }elseif ($status == 'rejected') {
        $statusBadge = '<span class="badge badge-danger">Rejected</span>';
    }

    $row['residency_badge'] = $statusBadge;

    if ($status == 'on hold') {
        $resident[] = $row;
    }elseif ($status == 'approved') {
        $resident[] = $row;
    }elseif ($status == 'rejected') {
        $resident[] = $row;
    }
}
$stmt->close();

?>
<?php
include 'model/status.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include 'templates/header.php' ?>
	<title>CertiFast Portal</title>
</head>
<body>
    <div class="wrapper">
        <?php include 'templates/main-header.php' ?>
        <?php include 'templates/sidebar.php' ?>
        <div class="main-panel">
            <div class="content d-flex flex-column">
                <div class="panel-header d-flex flex-column mt-1">
                    <?php if (isset($_SESSION['username']) && ($_SESSION['role'] == 'staff' || $_SESSION['role'] == 'administrator')): ?>
                        <div class="container mt-3">
                            <h3 class="fw-bold text-black mb-4" style="font-size: 400%;">Overview</h3>
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <?php if (isset($_SESSION['message'])): ?>
                                        <div class="alert alert-<?= $_SESSION['success']; ?> <?= $_SESSION['success'] == 'danger' ? 'bg-danger text-light' : null ?>" role="alert">
                                            <?php echo $_SESSION['message']; ?>
                                        </div>
                                        <?php unset($_SESSION['message']); ?>
                                    <?php endif ?>
                                    <div class="row mb-3">
                                        <div class="col-sm-12 col-md-4 mb-2">
                                            <label for="fromDate">From:</label>
                                            <input type="date" class="form-control" id="fromDate" name="fromDate" value="<?php echo isset($_POST['fromDate']) ? htmlspecialchars($_POST['fromDate']) : date('Y-m-d'); ?>">
                                        </div>
                                        <div class="col-sm-12 col-md-4 mb-2">
                                            <label for="toDate">To:</label>
                                            <input type="date" class="form-control" id="toDate" name="toDate" value="<?php echo isset($_POST['toDate']) ? htmlspecialchars($_POST['toDate']) : date('Y-m-d'); ?>">
                                        </div>
                                        <div class="col-sm-12 col-md-4 mb-2">
                                            <label for="dateType">Date Type:</label>
                                            <select class="form-control" id="dateType" name="dateType">
                                                <option value="weekly" <?php if (isset($_POST['dateType']) && $_POST['dateType'] === 'weekly') echo 'selected'; ?>>By Week</option>
                                                <option value="monthly" <?php if (isset($_POST['dateType']) && $_POST['dateType'] === 'monthly') echo 'selected'; ?>>By Month</option>
                                                <option value="yearly" <?php if (isset($_POST['dateType']) && $_POST['dateType'] === 'yearly') echo 'selected'; ?>>By Year</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-12 col-md-4 mb-2">
                                            <label for="documentType">Document Type:</label>
                                            <select class="form-control" id="documentType" name="documentType">
                                                <option value="All" <?php if (isset($_POST['documentType']) && $_POST['documentType'] === 'All') echo 'selected'; ?>>All</option>
                                                <option value="Barangay Clearance" <?php if (isset($_POST['documentType']) && $_POST['documentType'] === 'Barangay Clearance') echo 'selected'; ?>>Barangay Clearance</option>
                                                <option value="Certificate of Residency" <?php if (isset($_POST['documentType']) && $_POST['documentType'] === 'Certificate of Residency') echo 'selected'; ?>>Certificate of Residency</option>
                                                <option value="Certificate of Indigency" <?php if (isset($_POST['documentType']) && $_POST['documentType'] === 'Certificate of Indigency') echo 'selected'; ?>>Certificate of Indigency</option>
                                                <option value="Business Permit" <?php if (isset($_POST['documentType']) && $_POST['documentType'] === 'Business Permit') echo 'selected'; ?>>Business Permit</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-6 mt-3">
                                            <button type="submit" class="btn btn-primary" id="applyFilterBtn">Apply Filter</button>
                                            <button type="button" class="btn btn-danger" id="pdfExportBtn">Export</button>
                                        </div>
                                    </div>
                                </form>
                                <div class="row md-5">
                                    <div class="col-md-12">
                                        <div class="chart-wrapper">
                                            <?php include 'model/chart.php' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php if(isset($_SESSION['username']) && $_SESSION['role']=='purok leader'):?>
                        <div class="container">
                        <div class="form">
                            <h1 class="text-left fw-bold ml-1 mb-2 mt-5" style="font-size: 400%;">Purok Dashboard</h1>
                        </div>
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
                                                    <h3 class="fw-bold" style="font-size: 30px; color: #C77C8D;"><?= number_format($pendingCount) ?></h3>
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
                                                    <h3 class="fw-bold" style="font-size: 30px; color: #C77C8D;"><?= number_format($approvedCount)?></h3>
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
                                                        <h3 class="fw-bold text-uppercase" style="font-size: 30px; color: #C77C8D;"><?= number_format($rejectedCount) ?></h3>
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
                            <div class="container">
                                <?php if(isset($_SESSION['message'])): ?>
        								<div class="alert alert-<?= $_SESSION['success']; ?> <?= $_SESSION['success']=='danger' ? 'bg-danger text-light' : null ?>" role="alert">
        									<?php echo $_SESSION['message']; ?>
        								</div>
        							<?php unset($_SESSION['message']); ?>
        						<?php endif ?>
                                <div class="card">
                                    <div class="card-header">
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="residenttable" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Fullname</th>
                                                            <th scope="col">Birthdate</th>
                                                            <th scope="col">Email</th>
                                                            <th scope="col">Purok</th>
                                                            <th scope="col">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>                                           
                                                    <?php if (!empty($resident)): ?>
                                                        <?php $no = 1; foreach ($resident as $row): ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="avatar avatar-xs ml-3">
                                                                        <img src="<?= preg_match('/data:image/i', $row['picture']) ? $row['picture'] : 'assets/uploads/resident_profile/'.$row['picture'] ?>" alt="Resident Profile" class="avatar-img rounded-circle">
                                                                    </div>
                                                                    <?= ucwords($row['lastname'].', '.$row['firstname'].' '.$row['middlename']) ?>
                                                                </td>
                                                                <td><?= $row['birthdate'] ?></td>
                                                                <td><?= $row['email'] ?></td>
                                                                <td><?= $row['purok'] ?></td>
                                                                <td class="text-center"><?= $row['residency_badge'] ?></td>
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
                    <?php endif ?>
                    </div>
               </div>
            <?php include 'templates/main-footer.php' ?>
        </div>
    </div>
<?php include 'templates/footer.php' ?>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
	<script>
    document.getElementById("pdfExportBtn").addEventListener("click", function () {
      var doc = new jsPDF();
      var chartRow = document.getElementById("chartRow");
      var fromDate = document.getElementById("fromDate").value;
      var toDate = document.getElementById("toDate").value;
      var documentType = document.getElementById("documentType").value;

      var title = "Overview Chart Visualization Reports";
      doc.setFontSize(18);
      doc.text(title, 10, 10);

      var currentDate = new Date().toLocaleDateString();
      doc.setFontSize(12);
      doc.text("Latest Date: " + currentDate, 10, 20);
      doc.text("Date: " + fromDate + " to " + toDate, 10, 30);
      doc.text("Document Type: " + documentType, 10, 40);

      var width = 180;
      var height = 120;

      html2canvas(chartRow, { scale: 2 }).then(function (canvas) {
        var imgData = canvas.toDataURL("image/png");
        doc.addImage(imgData, "PNG", 10, 50, width, height);

        doc.save("dashboard-chart.pdf");
      });
    });
</script>
</body>
</html>