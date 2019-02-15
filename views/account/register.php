

<div id="about-container">
  <div class="jumbotron">
    <h1 class="display-5">Register</h1>
    <hr class="my-4">
    <form class="col-sm-6 center">
      <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
        <small id="emailHelp" class="form-text text-muted text-center">We will only use your email to allow you to reset your password and to send you release updates if you want them.</small>
      </div>
      <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" aria-describedby="usernameHelp" placeholder="Pick a username">
        <small id="usernameHelp" class="form-text text-muted text-center">This username isn't unique and is just used to personalize your experience. Pick whatever you want.</small>
      </div>  
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" placeholder="Password">
      </div>
      <div class="text-center">
        <button type="submit" id="registration-submit" class="btn btn-primary text-center">Submit</button>
      </div>
    </form>
  </div>
</div>

<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
  <div id="registration-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
    <div class="toast-header">
      <img src="..." class="rounded mr-2" alt="...">
      <strong class="mr-auto">Bootstrap</strong>
      <small>11 mins ago</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      <span id="toast-text"></span>
    </div>
  </div>
</div>