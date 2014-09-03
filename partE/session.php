<?php
// Starting the session
session_start();

// Get variables from form
$_SESSION['wines'] = $_GET['wines'];

?>

<html>
<head><title>List of Wine Names</title></head>
<h3>List of Wine Names</h3>
<body>
<table border="1" cellpadding="3">
<tr>
<?php
foreach($_SESSION as $key => $value) {
    echo "<tr><td>";
    echo $value;
    echo "</td></tr>";
    
}
?>
</tr>
</table>
<p><a href="search.php">< go back to search page</a></p>
</body>
</html>
