<?php

//Header files
require_once('dbAcess.php'); //connect to database

$queryResult = array(); //Varible to store the query result

if(ISSET($_POST["disCategorySelected"]) and ISSET($_POST["netGenMethod"])) {
   $disCategorySelected = mysqli_real_escape_string($dbConnect, $_POST["disCategorySelected"]);
   $netGenMethod = mysqli_real_escape_string($dbConnect, $_POST["netGenMethod"]);

   $query = "SELECT 
               m1 AS source, 
               m2 AS target, 
               score AS type 
            FROM consensus_avg_pancancer
            WHERE d1=d2
            AND d1='colorectal cancer'
            AND score <1
            AND score>0.9
            ORDER BY type DESC
            LIMIT 50"; //Dummy query for test
   $queryCSV = "SELECT 
               m1 AS source, 
               m2 AS target, 
               score AS type 
            FROM consensus_avg_pancancer
            WHERE d1=d2
            AND d1='colorectal cancer'
            AND score <1
            AND score>0.9
            ORDER BY type DESC
            LIMIT 50 into outfile '/var/www/bnet.egr.vcu.edu/public_html/mirid/CSV/network.csv"; //Dummy query for test

 $queryResult = mysqli_query($dbConnect, $query);
 $queryResultCSV = mysqli_query($dbConnect, $queryCSV);
	 
 for ($x = 0; $x < mysqli_num_rows($queryResult); $x++) 
	  {
		$data[] = mysqli_fetch_assoc($queryResult);
	  }

	  echo json_encode($data);
	 
}

else echo ("ISSET condition failed");

?>

