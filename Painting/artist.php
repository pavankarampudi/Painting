<?php

include 'Header1.php';
?>
<div style="text-align:center">
    
     <?php
        // put your code here
         if($_SERVER['REQUEST_METHOD']=='POST')
         {
             // validate the first and middle names(neither required)
             $fn=(!empty($_POST['first_name']))
             ? trim($_POST['first_name']):NULL;
             $mn=(!empty($_POST['middle_name']))
             ? trim($_POST['middle_name']):NULL;
             // check for the last name
             if(!empty($_POST['last_name']))
             {
                 $ln=trim($_POST['last_name']); 
             
             // add an artist to the database
             require ('mysqli_connect.php');
             $q='insert into artists(first_name,middle_name,last_name)
                 values(?,?,?)';
             $stmt=  mysqli_prepare($dbc, $q);
             mysqli_stmt_bind_param($stmt, 'sss',$fn,$mn,$ln);
             mysqli_stmt_execute($stmt);
             //check the result
             if(mysqli_stmt_affected_rows($stmt)==1)
             {
                 echo "<p style='color:green'> The Artist has been added successfully added";
                 $post=array(); 
                 
             }
             else
                 $error='The new Artist could not be added to the database';
             
             mysqli_stmt_close($stmt);
             mysqli_close($dbc);
             
             
         
         }
         else
         {
             $error="please enter the artist name";
         }
         
     }
     // check for an error and print it
      if(isset($error))
      {
          echo "<h1>ERROR</h1>
          <p style='font-weight:bold;color:#C00'>".$error.'Please Try againt.</p>';
      }
        ?>
        <h1>Add a Artist</h1>
        <form action='artist.php' method="post">
            <fieldset>
                <legend>
                    Fill out the Form to add an artist
                </legend>
                <table align="center">
              <tr><td> <p><b>First Name:</b>
         <td><input type="text" name="first_name" size="10" maxlength="20"
        value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name'] ?>" />
         
         
               </p>
              <tr><td> <p><b>Middle Name:</b>
        <td><input type="text" name="middle_name" size="10" maxlength="20"
        value="<?php if(isset($_POST['middle_name'])) echo $_POST['middle_name'] ?>" />
               </p>
              <tr><td> <p><b>Last Name:</b>
       <td>  <input t0ype="text" name="last_name" size="10" maxlength="20"
        value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name'] ?>" />
         
         
               </p>       
         
                </table>
            </fieldset>
           <div align="center">
                   
                   <input type="submit" name="submit" value="submit" />
                    
           </div>    
        </form>
</div>
    <?php
include 'footer.php';
?>
