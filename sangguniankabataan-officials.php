<?php
    include 'server/dbconnect.php';

    $query1 = "SELECT * FROM tblbrgy_info WHERE id='1'";
    $result1 = $conn->query($query1)->fetch_assoc();

    $query = "SELECT tblofficials.fullname, tblofficials.picture, tblposition.position FROM tblofficials JOIN tblposition ON tblofficials.position = tblposition.id WHERE tblposition.position IN ('SK Chairman','SK Kagawad') AND `status`='Active'";
    $result = $conn->query($query);

    $rows = array();
    while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>CertiFast Portal</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <link href="Homepage/assets/img/favicon.png" rel="icon">
  <link href="Homepage/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <link href="Homepage/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="Homepage/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="Homepage/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="Homepage/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="Homepage/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="Homepage/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="Homepage/assets/css/style.css" rel="stylesheet">
</head>

<body>

  <section id="topbar" class="d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope d-flex align-items-center ms-4"><a href="mailto:losamigosdavaocity.gov@gmail.com"> <?= ucwords($result1['brgy_email']) ?></a></i>
        <i class="bi bi-telephone d-flex align-items-center ms-4"> <span><?= ucwords($result1['contact_number']) ?></span></i>
      </div>
    </div>
  </section>

  <header id="header" class="d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">
      <h1 class="logo"><a href="index.php"><img src="Homepage/assets/img/title-logo.png" alt="title-logo"></a></h1>
      <nav id="navbar" class="navbar">
        <ul>
            <li><a class="nav-link scrollto active" href="index.php#hero">Home</a></li>
            <li><a class="nav-link scrollto" href="index.php#about">About</a></li>
            <li><a class="nav-link scrollto" href="index.php#services">Certificates</a></li>
            <li class="dropdown"><a href="#"><span>Officials</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a class="nav-link scrollto " href="barangay-officials.php">Barangay Officials</a></li>
              <li><a class="nav-link scrollto " href="sangguniankabataan-officials.php">SK Officials</a></li>
            </ul>
          </li>
          <li><a href="login.php" class="btn-login"> Login</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
    </div>
  </header>

  <main id="main" data-aos="fade-up">
    <section id="team" class="team section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h3>Our Hardworking <span>Sanggunian Kabataan Officials</span></h3><br>
          <span>Thank you for elected us, as your officials of local governance in our barangay, working tirelessly to address community needs and ensure the well-being of residents.</span>
        </div>

        <div class="row">
          <?php foreach ($rows as $row): ?>
            <div class="col-lg-3 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
              <div class="member">
                <div class="member-img">
                  <img src="assets/uploads/officials_profile/<?= $row['picture'] ?>" class="img-fluid" alt="">
                </div>
                <div class="member-info">
                  <h4><?= ucwords($row['fullname']) ?></h4>
                  <h6><i><?= ucwords($row['position']) ?></i></h6>
                </div>
              </div>
            </div>
            <?php endforeach ?>
        </div>
      </div>
    </section>
  </main>

  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-6 footer-contact">
            <h3>CertiFast</h3>
            <p><?= ucwords($result1['brgy_address']) ?> <br> <br>
              <strong>Telephone:</strong> <?= ucwords($result1['contact_number']) ?><br>
              <strong>Email:</strong> <?= ucwords($result1['brgy_email']) ?><br>
            </p>
          </div>
          <div class="col-lg-3 col-md-6 footer-links">
            <h4>CertiFast</h4>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">About us</a></li>
              <li><a href="#">Services</a></li>
              <li><a href="#">Barangay Officials</a></li>
              <li><a href="#">Sk Officials</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Services</h4>
            <ul>
              <li> <a href="#">Resident Registration</a></li>
              <li> <a href="#">Residency Certificate</a></li>
              <li> <a href="#">Barangay Clearance</a></li>
              <li> <a href="#">Barangay Indingency</a></li>
              <li> <a href="#">Business Permit</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Our Social Networks</h4>
            <p>For more information, please contact us directly to our social media.</p>
            <div class="social-links mt-3">
              <a href="https://www.facebook.com/profile.php?id=100064303345469" class="facebook"><i class="bx bxl-facebook"></i></a>
              <a href="https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=&cad=rja&uact=8&ved=2ahUKEwjI-Ma0x6b_AhWGft4KHc8sDiIQFnoECA8QAQ&url=https%3A%2F%2Fmail.google.com%2F%3F&usg=AOvVaw0UbLmQh5BLuX0lunN8sC9n" class="google-email"><i class="bx bxl-gmail"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container py-4">
      <div class="copyright">
        <?php $year = date("Y"); echo  $year . " &copy; <strong><span>Barangay Los Amigos - CertiFast Portal</span></strong>" ?> . All Rights Reserved
      </div>
    </div>
  </footer>

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script src="Homepage/assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="Homepage/assets/vendor/aos/aos.js"></script>
  <script src="Homepage/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="Homepage/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="Homepage/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="Homepage/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="Homepage/assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="Homepage/assets/vendor/php-email-form/validate.js"></script>
  <script src="Homepage/assets/js/main.js"></script>

</body>

</html>