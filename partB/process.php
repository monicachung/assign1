<?php
// Connect to the database
require_once('db.php');

    if(!$mysql = mysql_connect(DB_HOST, DB_USER, DB_PW)) {
        echo 'Could not connect to mysql on ' . DB_HOST . '\n';
        exit;
    }   
        if(!mysql_select_db(DB_NAME, $mysql)) {
            echo 'Could not user databse ' . DB_NAME . '\n';
            echo mysql_error() . '\n';
            exit;
        }   

// Get data from form
$wineName = $_GET['wineName'];
$wineryName = $_GET['wineryName'];
$region = trim($_GET['region']);
$variety = $_GET['variety'];
$minRange = $_GET['lowerBound'];
$maxRange = $_GET['upperBound'];
$minWinesInStock = $_GET['minInStock'];
$minWinesOrdered = $_GET['minOrdered'];
$minCostRange = $_GET['minCost'];
$maxCostRange = $_GET['maxCost'];


$query = "SELECT wine_name, variety, year, winery_name, region_name, cost,
   on_hand, qty, price 
    FROM wine
    JOIN winery w
    ON wine.winery_id = w.winery_id
    JOIN region r
    ON w.region_id = r.region_id 
    JOIN wine_variety wv
    ON wine.wine_id = wv.wine_id
    JOIN grape_variety gv
    ON wv.variety_id = gv.variety_id
    JOIN items
    ON wine.wine_id = items.wine_id
    JOIN inventory
    ON wine.wine_id = inventory.wine_id
    WHERE wine_name LIKE '%$wineName%'
    ";

if(!empty($wineryName)) {
$query .= " AND winery_name LIKE '%$wineryName%'";
}

if(!empty($region)) {
$query .= " AND region_name LIKE '%$region%'";
}

if(!empty($variety)) {
$query .= " AND variety LIKE '%$variety%'"; 
}

if((!empty($minRange)) && (!empty($maxRange))) {
    if($minRange > $maxRange) {
        echo "Min Year Range cannot be higher than Max Year Range!";
    } else {
        $query .= " AND year BETWEEN '{$minRange}' AND '{$maxRange}'";
    }
}

if(!empty($minWinesInStock)) {
$query .= " AND on_hand >= '{$minWinesInStock}'";
}

if(!empty($minWinesOrdered)) {
$query .= " AND qty >= '{$minWinesOrdered}'";
}

if((!empty($minCostRange)) && (!empty($maxCostRange))) {
    if($minCostRange > $maxCostRange) {
        echo "Min Cost Range cannot be higher than Max Cost Range!";
    }
    else {
        $query .= " AND price between '{$minCostRange}' AND '{$maxCostRange}'";
    }
}

$results = mysql_query($query);
    echo "<h3>Search Results</h3>";
    echo "<table border='1' cellpadding='3'>";
    echo "<tr><td>Wine</td>";
    echo "<td>Variety</td>";
    echo "<td>Year</td>";
    echo "<td>Winery</td>";
    echo "<td>Region</td>";
    echo "<td>Cost</td>";
    echo "<td>Available</td>";
    echo "<td>Stock Sold</td>";
    echo "<td>Sales Revenues</td>";
    echo "</tr>";

while($row = mysql_fetch_array($results)) {
     // print_r($row);
    echo "<tr>";
    echo "<td>". $row['wine_name'] ."</td>";  
    echo "<td>". $row['variety'] ."</td>";
    echo "<td>". $row['year'] ."</td>";
    echo "<td>". $row['winery_name'] ."</td>";
    echo "<td>". $row['region_name'] ."</td>";
    echo "<td>". $row['cost'] ."</td>";
    echo "<td>". $row['on_hand'] ."</td>";
    echo "<td>". $row['qty'] ."</td>";
    echo "<td>". $row['price']*$row['qty'] ."</td>";
}
echo "</table>";

// If no matches found, display message
    if(mysql_num_rows($results) == 0) {
        echo "<p>No matches found</p>";
    }

echo "<p><a href='search.php'>< go back to search page</a></p>";

?>
