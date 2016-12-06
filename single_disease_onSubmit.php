<?php

  
require_once('dbAcess.php');

$disSelected = mysqli_real_escape_string($dbConnect, $_POST["disSelected"]);
$netGenMethod = mysqli_real_escape_string($dbConnect, $_POST["netGenMethod"]);

$queryResult = array();

if(ISSET($_POST["disSelected"]) and ISSET($_POST["netGenMethod"])) 
 {

   if($netGenMethod == 'All edges above 0.9 score, rescored to 0.05')
    {
      $query = "SELECT mirna1 AS source, mirna2 AS target, score AS type FROM mirna_rescored WHERE disease='".$disSelected."' ORDER BY type DESC LIMIT 500";
      $queryCSV = "SELECT mirna1 AS source, mirna2 AS target, score AS type FROM mirna_rescored WHERE disease='".$disSelected."' ORDER BY type DESC into outfile '/var/www/bnet.egr.vcu.edu/public_html/mirid/CSV/network.csv' fields terminated by ','";
    } //Ending if netGenMethod

else if($netGenMethod == 'Optimized network based on expression scores')
    {
      $query = "SELECT mirna1 AS source, mirna2 AS target, score AS type FROM mirna_opt WHERE disease='".$disSelected."' ORDER BY type DESC LIMIT 500";
      $queryCSV = "SELECT mirna1 AS source, mirna2 AS target, score AS type FROM mirna_opt WHERE disease='".$disSelected."' ORDER BY type DESC into outfile '/var/www/bnet.egr.vcu.edu/public_html/mirid/CSV/network.csv' fields terminated by ','";
    } //Ending if.

    $queryResult = mysqli_query($dbConnect, $query);
    $queryResultCSV = mysqli_query($dbConnect, $queryCSV);

    for ($x = 0; $x < mysqli_num_rows($queryResult); $x++)                                                                                                                                                             {
         $data[] = mysqli_fetch_assoc($queryResult);
     }
    
    echo json_encode($data);
 } 

else echo("ISSET condition failed");   

?>

