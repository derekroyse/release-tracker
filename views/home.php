<div id="main-content" class="container-fluid">
    <div class="row">
        <div id="about-container" class="col-xs-12 text-center">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Welcome to Release Tracker!</h2>
                </div>
                <div class="panel-body">
                    <?php if(array_key_exists('logged_in', $_SESSION) && $_SESSION['logged_in']){
                        echo 'Welcome back ' . $_SESSION['username'] . '!<br>
                                Get started by accessing your <a href="/?go=list&type=user">list</a> or by <a href="/?go=list&type=master">adding</a> new titles.';
                    } else{
                        echo '<br> 
                                Release Tracker allows you to track the release date of upcoming movies and video games. 
                                <br><br> 
                                After creating an account, you will be able to view upcoming releases, add them to your personal list, 
                                and see when something you\'re interested in has been released. 
                                <br><br>
                                This site is in active development, and I\'m actively working on adding new features and new types of content to the release list.
                                <br><br>
                                Please <a href="?go=account:register">Register</a> or <a href="?go=account:login">Login</a> to get started!';
                    }?>                    
                </div>
            </div>
        </div>
    </div>
</div>    