<?php session_start(); ?>
<html><body>
<?php
include('include.php');

if ( $_REQUEST['action'] == 'logout') {
	$_SESSION['user_is_loggedin'] = 0;
}

if ( ! empty($_REQUEST['user']) ) {
	// try to autenticate
	$sql = "select * from users where user='".$_REQUEST['user']."' and pass=md5('".$_REQUEST['pass']."')";
	echo $sql;
	$res = mysql_query($sql);
	if ( mysql_num_rows($res) > 0 ){
		$_SESSION['user_is_loggedin'] = 1;
	}  
}

if ( ! $_SESSION['user_is_loggedin'] ){
?>

<form method="POST" action="bo.php">
<label>Username</label> <input type="input" name="user"><br>
<label>Password</label> <input type="password" name="pass"><br>
<input type="submit" name="submit" value="Go!">

<?
} else {
    echo '<a href="bo.php?action=logout">Logout</a>';

    if ( $action == "delete"){
        echo "delete";
	$id = $_GET['id'];
	$sql = "DELETE FROM users WHERE id='".$_GET['id']."'";
	

    }
    
    if ( $action == "edit" ) {
		echo "Caixa de edição";
	} else {
	    echo "Listar USers";
	    $sql_get_all = "SELECT * FROM addressbook";
	    $res_all = mysql_query($sql_get_all);
	    echo "<h1>Lista de Pessoal</h1>";
        echo "<table>\n";
        while( $row = mysql_fetch_assoc($res_all) ){
            echo "<tr>\n";
            echo "<td>".$row['name']."</td>\n";
            echo "<td>".$row['tel']."</td>\n";
            echo "<td><a href=\"bo.php?action=edit&id=".$row['id']."\">Edit</a></td>\n";
            echo "<td><a href=\"bo.php?action=delete&id=".$row['id']."\">Delete</a></td>\n";
            echo "</tr>\n";
        }
        echo "</table>";
	}
}


?>
</body></html>
