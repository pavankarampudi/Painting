<?php

// This page inserts the order information into table
// This page would come after the billing process
// This page assume that the billing process worked (money has been taken)
// set the page title and include the html header
$page_title='Order Confirmation';
include 'Header2.php';
if(!isset($_SESSION['user']))
{
    header('Location:Welcome_user.php');
}
$email=$_SESSION['user'];
?>

 <?php
// Assume that the customer i logged in and that this page has access to cutomers id
include 'mysqli_connect.php';
$r=mysqli_query($dbc,"select customer_id from customers where email='$email'") or die(mysql_errno($dbc));
$row=mysqli_fetch_array($r,MYSQLI_NUM);
$cid=$row[0]; // Temporary
// Assume that this page receives order total
$total=$_SESSION['total'];

require 'mysqli_connect.php';

// Connect to database
//Turn autocommit off;
mysqli_autocommit($dbc,FALSE);
// Add the order to the orders table
$q="insert into orders(customer_id,total)values($cid,$total)";
$r=  mysqli_query($dbc, $q);
if(mysqli_affected_rows($dbc)==1)
{
    // need the order id
    $oid=  mysqli_insert_id($dbc);
    // insert the specific order conetent into the database
    // prepare the query
    $q="insert into order_contents(order_id,print_id,quantity,price)
        values(?,?,?,?)";
    $stmt=mysqli_prepare($dbc,$q);
    mysqli_stmt_bind_param($stmt, 'iiid',$oid,$pid,$qty,$price);
    // Execute each query Count the total effected
    $affected=0;
    foreach ($_SESSION['cart'] as $pid=>$item)
    {
        $qty=$item['quantity'];
        $price=$item['price'];
        mysqli_stmt_execute($stmt);
        $affected+=mysqli_stmt_affected_rows($stmt);
        
    }
    // close the prepared statement
    mysqli_stmt_close($stmt);
    // Report on the success
    if($affected==count($_SESSION['cart']))
    {
        // Commit the transaction
        mysqli_commit($dbc);
        // clear the cart
        unset($_SESSION['cart']);
        // Message to the customer
        echo '<p> Thank you for your order you will be getting email notification for you order';
        // send email and do what ever
        
        require 'class.phpmailer.php';

$mail = new PHPMailer;

$mail->IsSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';                 // Specify main and backup server
$mail->Port = 587;                                    // Set the SMTP port
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'pavankarampudi1@gmail.com';                // SMTP username
$mail->Password = '9908872716';                  // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

$mail->From = 'pavankarampudi1@gmail.com';
$mail->FromName = 'Online Painting Store';
$mail->AddAddress($email);  // Add a recipient
//$mail->AddAddress('ellen@example.com');               // Name is optional

$mail->IsHTML(true);                                  // Set email format to HTML

$mail->Subject = 'E-Shopping Cart';
$mail->Body    = 'Your order for the paintings will delivered with in Two days<br> Your total bill amount is   </strong>'.$total;
$mail->AltBody = '';

if(!$mail->Send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}

echo '<br><br>Message has been sent';
        
        // end of code
    }
    else
    {
        // Rollback and report
        mysqli_rollback($dbc);
            echo '<p>Your order could be order due to system error</p>';
         // send the order information to administrator
   }
   
}
else
{
    // Rollback and report
        mysqli_rollback($dbc);
            echo '<p>Your order could be order due to system error</p>';
         // send the order information to administrator
}
mysqli_close($dbc);
include 'footer.php';
?>
