<?php
/**
 * This file is part of "hack me, a simple vulnerable application"
 *
 * WARNING!! this application has security issues, don't install
 * this on a public server!
 */

session_start();
?>
<html><body>
<?php
include('include.php');

if ( ! array_key_exists('action',$_REQUEST) ){
    $_REQUEST['action']='';
}

if ( $_REQUEST['action'] == 'logout') {
	$_SESSION['user_is_loggedin'] = 0;
	$_SESSION['user']="";
}

if ( ! empty($_REQUEST['user']) ) {
	// try to autenticate
	$user = mysql_escape_string($_REQUEST['user']);
	$pass = mysql_escape_string($_REQUEST['pass']);
	$sql = "select * from people where user='".$user."' and pass=md5('".$pass."')";
	$res = mysql_query($sql);
	$row = mysql_fetch_assoc($res);
	if ( $row ){
		$_SESSION['user_is_loggedin'] = 1;
		$_SESSION['user'] = $row['user'];
		$_SESSION['pass'] = $row['pass'];
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
    echo '<div><a href="bo.php?action=logout">Logout</a></div>';

    if ( $_REQUEST['action'] == "editdetails"){
	    $id = mysql_escape_string($_REQUEST['id']);
	    $name=mysql_escape_string($_REQUEST['name']);
	    $phone=mysql_escape_string($_REQUEST['phone']);
	    
	    $sql = "UPDATE people set name='".$name."', phone='".$phone."' where id='".$id."'";
	    mysql_query($sql);
    }
    
    if ( $_REQUEST['action'] == "editpass"){
	    $id = mysql_escape_string($_REQUEST['id']);
	    $old_pass = mysql_escape_string($_REQUEST['old_pass']);
	    $new_pass_db = mysql_escape_string($_REQUEST['new_pass']);
	    $pass = $_REQUEST['new_pass'];
	    $pass_repeat = $_REQUEST['new_pass_repeat'];
	    
	    if ( !empty($pass) && ($pass==$pass_repeat) ){
	        if ( md5($old_pass) == $_SESSION['pass'] ){
	             $sql = "UPDATE people set pass = md5('".$new_pass_db."') where id='".$id."'";   
	             mysql_query($sql);
	        } else {
	            echo "<h2Old Pass don't match</h2>";
	        }
	    } else {
	        echo "<h2New password is Empty or don't match</h2>";
	    }	    
    }
    
    if ( $_REQUEST['action'] == "delete"){
	    $id = $_REQUEST['id'];
	    $sql = "DELETE FROM users WHERE id='".$_REQUEST['id']."'";
    }
    
    if ( $_REQUEST['action'] == "edit" ) {
        $id = mysql_escape_string($_REQUEST['id']);
        $sql = "select * from people where id='".$id."'";
        $res_user = mysql_query($sql);
        $row = mysql_fetch_assoc($res_user);
?>
        <h1>Detalhes Pessoais</h1>
        <form method="post" action="bo.php">
        <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>">
        <input type="hidden" name="action" value="editdetails">
        <table cellpadding="2">
        	<tr>
                <td>Nome</td>
                <td><input type="text" name="name" value="<?php echo $row['name']; ?>"></input></td>
            </tr>
        	<tr>
                <td>Telefone</td>
                <td><input type="text" name="phone" value="<?php echo $row['phone']; ?>"></input></td>
            </tr>
        	<tr>
                <td colspan="2"><input type="submit" name="submit" value="Go!"></td>
            </tr>
        </table>
        </form>
        <h1>Password</h1>
        <form method="post" action="bo.php">
        <input type="hidden" name="id" value="<?php echo $_REQUEST['id']; ?>">
        <input type="hidden" name="action" value="editpass">
        <table cellpadding="2">
        	<tr>
                <td>Old Password</td>
                <td><input type="password" name="old_pass"></input></td>
            </tr>
        	<tr>
                <td>New Password</td>
                <td><input type="password" name="new_pass"></input></td>
            </tr>
        	<tr>
                <td>New Password (repeat)</td>
                <td><input type="password" name="new_pass_repeat"></input></td>
            </tr>
        	<tr>
                <td colspan="2"><input type="submit" name="submit" value="Go!"></td>
            </tr>
        </table>
        </form>
<?php        
	} else {
	    $sql_get_all = "SELECT * FROM people";
	    $res_all = mysql_query($sql_get_all);
?>
        <h1>Lista de Pessoal</h1>
        <table cellpadding="2">
        	<tr>
                <td style="width:200px;">Nome</td>
                <td style="width:100px;">Telefone</td>
                <td style="width:80px;">Editar</td>
        <?php if ( $_SESSION['user'] == 'admin' ){ ?>
        		<td style="width:80px;">Apagar</td>
        <?php } ?>
        	</tr>
        <?php while( $row = mysql_fetch_assoc($res_all) ){ ?>
        	<tr>
        		<td><?php echo $row['name']; ?></td>
        		<td><?php echo $row['phone']; ?></td>
        <?php if ( $_SESSION['user'] == 'admin' || $_SESSION['user'] == $row['user'] ){ ?>
        		<td><a href="bo.php?action=edit&id=<?php echo $row['id']; ?>">Edit</a></td>
        <?php } else { ?>
        		<td>&nbsp;--&nbsp;</td>
        <?php }
              if ( $_SESSION['user'] == 'admin' ){ ?>
        		<td><a href="bo.php?action=delete&id=<?php echo $row['id']; ?>">Delete</a></td>
        <?php } ?>
        	</tr>
        <?php } ?>
        </table>
<?php
	}
}


?>
</body></html>
