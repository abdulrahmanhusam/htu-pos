<h1 class="text-center text-info bg-gradient fw-bolder name-font">Create User</h1>
<hr class="w-50 m-auto  mb-5">

<div class="container-fluid">
    <?php if (isset($_SESSION['backend_validation_error']) && !empty($_SESSION['backend_validation_error'])) : ?>
        <div class="alert alert-danger w-50" role="alert"><?= $_SESSION['backend_validation_error'] ?></div><?php endif; ?>
    <?php $_SESSION['backend_validation_error'] = null; ?>
    <div class="row align-items-center">
        <form action="/users/store" method="POST">
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="display-name" class="form-label text-info">Display Name</label>
                <input type="text" class="form-control shadow" id="display-name" name="display_name" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="user-email" class="form-label text-info">Email</label>
                <input type="email" class="form-control shadow" id="user-email" name="email" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="user-username" class="form-label text-info">Username</label>
                <input type="text" class="form-control shadow" id="username-email" name="username" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="user-password" class="form-label text-info">Password</label>
                <input type="password" class="form-control shadow" id="password-email" name="password" required>
            </div>
            <div class="mb-3 col-md-12 col-lg-6 mt-3 mt-lg-0">
                <label for="user-role" class="form-label text-info">Role</label>
                <select class="form-select" aria-label="Role" name="role">
                    <option value="admin">Admin</option>
                    <option value="procurement">Procurement Manager</option>
                    <option value="seller">Seller</option>
                    <option value="accountant">Accountant</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success mt-4"><i class="fa fa-user-plus" aria-hidden="true"></i> Create</button>
            <a href="/users" class="btn btn-outline-danger ms-3 mt-4"><i class="fa fa-times"></i> Cancel</a>
        </form>
    </div>
</div>