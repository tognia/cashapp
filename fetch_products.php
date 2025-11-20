<?php

try{
    $pdo = new PDO("mysql:host=localhost;dbname=bevilec", "root", "");
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}

if (isset($_POST["search"])) {
  $searchText = $_POST["search"];

  $sql = "SELECT product_name FROM tbl_product WHERE product_name LIKE '%$searchText%'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<option value='" . $row["product_name"] . "'>";
    }
  } else {
    echo "<option value='' disabled>No products found.</option>";
  }
}

$conn->close();
?>
