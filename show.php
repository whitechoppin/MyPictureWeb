<!DOCTYPE html>
<html>
<head>
    <title>Show Data With Description</title>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/azurescript.js"></script>
</head>
<body onload="processImage()">
    <div class="container">
        <div class="row">
            <h3>Lihat Gambar</h3>
        </div>
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
            $id = null;
            if ( !empty($_GET['id'])) {
                $id = $_REQUEST['id'];
            }
            
            $sql = "SELECT * FROM gambar where id = ?";
            $params = array($id);  
            $getResults = sqlsrv_query($conn, $sql, $params); 
            $data = sqlsrv_fetch_array($getResults);
            $conn = null;  
        ?>
        <div class="row">
            <div class="control-group">
                <input type="text" name="inputImage" id="inputImage"
                    value= "https://mypictureweb.blob.core.windows.net/<?php echo $data['alamat'];?>" />
            </div>
        </div>
        <div id="wrapper" style="width:1020px; display:table;">
            <div id="jsonOutput" style="width:600px; display:table-cell;">
                Response:
                <br><br>
                <textarea id="responseTextArea" class="UIInput"
                        style="width:580px; height:400px;"></textarea>
            </div>
            <div id="imageDiv" style="width:420px; display:table-cell;">
                Source image:
                <br><br>
                <img id="sourceImage" width="400" />
            </div>
        </div>
    </div>
</body>
</html>