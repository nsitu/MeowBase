<?php   include 'partials/header.php'; ?> 
 
<div id="banner">
  <?php   include 'partials/navigation.php';  ?>
  <h1>Login</h1>
</div>

<?php include 'partials/notices.php';   ?>

<main class="container-fluid mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="col col-sm-6 col-md-6 col-lg-4 col-xl-3">
          <!-- note the form's action means that is is submitting to /login 
          You can check the /login route to see what happens next. -->
            <form  method="post" action="<?= App::root(); ?>/login">
              <label class="text-muted">Your credentials, please.</label>
              <input class="form-control form-control-lg my-1" type="email" name="Email" placeholder="Email Address" required>
              <input class="form-control form-control-lg my-1" type="password" name="Password" placeholder="Password" required>
              <button class="btn btn-info btn-lg btn-block my-3" type="submit" name="Login" value="Login">Login</button>
              <p class="text-muted text-center">Need an Account? <a href="<?= App::root(); ?>/register">Register Here</a>.</p>
            </form>
        </div>
    </div>
</main>

<?php  include 'partials/footer.php'; ?>
