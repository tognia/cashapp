<?php
include_once 'db/connect_db.php';

if (isset($_POST["search"])) {
  $searchText = $_POST["search"];
   echo "BOBALI".$_POST["search"];
  $sql = "SELECT * FROM tbl_product WHERE product_name LIKE '%$searchText%'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<p>" . $row["product_name"] . "</p>";
    }
  } else {
    echo "<p>No results found.</p>";
  }
}

?>