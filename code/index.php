<html><body>
<?php

require_once('include.php');

$start_item = $_GET['start_item'];
if ( empty($start_item) ){
    $start_item = 0;
}

$sql_get_total = "SELECT count(*) FROM addressbook";
$sql_get_paged = "SELECT * FROM addressbook limit " . $start_item . ",2";

echo $sql_get_paged;
$res = mysql_query($sql_get_total);
$row = mysql_fetch_array($res);
$count = $row[0];


$table_html = "";
$res2 = mysql_query($sql_get_paged);
while( $row = mysql_fetch_assoc($res2) ){
    $table_html .= "<tr>\n";
    $table_html .= "<td>".$row['name']."</td>\n";
    $table_html .= "<td>".$row['tel']."</td>\n";
    $table_html .= "</tr>\n";
}

if ( empty($table_html) ){
    echo "<h1>Sem dados</h1>";
} else {
    echo "<h1>Lista de Pessoal</h1>";
    echo "<table>\n";
    echo $table_html;
    echo "</table>";
}

$num_pages = floor($count/2);
echo "<h2>Pagina</h2>";
echo "<table><tr>\n";
for ( $i = 0 ; $i <= $num_pages ; $i ++ ){
    $val = $i*2;
    echo '<td><a href="index.php?start_item='.$val.'">'.$val.'</a></td>';
}
echo "</tr></table>";

?>
</body></html>
