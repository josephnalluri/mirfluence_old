<?php
//Header files
require_once('dbAcess.php');  // Connect to Database
//$query = "SELECT d1 as disease from d1_from_consensus order by d1 asc";
$query = "SELECT distinct disease from mirna_rescored order by disease asc";
$queryResult = mysqli_query($dbConnect, $query) or die("Error in the query" . mysqli_error($dbConnect));

$diseaseArray = array();
for($i = 0; $i < mysqli_num_rows($queryResult); $i++)
{
  $diseaseArray[] = mysqli_fetch_assoc($queryResult);
}
$diseaseDropdown = json_encode($diseaseArray);


if(file_exists('CSV/network.csv'))
{
unlink('CSV/network.csv'); // To delete the previous network CSV file
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  
  <title>Identifying influential miRNA targets in diseases via influence diffusion model</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="googleTableCss.css">
  <link rel="stylesheet" href="graphCSS.css"> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script src="drawGraph.js"></script>
  <!-- <script src="newGraph.js"></script> -->
  <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
 
 </head>
 
<body onload="fillDropdown(); singleDisease_fillDropdown();">
<div class="container">

   <!-- Modal. Recommeded to place it at the top of the DOM tree -->
   <!-- Taken from: https://myzeroorone.wordpress.com/2015/03/02/creating-simple-please-wait-dialog-with-twitter-bootstrap/--> 
   <!-- Modal Start here-->
   <div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
 	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">
					<span class="glyphicon glyphicon-time">
					</span> Please Wait... loading results and visualization
				 </h5>
			</div> <!-- End div for class modal-header -->
			<div class="modal-body">
				<div class="progress">
					<div class="progress-bar progress-bar-info progress-bar-striped active" style="width: 100%"></div>
				</div> <!-- End div for class=progress  -->
			</div><!-- End div tag for class=modal-body  -->
		</div> <!-- End div tag for class=modal-content -->
	</div><!-- End div tag for class=modal-dialog -->
   </div><!-- End div tag for class=modal fade -->
   <!-- Modal ends Here -->

  <div class="jumbotron">
    <h1>Identifying influential miRNA targets in diseases via influence diffusion model</h1>
  </div><!-- End div for jumbotron-->
  <div class="row">
    <div class="col-sm-10">
	
      <h3>This tool predicts influential disease-miRNAs in several diseases based on influence diffusion model. These miRNAs tend to work closely in diseases of similar profiles. </h3>
	
	  <div class="alert alert-success">
         <strong>Note:</strong> <br>
		 1. Users can view individual miRNA-miRNA predicted interactions for a specific disease <br>
		 2. Users can view miRNA-miRNA predicted interactions for a group of diseases by clicking <strong>Select more diseases</strong><br>
		 3. Some queries can take upto 1 minute to load based on the selection <br>
		 4. <strong>Maximum</strong> and <strong>Minimum</strong> input fields are <i>probablity scores</i> meant for user to specify the confidence-range.<br>
		 5. Upon <strong>Submit</strong> the miRNA-miRNA interactions will be displayed below. <br>
      </div> <!-- End div for class=alert-sucess-->
  
      <!-- Implementing tab-panel navigation -->
      <ul class = "nav nav-tabs">
         <li class="active"><a href="#disease_category" data-toggle="tab">Disease Category</a></li>
         <li><a href="#individual_disease" data-toggle="tab">Individual Disease</a></li>
         <li><a href="#create_category" data-toggle="tab">Create your own category</a></li>
      </ul>

    <!-- Implementing tab panel content -->
     <div class="tab-content">

     <!-- Code for 1st tab: Disease category -->
     <div class="tab-pane active" id="disease_category">
       <h5>Please choose a disease category</h5>
		 <form id = "disease_category_form">
		  <div id = "disease_category_selectDiseaseform">
		     <select name ="disease_category_dis" id = "disease_category_selectDropdown" class="form-control"> 
               <option>Choose disease category</option>
               <option>Gastrointestinal cancers</option>
               <option>Leukemia cancers</option>
               <option>Endocrine cancers</option>
               <option>Nerve cancers</option>
             </select><br>
          
            <!-- Option for network generation. Option 1 is checked by default -->
            <h5>Please choose a network generation method</h5>
            <select name="network_gen_method" id="network_gen_method_dropdown" class="form-control">
              <option selected="selected">All edges above 0.9 score, rescored to 0.05</option>
              <option>Optimized network based on expression scores</option>
            </select><br>

            
            <!-- Option for infusion diffusion methodology. Option 1 is checked by default -->
            <h5>Please choose an infusion diffusion methodology </h5>
            <select name="infusion_diff_method" id="infusion_diff_method_dropdown" class="form-control">
              <option selected="selected">Intersection (Logical OR) approach</option>
              <option>Cumulative Union</option>
            </select>
          
	      </div> <!-- End div tag for id=disease_category_selectDiseaseform -->
          <br>		  
	      <hr>
		  <button onclick = "disease_category_onSubmit()" type="button" class="btn btn-success" id="btn-submit"> SUBMIT</button>  &nbsp;  &nbsp;
		  <input type="reset" class="btn btn-info" id="btn-reset" value="RESET" onClick="window.location.reload()"> </button>
	      <br><br>
	    </form>
      
       <a href="/mirid/CSV/network.csv" id="disease_category_downloadCSV" style="display:none;"> Download the network (CSV) </a>
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> cbabcb9975ce207f5467e326e113d611137dab35
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e

      <!-- Placeholder for disease category graph -->
      <div id="disease_category_graph" tabindex="0"></div>
     </div> <!-- End div for id=disease_category -->

     <!-- Implementing tab content for 2nd tab - Individual Disease tab-->
      <div class="tab-pane" id="individual_disease">
        <h5>Please select a disease below</h5>
        <form id="single_disease_form">
         <div id= "singleDiseaseForm">
           <select name = "single_dis" id = "singlediseaseDropdown" class="form-control"> </select> <br>

            <!-- Option for network generation. Option 1 is checked by default -->
            <h5>Please choose a network generation method</h5>
            <select name="single_dis_network_gen_method" id="single_dis_network_gen_method_dropdown" class="form-control">
              <option selected="selected">All edges above 0.9 score, rescored to 0.05</option>
              <option>Optimized network based on expression scores</option>
<<<<<<< HEAD
            </select><br>

            <!-- Option for infusion diffusion methodology. Option 1 is checked by default -->
            <h5>Please choose an infusion diffusion methodology </h5>
            <select name="single_dis_infusion_diff_method" id="single_dis_infusion_diff_method_dropdown" class="form-control">
              <option selected="selected">Intersection (Logical OR) approach</option>
              <option>Cumulative Union</option>
            </select>
=======
            </select>

>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
        </div> <!-- End div for div id="singleDiseaseForm"-->  
        
           <br>
           <button onclick = "singleDisease_onSubmit()" type="button" class="btn btn-success" id="btn-submit">SUBMIT</button> &nbsp; &nbsp;
           <input type="reset" class="btn btn-info" id="btn-reset" value="RESET" onclick="window.location.reload()"> </button>
         </form>

     
<<<<<<< HEAD
      <a href="/mirid/CSV/network.csv" id="single_disease_downloadCSV" style="display:none;">Download the network (CSV)</a>

 	  <!-- Placeholder for graph --> 
	  <div id="single_disease_graph" tabindex="0"></div>
     </div> <!-- End div tag for id=individual_disease-->
=======
<<<<<<< HEAD
      <a href="/mirid/CSV/network.csv" id="single_disease_downloadCSV" style="display:none;">Download the network (CSV)</a>

 	  <!-- Placeholder for graph --> 
	  <div id="single_disease_graph" tabindex="0"></div>
     </div> <!-- End div tag for id=individual_disease-->
=======
      <a href="/mirid/CSV/network.csv" id="downloadCSV" style="display:none;">Download the network (CSV)</a>

 	  <!-- Placeholder for graph --> 
	  <div id="single_disease_graph" tabindex="0"></div>
     </div> <!-- End div tag for id=individual_disease-->
=======

      <!-- Placeholder for disease category graph -->
      <div id="disease_category_graph" tabindex="0"></div>
     </div> <!-- End div for id=disease_category -->

     <!-- Implementing tab content for 2nd tab - Individual Disease tab-->
      <div class="tab-pane" id="individual_disease">
<<<<<<< HEAD
       <h4>This tab is for individual disease </h4>
      <!-- Insert code here -->
       
     </div> <!-- End div tag for id=individual_disease-->
=======
      <h5>Please select a disease below</h5>
		 <form id = "form">
		  <div id = "selectDiseaseform">
		   <select name ="dis" id = "selectDropdown" class="form-control">  </select> <br>
		  </div> <!-- End div tag for id selectDiseaseform -->
            <br>		  
			<button  type="button" onclick = "addDisease()" class="btn btn-primary" id="btn-addDisease"> Select more diseases</button>
			<br><br>
			
			Maximum Score: <input type="text" id="max" name="max" size="4">  &nbsp;  &nbsp;
			Minimum Score: <input type="text" id="min" name="min" size="4"> &nbsp;  &nbsp; <i>[<b>Default</b>: Max is 1 and Min is 0.5000]</i>		
		   
		    <hr>
			  <button onclick = "onSubmit()" type="button" class="btn btn-success" id="btn-submit"> SUBMIT</button>  &nbsp;  &nbsp;
			  
			   <input type="reset" class="btn btn-info" id="btn-reset" value="RESET" onClick="window.location.reload()"> </button>
	        <br><br>
	     </form>
	 
      <a href="/miRsig/CSV/network.csv" id="downloadCSV" style="display:none;">Download the network (CSV)</a>

 	  <!-- Placeholder for graph --> 
	  <div id="graph" tabindex="0"></div>
	  <div id = "graph-bottom"> </div>
      </div> <!-- End div tag for id=individual_disease-->
>>>>>>> 439bc48c12e9af80487f6d150d974a78a89c8d66

>>>>>>> f500cc50b3c6a388af3001b57e834ea19fb2c73b
>>>>>>> cbabcb9975ce207f5467e326e113d611137dab35
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
   

      <!-- Code for 3rd tab: Create your own category-->
      <div class="tab-pane" id="create_category">
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> f500cc50b3c6a388af3001b57e834ea19fb2c73b
>>>>>>> cbabcb9975ce207f5467e326e113d611137dab35
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
       <h5>Please select a disease below</h5>
		 <form id = "form">
		  <div id = "selectDiseaseform">
		   <select name ="dis" id = "selectDropdown" class="form-control">  </select> <br>
<<<<<<< HEAD
  
=======
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
		  </div> <!-- End div tag for id selectDiseaseform -->
            <br>		  
			<button  type="button" onclick = "addDisease()" class="btn btn-primary" id="btn-addDisease"> Select more diseases</button>
			<br><br>
			
<<<<<<< HEAD
            <!-- Option for network generation. Option 1 is checked by default -->
            <h5>Please choose a network generation method</h5>
            <select name="category_network_gen_method" id="category_network_gen_method_dropdown" class="form-control">
              <option selected="selected">All edges above 0.9 score, rescored to 0.05</option>
              <option>Optimized network based on expression scores</option>
            </select><br>
 
            <!-- Option for infusion diffusion methodology. Option 1 is checked by default -->
            <h5>Please choose an infusion diffusion methodology </h5>
            <select name="category_infusion_diff_method" id="category_infusion_diff_method_dropdown" class="form-control">
              <option selected="selected">Intersection (Logical OR) approach</option>
              <option>Cumulative Union</option>
            </select>
=======
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
			<!-- 
            Maximum Score: <input type="text" id="max" name="max" size="4">  &nbsp;  &nbsp;
			Minimum Score: <input type="text" id="min" name="min" size="4"> &nbsp;  &nbsp; <i>[<b>Default</b>: Max is 1 and Min is 0.5000]</i>		
		    
            Display <input type="number" name="numberOfEdges" value="500" style="width: 4em"> results. (Edges in the visual network)
            --> 
		    <hr>
			  <button onclick = "onSubmit()" type="button" class="btn btn-success" id="btn-submit"> SUBMIT</button>  &nbsp;  &nbsp;
			  
			   <input type="reset" class="btn btn-info" id="btn-reset" value="RESET" onClick="window.location.reload()"> </button>
	        <br><br>
	     </form>
	 
      <a href="/mirid/CSV/network.csv" id="downloadCSV" style="display:none;">Download the network (CSV)</a>

 	  <!-- Placeholder for graph --> 
	  <div id="graph" tabindex="0"></div>
	  <div id = "graph-bottom"> </div>
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
=======
       <h4>This tab is for creating your own category</h4>
>>>>>>> 439bc48c12e9af80487f6d150d974a78a89c8d66
>>>>>>> f500cc50b3c6a388af3001b57e834ea19fb2c73b
>>>>>>> cbabcb9975ce207f5467e326e113d611137dab35
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
      </div><!-- End div tag for id=create_category -->

  </div> <!-- End div tag for class=tab-content-->
 </div> <!-- End div tag for class="col-sm-10"-->
</div><!-- End div tag for class=row -->
</div><!-- End div tag for class=container -->

<!-- PHP code to POST the form and run thequery -->
<?php
//Do it later if there are 3 diseases:		
//	$dis = $_POST["dis"];
//	$min = $_POST["min"];
//	$max = $_POST["max"];
	
if (!empty($_POST["min"]))
 {
    $min = $_POST["min"];
 }
else
{
    $min = 0.5;
}
if (!empty($_POST["max"]))
{
    $max = $_POST["max"];
}
else
{
    $max = 1;
}


if (!empty($_POST["dis"]))
{
   //$dis= '"' . $_POST["dis"] . '"';
   $dis = $_POST["dis"];
}

?> 

<!-- Script to send disease names to JavaScript and populate the dropdown   -->
<script type="text/javascript">
var diseaseList = <?php echo $diseaseDropdown; ?>;
var counterID = 1;

function fillDropdown()
{
  var selectDisease = document.getElementById("selectDropdown");
  var option = document.createElement("option");
  option.textContent = "Select Disease";
  option.value = "Select Disease";
  selectDisease.appendChild(option);
  
  for(var i = 0; i<diseaseList.length; i++)
   { 
	  var disName = diseaseList[i].disease; <!-- disease is the column name derived from the query-->
	  var option = document.createElement("option");
	  option.textContent = disName;
	  option.value = disName;
	  selectDisease.appendChild(option);
    }
 }

function singleDisease_fillDropdown()
 {
   var selectDisease = document.getElementById("singlediseaseDropdown");
   var option = document.createElement("option");
   option.textContent = "Select Disease";
   option.value = "Select Disease";
   selectDisease.appendChild(option);
  
   for(var i = 0; i<diseaseList.length; i++)
    { 
	  var disName = diseaseList[i].disease; <!-- disease is the column name derived from the query-->
	  var option = document.createElement("option");
	  option.textContent = disName;
	  option.value = disName;
	  selectDisease.appendChild(option);
    }
 }
   
function addDisease() 
 {
   if(counterID < 5)
   {  
	   var lineBreak = document.createElement("br");
	   var addDisease = document.createElement("select");
	   var classAttr = document.createAttribute("class");
	   var nameAttr = document.createAttribute("name");
	   
	   classAttr.value = "form-control";
	   nameAttr.value = "dis" + counterID;
	   
	   addDisease.setAttributeNode(classAttr);
	   addDisease.setAttributeNode(nameAttr)
	   addDisease.id = "selectDropdown" + counterID;
	   addDisease.textContent = "Select Disease";
	   document.getElementById("selectDiseaseform").appendChild(addDisease);
	   document.getElementById("selectDiseaseform").appendChild(lineBreak);
	   counterID +=1;
	  	   
	   var selectDisease = document.getElementById(addDisease.id);
	   var option = document.createElement("option");
	   option.textContent = "Select Disease";
	   option.value = "Select Disease";
	   selectDisease.appendChild(option);
	  
	   for(var i = 0; i<diseaseList.length; i++)
		{ 
		  var disName = diseaseList[i].disease;
		  var option = document.createElement("option");
		  option.textContent = disName;
		  option.value = disName;
		  selectDisease.appendChild(option);
		}
	}
   else
    {	
	 var para = $("<input>", {id:"para", class: "alert alert-danger", value:"Cannot exceed 5 diseases"});
	 $("#selectDiseaseform").append(para);
	 $("#btn-addDisease").attr("disabled","disabled");
	 
	}   	
 }

function isEmpty(str) 
{ return (!str || 0 === str.length); }

function isBlank(str)
{ return (!str || /^\s*$/.test(str)); }

</script>

<!-- <script src="http://code.jquery.com/jquery-1.11.3.js"></script> -->
<script src="https://rawgit.com/gka/d3-jetpack/master/d3-jetpack.js"></script>
 
<script type="text/javascript">
function onSubmit(){
   	var disSelected = document.getElementById("selectDropdown").value;
<<<<<<< HEAD
	var netGenMethod = document.getElementById("category_network_gen_method_dropdown").value;
  
   // To decide the AJAX request based on number of inputs.
	switch(counterID)
	{ 
	  case 1: var params = {'disSelected':disSelected,
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
	//var minSelected = document.getElementById("min").value;
	//var maxSelected = document.getElementById("max").value;
    //if(isEmpty(minSelected)||isBlank(minSelected)) {minSelected=0;}
    //if(isEmpty(maxSelected)||isBlank(maxSelected)){maxSelected=1;}
	
>>>>>>> f500cc50b3c6a388af3001b57e834ea19fb2c73b
>>>>>>> cbabcb9975ce207f5467e326e113d611137dab35
	// To decide the AJAX request based on number of inputs.
	switch(counterID)
	{ 
	  case 1: var params = {'disSelected':disSelected,
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
	          //'minSelected':minSelected,
			  //'maxSelected':maxSelected,
>>>>>>> f500cc50b3c6a388af3001b57e834ea19fb2c73b
>>>>>>> cbabcb9975ce207f5467e326e113d611137dab35
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
			  'counterID':counterID};	
	          break;
			  
	  case 2: var params = {'disSelected':disSelected,
	          'disSelected2': document.getElementById("selectDropdown1").value,
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
	          //'minSelected':minSelected,
			  //'maxSelected':maxSelected,
>>>>>>> f500cc50b3c6a388af3001b57e834ea19fb2c73b
>>>>>>> cbabcb9975ce207f5467e326e113d611137dab35
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
			  'counterID':counterID};	
			  break;
	  case 3: var params = {'disSelected':disSelected,
	          'disSelected2': document.getElementById("selectDropdown1").value,
			  'disSelected3': document.getElementById("selectDropdown2").value,
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
	          //'minSelected':minSelected,
			  //'maxSelected':maxSelected,
>>>>>>> f500cc50b3c6a388af3001b57e834ea19fb2c73b
>>>>>>> cbabcb9975ce207f5467e326e113d611137dab35
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
			  'counterID':counterID};
	          break;
	  case 4: var params = {'disSelected':disSelected,
	          'disSelected2': document.getElementById("selectDropdown1").value,
			  'disSelected3': document.getElementById("selectDropdown2").value,
			  'disSelected4': document.getElementById("selectDropdown3").value,
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
	          //'minSelected':minSelected,
			  //'maxSelected':maxSelected,
>>>>>>> f500cc50b3c6a388af3001b57e834ea19fb2c73b
>>>>>>> cbabcb9975ce207f5467e326e113d611137dab35
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
			  'counterID':counterID};
	          break;
	  case 5: var params = {'disSelected':disSelected,
	          'disSelected2': document.getElementById("selectDropdown1").value,
			  'disSelected3': document.getElementById("selectDropdown2").value,
			  'disSelected4': document.getElementById("selectDropdown3").value,
			  'disSelected5': document.getElementById("selectDropdown4").value,
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
	          //'minSelected':minSelected,
			  //'maxSelected':maxSelected,
>>>>>>> f500cc50b3c6a388af3001b57e834ea19fb2c73b
>>>>>>> cbabcb9975ce207f5467e326e113d611137dab35
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
			  'counterID':counterID};
	          break;
	}	
	$("#myPleaseWait").modal("show");
	//Ajax request
	$.ajax({
	type: "POST",
   // dataType: "json",
	url: "onSubmit.php",
	/*
	data: {'disSelected':disSelected,
	        'minSelected':minSelected,
			'maxSelected':maxSelected,
			'counterID':counterID},
	*/
   	data: params,
	success: function(dataReceived) {
	   $('#myPleaseWait').modal('hide');
	  if(dataReceived)
		{ 
		    $("#graph").empty();
            $("#downloadCSV").show();
		   // Don't know what the deal is with this
		   //console.log("data.index of NULL is - ".concat(dataReceived.indexOf("null")));
		   if (dataReceived.indexOf("null")> -1)
		   {
			 alert("No results for this selection. Please try reducing the number of diseases or widening the range of score. ");
		   }
		  else
		   {  //console.log(dataReceived);
			  createGraph(JSON.parse(dataReceived),"#graph", counterID); 
		   }
		 }
		 else
		   {
		     alert("No results from the selected specification"); 
		   }
	},
	error: function(jqXHR, textStatus, errorThrown) 
	{
	  $('#myPleaseWait').modal('hide');
	  console.log(jqXHR.responseText);
	  console.log(errorThrown);
	 }	
});
}
</script>

<!-- Script to implement disease_Category_onSubmit() -->
<script type="text/javascript">
function disease_category_onSubmit(){
  var disCategorySelected = document.getElementById("disease_category_selectDropdown").value;
  var netGenMethod = document.getElementById("network_gen_method_dropdown").value;
  
  // A quick way to check if the variables have captured the values properly
 // window.alert(disCategorySelected + " " + netGenMethod); 
 
  $("#myPleaseWait").modal("show");
  // Ajax request
  $.ajax({
  type: "POST",
  //dataType: "json",
  url: "disease_category_onSubmit.php",
  data: {'disCategorySelected':disCategorySelected,
         'netGenMethod':netGenMethod},
  success: function(dataReceived){
   $('#myPleaseWait').modal("hide");
   if (dataReceived){
       $("#disease_category_graph").empty();
       $("#disease_category_downloadCSV").show();
       if (dataReceived.indexOf("null")> -1)
           {
              //console.log(dataReceived);
              alert("No results for this selection. Please try reducing the number of diseases or widening the range of score. ");
           }
          else
           {  //console.log("Data received!!! now create graph - > ");
              //console.log(dataReceived);
              createGraph(JSON.parse(dataReceived),"#disease_category_graph"); 
           }
         }
   else
       {
         alert("No results from the selected specification"); 
       }
    },
    error: function(jqXHR, textStatus, errorThrown) 
    {
      $('#myPleaseWait').modal('hide');
      console.log(jqXHR.responseText);
      console.log(errorThrown);
    }  
}); 
}
</script>

<!-- Script to implement individual_ -->
<!-- single disease graph ;  -->
<script type="text/javascript">
function singleDisease_onSubmit(){
 var disSelected = document.getElementById("singlediseaseDropdown").value;
 var netGenMethod = document.getElementById("single_dis_network_gen_method_dropdown").value;
 
 //window.alert(disSelected + "  " + netGenMethod);
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e
   
  $("#myPleaseWait").modal("show");
  // Ajax request
  $.ajax({
  type: "POST",
  //dataType: "json",
  url: "single_disease_onSubmit.php",
  data: {'disSelected':disSelected,
         'netGenMethod':netGenMethod},
  success: function(dataReceived){
   $('#myPleaseWait').modal("hide");
   if (dataReceived){
       $("#single_disease_graph").empty();
       $("#single_disease_downloadCSV").show();
       if (dataReceived.indexOf("null")> -1)
           {
              //console.log(dataReceived);
              alert("No results for this selection. Please try reducing the number of diseases or widening the range of score. ");
           }
          else
           {  //console.log("Data received!!! now create graph - > ");
              //console.log(dataReceived);
              createGraph(JSON.parse(dataReceived),"#single_disease_graph"); 
           }
         }
   else
       {
         alert("No results from the selected specification"); 
       }
    },
    error: function(jqXHR, textStatus, errorThrown) 
    {
      $('#myPleaseWait').modal('hide');
      console.log(jqXHR.responseText);
      console.log(errorThrown);
    }  
}); 
<<<<<<< HEAD
=======
=======
  
>>>>>>> cbabcb9975ce207f5467e326e113d611137dab35
>>>>>>> 36ae713b236c96051f024feb2903cedb75c18f7e

}
</script> <!-- End script tag for singleDisease_onSubmit() -->
</body>
</html>
