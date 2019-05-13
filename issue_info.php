<?php
  include "connection.php";
  include "navbar.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Book Request</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<style type="text/css">

		.srch
		{
			padding-left: 850px;

		}
		.form-control
		{
			width: 300px;
			height: 40px;
			background-color: black;
			color: white;
		}
		
		body {
			background-image: url("images/aa.jpg");
			background-repeat: no-repeat;
  	font-family: "Lato", sans-serif;
  	transition: background-color .5s;
}

.sidenav {
  height: 100%;
  margin-top: 50px;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #222;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidenav a:hover {
  color: white;
}

.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

#main {
  transition: margin-left .5s;
    padding: 16px;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
.img-circle
{
	margin-left: 20px;
}
.h:hover
{
	color:white;
	width: 300px;
	height: 50px;
	background-color: #00544c;
}
.container
{
	height: 600px;
	background-color: black;
	opacity: .8;
	color: white;
}
.scroll
{
  width: 100%;
  height: 500px;
  overflow: auto;
}
th,td
{
  width: 10%;
}

	</style>

</head>
<body>
<!--_________________sidenav_______________-->
	
	<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

  			<div style="color: white; margin-left: 60px; font-size: 20px;">

                <?php
                if(isset($_SESSION['login_user']))

                { 	echo "<img class='img-circle profile_img' height=120 width=120 src='images/".$_SESSION['pic']."'>";
                    echo "</br></br>";

                    echo "Welcome ".$_SESSION['login_user']; 
                }
                ?>
            </div><br><br>

 
  <div class="h"> <a href="books.php">Books</a></div>
  <div class="h"> <a href="request.php">Book Request</a></div>
  <div class="h"> <a href="issue_info.php">Issue Information</a></div>
  <div class="h"><a href="expired.php">Expired List</a></div>
</div>

<div id="main">
  
  <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; open</span>


	<script>
	function openNav() {
	  document.getElementById("mySidenav").style.width = "300px";
	  document.getElementById("main").style.marginLeft = "300px";
	  document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
	}

	function closeNav() {
	  document.getElementById("mySidenav").style.width = "0";
	  document.getElementById("main").style.marginLeft= "0";
	  document.body.style.backgroundColor = "white";
	}
	</script>
  <div class="container">
    <h3 style="text-align: center;">Information of Borrowed Books</h3><br>
    <?php
    $c=0;

      if(isset($_SESSION['login_user']))
      {
        $sql="SELECT student.username,roll,books.bid,name,authors,edition,issue,issue_book.return,issue_book.fine FROM student inner join issue_book ON student.username=issue_book.username inner join books ON issue_book.bid=books.bid WHERE issue_book.approve ='Yes' ORDER BY `issue_book`.`return` ASC";
        $res=mysqli_query($db,$sql);
        
        
        echo "<table class='table table-bordered' style='width:100%;' >";
        //Table header
        
        echo "<tr style='background-color: #6db6b9e6;'>";
        echo "<th>"; echo "Username";  echo "</th>";
        echo "<th>"; echo "Roll No";  echo "</th>";
        echo "<th>"; echo "BID";  echo "</th>";
        echo "<th>"; echo "Book Name";  echo "</th>";
        echo "<th>"; echo "Authors Name";  echo "</th>";
        echo "<th>"; echo "Edition";  echo "</th>";
        echo "<th>"; echo "Issue Date";  echo "</th>";
        echo "<th>"; echo "Return Date";  echo "</th>";
        echo "<th>"; echo "Fine";  echo "</th>";

        echo "</tr>";
      echo "</table>";

       echo "<div class='scroll'>";
        echo "<table class='table table-bordered' >";
      while($row=mysqli_fetch_assoc($res))
      {
        $d=date("Y-m-d");
        if($d > $row['return'])
        {
          $c=$c+1;
          $var='<p style="color:yellow; background-color:red;">EXPIRED</p>';

          mysqli_query($db,"UPDATE issue_book SET approve='$var' where `return`='$row[return]' and approve='Yes' limit $c;");
          
          echo $d."</br>";
        }

        echo "<tr>";
          echo "<td>"; echo $row['username']; echo "</td>";
          echo "<td>"; echo $row['roll']; echo "</td>";
          echo "<td>"; echo $row['bid']; echo "</td>";
          echo "<td>"; echo $row['name']; echo "</td>";
          echo "<td>"; echo $row['authors']; echo "</td>";
          echo "<td>"; echo $row['edition']; echo "</td>";
          echo "<td>"; echo $row['issue']; echo "</td>";
          echo "<td>"; echo $row['return']; echo "</td>";
          echo "<td>"; echo $row['fine'];  echo "</td>";
        echo "</tr>";
      }
    echo "</table>";
        echo "</div>";
       
      }
      else
      {
        ?>
          <h3 style="text-align: center;">Login to see information of Borrowed Books</h3>
        <?php
      }
    ?>
      
