<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=bevilec", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

try {
    if(isset($_REQUEST["term"])) {
        $sql = "SELECT * FROM tbl_product WHERE product_name LIKE :term";
        $stmt = $pdo->prepare($sql);
        $term = $_REQUEST["term"] . '%';
        $stmt->bindParam(":term", $term);
        $stmt->execute();

        $searchResults = []; // Initialize an array to store search results

        if($stmt->rowCount() > 0) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Add the row data to the search results array
                $searchResults[] = [
                    "product_id" => $row["product_id"],
                    "product_name" => $row["product_name"]
                ];
            }
        }

        // Return search results as JSON
        header("Content-Type: application/json");
        echo json_encode($searchResults);
    }  
} catch(PDOException $e) {
    die("ERROR: Could not able to execute $sql. " . $e->getMessage());
}

unset($stmt);
unset($pdo);
?>
