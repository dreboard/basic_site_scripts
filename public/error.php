<?php require_once 'head.php'; ?>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Test v0.1</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="index.php">Home</a></li>
                    <?php
                    if(isset($_SESSION['user']['id'])){
                        echo '<li><a href="page2.php">Page 2</a></li>
                              <li><a href="page3.php">Page 3</a></li>
                              <li><a href="'.$_SERVER['PHP_SELF'].'">'.$_SESSION['user']['name'].'</a></li>';
                    }
                    ?>
                </ul>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">

    <h1>Database encrypted sessions</h1>
    <p class="lead">Test Environment Errors</p>
    <div class="row">
        <div class="col-md-8">
            <h5>ENV Data</h5>
            <form method="post" action="logout.php" class="form-inline">
                <button name="out" type="submit" class="btn btn-default">Logout</button>
            </form>
            <pre>
                <h5>An Unknown Error Has Occured</h5>
                <?php //echo $error ?? ''; ?>
                <?php
                //
                require_once 'session.php';

                ?>
            </pre>
        </div>
        <div class="col-md-4">

        </div>
    </div>

</div><!-- /.container -->

<?php require_once 'footer.php'; ?>


