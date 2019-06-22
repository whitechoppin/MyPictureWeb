<!DOCTYPE html>
<html>
<head>
    <title>Show Data With Description</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/azurescript.js"></script>
</head>
<body>
    <h1>Analyze image:</h1>
    Enter the URL to an image, then click the <strong>Analyze image</strong> button.
    <br><br>
    Image to analyze:
    <input type="text" name="inputImage" id="inputImage"
        value="http://upload.wikimedia.org/wikipedia/commons/3/3c/Shaki_waterfall.jpg" />
    <button onclick="processImage()">Analyze image</button>
    <br><br>
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
</body>
</html>