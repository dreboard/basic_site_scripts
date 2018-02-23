

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>$.noConflict();</script>
<script type="text/javascript" src="css/vendor/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="css/vendor/highlightjs/highlight.pack.js"></script>
<?php if (ENVIRONMENT === 'development'): ?>
    <script type="text/javascript" src="css/debugbar.js"></script>
    <script type="text/javascript" src="css/widgets.js"></script>
    <script type="text/javascript" src="css/openhandler.js"></script>
<?php endif; ?>
<?php
if (ENVIRONMENT === 'development'){
    echo $debugbarRenderer->render();
}
?>
</body>
</html>
