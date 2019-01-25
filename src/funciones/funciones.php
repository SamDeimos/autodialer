<?php
	function conecta($dbase){
		$link = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die("DB Connection Error $dbase ".mysql_error()."\n");
		mysql_query("SET NAMES 'utf8'");
		$sel_db = mysql_select_db($dbase) or die("Error al selecionar $dbase");
	
	}

	function listar_trunk(){
		conecta('asterisk');
		$query = "select * from trunks";
		$result = mysql_query($query) or die(mysql_error());
		$c=0;
		while($reg=mysql_fetch_array($result)){
			$c++;
			$response[$c] = $reg;
			}
		return $response;
	}
	function export_csv($filename,$sql_query){
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
		exit;
		}
?>