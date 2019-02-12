<?php
// remove all session variables
session_unset();

// destroy the session
session_destroy();
?>

<script type="text/javascript">
	alert('Successfully logged out!');
	window.location.href = "/";
</script>