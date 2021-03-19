<html>
	<head>
		<title>Main</title>
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<style type="text/css">
		body
		{
			background-color: #84a98c;
			font-family: 'Poppins', sans-serif;
		}
		#shopping
		{
			width:150px;
			height:100px;
			margin-left:40%;
		}
		h4
		{
			font-size: 16;
			margin-left: 30%;
		}
		table
		{
			border-collapse: collapse;
			width: 100%;
		}
		
		th
		{
			font-size="14px";
			background-color: #d2d2cf;
		}
		
		td
		{
			font-size="12px";
		}
		
		th, td 
		{
			text-align: center;
			padding: 8px;
		}

		tr:nth-child(odd) 
		{
		}
		
		#submit 
		{
			box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
		}
		
		#submit 
		{
			background-color: #f2f2f2;
			border-radius: 12px;
			border: none;
			color: black;
			padding: 10px 10px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 12px;
			margin: 8px 8px;
			cursor: pointer;
			transition-duration: 0.4s;
		}
		
		#submit:hover 
		{
		  box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
		}
		</style>
	</head>
	<body>
	<?php
	//Connects to mysql database
	$connection = mysqli_connect("rerun", "potiro", "pcXZb(kL", "poti");
	//Checks if the connection is valid
	if(!$connection)
    {
        //if not than kill attempt to connect and display message
        die("Server Error");
    }
	$product_id = $_REQUEST["product"];
	//If there is nothing in the $_REQUEST array
	if($product_id == null)
	{
		//Default Page
		echo "<br>";
		echo "<img src= groceries.png "." id=shopping>";
		echo "<h4>Start Browsing our Products</h4><br>";
	}
	//If there is something in the $_REQUEST array
	else
	{
		//Get product_id
		$product_id = $_REQUEST["product"];
		//Create Query
		$query_string="select * from products where (product_id = $product_id)";
		//Query Product Details
		$results=mysqli_query($connection, $query_string);
		$num_rows=mysqli_num_rows($results);
		//Display all details from database
		if($num_rows>0)
		{
			print "<table>";
			//While there are still rowas to be fetch keep displaying them in this format (mysqli_fetch_ssoc outputs in the form of an associativve array)
			while($a_row=mysqli_fetch_assoc($results))
			{
            //Prints the rows (with item details per row)
				print "<h3>$a_row[product_name] ($a_row[unit_quantity])</h3>";
				print "<tr>\n";
				print "<th>Id</th>";
				print "<th>Name</th>";
				print "<th>Price</th>";
				print "<th>Quantity</th>";
				print "<th>In stock</th>";
				print "<th>Purchase Amount</th>";
				print "</tr>";
				
				print "<tr>\n";
				print "<td>$a_row[product_id]</td>";
				print "<td>$a_row[product_name]</td>";
				print "<td>A$ $a_row[unit_price]</td>";
				print "<td>$a_row[unit_quantity]</td>";
				print "<td>$a_row[in_stock]</td>";
				print "<form method=\"post\" action=\"shopping_cart.php\" target=\"shopping_cart\"><td><input type=\"text\" name=\"quantity\" id=\"quantity\" required>";
				print "<input type=\"hidden\" name=\"hiddenId\" value=\"$a_row[product_id]\">";
				print "<input type=\"hidden\" name=\"hiddenName\" value=\"$a_row[product_name]\">";
				print "<input type=\"hidden\" name=\"hiddenPrice\" value=\"$a_row[unit_price]\">";
				print "<button type=\"submit\" id=\"submit\" name=\"add_to_cart\" >Add to Cart</button></td></form>";
				print "<div id=\"error\"><div>";
				print "</tr>";
				
			}
        print "</table>";
		}
	}
	//After finish terminate the connection to the database
	mysqli_close($connection);
	?>
	
	<!--Form validation using JQuery-->
	<script>
	//Checks for a form attribute that is submitted
	$("form").submit(function (e) 
	{
		//variable to contain error messages
		var error = "";
		//variable to contain success messages
		var valid= "Item Added to Cart";
		//variable to get the value of the quantity inputed by user
        var inputVal = $("#quantity").val();
		//IsNumeric is a JQuery function that returns true of the value is a number and false otherwise
       	if(!($.isNumeric(inputVal)))
		{
			//Prepends to error message
			error += "<p>Please enter a number</p><br>";
		}
		//Check if the inputed value is more than 20 (so, the user does not buy unrealistic amounts)
		if(inputVal>20)
		{
			//Prepends to error message
			error += "Maximum quantity is 20<br>";
		}
		//Checks if the inputed value is a positive integer (you can't buy -1 Apple)
		if(inputVal<0)
		{
			//Prepends to error message
			error += "Invalid Quantity<br>";
		}
		//Checks the error variable for error messages
		if(error != "")
		{
			//if it's not empty that display the error message and return false
			$("#error").html(error);
			//return false prevents the form from being submitted
			return false;
		}
		else
		{
			//if nothing is wrong display a success message and proceede to submit the form to the shopping_cart.php
			$("#error").html(valid);
			return true;
		}
    });
	</script>
	</body>
</html>
