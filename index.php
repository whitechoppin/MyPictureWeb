<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
            <div class="row">
                <h3>Daftar Gambar Terupload</h3>
            </div>
            <div class="row table-responsive">
                <p>
                    <a href="create.php" class="btn btn-success">Create</a>
                </p>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Tools</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $serverName = "tcp:mypictureweb.database.windows.net,1433";
                    $connectionOptions = array(
                        "Database" => "mypictureweb", // update me
                        "Uid" => "alexwibowo", // update me
                        "PWD" => "08Maret2017" // update me
                    ); 
                    $conn = sqlsrv_connect($serverName, $connectionOptions);  
                    
                    if ($conn === false)  
                    {  
                        die(print_r(sqlsrv_errors() , true));  
                    }  
                    
                    $sql = 'SELECT * FROM gambar ORDER BY id ASC';
                    $getResults= sqlsrv_query($conn, $sql);
                    if ($getResults)
                    {
                        while ($row = sqlsrv_fetch_array($getResults)) {
                            echo '<tr>';
                            echo '<td>'. $row['id'] . '</td>';
                            echo '<td><a class="" href="show.php?id='.$row['id'].'">'. $row['nama'] . '</a></td>';
                            echo '<td width=250>';
                            echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Delete</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    $conn = null;
                    ?>
                    </tbody>
            </table>
        </div>
    </div>
  </body>
</html>