
<div class="section"></div>
<main>
    <center>
        <div class="section"></div>

        <h5 class="black-text">Account Login</h5>
        <div class="section"></div>
        <div class="container">
            <div class="z-depth-1 grey lighten-4 row" style="display: inline-block; padding: 32px 48px 0px 48px; border: 1px solid #EEE;">

                <form class="col s12" method="post" action="accountcheck.php">
                    <div class='row'>
                        <div class='col s12'>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='input-field col s12'>
                            <input class='validate' type='email' name='emaillogin' id='emaillogin' />
                            <label for='emaillogin'>Enter your email</label>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='input-field col s12'>
                            <input class='validate' type='password' name='passwordlogin' id='passwordlogin' />
                            <label for='passwordlogin'>Enter your password</label>
                        </div>
                        <label style='float: right;'>
                        </label>
                    </div>

                    <br />

                    <div class='row'>
                        <button type='submit' name='btn_login' class='col s12 btn btn-large waves-effect red'>Login</button>
                        <br><br><a href="createuser.php">Create Account</a>
                    </div>
                </form>
            </div>
        </div>
    </center>

    <div class="section"></div>
    <div class="section"></div>
	<div class="section"></div>
</main>
