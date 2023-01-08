<h1 class="text-center text-info bg-gradient fw-bolder name-font">Update User Info</h1>
<hr class="w-50 m-auto  mb-5">

<div class="container-fluid">
    <?php if (isset($_SESSION['backend_validation_error']) && !empty($_SESSION['backend_validation_error'])) : ?>
        <div class="alert alert-danger w-50" role="alert"><?= $_SESSION['backend_validation_error'] ?></div><?php endif; ?>
    <?php $_SESSION['backend_validation_error'] = null; ?>
    <div class="row align-items-center">
        <form action="/users/update" method="POST">

            <input type="hidden" name="id" value="<?= $data->user->id ?>">
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="display-name" class="form-label text-info">Display Name</label>
                <input type="text" class="form-control shadow" id="display-name" name="display_name" value="<?= $data->user->display_name ?>" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0 ">
                <label for="user-email" class="form-label text-info">Email</label>
                <input type="email" class="form-control shadow" id="user-email" name="email" value="<?= $data->user->email ?>" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="user-username" class="form-label text-info">Username</label>
                <input type="text" class="form-control shadow" id="username-email" name="username" value="<?= $data->user->username ?>" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="user-role" class="form-label text-info ">Role</label>
                <select class="form-select shadow" aria-label="Role" name="role">
                    <option value="admin">Admin</option>
                    <option value="procurement">Procurement Manager</option>
                    <option value="seller">Seller</option>
                    <option value="accountant">Accountant</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning mt-4"><i class="fas fa-user-edit"></i> Update</button>
            <a href="/user?id=<?= $data->user->id ?>" class="btn btn-outline-danger ms-3 mt-4"><i class="fa fa-times"></i> Cancel</a>

        </form>

    </div>
</div>