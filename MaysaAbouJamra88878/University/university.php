<html>
<head><title></title>
<link rel="stylesheet" type="text/css" href="./form.css"/>
</head>
<body>
<h1>About Universities </h1>
<?php 
include_once('semsol/ARC2.php');

$dbpconfig = array(
		"remote_store_endpoint" => "http://dbpedia.org/sparql",
);

$store = ARC2::getRemoteStore($dbpconfig);

if ($errs = $store->getErrors()) {
	echo "<h1>getRemoteStore error<h1>" ;
}
 $query='PREFIX dbo: <http://dbpedia.org/ontology/>
select ?abstract
where { <http://dbpedia.org/resource/University> dbo:abstract ?abstract. filter(langmatches(lang(?abstract),"en")).}';
 $rows = $store->query($query, 'rows');
 
 if ($errs = $store->getErrors()) {
 	echo "Query errors" ;
 	print_r($errs);
 }
 foreach($rows as $row){
 	$description=$row['abstract'];
 }
   
   
 	
 
?>
<h4><?php echo $description ?></h4>
<form name="form1" action="#" method="post">
   <p>
    <label>Select the number of Universities to show :</label>
	<select name="number" id="num">
	   <?php 
	     $s=0;
	      for($i=10;$i<=50;$i++){?>
	      	
	      	<option value="<?php echo $s ; ?>"><?php echo $i ;?></option>';
	      	<?php 
	      	$s++;
	      }
	   ?>
	    <?php 
	   if(isset($_POST['submit']) || isset($_POST['next']) || isset($_POST['previous'])){
	   	if(empty($_POST["type"])){
	   		$type="";
	   	}
	   	else $type=$_POST["type"];
	   }
	   ?>
	</select>
	<label> Choose the Criterea :</label>
	<select id="crit" name="critere">
		<option value="0">Nb Of students</option><option value="1">Staff</option><option value="2">Faculty Size</option>
	</select>
	<label>Type of University : </label>
	<label id="public">Public</label><input type="radio" name="type" <?php if (isset($type) && $type=="Public") echo 'checked="checked"';?> value="Public">
	<label id="private">Private</label><input type="radio" name="type"<?php if (isset($type) && $type=="Private") echo 'checked="checked"';?>  value="Private">
	<input type="submit" name="submit" value="Show"/> 
	</p>
	<br>

<?php 
  function display($k,$critere,$offset) {
 if($critere=="1"){
 	if(isset($_POST['type'])){
 		$type=$_POST['type'];
 		if($type=="Public"){
 			$query='PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dbpedia2: <http://dbpedia.org/property/>
PREFIX dbo: <http://dbpedia.org/ontology/>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX dbr:<http://dbpedia.org/resource/>
select distinct ?uni ?title ?president ?motto ?staff ?type ?country
where {?uni rdf:type dbo:University.
       ?uni rdfs:label ?title. filter(langmatches(lang(?title),"En")).
       ?uni dbpedia2:president ?president.
       optional{?uni dbpedia2:mottoeng ?motto.}
  	  	?uni dbo:type ?type.
  	   ?uni dbo:type dbr:Public_university.
       ?uni dbo:country ?country.
 	 ?uni dbpedia2:staff ?staff. filter(xsd:integer(?staff)).}	
      
order by asc(?staff) limit '.$k.' offset '.$offset;

 
 		}// eza staff ma3 public
 		else if($type=="Private"){
 				
 			$query='PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dbpedia2: <http://dbpedia.org/property/>
PREFIX dbo: <http://dbpedia.org/ontology/>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX dbr:<http://dbpedia.org/resource/>
select distinct ?uni ?title ?president ?motto ?type ?country ?staff
where {?uni rdf:type dbo:University.
       ?uni rdfs:label ?title. filter(langmatches(lang(?title),"En")).
       ?uni dbpedia2:president ?president.
       optional{?uni dbpedia2:mottoeng ?motto.}
	   ?uni dbo:type ?type.
       ?uni dbo:type dbr:Private_university.
       ?uni dbo:country ?country.
 	   ?uni dbpedia2:staff ?staff. filter(xsd:integer(?staff)).}
order by asc(?staff) limit '.$k.' offset '.$offset;
 		}
 		}
 		
 		else if(!isset($_POST['type'])){
 			
 			$query='PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dbpedia2: <http://dbpedia.org/property/>
PREFIX dbo: <http://dbpedia.org/ontology/>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
select distinct ?uni ?title ?president ?motto ?type ?country ?staff
where {?uni rdf:type dbo:University.
       ?uni rdfs:label ?title. filter(langmatches(lang(?title),"En")).
       ?uni dbpedia2:president ?president.
       optional{?uni dbpedia2:mottoeng ?motto.}
      optional{ ?uni dbo:type ?type.}
       ?uni dbo:country ?country.
 	   ?uni dbpedia2:staff ?staff. filter(xsd:integer(?staff)).}
order by asc(?staff) limit '.$k.' offset '.$offset;
 		}
 } // eza na2ayna staff
 
 
 
 else
 	if($critere=="0"){
 	if(isset($_POST['type'])){
 		$type=$_POST['type'];
 		if($type=="Public"){
 			$query='PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dbpedia2: <http://dbpedia.org/property/>
PREFIX dbo: <http://dbpedia.org/ontology/>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX dbr:<http://dbpedia.org/resource/>
select distinct ?uni ?title ?president ?motto ?nbStudents ?type ?country 
where {?uni rdf:type dbo:University.
       ?uni rdfs:label ?title. filter(langmatches(lang(?title),"En")).
       ?uni dbpedia2:president ?president.
       optional{?uni dbpedia2:mottoeng ?motto.}
       ?uni dbo:numberOfStudents ?nbStudents.
  	  	?uni dbo:type ?type.
  	   ?uni dbo:type dbr:Public_university.
       ?uni dbo:country ?country.
  
      }
order by asc(?nbStudents) limit '.$k.' offset '.$offset;
 			
 			
 		}
 		else if($type=="Private"){
 				
 			$query='PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dbpedia2: <http://dbpedia.org/property/>
PREFIX dbo: <http://dbpedia.org/ontology/>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX dbr:<http://dbpedia.org/resource/>
select distinct ?uni ?title ?president ?motto ?type ?country ?nbStudents
where {?uni rdf:type dbo:University.
       ?uni rdfs:label ?title. filter(langmatches(lang(?title),"En")).
       ?uni dbpedia2:president ?president.
       optional{?uni dbpedia2:mottoeng ?motto.}
	   ?uni dbo:type ?type.
       ?uni dbo:type dbr:Private_university.		
       ?uni dbo:country ?country.
 	   ?uni dbo:numberOfStudents ?nbStudents.}
order by asc(?nbStudents) limit '.$k.' offset '.$offset;
 		}
 	}
 		else if(!isset($_POST['type'])){
 	$query='PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dbpedia2: <http://dbpedia.org/property/>
PREFIX dbo: <http://dbpedia.org/ontology/>
select distinct ?uni ?title ?president ?motto ?nbStudents ?country ?type
where {?uni rdf:type dbo:University.
       ?uni rdfs:label ?title. filter(langmatches(lang(?title),"En")).
       ?uni dbpedia2:president ?president.
       optional{?uni dbpedia2:mottoeng ?motto.}
       ?uni dbo:numberOfStudents ?nbStudents.
       ?uni dbo:country ?country.
 	   optional{?uni dbo:type ?type.}
 	   }
order by asc(?nbStudents) limit '.$k.' offset '.$offset;
 
 		}
 
 		}//ascendant by number of students
  
 else
 	if($critere=="2"){
 	// critere hiye faculty size
 	if(isset($_POST['type'])){
 		$type=$_POST['type'];
 		if($type=="Public"){
 			$query='PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dbpedia2: <http://dbpedia.org/property/>
PREFIX dbo: <http://dbpedia.org/ontology/>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX dbr:<http://dbpedia.org/resource/>
select distinct ?uni ?title ?president ?motto  ?facultySize ?type ?country
where {?uni rdf:type dbo:University.
       ?uni rdfs:label ?title. filter(langmatches(lang(?title),"En")).
       ?uni dbpedia2:president ?president.
       optional{?uni dbpedia2:mottoeng ?motto.}
       ?uni dbo:facultySize ?facultySize.
  	  	?uni dbo:type ?type.
  	   ?uni dbo:type dbr:Public_university.
       ?uni dbo:country ?country.
 	
      }
order by asc(?facultySize) limit '.$k.' offset '.$offset;
 	
 	
 		}
 		else if($type=="Private"){
 				
 			$query='PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dbpedia2: <http://dbpedia.org/property/>
PREFIX dbo: <http://dbpedia.org/ontology/>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX dbr:<http://dbpedia.org/resource/>
select distinct ?uni ?title ?president ?motto ?type ?country ?facultySize
where {?uni rdf:type dbo:University.
       ?uni rdfs:label ?title. filter(langmatches(lang(?title),"En")).
       ?uni dbpedia2:president ?president.
       optional{?uni dbpedia2:mottoeng ?motto.}
	   ?uni dbo:type ?type.
       ?uni dbo:type dbr:Private_university.
       	?uni dbo:facultySize ?facultySize.
       ?uni dbo:country ?country.}
 	   
order by asc(?facultySize) limit '.$k.' offset '.$offset;
 		}
 	}
 	
 	else if(!isset($_POST['type'])){
 		$query='PREFIX rdf:<http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dbpedia2: <http://dbpedia.org/property/>
PREFIX dbo: <http://dbpedia.org/ontology/>
select distinct ?uni ?title ?president ?motto ?facultySize ?country ?type
where {?uni rdf:type dbo:University.
       ?uni rdfs:label ?title. filter(langmatches(lang(?title),"En")).
       ?uni dbpedia2:president ?president.
       optional{?uni dbpedia2:mottoeng ?motto.}
       ?uni dbo:facultySize ?facultySize.
       ?uni dbo:country ?country.
 	   optional{?uni dbo:type ?type.}
 	   }
order by asc(?facultySize) limit '.$k.' offset '.$offset;
 	
 	}
 	

 } // eza na2a facuty
 
 $dbpconfig = array(
 		"remote_store_endpoint" => "http://dbpedia.org/sparql",
 );
 
 $store = ARC2::getRemoteStore($dbpconfig);
 
 if ($errs = $store->getErrors()) {
 	echo "<h1>getRemoteStore error<h1>" ;
 }
 //bttkarrar el uni krmel hek kl ma jib uni min el array taba3 el rows bzida 3a array 3nde ye hon w bf7as bsir kl mara eza mkarar aw la2 krmel o3rdo bl table
 $rows = $store->query($query, 'rows');
 
 if ($errs = $store->getErrors()) {
 	echo "Query errors" ;
 	print_r($errs);
 }
 
 echo "<table align='center' width='1300'>";
 echo "<thead>
 
       	<th>Name of University</th>
       	<th>President</th>
       	<th>Motto</th>
       	<th>Country</th>
       	<th>Type</th>";
 
 //zobet el query
 $i=0;
 foreach($rows as $row){
 	$link=$row['uni'];
 	$link2=$row['president'];
 	$pres =$link2;
 	$presExp= end(explode('/', $pres));
 	$link3=$row['country'];
 	$countr =$link3;
 	$countExp= end(explode('/', $countr));
 	if($critere=="0"){ // selon le nb d'etudiants
 		if($i==0)
 			echo"<th>number Of students</th></thead>";
 
 		$i++;
 		echo "<tr>";
 		echo"<td><a href=\"$link\">".$row['title']."</a></td>";
 		echo"<td><a href=\"$link2\">".$presExp."</a></td>";
 		if((isset($row['motto'])))
 			$j=$row['motto'];
 		else 
 			$j="Not assigned";
 			
 		echo"<td>".$j."</a></td>";
 		echo"<td><a href=\"$link3\">".$countExp."</a></td>";
 		if((isset($row['type']))){
 			$s=$row['type'];
 			$typeExp= end(explode('/', $s));
 		}
 		else {
 			$s="Not assigned";
 			$typeExp=$s;
 		}
 			
 		echo"<td>".$typeExp."</td>";
 		echo"<td class='nb'>".$row['nbStudents']."</a></td>";
 		echo"</tr>";
 
 	}
 	else if($critere=="1"){
 		if($i==0)
 			echo"<th>Staff</th></thead>";
 		$i++;
 		echo "<tr>";
 		echo"<td><a href=\"$link\">".$row['title']."</a></td>";
 		echo"<td><a href=\"$link2\">".$presExp."</a></td>";
 		
 		if((isset($row['motto'])))
 			$j=$row['motto'];
 		else
 			$j="Not assigned";
 		
 			
 		echo"<td>".$j."</td>";
 		echo"<td><a href=\"$link3\">".$countExp."</a></td>";
 		if((isset($row['type']))){
 			$s=$row['type'];
 			$typeExp= end(explode('/', $s));
 		}
 		else {
 			$s="Not assigned";
 			$typeExp=$s;
 		}
 			
 		echo"<td>".$typeExp."</td>";
 		echo"<td class='nb'>".$row['staff']."</td>";
 		echo"</tr>";
 	}
 	else if($critere=="2") // eza el critere ken faculty Size 3am etba3 table fi case zyede faculty w ma fi staff krmel el result ma tetla3 wala mara empty krmel msh 7lwe tkoun empty bl table :p
 	{
 		if($i==0)
 			echo"<th>Faculty Size</th></thead>";
 		$i++;
 		echo "<tr>";
 		echo"<td><a href=\"$link\">".$row['title']."</a></td>";
 		echo"<td><a href=\"$link2\">".$presExp."</a></td>";
 		if((isset($row['motto'])))
 			$j=$row['motto'];
 		else
 			$j="Not assigned";
 		echo"<td>".$j."</td>";
 		echo"<td><a href=\"$link3\">".$countExp."</a></td>";
 		if((isset($row['type']))){
 			$s=$row['type'];
 			$typeExp= end(explode('/', $s));
 		}
 		else {
 			$s="Not assigned";
 			$typeExp=$s;
 		}
 			
 		echo"<td>".$typeExp."</td>";
 		echo"<td class='nb'>".$row['facultySize']."</td>";
 		echo"</tr>";
 	}
 }// fin de foreach
 echo "</table>";
 echo"<br>";
 ?><input type="submit" name="next" id="next" value="next" hidden="true"/>
 <input type="submit" name="previous" id="previous" value="previous" hidden ="true"/>
 <?php
  }
 ?>

  <?php 

 if(isset($_POST['submit'])){
 	
 	$n=$_POST['number'];
 	
 	$k=$n+10;
 	$critere=$_POST['critere'];
 	
 	$next=0;
 	$offset=0;
 	display($k,$critere,$offset);
 	echo'<input type="hidden" name="k" value="'.$k.'">';
 	//echo'<input type="hidden" name="offset" value="'.$offset.'">';
 	//echo'<input type="hidden" name="suiv" value="'.$next.'">';
 	//bl awl previous hidden ba3den bde ef7as next eza makbuse
 	
 	 ?>
 	<script type='text/javascript'>
 	window.onload = function(){
 		 		document.getElementById('next').style.display = 'block';
 		 		var a =document.getElementById('num');
 		 		a.options[<?php echo $_POST['number'] ?>].selected=true;
 		 		var b=document.getElementById('crit');
 		 	 	b.options[<?php echo $_POST['critere'] ?>].selected=true;
 		 		
 		 	 	
 	}
 	</script>
 		 		
  
    
 	
 	<?php 
 	
    }// fin de ifisset 
 	?>
 
 
 
<?php
?>

<input type="hidden" name="offset" id="offset" value="0"/>
<input type="hidden" name="suiv" id="suiv" value="0"/>
 	<?php 
 	 
   
 	      if(isset($_POST['next'])){
 	      ?>
 	      <script type='text/javascript'>
 	  window.onload = function(){
 	  	document.getElementById('previous').style.display = 'block';
 	  	document.getElementById('next').style.display = 'block';
 	 var a =document.getElementById('num');
	 		a.options[<?php echo $_POST['number'] ?>].selected=true;
	 		var b=document.getElementById('crit');
	 	 b.options[<?php echo $_POST['critere'] ?>].selected=true;
 	  }
 	  </script>
 	  <?php 
 	// $next
 	//  $offset = $_COOKIE['off'];
 	//  $k=$_COOKIE['num'];
 	//  $critere=$_COOKIE['crit'];
 //	$critere=$_POST['critere'];
 	//$next = $_COOKIE['next'];
 	//$offset=$_COOKIE['offset'];
 	//$k=$_COOKIE['k'];
 	//$next++;
 	 $n=$_POST['number'];
 	 $k=$n+10;
 	 $next=$_POST['suiv'];
 	 $next=$next+1;
 	 $offset=$_POST['offset'];
 	 $offset+=$k;
 	 $critere=$_POST['critere'];
 	 
 	 echo'<input type="hidden" name="k" value="'.$k.'">';
 	 echo'<input type="hidden" name="offset" value="'.$offset.'">';
 	 echo'<input type="hidden" name="critere" value="'.$critere.'">';
 	 echo'<input type="hidden" name="suiv" value="'.$next.'">';
 	 
 	 
 	 display($k,$critere,$offset);
 	 $_POST['offset']=$offset;
 	 $_POST['critere']=$critere;
 	 $_POST['suiv']=$next;
 	  
 	    }
 	    if(isset($_POST['previous'])){
 	    	?>
 	    
 	    	<script type='text/javascript'>
 	    	window.onload = function(){
 	    		document.getElementById('previous').style.display = 'block';
 	    		document.getElementById('next').style.display = 'block';
 	    		
 	    		var a =document.getElementById('num');
 	    		a.options[<?php echo $_POST['number'] ?>].selected=true;
 	    		 		var b=document.getElementById('crit');
 	    		 	 b.options[<?php echo $_POST['critere'] ?>].selected=true;
 	    	 	  }
 	    	 	  </script>
 	    	 	  <?php
 	    	 	
 	    	$next=$_POST['suiv'];
 	    	if($next!=0){
 	    	$n=$_POST['number'];
 	    	$k=$n+10;
 	    	$offset=$_POST['offset'];
 	    	$offset-=$k;
 	    	$next=$_POST['suiv'];
 	    	$next=$next-1;	
 	    	$critere=$_POST['critere'];
 	    	echo'<input type="hidden" name="k" value="'.$k.'">';
 	    	echo'<input type="hidden" name="offset" value="'.$offset.'">';
 	    	echo'<input type="hidden" name="critere" value="'.$critere.'">';
 	    	echo'<input type="hidden" name="suiv" value="'.$next.'">';
 	    	display($k,$critere,$offset);
 	    	$_POST['offset']=$offset;
 	    	$_POST['critere']=$critere;
 	    	$_POST['suiv']=$next;
 	    	}
 	    	else{
 	    		
 	    		echo "<script type='text/javascript'>document.getElementById('previous').style.display='none';</script>";
 	    	}
 	   
 	    }
 
 ?>
   
 </form>
</body>
</html>
 
