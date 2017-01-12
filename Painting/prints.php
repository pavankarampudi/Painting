<?php

include 'Header1.php';
?>
<div style="text-align:center">
     <?php
require 'mysqli_connect.php';

  if($_SERVER['REQUEST_METHOD']=='POST')
  {
      //validate the incoming data
      $errors=array();
      // check for the printname
      if(!empty($_POST['print_name']))
      {
          $pn=trim($_POST['print_name']);
      }
      else
      {
          $errors[]="please enter the prints name";
      }
      // chec for an image
      if(is_uploaded_file($_FILES['image']['tmp_name']))
     {
          // create a temporary file name
        $temp='./uploads/'.md5($_FILES['image']['name']);
        
        // move the file over
        
        if(move_uploaded_file($_FILES['image']['tmp_name'], $temp))
        {
            echo '<p style="font-weight:bold;color:green">File has been uploaded</p>';
            // set the $i var name to the images name
            $i=$_FILES['image']['name'];
        }
        else
        {
            $errors[]="The file could not be moved";
            $temp=$_FILES['image']['tmp_name'];
        }
      }
       else
       {
           $errors[]='no file was uploaded';
           $temp=NULL;
       }
       // check for a size (not required)
       $s=(!empty($_POST['size']))?trim($_POST['size']):NULL;
       
       // check for price
       if(is_numeric($_POST['price']) && ($_POST['price']>0))
       {
           $p=(float)$_POST['price'];
       }
       else
       {
           $errors[]='please enter the prints price!';
       }
   // check for description or not
       $d=(!empty($_POST['description']))?trim($_POST['description']):NULL;
        // validate the artist
 if(isset($_POST['artist']) && filter_var($_POST['artist'],FILTER_VALIDATE_INT,array('min_range'=>1)))
       {
           $a=$_POST['artist'];
       }
      else
      {
          $errors[]='plese select the print\s artist!';
      }
      
        if(empty($errors)){
            // every thing is ok.
            $q='Insert into prints(artist_id,print_name,price,size,description,image_name)
                 value(?,?,?,?,?,?)';
            $stmt=  mysqli_prepare($dbc, $q);
            mysqli_stmt_bind_param($stmt, 'isdsss',$a,$pn,$p,$s,$d,$i);
            mysqli_stmt_execute($stmt);
            if(mysqli_stmt_affected_rows($stmt)==1)
            {
                echo '<p style="font-weight:bold;color:green"> The print has been added.</p>';
                // Rename the image
                $id=  mysqli_stmt_insert_id($stmt);
                rename($temp,"./uploads/$id");
                //clear $_POST
                $_POST=array();
            }
            else
            {
                echo '<p Style="font-weight:bold;color:c00">your submission
                    cannot be processed due to system error.</p>';
                
            }
            mysqli_stmt_close($stmt);
        }
        // delete upload files if still exists
        if(isset($temp) && file_exists($temp) && is_file($temp))
        {
            unlink($temp);
        }
  }
  //check for any errors and print them
  
  if(!empty($errors) && is_array($errors))
  {
       echo '<h1>Error!</h1>
       <p style="font-weight:bold;color:#c00">The following error occured:
       <br>';
       foreach($errors as $msg)
       {
         echo "-$msg<br/>\n";
       }
      echo 'please reselect the print image and try again </p>';
    }
 //  Displaying the form  
?>
        <h1> Add A print</h1>
        <form enctype="multipart/form-data" action="prints.php"  method="post">
          <input type="hidden" name="max-file-size" value="524288" />
          
          <fieldset>
              <legend>
                  Fill out the form to add a print to catalog:
              </legend>
          <table align="center">
         
        <tr><td>  <p>
              <b>print Name: </b>
           <td>   <input type="text" name="print_name" size="30" maxlength="60"
                     value="<?php if(isset($_POST['print_name'])) 
                         echo htmlspecialchars($_POST['print_name']);?>" />
          </p>
      <tr><td><p>
              <b>Image: </b>
            <td>  <input type="file" name="image"  />
          </p>
         <tr><td>
              <b>Artist: </b>
            <td>  <select name="artist">
                  <option>Select One</option>
                <?php
                // Retrive all artist and add to pull down menu.
                $q="select artist_id,concat_ws('',first_name,middle_name
                    ,last_name)from artists order by last_name,first_name asc";
                $r=  mysqli_query($dbc, $q) or die(mysql_error());
                if(mysqli_num_rows($r)>0)
                {
                    while ($row=  mysqli_fetch_array($r, MYSQLI_NUM))
                            
                    {
                        echo "<option value=\"$row[0]\"";
                        // check for stickyness
                        if(isset($_POST['existing']) &&($_POST['existing']==$row[0]))
                        
                        echo 'selected="selected"';
                        echo ">$row[1]</option>\n";
                        
                    }
                }
                        else
                        {
                            echo '<option> please add a new artist first</option';
                        }
                        
                        mysqli_close($dbc);
                    
                     
                    
                   
                ?>
              </select>
                  
       <tr> <td>  
              <b>price: </b>
             <td> <input type="text" name="price" size="10" maxlength="10"
                     value="<?php if(isset($_POST['price'])) 
                         echo ($_POST['price']);?>" />
              <small>Do not include the dollar sign  or comma.</small>
          </p>
       <tr><td>   <p>
              <b>size: </b>
            <td>  <input type="text" name="size" size="30" maxlength="60"
                     value="<?php if(isset($_POST['size'])) 
                         echo htmlspecialchars($_POST['size']);?>" />(optional)
          </p>
          
        <tr><td>  <p>
              <b>Description : </b>
         <td>     <textarea name="description" cols="40" rows="5" >
                    <?php if(isset($_POST['Description'])) 
                         echo htmlspecialchars($_POST['Description']);?>
              </textarea>(optional)
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
