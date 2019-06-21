<!-- 
// else if (isset($_GET["Cleanup"]))
// {

//     try{
//         // Delete container.
//         echo "Deleting Container".PHP_EOL;
//         echo $_GET["containerName"].PHP_EOL;
//         echo "<br />";
//         $blobClient->deleteContainer($_GET["containerName"]);
//     }
//     catch(ServiceException $e){
//         // Handle exception based on error codes and messages.
//         // Error codes and messages are here:
//         // http://msdn.microsoft.com/library/azure/dd179439.aspx
//         $code = $e->getCode();
//         $error_message = $e->getMessage();
//         echo $code.": ".$error_message."<br />";
//     }
// }
// ?> -->

<!-- <form method="post" action="add.php?Upload"> -->
<!-- <button type="submit">Upload Data</button> -->
<!-- </form> -->
<!-- <form method="post" action="index.php?Cleanup&containerName=<?php echo $containerName; ?>"> -->
    <!-- <button type="submit">Press to clean up all resources created by this sample</button> -->
<!-- </form>  -->

<!DOCTYPE html>
<html lang="en">
<?php
     
    require_once 'vendor/autoload.php';
    require_once "./random_string.php";
    
    use MicrosoftAzure\Storage\Blob\BlobRestProxy;
    use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
    use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
    use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
    use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
    
    $connectionString = "DefaultEndpointsProtocol=https;AccountName=mypictureweb;AccountKey=yJlewTq7A4Y60BbeaTPjFAwGVeXqLjcuHm5qZKwPuQdPWjyRichNf2aAXtyBKynGOygCOlFdrnOG+Ka8HmlPkA==;EndpointSuffix=core.windows.net";
    // Create blob client.
    $blobClient = BlobRestProxy::createBlobService($connectionString);
    
    $fileToUpload = "elis.jpg";

    $serverName = "tcp:mypictureweb.database.windows.net,1433";
    $connectionOptions = array(
        "Database" => "mypictureweb", // update me
        "Uid" => "alexwibowo", // update me
        "PWD" => "08Maret2017" // update me
    ); 
    $conn = sqlsrv_connect($serverName, $connectionOptions);  
 
    if ( !empty($_POST)) {
        $namaError = null;
        $fotoError = null;
        $nama = $_POST['nama'];
        $foto = $_POST['foto'];
         
        // validasi inputan
        $valid = true;
        if (empty($nama)) {
            $namaError = 'Tolong isi nama';
            $valid = false;
        }

        if (empty($foto)) {
            $fotoError = 'Tolong isi foto';
            $valid = false;
        }
         
        // isi data
        if ($valid) {

            $fileToUpload = $_FILES["foto"]["name"];

            // Create container options object.
            $createContainerOptions = new CreateContainerOptions();
            $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
        
            // Set container metadata.
            $createContainerOptions->addMetaData("key1", "value1");
            $createContainerOptions->addMetaData("key2", "value2");
            $containerName = "blockblobs".generateRandomString();
        
            try {
                // Create container.
                $blobClient->createContainer($containerName, $createContainerOptions);
        
                // Getting local file so that we can upload it to Azure
                $myfile = fopen($_FILES["foto"]["tmp_name"], "r") or die("Unable to open file! ");
                fclose($myfile);
                
                # Upload file as a block blob
                echo "Uploading BlockBlob: ".PHP_EOL;
                echo $fileToUpload;
                echo "<br />";
                
                // $content = fopen($fileToUpload, "r");
                
                $content = fopen($_FILES["foto"]["tmp_name"], "r");
        
                //Upload blob
                $blobClient->createBlockBlob($containerName, $fileToUpload, $content);
        
                // List blobs.
                $listBlobsOptions = new ListBlobsOptions();
                $listBlobsOptions->setPrefix("MyPictureWeb");
        
                echo "These are the blobs present in the container: ";
        
                do{
                    $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
                    foreach ($result->getBlobs() as $blob)
                    {
                        echo $blob->getName().": ".$blob->getUrl()."<br />";
                    }
                
                    $listBlobsOptions->setContinuationToken($result->getContinuationToken());
                } while($result->getContinuationToken());
                echo "<br />";
                // Get blob.
                echo "This is the content of the blob uploaded: ";
                $blob = $blobClient->getBlob($containerName, $fileToUpload);
                // fpassthru($blob->getContentStream());
                echo "<br />";
                echo $containerName;
                echo "<br />";
                echo $fileToUpload;
                $alamat = $containerName."/".$fileToUpload;
            }
            catch(ServiceException $e){
                // Handle exception based on error codes and messages.
                // Error codes and messages are here:
                // http://msdn.microsoft.com/library/azure/dd179439.aspx
                $code = $e->getCode();
                $error_message = $e->getMessage();
                echo $code.": ".$error_message."<br />";
            }
            catch(InvalidArgumentTypeException $e){
                // Handle exception based on error codes and messages.
                // Error codes and messages are here:
                // http://msdn.microsoft.com/library/azure/dd179439.aspx
                $code = $e->getCode();
                $error_message = $e->getMessage();
                echo $code.": ".$error_message."<br />";
            }
            //

            $insertSql = "INSERT INTO gambar (nama,alamat) values(?, ?)";
            $params = array($nama,$alamat);  
            $stmt = sqlsrv_query($conn, $insertSql, $params);  
            $conn = null;

            // header("Location: index.php");
        }
    }
?>
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
     
                <div class="span10 offset1">
                    <div class="row">
                        <h3>Create a Customer</h3>
                    </div>
             
                    <form class="form-horizontal" action="add.php" method="post">
                        <div class="control-group <?php echo !empty($namaError)?'error':'';?>">
                            <label class="control-label">Nama</label>
                            <div class="controls">
                                <input name="nama" type="text"  placeholder="Nama" value="<?php echo !empty($nama)?$nama:'';?>">
                                <?php if (!empty($namaError)): ?>
                                    <span class="help-inline"><?php echo $namaError;?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="control-group <?php echo !empty($fotoError)?'error':'';?>">
                            <label class="control-label">Foto</label>
                            <div class="controls">
                                <input name="foto" type="file"  placeholder="foto" accept=".jpeg,.jpg,.png">
                                <?php if (!empty($fotoError)): ?>
                                    <span class="help-inline"><?php echo $fotoError;?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">Create</button>
                            <a class="btn" href="index.php">Back</a>
                        </div>
                    </form>
                </div>
                 
    </div> 
  </body>
</html>
