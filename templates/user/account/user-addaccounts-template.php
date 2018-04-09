<body>
<div class="wrap">
  <div class="container">
    <form class="cool-b4-form" method="post" action="websitecheck.php">
    <h4 class="text-center pt-2">Add Site</h4>
      <div class="form-row">
        <div class="col-md-6">
          <div class="form-group">
            <input type="text" class="form-control" name="url" id="url">
            <label for="url">^ Login URL</label>
            <span class="input-highlight"></span>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="name" id="name">
            <label for="name">^ Website Name</label>
            <span class="input-highlight"></span>
          </div>
          <div class="form-group">
            <input type="text" class="form-control" name="username" id="username">
            <label for="username">^ Username</label>
            <span class="input-highlight"></span>
          </div>
          <div class="form-group">
            <input type="password" class="form-control" name="password" id="password">
            <label for="password">^ Password</label>
            <span class="input-highlight"></span>
          </div>
        </div>
      </div>
      <div><br><br></div>
        <div class="col-md-10 mx-auto mt-3">
        <center><button type="submit" class="btn-large waves-effect waves-light red">Submit</button></center>
      </div>

        <div class="col-md-10 mx-auto mt-3">
        <br><br>
        <center><a href="accountmanage.php" id="logoff-button" class="btn-large waves-effect waves-light red">Back</a></center>
      </div>
    </form>
    <div><br><br></div>
  </div>
</div>