<?php

require('includes/sqlsrv_connect.php');

$page_title = 'Plantilla';
include('includes/header.html');

echo    '<div class="container-fluid">
        <h2>Plantilla de Reportes</h2>';

// Date functions:
$fini = strtotime("First day of this month");
$ffin = strtotime("Today");

echo '
<form formaction="ventas.php" method="post">
  <div class="form-group row">
    <label for="inputEmail3" class="col-sm-2 col-form-label">Fecha Inicio</label>
    <div class="col-sm-3">
      <input type="date" class="form-control" name="f_ini" value="' . date("Y-m-d", $fini) . '">
    </div>
  </div>

  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Fecha Fin</label>
    <div class="col-sm-3">
      <input type="date" class="form-control" name="f_fin" value="' . date("Y-m-d", $ffin) . '">
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
      $q = "
      SELECT *
      FROM [BODEGA TIPO]";

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
        // Table header
        $tableid = 'reporte_t1';
        echo "<div class=\"container-fluid\">
              <table class=\"table table-hover table-sm\" id=$tableid>";
        echo '<thead class="thead-light">
            <tr>
            <th align="left">Codigo</th>
            <th align="left">Descripcion</th>
            <th align="left">Poliza Bodega</th>
            <th align="left">Cuenta</th>
            <th align="left">Bodega Virtual</th>
            <th align="left">Desrcripcion General</th>
            </tr>
            </thead>
            <tbody>';
        // Fetch and print all the records
        while ($row = sqlsrv_fetch_array($r, SQLSRV_FETCH_ASSOC)) {
                echo '<tr><td align="left">' . $row['Codigo'] .
                '</td><td align="left">' . $row['Descripcion'] .
                '</td><td align="left">' . $row['Poliza Bodega'] .
                '</td><td align="left">' . $row['Cuenta'] .
                '</td><td align="left">' . $row['Bodega Virtual'] .
                '</td><td align="left">' . $row['Descripcion General'] .
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

} // End of main submission IF.

echo    '</div>';
include('includes/footer.html');
?>
