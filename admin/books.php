<?php include '../functions.php'?>
<?php
if(!isset($_SESSION['user'])) header("location: ../login.php");
?>
<?php
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: ../index.html");
}

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>books</title>
    <link rel="stylesheet" href="main/css/linearicons.css" />
  <link rel="stylesheet" href="main/css/font-awesome.min.css" />
  <link rel="stylesheet" href="main/css/bootstrap.css" />
  <link rel="stylesheet" href="main/css/magnific-popup.css" />
  <link rel="stylesheet" href="main/css/owl.carousel.css" />
  <link rel="stylesheet" href="main/css/nice-select.css">
  <link rel="stylesheet" href="main/css/hexagons.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css" />
  <link rel="stylesheet" href="main/css/main.css" />
    <style>
.card {
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
  width: 80%;
}

.card:hover {
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}

.container {
  padding: 2px 16px;
}
.cover {
  object-fit: cover;
  width: 50px;
  height: 100px;
}
.button {
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 15px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}
.buttonLocal {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  padding: 16px 16px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  -webkit-transition-duration: 0.4s; /* Safari */
  transition-duration: 0.4s;
  cursor: pointer;
  border-radius:8px;
  width:70px;
}

.button1Local {
  background-color: #a836e8; 
  color: black; 
  border: 2px solid #7d31fb;
}
.button1Local:hover{
  background-color: #a836e8;
  color: white;
}
.btn-grad {background-image: linear-gradient(to right, #7e32fb 0%, #9733EE 51%, #7e32fb 100%)}
.btn-grad:hover { background-position: right center;color:white; }


.buttonLocal:hover {
  background-color: #2EE59D;
  box-shadow: 0px 15px 20px rgba(46, 229, 157, 0.4);
  color: #fff;
  transform: translateY(-7px);
}
</style>
  </head>
  <body>
  <header class="">
    <nav style="background-image: linear-gradient(to right, #7e32fb 0%, #9733EE 51%, #7e32fb 100%)">
      
        <a class="navbar-brand" href="index.html">
          <img src="../main/img/elibrarylogo.png" alt="" height="50px" width="100px" />
        </a>
        <a  style="float:right;margin-left:24px;" href="books.php?logout='1'"  class="buttonLocal">Logout!</a>
        
      </nav>
    
  </header>

  <section >

      <div class="row">
      <?php
        $books = listAdminBooks();
        $reserved = "";
        for ($i = 0; $i < sizeof($books); $i++) {
            if($books[$i][3] == 1) $reserved = '<h1>Reserved!</h1>';
            else $reserved='';
echo '<div class="col-md-3" style="margin:8px;">
                <div class="card">
                <img height="200px" width="100%" src="../uploads/'.$books[$i][4].'">
                
                  <div class="container">
                  
                    <h4><b>' . $books[$i][1] . '</b></h4>
                    <p>' . $books[$i][2] . '</p>
                    '.$reserved.'
                  </div>
                </div>
            </div>';
        }
?>

<?php 

 if(isset($_GET['id'])){
  reserve();
 }
 

 function reserve(){
   $id =  $_GET['id'];
   $db = mysqli_connect('localhost', 'root', '', 'E-library');
  $query = "UPDATE  books SET reserved = 1 where serialNo =".$id;
  mysqli_query($db, $query);
 }

?>
      </div>
    </div>
   <button onClick="phpcall();"></button>
    <script>
    function phpcall(){
      let btn = document.getElementById('reserve');
      let excec = <?php reserve(btn);?>
    }
    </script>

    

    </section>







    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
