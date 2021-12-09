<?php  
  include 'partials/header.php'; 
?> 

<div id="banner">
  <?php   include 'partials/navigation.php';   ?>
  <h1>Register</h1>
</div>

<?php include 'partials/notices.php';   ?>

<main class="container-fluid mt-5">
    <div class="row justify-content-center align-items-center">
        <div class="col col-sm-6 col-md-6 col-lg-4 col-xl-3">
          <!-- note the form's action means that is is submitting to /register 
          You can check the /register route to see what happens next. -->
            <form method="post" action="/register">
              <label class="text-muted">Let's get started.</label>
              <input class="form-control form-control-lg my-1" type="text" name="FullName" placeholder="Full Name" required>
              <input class="form-control form-control-lg my-1" type="email" name="Email" placeholder="Email Address" required>
              <input class="form-control form-control-lg my-1" type="password" name="Password" placeholder="Password" required>

              <div class="form-check">
                <input name="PolicyApproved" class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                 I agree to the   <a  data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">Terms</a>  
                </label>
              </div>

               <div class="collapse" id="collapseExample">
                <div class="card card-body">
                  By registering for an account on this site, you agree that cats are phenomenal.
                </div>
              </div>

              <button class="btn btn-info btn-lg btn-block my-3" type="submit" name="Register" value="Register">Register</button>
              <p class="text-center text-muted">Already Have an Account? <a href="/login">Login Here</a>.</p>

              

            </form>
        </div>
    </div>
   
</main>

<?php require 'partials/footer.php'; ?>