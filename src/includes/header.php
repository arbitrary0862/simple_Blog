
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php
if (isset($_SESSION['error'])){ ?>
    <div class="container d-flex justify-content-center mt-5">
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert" style="max-width: 300px;">
            <strong>錯誤！</strong> <?php echo htmlspecialchars($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php }
if (isset($_SESSION['success'])){ ?>
    <div class="container d-flex justify-content-center mt-5">
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert" style="max-width: 300px;">
            <strong>成功！</strong> <?php echo htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php }?>