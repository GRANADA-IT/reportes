<?php

// Date functions:
$fini = strtotime("First day of this month");
$ffin = strtotime("Today");

// Create the form:
echo '<form action="ventas.php" method="post">
<p>Fecha de Inicio: <input type="date" name="f_ini" value="'. date("Y-m-d", $fini) . '"></p>
<p>Fecha de Fin: <input type="date" name="f_fin" value="'. date("Y-m-d", $ffin) . '"></p>
<p><input type="submit" name="submit" value="Ver"></p>
</form><hr>
</div>';

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
              <table class=\"table\" id=$tableid>";
        echo '<thead>
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
    echo '<div class="page-header"><h1>Error!</h1></div>
    <p class="text-danger">Please enter correct values.</p>';
    }

} // End of main submission IF.

?>