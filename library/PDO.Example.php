<?php
	require("Db.class.php");

	// Creates the instance
	$db = new Db();
		
	// 3 ways to bind parameters :		
	
	// 1. Read friendly method	
	$DB->bind("firstname","John");
	$DB->bind("age","19");

	// 2. Bind more parameters
	$DB->bindMore(array("firstname"=>"John","age"=>"19"));		

	// 3. Or just give the parameters to the method
	$DB->query("SELECT * FROM Persons WHERE firstname = :firstname AND age = :age", array("firstname"=>"John","age"=>"19"));

	//  Fetching data
	$person 	 =     $DB->query("SELECT * FROM Persons");

	// If you want another fetchmode just give it as parameter
	$persons_num =     $DB->query("SELECT * FROM Persons", null, PDO::FETCH_NUM);
	
	// Fetching single value
	$firstname	 =     $DB->single("SELECT firstname FROM Persons WHERE Id = :id ", array('id' => '3' ) );
	
	// Single Row
	$id_age 	 =     $DB->row("SELECT Id, Age FROM Persons WHERE firstname = :f", array("f"=>"Zoe"));
		
	// Single Row with numeric index
	$id_age_num  =     $DB->row("SELECT Id, Age FROM Persons WHERE firstname = :f", array("f"=>"Zoe"),PDO::FETCH_NUM);
	
	// Column, numeric index
	$ages  		 =     $DB->column("SELECT age FROM Persons");

	// The following statemens will return the affected rows
	
	// Update statement
	$update		=  $DB->query("UPDATE Persons SET firstname = :f WHERE Id = :id",array("f"=>"Johny","id"=>"1")); 
	
	// Insert statement
//	$insert	 	=  $DB->query("INSERT INTO Persons(Firstname,Age) 	VALUES(:f,:age)",array("f"=>"Vivek","age"=>"20"));
	
	// Delete statement
//	$delete	 	=  $DB->query("DELETE FROM Persons WHERE Id = :id",array("id"=>"6")); 
	
	function d($v,$t) 
	{
		echo '<pre>';
		echo '<h1>' . $t. '</h1>';
		var_dump($v);
		echo '</pre>';
	}
	d($person, "All persons");
	d($firstname, "Fetch Single value, The firstname");
	d($id_age, "Single Row, Id and Age");
	d($ages, "Fetch Column, Numeric Index");
$DB->CloseConnection();//Close
?>