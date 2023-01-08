<h1 class="text-center text-info bg-gradient fw-bolder name-font">Update Your Profile</h1>
<hr class="w-50 m-auto  mb-5">

<div class="container-fluid">
    <?php if (isset($_SESSION['backend_validation_error']) && !empty($_SESSION['backend_validation_error'])) : ?>
        <div class="alert alert-danger w-50" role="alert"><?= $_SESSION['backend_validation_error'] ?></div><?php endif; ?>
    <?php $_SESSION['backend_validation_error'] = null; ?>
    <div class="row align-items-center">
        <form action="/profile/update" method="POST" enctype="multipart/form-data">
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="display-name" class="form-label text-info">Display Name</label>
                <input type="text" class="form-control shadow" id="display-name" name="display_name" value="<?= $data->user->display_name ?>" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="user-email" class="form-label text-info">Email</label>
                <input type="email" class="form-control shadow" id="user-email" name="email" value="<?= $data->user->email ?>" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="user-username" class="form-label text-info">Username</label>
                <input type="text" class="form-control shadow" id="username-email" name="username" value="<?= $data->user->username ?>" required>
            </div>
            <div class="form-group">
                <label class="d-block pb-1 text-info" for="user-img">Photo</label>
                <input name="image" type="file" class="form-control-file" id="user-img" accept="image/*">
                <?php if (!is_null($data->user->image)) : ?>
                    <p class="text-muted mt-2">Current Photo:</p>
                    <img src="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] ?>/resources/uploads/<?= $data->user->image ?>" alt="User-img" class="w-25 rounded-3 img-mh img-fluid img-thumbnail">
                <?php endif; ?>
                <p class="text-muted">Allowed Images (.JPG, .JPEG, .PNG, .RAW, .WEBP) <span class="fs-6">Max size 1 MB</span></p>
                <?php if (!empty($_GET['error']) && isset($_GET['error'])) : ?><!-- GET error msg -->
                    <div class="alert alert-danger w-50 m-auto" role="alert"><?= $_GET['error'] ?></div><?php endif; ?>
            </div>
            <button type="submit" class="btn btn-warning mt-4"><i class="fas fa-user-edit"></i> Update</button>
            <a href="/profile" class="btn btn-outline-danger ms-3 mt-4"><i class="fa fa-times"></i> Cancel</a>

        </form>
    </div>
</div>