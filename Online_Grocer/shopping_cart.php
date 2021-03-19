<html>
	<head>
		<title>Shopping Cart</title>
		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
		<style type="text/css">
		body
		{
			background-color: #c9ada7;
			font-family: 'Poppins', sans-serif;
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	</head>
	<body> 
	<h2>Shopping Cart</h2>
	<?php
	//Starts global session variable
	session_start();
	//Implementation of array_column function (since array_column is not supported in php 5.3.21)
	function array_column($array, $column_name)
    {
		//array maps sends each value of an array to the function
		//Using the "use" keyword to pass in the column name (item_id)
		return array_map(function($element) use($column_name)
		{
			//returns the item
			return $element[$column_name];
		//Scope of the array (in this case the $_SESSION["shopping_cart"] associative array)
		}, $array);
    }
	$connection = mysqli_connect("rerun", "potiro", "pcXZb(kL", "poti");
	//Checks if there are any request from the product_details.php
			if(isset($_REQUEST["add_to_cart"]))
			{
				//Checks if the shopping cart is empty or not
				if(isset($_SESSION["shopping_cart"]))
				{
					//array columns returns the value of a single column of the input (identified using the column key in this case item_id) in the array
					$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
					//If the item id passed is not in the array than append the id into the array
					if(!in_array($_REQUEST["hiddenId"], $item_array_id))
					{
						//counts the size of the array
						$count = count($_SESSION["shopping_cart"]);
						$item_array = array(
						"item_id" => $_REQUEST["hiddenId"],
						"item_name" => $_REQUEST["hiddenName"],
						"item_price" => $_REQUEST["hiddenPrice"],
						"item_quantity" => $_REQUEST["quantity"]
						);
						//Assigns the new item in the highest index ($count)
						$_SESSION["shopping_cart"][$count] = $item_array;
					}
					//If the item exist already than it cannot be added
					else
					{
						print "<script>alert(\"Item Already Added\")</script>";
					}
				}
				//if the shopping cart is empty add the item from index 0
				else
				{
					$item_array = array(
					"item_id" => $_REQUEST["hiddenId"],
					"item_name" => $_REQUEST["hiddenName"],
					"item_price" => $_REQUEST["hiddenPrice"],
					"item_quantity" => $_REQUEST["quantity"]
					);
					//Assigns new item in the first index
					$_SESSION["shopping_cart"][0] = $item_array;
				}
			}
			else{}

			//Checks to see if there is $_REQUEST with name "action" (GET method)
			if(isset($_REQUEST["action"]))
			{
				//if the action value is delete
				if($_REQUEST["action"] == "delete")
				{
					//For each item in the shopping cart
					foreach($_SESSION["shopping_cart"] as $keys => $values)
					{
						//Deletes the item with the passed id with the get method
						if($values["item_id"] == $_GET["hiddenId"])
						{
							//Delete everything with the identifier key
							unset($_SESSION["shopping_cart"][$keys]);
							//Alert message to the user that the item has been removed
							print "<script>alert(\"Item Removed\")</script>";
							//Redirect user to the shopping cart page
							print "<script>window.location=\"shopping_cart.php\"</script>";
						}
					}
				}
			}
			else{}

			//Checks if there is a clear request
			if(isset($_REQUEST["clear"]))
			{
				//for each item in the shopping cart
				foreach($_SESSION["shopping_cart"] as $keys => $values)
				{
					//Delete everything
					unset($_SESSION["shopping_cart"][$keys]);
					print "<script>alert(\"Item Removed\")</script>";
					print "<script>window.location=\"shopping_cart.php\"</script>";
				}
			}
			else{}
			
			//Checks if there is a check out request
			if(isset($_REQUEST["checkOut2"]))
			{
				if(!isset($_SESSION["shopping_cart"]))
				{
				}
				//if there is no item in the shopping cart than prevent check out
				else
				{
					print "<script>alert(\"Shopping Cart is Empty\")</script>";
					print "<script>window.location=\"shopping_cart.php\"</script>";
				}
			}
			else{}	
		
	?>
	
		<?php
		if(!(empty($_SESSION["shopping_cart"])))
		{
			?>
			<h3>Order Details <h3>
			<table>
			<tr>
				<th>Item Name</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Total</th>
				<th>Action</th>
			</tr>
			<?php
			//Total Price variable
			$total = 0;
			//Total Quantity variable
			$totalQuantity = 0;
			foreach($_SESSION["shopping_cart"] as $keys => $values)
			{
		?>
			<tr>
				<td><?php print $values["item_name"];?></td>
				<td><?php print $values["item_quantity"];?></td>
				<td>A$.<?php print $values["item_price"];?></td>
				<td>A$.<?php print number_format($values["item_quantity"]*$values["item_price"], 2);?> </td>
				<!--A link to for a GET method that passes the delete action and hidden id to shopping_cart.php (this page) -->
				<td><a href="shopping_cart.php?action=delete&hiddenId=<?php print $values["item_id"];?>">Remove</a></td>
			</tr>
			<?php
				//Calculates the total price by multiplying the quantity and price everytime an item is added
				$total = $total + ($values["item_quantity"] * $values["item_price"]);
				//Calculates the total quantity by adding the item quantitiy everyrtime an item is added
				$totalQuantity = $totalQuantity + ($values["item_quantity"]);
			}
			?>
			<tr>
				<td colspan="3" align="right">Total Purchase</td>
				<td>A$.<?php print $total ?></td>
				<form method="POST">
					<td><input type="submit" name="clear" id="submit" value="Clear All"></td></form>
			</tr>
			<tr>
				<td colspan="3" align="right">Total Quantity Purchased</td>
				<td><?php print $totalQuantity ?> item(s)</td>
				<!-- Post method for checkOut that submits to the CheckOut.php page-->
				<form method="POST" action="CheckOut.php" target="_top">
				<input type="hidden" name="hiddenTotalPrice" value=<?php $total ?>>
				<input type="hidden" name="hiddenTotalQuantity" value=<?php $totalQuantity?>>
				<td><input type="submit" name="checkOut" id="submit" value="Check Out"></td>
				</form>
			</tr>
			</table>
		<?php
		}
		else
		{
			print "Your Shopping Cart is empty";
			print "<form method=\"POST\">
					<input type=\"submit\" name=\"clear\" id=\"submit\" value=\"Clear All\"></form>
					
					<form method=\"POST\">
					<input type=\"submit\" name=\"checkOut2\" id=\"submit\" value=\"Check Out\"></form>";
		}
		?>
	</body>
</html>