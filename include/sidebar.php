<!-- This is a reverse engineering of the "Hyperspace"
     design from HTML5 Up! https://html5up.net/hyperspace -->

<main class="main">
  <aside class="sidebar">

    <h4>DERNIERS ARTICLES</h4>

    <?php
                            $no = 1;
                            $selectlast = $pdo->prepare("SELECT * FROM tbl_product ORDER BY product_id DESC LIMIT 0,3");
                            $selectlast->execute();
                            while($row=$selectlast->fetch(PDO::FETCH_OBJ))
                                {


    ?>

                            <h2 style="color: blue"><?php echo $row->product_name; ?></h2>
                            <h5 style="color: blue"><?php echo $row->description; ?></h5>
                            <h4 style="color: blue"><?php echo number_format($row->sell_price)." "; ?> FCFA</h4>

    <?php

                                }             

    ?>
    <!--<nav class="nav">
      <ul>
        <li class="active"><a href="#">Welcome</a></li>
        <li><a href="#">Who We Are</a></li>
        <li><a href="#">What We Do</a></li>
        <li><a href="#">Get In Touch</a></li>
      </ul>
    </nav>-->
  </aside>

  <!--<section class="twitter">
    <div class="container">
      <a target="_blank" href="https://twitter.com/ReisnerShawn">
        <img class="social" src="https://cdn1.iconfinder.com/data/icons/logotypes/32/twitter-128.png">
      </a>
      <p>Follow me</p>
      <p>on Twitter!</p>
    </div>
  </section>-->
</main>