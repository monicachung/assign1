<?php
require_once('MiniTemplator.class.php');

function getWine() {

// Connect to the database
require_once('db.php');
$pdo = new PDO("mysql:host=localhost;dbname=winestore", DB_USER, DB_PW);

// All errors with throw exceptions
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

// execute the query
return $pdo->query($query);
}

function generatePage() {
    $t = new MiniTemplator;

    $t->readTemplateFromFile("winestore_template.htm");

    $results = getWine();

while($row = $results->fetch(PDO::FETCH_ASSOC)) {
    $t->setVariable('wineName', $row['wine_name']);
    $t->setVariable('variety', $row['variety']);
    $t->setVariable('year', $row['year']);
    $t->setVariable('wineryName', $row['winery_name']);
    $t->setVariable('region', $row['region_name']);
    $t->setVariable('cost', $row['cost']);
    $t->setVariable('stock', $row['on_hand']);
    $t->setVariable('sold', $row['qty']);
    $t->setVariable('sales', $row['cost']*$row['qty']);

    $t->addBlock("wine");
}
$t->generateOutput();

// If no matches found, display message
$row_count = $results->rowCount();
if($row_count == 0) {
    echo "<p>No matches found</p>";
}

}

generatePage();

echo "<p><a href='search.php'>< go back to search page</a></p>";

// Close the connection
$pdo = null;
?>