<?php
    //Calculate fine

     /* $i = 0;

      While(!$var1='<p style="color:yellow; background-color:green;">RETURNED</p>'){

      mysqli_query($db,"UPDATE  `issue_book` SET  `fine` =  , , `return` =  '$_POST[return]' WHERE username='$_SESSION[name]' and bid='$_SESSION[bid]';");
      $i++;
        } */ ?>

<?php
        function calculate_fines()
{
    $result1 = mysqli_query("SELECT * FROM issue_book;");
    // for each loan id in book loans table update the fines table
    while ($row1 = mysqli_fetch_array($result1)) {
        $do_not_take_fine = 0;
        //$loan_id = $row1{'Loan_id'};
        $username = $row1['username'];
        $issue = strtotime($row1['issue']);
        $return = strtotime($row1['return']);
        $days_diff = $issue - $return;
        $days_past_due_date = floor($days_diff / (60 * 60 * 24));
        if ($days_past_due_date > 0 || $row1['issue'] == '0000-00-00') {
            //book is returned after due date, charge fine
            //Fine Computation :-
            $current_date = time();
            $future_due_diff = $current_date - $due_date;
            $future_due = floor($future_due_diff / (60 * 60 * 24));
            if ($row1['issue'] == '0000-00-00' && $future_due > 0) {
                // if book is not returned till today's date, and due date has passed
                $diff = $current_date - $due_date;
            } elseif ($row1['issue'] != '0000-00-00') {
                // if book is returned but delayed from its due date
                $diff = $issue - $return;
            } else {
                //if book is not returned till today, but due date has still not passed
                //do nothing
                $do_not_take_fine++;
            }
            $paid = 0;
            $date_diff = floor($diff / (60 * 60 * 24));
            $fine = $date_diff * 0.25;
            //$result2 = mysql_query("SELECT * FROM FINES WHERE Loan_id = $loan_id");
            $result2 = mysqli_query("SELECT * FROM issue_book WHERE username = $username");
            // checking if this loan id is already there in fines table
            if ($row2 = mysqli_fetch_array($result2)) {
                // Already paid the fine. do nothing
                // if not paid fine then update the fine table with new fine_amt
                if ($row2['fine'] == 0 && $do_not_take_fine == 0) {
                    $result3 = mysqli_query($db,"UPDATE 'issue_book' SET 'fine' = '$_POST[fine]' WHERE username ='$_SESSION[name]' and bid='$_SESSION[bid]';");
                }
            } else {
                // this loan id is not present in fines table, it's a new entry, so use insert command
                if ($do_not_take_fine == 0)
                    $result3 = mysqli_query($db,"INSERT INTO issue_book VALUES($username, $fine, $paid);");
            }
        }
        // else Borrower has returned by the due date so no charge is to be fined on the borrower.
    }
    echo "<br><br><br><br> Fines table is updated successfully !!!";
}
$dbhandle->close();

      ?>
      ?>
  </div>
</div>
</body>
</html>