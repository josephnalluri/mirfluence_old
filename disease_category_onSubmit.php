<?php

//Header files
require_once('dbAcess.php'); //connect to database

$disCategorySelected = mysqli_real_escape_string($dbConnect, $_POST["disCategorySelected"]);
$netGenMethod =  mysqli_real_escape_string($dbConnect, $_POST["netGenMethod"]);

//Remember: These echo statements are only to check how the the program is coming. They should be removed before running because the calling Ajax function in index.php file is expecting only JSON response. Mixing it with echo statements will not make it a JSON response and the json.parse function will fail!
//echo "\n dis Category is -> ".$disCategorySelected;
//echo "\n net gen method is -> ".$netGenMethod;

$queryResult = array(); //Varible to store the query result

if(ISSET($_POST["disCategorySelected"]) and ISSET($_POST["netGenMethod"]))
 {
    
   if($netGenMethod == 'All edges above 0.9 score, rescored to 0.05' )
  {
    //echo "\n Inside the 1st IF condition. IF condition works\n";
    $query = "SELECT mirna1 AS source, mirna2 AS target, score AS type FROM mirna_rescored WHERE disease='colorectal cancer' ORDER BY type DESC LIMIT 50"; //Dummy query for test
    $queryCSV = "SELECT mirna1 AS source, mirna2 AS target, score AS type FROM mirna_rescored WHERE disease='colorectal cancer' ORDER BY type DESC LIMIT 50 into outfile '/var/www/bnet.egr.vcu.edu/public_html/mirid/CSV/network.csv' fields terminated by ','"; //Dummy query for test

  } //Ending if($netGenMethod...)

else if($netGenMethod == 'Optimized network based on expression scores') 
  {
  
    //echo "\n Inside the 2nd IF condition. IF condition works\n";
    $query = "SELECT mirna1 AS source, mirna2 AS target, score AS type FROM mirna_rescored WHERE disease='colorectal cancer' ORDER BY type DESC LIMIT 50"; //Dummy query for test
    $queryCSV = "SELECT mirna1 AS source, mirna2 AS target, score AS type FROM mirna_rescored WHERE disease='colorectal cancer' ORDER BY type DESC LIMIT 50 into outfile '/var/www/bnet.egr.vcu.edu/public_html/mirid/CSV/network.csv' fields terminated by ','"; //Dummy query for test

  }

  $queryResult = mysqli_query($dbConnect, $query);
  $queryResultCSV = mysqli_query($dbConnect, $queryCSV);
	 
  for ($x = 0; $x < mysqli_num_rows($queryResult); $x++) 
	  {
		 //echo "\nI am inside the loop for the -> ".$x;
         $data[] = mysqli_fetch_assoc($queryResult);
	  }

	  echo json_encode($data);
	 
 } 

else echo ("ISSET condition failed");

?>

