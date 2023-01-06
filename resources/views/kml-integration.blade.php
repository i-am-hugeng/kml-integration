<!DOCTYPE html>
<html>

<head>
    <title>Upload a KML file in Laravel using Ajax and display on a Leaflet Map</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin="" />
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" /> --}}
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
    <script src='https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-omnivore/v0.3.1/leaflet-omnivore.min.js'></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> --}}
</head>

<body>
    <div class="container-fluid">
        <h3 class="text-center mt-4 mb-4">Uploading a KML file using Ajax and Displaying on a Leaflet Map</h3>

        <div id="map" style="height: 550px"></div>  
        <form method="post" id="upload_form" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group mt-4 mb-4">
                <table class="table">
                    <tr>
                        <td width="40%" class="text-end"><label>Select File for Upload</label></td>
                        <td width="30"><input type="file" name="select_file" id="select_file" /></td>
                        <td width="30%" class="text-start"><input type="submit" name="upload" id="upload" class="btn btn-primary bg-gradient" value="Upload"></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</body>

</html>

<script>
    // initialize Leaflet
    var map = L.map('map').setView({
        //-2.565139471118831, 121.86494872369084 -> Kota Mamuju
        lon: 121.86494872369084,
        lat: -2.565139471118831
    }, 5);
    // add the OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>'
    }).addTo(map);
    // show the scale bar on the lower left corner
    L.control.scale().addTo(map);
    $(document).ready(function() {
        $('#upload_form').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('ajaxupload.action') }}",
                method: "POST",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    $('#message').css('display', 'block');
                    $('#message').html(data.message);
                    $('#message').addClass(data.class_name);
                    $('#uploaded_image').html(data.uploaded_image);
                    var tmp = data.uploaded_file;
                    console.log(tmp);
                    fetch(tmp)  //get the location with the new name of the saved file
                        .then(res => res.text())
                        .then(kmltext => {
                            // Create new kml overlay
                            const track = new omnivore.kml.parse(kmltext);
                            map.addLayer(track);    //add a layer with the coordinates in the file
                            // Adjust map to show the kml
                            const bounds = track.getBounds();
                            map.fitBounds(bounds);
                        }).catch((e) => {
                            console.log(e);
                        })
                }
            })
        });
    });
</script>