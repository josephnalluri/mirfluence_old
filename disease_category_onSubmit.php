<?php

//Header files
require_once('dbAcess.php'); //connect to database

$queryResult = array(); //Varible to store the query result

if(ISSET($_POST["disCategorySelected"]) and ISSET($_POST["netGenMethod"])) {
   $disCategorySelected = mysqli_real_escape_string($dbConnect, $_POST["disCategorySelected"]);
   $netGenMethod = mysqli_real_escape_string($dbConnect, $_POST["netGenMethod"]);
   echo $disCategorySelected;
   echo "\r\n";
   echo $netGenMethod;
   echo "\r\n";
   
   if($netGenMethod == 'All edges above 0.9 score, rescored to 0.05' ){

   $query = "SELECT 
               mirna1 AS source, 
               mirna2 AS target, 
               score AS type 
            FROM mirna_rescored
            WHERE disease='colorectal cancer'
            ORDER BY type DESC
            LIMIT 50"; //Dummy query for test
   $queryCSV = "SELECT 
               mirna1 AS source, 
               mirna2 AS target, 
               score AS type 
            FROM mirna_rescored
            WHERE disease='colorectal cancer'
            ORDER BY type DESC
            LIMIT 50 into outfile '/var/www/bnet.egr.vcu.edu/public_html/mirid/CSV/network.csv"; //Dummy query for test

  } //Ending if($netGenMethod...)
   else if($netGenMethod == 'Optimized network based on expression score') {
  
 echo "Second loop initiated, yaar! "; }

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

