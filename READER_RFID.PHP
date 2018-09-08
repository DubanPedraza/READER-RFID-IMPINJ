<?php

echo " PRUEBA POST PHP READER RFID";



//Define el Usuario, clave, nombre de MySQL y nombre de la base de datos.
 define("DB_USER", "root");
 define("DB_PASS", "");
 define("DB_HOST", "localhost");
 define("DB_NAME", "rfid");


 // Conexión a la Base de Datos.
 $con = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
 mysqli_select_db($con,DB_NAME) or die( "Unable to select database");

//Recibe variables POST
$readerName=isset($_POST['reader_name']) ? $_POST['reader_name']:NULL;
$macAddress=isset($_POST['mac_address']) ? $_POST['mac_address']:NULL;
$lineEnding = isset($_POST['line_ending']) ? $_POST['line_ending']:NULL;
$fieldDelim = isset($_POST['field_delim']) ? $_POST['field_delim']:NULL;
$fieldNames = isset($_POST['field_names']) ? $_POST['field_names']:NULL;
$fieldValues =isset($_POST['field_values'])? $_POST['field_values']:NULL;


 if ($readerName  && $macAddress && $lineEnding && $fieldDelim && $fieldNames && $fieldValues){

// Replace the field delimiter with a comma.
str_replace("$fieldDelim", ",", $fieldNames);

// Break the field values up into rows.
 $rows = explode("\n", $fieldValues);


for($i=0;$i<(count($rows)-1);$i++){
$fieldValues1=$rows[$i];
$Campo=explode(",", $fieldValues1);
//$timeZone= (-4*3600)-(18*60);//tiempo para el reader deportes
$timeZone= (44*60);//tiempo para el reader laboratorio
$Campo1=Date('d-m-Y H:i:s a',($Campo[2]/1000000)+$timeZone);

//Escribe los Datos en la Base de Datos
$query1="INSERT INTO rfid_1 (READER_NAME, MAC_ADDRESS,  ANTENA_PORT, EPC, TIME_STAMP, PEAK_RSSI) values ('$readerName','$macAddress','$Campo[0]','$Campo[1]','$Campo1', '$Campo[3]')";
 mysqli_query ($con, $query1) or die( "NO ESCRIBE EN LA BASE DE DATOS");
}
mysqli_close($con);

}


?>
<html>
	<head>
		<title>PRUEBA DEL LECTOR RFID</title>
	</head>
	<body>
<table border="1" style="text-align:center;">


		<tr>
			<td colspan = "7"> BASE DE DATOS RFID_1</td>
		</tr>
		<tr>
			<td>ID</td>
			<td>NOMBRE DEL LECTOR</td>
			<td>DIRECCION MAC </td>
			<td>ANTENA #</td>
			<td>ETIQUETA</td>
			<td>TIEMPO</td>
			<td>PICO SENAL</td>
		</tr>
		<?php
		$result = mysqli_query($con, 'SELECT * from rfid_1');
			while($row=  mysqli_fetch_array($result)){ ?>
			<tr>
				<td><?php echo $row['ID']; ?></td>
				<td><?php echo $row['READER_NAME']; ?></td>
				<td><?php echo $row['MAC_ADDRESS']; ?></td>
				<td><?php echo $row['ANTENA_PORT']; ?></td>
        <td><?php echo $row['EPC']; ?></td>
        <td><?php echo $row['TIME_STAMP']; ?></td>
				<td><?php echo $row['PEAK_RSSI']; ?></td>
			</tr>
		<?php echo $fieldValues; ?>
		<?php
		}

		mysqli_close($con);

		?>
		</table>
	</body>
</html>

<?php

//Código de actualización
$self = $_SERVER['PHP_SELF']; //Obtenemos la página en la que nos encontramos
header("refresh:5; url=$self"); //Refrescamos cada 10 segundos

?>
