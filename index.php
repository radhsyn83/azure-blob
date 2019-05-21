<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Submission 2</title>
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="display-6">Submission 2</h1>
            <form class="form-inline" id="form">
                <div class="form-group mb-2">
                    <input type="hidden" id="uploadImage" name="uploadImage">
                    <label for="image">Choose image you want to upload</label>
                    <input type="file" class="form-control-file" id="imageUpload" name="imageUpload">
                </div>
                <button type="submit" class="btn btn-primary mb-2" id="submitBtn">Upload</button>
            </form>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body" style="padding: 20px !important;">
            <div class="row" id="image-card">
            </div>
        </div>
    </div>
</div>

<!--Modal-->
<div class="modal" tabindex="-1" role="dialog" id="analyze-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Analyze</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="jsonOutput">Response: </label>
                    <textarea class="form-control" id="responseTextArea" rows="3" style="height: 300px" readonly></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    getImage();

    function getImage() {
        $.ajax({
            type : 'POST',
            url : 'action.php',
            dataType: "JSON",
            data : 'getBlob',
            beforeSend: function() {
                $("#image-card").html("<span style='padding: 20px'>Loading...</span>");
            },
            success : function (blob) {
                imageBlob = blob;
                if (blob.length > 0) {
                    var a = "";
                    for (let i = 0; i < blob.length; i++) {
                        a += "<div class='col-md-2'>\n" +
                            "   <div class='card' style='width: 18rem;width: 100%; padding: 10px'>\n" +
                            "       <img class='card-img-top' src='"+blob[i].url+"' alt='Card image cap' style='height: 100px; object-fit: contain;'>\n" +
                            "       <div class='card-body'>\n" +
                            "            <center><a href='#' onclick='processImage(\""+blob[i].url+"\")' class='btn btn-primary btn-sm'>Analyze</a></center>\n" +
                            "       </div>\n" +
                            "   </div>\n" +
                            "</div>"
                    }
                    $("#image-card").html(a);
                } else {
                    $("#image-card").html("<span style='padding: 30px'>No image in storage</span>");
                }
            }
        })
    }

    function analyze(url) {
        $.ajax({
            type : 'POST',
            url : 'action.php',
            dataType: "JSON",
            data : 'getAnalyze',
            success : function (blob) {
                showModalAnalyze(blob)
            }
        })
    }

    $("#form").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            type : 'POST',
            url : 'action.php',
            dataType: "JSON",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function() {
                $("#submitBtn").prop("disabled", true);
                $("#submitBtn").html("Loading...");
            },
            success : function (blob) {
                getImage()
                $("#submitBtn").prop("disabled", false);
                $("#submitBtn").html("Upload");
            }
        })
    })

    function processImage(url) {
        var subscriptionKey = "72568fe162eb4ac08fd208934d8e934b";
        var uriBase = "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
        var params = {
            "visualFeatures": "Categories,Description,Color",
            "details": "",
            "language": "en",
        };
        $.ajax({
            url: uriBase + "?" + $.param(params),
            beforeSend: function(xhrObj){
                $("#analyze-modal").modal("show");
                $("#responseTextArea").val("loading...");
                xhrObj.setRequestHeader("Content-Type","application/json");
                xhrObj.setRequestHeader(
                    "Ocp-Apim-Subscription-Key", subscriptionKey);
            },
            type: "POST",
            data: '{"url": ' + '"' + url + '"}',
            success: function (data) {
                $("#responseTextArea").val(JSON.stringify(data, null, 2));
            }
        })
            .fail(function(jqXHR, textStatus, errorThrown) {
                // Display error message.
                var errorString = (errorThrown === "") ? "Error. " :
                    errorThrown + " (" + jqXHR.status + "): ";
                errorString += (jqXHR.responseText === "") ? "" :
                    jQuery.parseJSON(jqXHR.responseText).message;
                alert(errorString);
            });
    };
</script>
</body>
</html>