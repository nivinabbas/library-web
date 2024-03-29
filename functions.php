<?php
session_start();
// $path = dirname(__DIR__).'/'."library-web/login.php";
// chmod($path,0777);
// connect to database
$db = mysqli_connect('localhost', 'root', '', 'E-library');

// variable declaration
$username = "";
$email = "";
$errors = array();

$serialNo = "";
$name = "";
$category = "";

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
    register();
}

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
    login();
}

if (isset($_POST['add_book'])) {
    registerBook();
}

if (isset($_POST['reserve_book'])) {
    reserve();
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: ../login.php");
}
// REGISTER USER
function register()
{
    global $db, $errors;

    // receive all input values from the form
    $username = e($_POST['username']);
    $email = e($_POST['email']);
    $password_1 = e($_POST['password_1']);
    $password_2 = e($_POST['password_2']);

    // form validation: ensure that the form is correctly filled
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password_1)) {
        array_push($errors, "Password is required");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    // register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = md5($password_1); //encrypt the password before saving in the database

        if (isset($_POST['user_type'])) {
            $user_type = e($_POST['user_type']);
            $query = "INSERT INTO users (username, email, user_type, password)
						  VALUES('$username', '$email', '$user_type', '$password')";
            mysqli_query($db, $query);
            $_SESSION['success'] = "New user successfully created!!";
            header('location: books.php');
        } else {

            $query = "INSERT INTO users (username, email, user_type, password)
						  VALUES('$username', '$email', 'user', '$password')";
            $resultSet = mysqli_query($db, $query);

            // get id of the created user
            $logged_in_user_id = mysqli_insert_id($db);

            $_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
            $_SESSION['success'] = "You are now logged in";
            header('location: books.php');
        }

    }

}

// return user array from their id
function getUserById($id)
{
    global $db;
    $query = "SELECT * FROM users WHERE id=" . $id;
    $result = mysqli_query($db, $query);

    $user = mysqli_fetch_assoc($result);
    return $user;
}

// LOGIN USER
function login()
{
    global $db, $username, $errors;

    // grap form values
    $username = e($_POST['username']);
    $password = e($_POST['password']);

    // make sure form is filled properly
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    // attempt login if no errors on form
    if (count($errors) == 0) {
        $password = md5($password);

        $query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) { // user found
            // check if user is admin or user
            $logged_in_user = mysqli_fetch_assoc($results);
            if ($logged_in_user['user_type'] == 'admin') {
                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success'] = "You are now logged in";
                header('location: admin/home.php');
            } else {
                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success'] = "You are now logged in";
                header('location: books.php');
            }
        } else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}

function isLoggedIn()
{
    if (isset($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}

function isAdmin()
{
    if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin') {
        return true;
    } else {
        return false;
    }
}

// escape string
function e($val)
{
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

function display_error()
{
    global $errors;

    if (count($errors) > 0) {
        echo '<div class="error">';
        foreach ($errors as $error) {
            echo $error . '<br>';
        }
        echo '</div>';
    }
}

// REGISTER Book
function registerBook()
{
    global $db, $errors;

    // receive all input values from the form
    $serialNo = e($_POST['serialNo']);
	$name = e($_POST['name']);
	$option = isset($_POST['category']) ? $_POST['category'] : false;
	if($option)
    $category = $_POST['category'];
    $path = $_FILES['file']['name'];
    echo $file = $_FILES['file']['name'];
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    // Get image name
    $img =rand().basename( $_FILES['file']['name']);
    echo $img;
    $baseTarget = dirname(__DIR__).'/'."library-web/uploads/";
    $target=$baseTarget.$img;
    echo $target;
    chmod($baseTarget,0777);
    echo $file = $_FILES['file']['tmp_name'];
  	if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
          $msg = "Image uploaded successfully";
          echo $msg;
  	}else{
          $msg = "Failed to upload image";
          echo $msg;
  	}

    // form validation: ensure that the form is correctly filled
    if (empty($serialNo)) {
        array_push($errors, "serialNo is required");
    }
    if (empty($name)) {
        array_push($errors, "name is required");
    }
    if (empty($category)) {
        array_push($errors, "category is required");
    }

    // register book if there are no errors in the form
    if (count($errors) == 0) {

        if (isset($_POST['serialNo'])) {
            $query = "INSERT INTO books (serialNo, name, category,fileToUpload)
						  VALUES('$serialNo', '$name', '$category','$img')";
            mysqli_query($db, $query);
            $_SESSION['success'] = "New book successfully created!!";
            header('location: home.php');
        } else {
            $query = "INSERT INTO books (serialNo, name, category,fileToUpload)
							VALUES('$serialNo', '$name', '$category','$img')";
            mysqli_query($db, $query);

            // get id of the created user
            $logged_in_book_id = mysqli_insert_id($db);

            $_SESSION['serialNo'] = getUserById($logged_in_book_id); // put logged in book in session
            $_SESSION['success'] = "You are now logged in";
           header('location: index.php');
        }

    }

}

// return book array from their id
function getBookById($serialNo)
{
    global $db;
    $query = "SELECT * FROM books WHERE id=" . $serialNo;
    $result = mysqli_query($db, $query);

    $book = mysqli_fetch_assoc($result);
    return $book;
}

function listBooks()
{
    global $db;
    $query = "SELECT * FROM books where reserved=0";
	$result = mysqli_query($db, $query);
	$books=array(); 
	while($row = mysqli_fetch_array($result))
	{
		array_push($books,$row);	

	}
	
    return $books;
}


function listAdminBooks(){
    global $db;
    $query = "SELECT * FROM books";
	$result = mysqli_query($db, $query);
	$books=array(); 
	while($row = mysqli_fetch_array($result))
	{
		array_push($books,$row);	

	}
	
    return $books;
}

function arrayImage(){

	$cards=glob("./images/*.jpg"); // this is the number of cards you want to show
	return $cards;
// for ($i=0; $i < sizeof($cards) ; $i++) { 
// 	echo $cards[$i];
// 	array_push($images,$cards[$i]);
// 	}
	

}

function getBooksViews(){
    $books = listBooks();
    for ($i = 0; $i < sizeof($books); $i++) {

    
echo '<div class="col-md-3" style="margin:8px;">
            <div class="card">
            <img height="200px" width="100%" src="uploads/'.$books[$i][4].'">
            
              <div class="container">
              
                <h4><b>' . $books[$i][1] . '</b></h4>
                <p>' . $books[$i][2] . '</p>
              </div>
              <a href="?id='.$books[$i][0].'"   class="buttonLocal btn-grad">Reserve!</a>
            </div>
        </div>';
    }
   }


function myfunc()
{
echo "clicked!!!!";
}
