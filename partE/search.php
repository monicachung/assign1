<?php
// Starting the session
if(isset($_GET['start'])) {
    session_start();
}

// Connect to database
require_once('db.php');

if(!$mysql = mysql_connect(DB_HOST, DB_USER, DB_PW)) {
    echo 'Could not connect to mysql on ' . DB_HOST . '\n';
    exit;
}
if(!mysql_select_db(DB_NAME, $mysql)) {
    echo 'Could not user database ' . DB_NAME . '\n';
    echo mysql_error() . '\n';
    exit;
}

?>

<html>
<head><title>Winestore</title></head>
<body>
<form action="process.php" method="get">
    <h3>Search</h3>
    <p>Wine Name: <input type="text" name="wineName" /></p>
    <p>Winery Name: <input type="text" name="wineryName" /></p>
    <p>Region: 
     <select name="region">
        <option id="0" value="">Select</option>
        <?php
            $getAllRegions = mysql_query("SELECT * FROM region ORDER BY region_name");
            while($viewAllRegions = mysql_fetch_array($getAllRegions)) {
        ?>
            <option id="<?php echo $viewAllRegions['region_id']; ?>"><?php echo $viewAllRegions['region_name'] ?></option>
        <?php } ?>
    </select>
    </label>

<p>Grape Variety:
<select name="variety">
    <option id="0" value="">Select</option>
    <?php
        $getAllVarieties = mysql_query("SELECT * FROM grape_variety ORDER BY variety");
        while($viewAllVarieties = mysql_fetch_array($getAllVarieties)) {
    ?>
    <option id="<?php echo $viewAllVarieties['variety_id']; ?>"><?php echo $viewAllVarieties['variety'] ?></option>
    <?php } ?>
</select></p>

<p>Year Range:
<select name="lowerBound">
<option id="0" value="">Select</option>
    <?php
        $getMinRange = mysql_query("SELECT DISTINCT year FROM wine ORDER BY year");
        while($viewMinRange = mysql_fetch_array($getMinRange)) {
    ?>
    <option id="<?php echo $viewMinRange['wine_id']; ?>"><?php echo $viewMinRange['year'] ?></option>
    <?php } ?>
</select>
<span>to</span>
<select name="upperBound">
<option id="0" value="">Select</option>
    <?php
        $getMaxRange = mysql_query("SELECT DISTINCT year FROM wine ORDER BY year DESC");
        while($viewMaxRange = mysql_fetch_array($getMaxRange)) {
    ?>
    <option id="<?php echo $viewMaxRange['wine_id']; ?>"><?php echo $viewMaxRange['year'] ?></option>
    <?php } ?>
</select></p>
<p>Min number of wines in stock: <input type="text" name="minInStock" /></p>
<p>Min number of wines ordered: <input type="text" name="minOrdered" /></p>
<p>Cost Range: $<input type="text" name="minCost" value="" /> 
<span>to </span> 
<span>$</span><input type="text" name="maxCost" value="" /></p>
<input type="submit" name="submit" value="Search" />
<p><a href="?start">Start Session</a></p>
</form>
</body>
</html>
<?php
// destroy the session
//session_destroy();
?>
