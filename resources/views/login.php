<main id="main">

    <section>
        <div class="imgBx">
            <img src="/resources/images/pos.jpg" alt="">
        </div>


        <div class="contentBx">
            <div class="formbx">
                <img src="/resources/images/htu-pos-logo.png" alt="logo-pic">
                <form method="POST" action="/authenticate">

                    <div class="inputbx">
                        <span>Username</span>
                        <input type="text" name="username" placeholder="username" required />
                    </div>
                    <div class="inputbx">
                        <span>Password</span>
                        <input type="password" name="password" placeholder="password" required />
                    </div>
                    <div class="remember">
                        <input type="checkbox" id="remember-me" name="remember_me">
                        <label for="remember-me">Remember Me</label>

                    </div>

                    <div class="inputbx">
                        <button type="submit">Sign in</button>
                    </div>
                    <?php if (!empty($_SESSION) && isset($_SESSION['error']) && !empty($_SESSION['error'])) : ?>
                        <div class="error-msg" role="alert">
                            <?= $_SESSION['error'] ?>
                        </div>
                    <?php
                        $_SESSION['error'] = null;
                    endif; ?>

                </form>

            </div>
        </div>

    </section>

</main>