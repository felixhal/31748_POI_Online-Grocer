<?php 
	session_start();
	$error = "";
	$valid = "";
	//Checks for if there is any purchase been made
	if($_REQUEST["purchase"])
	{
		//Validates the fields reuqired
		if(!$_REQUEST["name"])
		{
			$error .= "An name is required<br>";
		}
		if(!$_REQUEST["email"])
		{
			$error .= "A email address is required<br>";
		}
		if(!$_REQUEST["address"])
		{
			$error .= "An address is required<br>";
		}
		if(!$_REQUEST["suburb"])
		{
			$error .= "A suburb is required<br>";
		}
		if(!$_REQUEST["state"])
		{
			$error .= "A state is required<br>";
		}
		if(!$_REQUEST["country"])
		{
			$error .= "An country is required<br>";
		}

		//php function thats validate if a string is an email address
		if (filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL) == false)
		{
			$error .= "email is not valid email address<br>";
		}
		
		//Checks if the error variable has something in it
		if($error != "")
		{
			$error = "<p>There are the following errors</p><p>$error</p>";
		}
		else
		{
			//Email fields
			$emailTo = $_REQUEST["email"];
			$subject = "Order Confirmation";
			$content = "Your Order has been received! along with the payment has been received"; 
			$headers = "From: john@gmail.com";
			
			//if the email function is successfully sent
			if(mail($emailTo, $subject, $content, $headers))
			{
				//Validation message is displayed and a link to the main page is produced
				$valid = "Order Received, Please Check Your Inbox for confirmation "."<a href=\"Index.html\">Click Here to Continue Shopping</a>";
				foreach($_SESSION["shopping_cart"] as $keys => $values)
				{
					//Clears the shopping cart for next session of shopping
					unset($_SESSION["shopping_cart"][$keys]);
				}
			}
			else
			{
				print "";
			}
			
		}
	}

?>
<html>
	<head>
		<title>Check Out</title>
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<style type="text/css">
		body
		{
			background-color: #f6f7f8;
			font-family: 'Poppins', sans-serif;
		}
		table
		{
			margin-left: auto;
			margin-right: auto;
			border-collapse: collapse;
			width: 50%;
			align: center;
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
		
		.submit 
		{
			box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
		}
		
		.submit 
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
			font-family: 'Poppins', sans-serif;
			margin: 8px 8px;
			cursor: pointer;
			transition-duration: 0.4s;
		}
		
		.submit:hover 
		{
			box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
		}
		input[type=text]
		{
			border: 2px solid black;
			width: 70%;
			padding: 12px 20px;
			margin: 8px 0;
			border-radius: 4px;
		}
		</style>
	</head>
	<body>
	<div id="valid"><?php print $valid?></div>
	<h3>Order Summary<h3>
			<table>
			<tr>
				<th>Item Name</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Total</th>
				<th></th>
			</tr>
			<?php
			$total = 0;
			$totalQuantity = 0;
			foreach($_SESSION["shopping_cart"] as $keys => $values)
			{
			?>
			<tr>
				<td><?php print $values["item_name"];?></td>
				<td><?php print $values["item_quantity"];?></td>
				<td>A$.<?php print $values["item_price"];?></td>
				<td>A$.<?php print number_format($values["item_quantity"]*$values["item_price"], 2);?> </td>
				<td></td>
			</tr>
			<?php
				$total = $total + ($values["item_quantity"] * $values["item_price"]);
				$totalQuantity = $totalQuantity + ($values["item_quantity"]);
				$item_name = $values["item_name"];
				$item_Quantity = $values["item_quantity"];
				$item_price = $values["item_price"];
			}
			?>
			<tr>
				<td colspan="3" align="right">Total Purchase</td>
				<td>A$.<?php print $total ?></td>
			</tr>
			</table>
	<h3>Purchase Form</h3>
	<div id="error"><span style="color:red"><?php print $error?><span></div>
	<form method="post">
		<table>
			<tr>
				<td><span style="color:red">*</span>Name</td>
				<td><input type="text" name="name" id="name" required></td>
			</tr>
			<tr>
				<td><span style="color:red">*</span>Email</td>
				<td><input type="text" name="email" id="email" required></td>
			</tr>
			<tr>
				<td><span style="color:red">*</span>Address</td>
				<td><input type="text" name="address" id="address" required></td>
			</tr>
			<tr>
				<td><span style="color:red">*</span>Suburb</td>
				<td><input type="text" name="suburb" id="suburb" required></td>
			</tr>
			<tr>
				<td><span style="color:red">*</span>State</td>
				<td><input type="text" name="state" id="state" required></td>
			</tr>
			<tr>
				<td><span style="color:red">*</span>Country</td>
				<td><input type="text" name="country" id="country" required></td>
			</tr>
			<tr>
				<td><input type="submit" name="purchase" value="purchase" id="purchase" class="submit"></td>
				<td><input type="button" name="cancel" class="submit" value="cancel" onclick="document.location = 'Index.html'"></td>
			</tr>
		</table>
    </form>
	
	</body>
</html>
