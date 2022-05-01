<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>YoPrint Upload</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">YoPrint</a>
    </nav>
    
    <div class="container mt-4">
        {{-- Upload Form --}}
        <div class="row">
            <form class="col-12" id="form-upload">
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex align-items-center">
                            <div class="col-6">
                                <p>Select file / Drag and drop</p>
                            </div>
                            
                            <div class="col-6 d-flex justify-content-end">
                                <button class="btn btn-primary" id="btn-upload">Upload File</button>
                            </div>

                            <div id="progressOuter" class="progress progress-striped active" style="display:none;">
                                <div id="progressBar" class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div id="msgBox"></div>

        {{-- Upload List --}}
        <div class="row mt-1">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Uploaded at</th>
                                    <th>File name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha512-bnIvzh6FU75ZKxp0GXLH9bewza/OIw6dLVh9ICg0gogclmYGguQJWl8U30WpbsGTqbIiAwxTsbe76DErLq5EDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/simple-ajax-uploader/2.6.7/SimpleAjaxUploader.min.js" integrity="sha512-sF1OQUX4620btxfaKLxsFeu/euV3FcPyH+uST3mdEjc8vW8R4z1xNiZhcG7wcZQbFkgFhiiBoAyYNMCL3jufPA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        var btn = document.getElementById('btn-upload'),
            progressBar = document.getElementById('progressBar'),
            progressOuter = document.getElementById('progressOuter'),
            msgBox = document.getElementById('msgBox');

        function escapeTags( str ) {
            return String( str )
                .replace( /&/g, '&amp;' )
                .replace( /"/g, '&quot;' )
                .replace( /'/g, '&#39;' )
                .replace( /</g, '&lt;' )
                .replace( />/g, '&gt;' );
        }

        var uploader = new ss.SimpleUpload({
            dropzone: 'dragbox',
            button: btn,
            url: '{{ route("upload.start") }}',
            name: 'uploadfile',
            allowedExtensions : ['csv'],
            multipart: true,
            hoverClass: 'hover',
            focusClass: 'focus',
            responseType: 'json',
            data : {
                _token : "{{ csrf_token() }}"
            },
            startXHR: function() {
                progressOuter.style.display = 'block'; // make progress bar visible
                // this.setProgressBar( progressBar );
            },
            onSubmit: function() {
                msgBox.innerHTML = ''; // empty the message box
                btn.innerHTML = 'Uploading...'; // change button text to "Uploading..."
            },
            onComplete: function( filename, response ) {
                btn.innerHTML = 'Choose Another File';
                progressOuter.style.display = 'none'; // hide progress bar when upload is completed

                if ( !response ) {
                    msgBox.innerHTML = 'Unable to upload file';
                    return;
                }

                if ( response.success === true ) {
                    msgBox.innerHTML = '<strong>' + escapeTags( filename ) + '</strong>' + ' successfully uploaded.';

                } else {
                    if ( response.msg )  {
                        msgBox.innerHTML = escapeTags( response.msg );

                    } else {
                        msgBox.innerHTML = 'An error occurred and the upload failed.';
                    }
                }
                
            },
            onError: function(file,error,code,message,response) {
                btn.innerHTML = 'Choose Another File';
                
                response = JSON.parse(response)
                if(code == 422){
                    errors = response.errors;
                    var text = $("<ul></ul>");
                    
                    $.each(errors,function(col,error){
                        text.append("<li>"+error[0]+"</li>");
                    })
                    
                    msgBox.innerHTML = text[0].outerHTML;
                }else{
                    progressOuter.style.display = 'none';
                    msgBox.innerHTML = 'Unable to upload file';
                }
            }
        });
    </script>
</body>

</html>
