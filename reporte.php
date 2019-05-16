<?php

require('includes/sqlsrv_connect.php');

$page_title = 'Plantilla';
include('includes/header.html');

echo    '<div class="container-fluid">
        <h2>Plantilla de Reportes</h2>';

// Date functions:
$fini = strtotime("First day of this month");
$ffin = strtotime("Today");

// Create dropdown menu array 
$q = "EXEC [COI_ContaVentaReportes]";
$r = sqlsrv_query( $dbc, $q, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

// Check for errors in dropdown menu query
if($r === false) {
    // Public message
    echo '<p class="error">El reporte no puede ser desplegado en estos momentos.</p>';
    // Debugging message and script termination
    die(print_r(sqlsrv_errors(), true));
}

// Create options array:
$options = '';
while ($row = sqlsrv_fetch_array($r, SQLSRV_FETCH_ASSOC)) {
    $options .= "<option name=\"tipoReporte\" value=\"{$row['Codigo']}\">{$row['Nombre']}</option>\n";
}



echo '
<form formaction="ventas.php" method="post">

  <div class="form-group row">
    <label for="inputDate1" class="col-sm-2 col-form-label">Fecha Inicio</label>
    <div class="col-sm-3">
      <input type="date" class="form-control" name="f_ini" value="' . date("Y-m-d", $fini) . '">
    </div>
  </div>

  <div class="form-group row">
    <label for="inputDate2" class="col-sm-2 col-form-label">Fecha Fin</label>
    <div class="col-sm-3">
      <input type="date" class="form-control" name="f_fin" value="' . date("Y-m-d", $ffin) . '">
    </div>
  </div>

  <div class="form-group row">
    <label for="inputReportType" class="col-sm-2 col-form-label">Tipo de Reporte</label>
    <div class="col-sm-3">
      <select name="tipoReporte" id="inputReportType" class="form-control">
        <option value=0 selected>Escoger...</option>
        // ' . $options . '
      </select>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-sm-10">
      <button type="submit" class="btn btn-primary">Ver</button>
    </div>
  </div>
</form>
<hr>';

// Check for form submission:
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Minimal form validation:
    if (isset($_POST['f_ini'], $_POST['f_fin'])) {
    
      // Run the query
      $q = "EXEC [COI_ContaVentaReportes]";
      $r = sqlsrv_query( $dbc, $q, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

      // Check for errors in query
      if($r === false) {
      // Public message
      echo '<p class="error">El reporte no puede ser desplegado en estos momentos.</p>';
      // Debugging message and script termination
      die(print_r(sqlsrv_errors(), true));
      }

      // If it ran OK run code
      if ($r) {
        // Check for report type
        if($_POST['tipoReporte'] == 1) {
          $reporte = 'Reporte 1';
        } else {
          $reporte = 'No es Reporte 1';
        };
        // Report received values
        echo $reporte . ' - ' . $_POST['f_ini'] . ' - ' . $_POST['f_fin'];
        // Table header
        $tableId = 'reporte_t1';
        echo "<table id=\"$tableId\" class=\"table table-sm table-striped table-bordered\" style=\"width:100%\">";
        echo '<thead>
            <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            </tr>
            </thead>
            <tbody>';
        // Fetch and print all the records
        while ($row = sqlsrv_fetch_array($r, SQLSRV_FETCH_ASSOC)) {
                echo '<tr><td>' . $row['Codigo'] .
                '</td><td>' . $row['Nombre'] .
                '</td></tr>';
        }
        echo '</tbody>';

        echo '</table></div>'; // Close the table

      sqlsrv_free_stmt($r); // Free up the resources.
      }

    } else { // Invalid submitted values.
    echo '<div class="alert alert-danger alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Error: </strong>Por favor ingresar otro rango.</div>';
    }
    // Close the database connection
    sqlsrv_close($dbc);
} // End of main submission IF.

echo    '</div>';
include('includes/footer.html');
