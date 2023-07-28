<?php
    session_start();
    if (isset($_SESSION['username'])) {
        header('Location: email-verify-code.php');
    }
    if (isset($_SESSION['fullname'])) {
        header('Location: email-verify-code.php');
    }
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <title>CertiFast Portal</title>
        <link rel="stylesheet" href="Homepage/vendor-login/css/password-style.css"/>
        <link rel="icon" href="Homepage/vendor-login/images/CFLogo2.ico" type="image/x-icon"/>
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>                        
    </head>
    <body>
    <div class="container">
        <section class="container forms">
            <div class="form">
                <div class="form-content">
                    <form id="myForm" method="POST" action="model/email_verified.php">
                        <h2 class="text-center">Verification Code</h2>
                        <p class="text-center">We sent you a code, please type the code to verified your account.</p>
                        <?php if (isset($_SESSION['message']) && isset($_SESSION['success']) && isset($_SESSION['form']) && $_SESSION['form'] == 'login'): ?>
                        <div class="modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    </div>                                  
                                    <div class="modal-body">
                                        <?php if ($_SESSION['success'] == 'danger'): ?>
                                            <h5 class="modal-title text-center w-100">
                                                <i class="fas fa-exclamation-triangle fa-3x d-block mx-auto" style="color: #d64242"></i>
                                            </h5>
                                        <?php elseif ($_SESSION['success'] == 'success'): ?>
                                            <h5 class="modal-title text-center w-100">
                                                <i class="fas fa-check-circle fa-3x d-block mx-auto" style="color: #34c240"></i>
                                            </h5>
                                        <?php endif; ?>
                                        <br>
                                        <p class="text-center" style="font-size: 24px; font-weight: bold;"><?php echo $_SESSION['message']; ?></p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" id="closeModalButton">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php unset($_SESSION['message']); ?>
                    <?php endif; ?>
                        <input type="hidden" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" class="input" readonly>
                        <div class="field input-field">
                            <input type="text" name="verification_code" autocomplete="off" placeholder="Verification Code" class="input">
                        </div>
                        <div class="field button-field">
                            <button type="submit" value="submit" class="far fa-paper-plane text-center" style='font-size:20px'> Confirm</button>
                        </div>
                    </form>
                </div>
                <footer class="footer mt-3">
                    <div class="container-fluid">
                        <div class="copyright ml-auto text-center" style="font-size: 14px;">
                            <?php  $year = date("Y"); echo  $year . " &copy Barangay Los Amigos - CertiFast Portal" ?>
                        </div>				
                    </div>
                </footer>
            </div>
        </section>
    </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var closeModalButton = document.getElementById('closeModalButton');
            closeModalButton.addEventListener('click', function() {
                var modal = document.getElementById('loginModal');
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
                modal.style.display = 'none';
            });
            var modal = document.getElementById('loginModal');
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            modal.style.display = 'block';
        });
    </script>
    <script>
    document.addEventListener('mousedown', function(event) {
        if (event.which === 2 || event.which === 3) {
            history.back();
        }
    });
</script>

    </body>
</html>