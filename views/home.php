<div id="main-content" class="container-fluid">
    <div class="row">
        <div class="col-xs-12 text-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Release Tracker</h2>
                </div>
                <div class="panel-body">
                    <?php if(array_key_exists('logged_in', $_SESSION) && $_SESSION['logged_in']){
                        echo 'Welcome ' . $_SESSION['username'] . '!';
                    } else{
                        echo 'Welcome to Release Tracker. Please Register or Login above to get started!';
                    }?>                    
                </div>
            </div>
        </div>
    </div>
</div>    