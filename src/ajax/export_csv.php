<?php
	require_once ("../../conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
	if (empty($_GET['id'])){
		$errors[] = "Id vacío.";
	} elseif (!empty($_GET['id'])){
    $id = intval($_GET['id']);
	

    // Exportar CSV
conecta('autodialer');
$sqlcampana = "SELECT archivo FROM calloutcampana WHERE idcampana = '$id'";
$querycampana = mysql_query($sqlcampana);
$resultcampana = mysql_fetch_array($querycampana);
$filename = $resultcampana['archivo'];


$sql_query = "SELECT a.campana, a.telefono, a.nombre, a.cedula, a.option1, a.option2, case a.respuesta when 'Cola' then ifnull(b.disposition,'NO ANSWER') when 'Llamado' then b.disposition end as disposition, b.duration, b.calldate, a.recordingfile
FROM autodialer.calloutnumeros a
LEFT JOIN asteriskcdrdb.cdr b
ON a.uniqueid = b.uniqueid
WHERE campana = '$id'";

$csv_terminated = "\n";
$csv_separator = ",";
$csv_enclosed = '"';
$csv_escaped = "\\";
// Gets the data from the database
$result = mysql_query($sql_query);			// Resultado
$fields_cnt = mysql_num_fields($result);	// cantidad de campos
$schema_insert = '';
for ($i = 0; $i < $fields_cnt; $i++)
	{
	$l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,
	stripslashes(mysql_field_name($result, $i))) . $csv_enclosed;	// Nombres de los campos
	$schema_insert .= $l;
	$schema_insert .= $csv_separator;
	} // end for
$out = trim(substr($schema_insert, 0, -1));
$out .= $csv_terminated;
// Format the data
while ($row = mysql_fetch_array($result))
	{
		$schema_insert = '';
		for ($j = 0; $j < $fields_cnt; $j++)
		{
		if ($row[$j] == '0' || $row[$j] != '')
		{
		if ($csv_enclosed == '')
		{
		$schema_insert .= $row[$j];
		} else
		{
		$schema_insert .= $csv_enclosed .
		str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $row[$j]) . $csv_enclosed;
		}
		} else
		{
		$schema_insert .= '';
		}
		if ($j < $fields_cnt - 1)
		{
		$schema_insert .= $csv_separator;
		}
		} // end for
		$out .= $schema_insert;
		$out .= $csv_terminated;
	} // end while
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Length: " . strlen($out));
// Output to browser with appropriate mime type, you choose  
header("Content-type: text/x-csv");
//header("Content-type: text/csv");
//header("Content-type: application/csv");
header("Content-Disposition: attachment; filename=$filename");
echo $out;
}
?>