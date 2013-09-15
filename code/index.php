<?php
/**
 * This file is part of "hack me, a simple vulnerable application"
 *
 * WARNING!! this application has security issues, don't install
 * this on a public server!
 */
?>
<html><body>
<?php
require_once('include.php');

$sql_get_total = "SELECT count(*) FROM people";
$res = mysql_query($sql_get_total);
$row = mysql_fetch_array($res);
$count = $row[0];


$table_html = "";
$start_item = $_GET['start_item'];
if ( empty($start_item) ){
    $start_item = 0;
}
$sql_get_paged = "SELECT * FROM people LIMIT " . $start_item . "," . ITEMS_BY_PAGE;

$res2 = mysql_query($sql_get_paged);
while( $row = mysql_fetch_assoc($res2) ){
    $table_html .= "<tr>\n";
    $table_html .= "<td>".$row['name']."</td>\n";
    $table_html .= "<td>".$row['phone']."</td>\n";
    $table_html .= "</tr>\n";
}

if ( empty($table_html) ){
    echo "<h1>Sem dados</h1>";
} else {
    echo "<h1>Lista de Pessoal</h1>";
    echo "<table>\n";
    echo "<tr><td style=\"width:200px;\">Nome</td><td>Telefone</td></tr>";
    echo $table_html;
    echo "</table>";
}

$num_pages = floor($count/ITEMS_BY_PAGE);
echo "<table cellpadding=\"2\"><tr>\n";
$i=0;
do {
    $val = $i*ITEMS_BY_PAGE;
    $val1 = $i*ITEMS_BY_PAGE+1;
    $val2 = ($i+1)*ITEMS_BY_PAGE;
    echo '<td><a href="index.php?start_item='.$val.'">'.$val1.'-'.$val2.'</a></td>';
    $i++;
} while ($i < $num_pages);
echo "</tr></table>";
?>
</body></html>
