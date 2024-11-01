<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PETA LEAFLET DENGAN PHP MYSQL</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        #map {
            width: 100%;
            height: 600px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container border border-primary rounded">
        <div class="alert alert-primary text-center" role="alert">
            <h1>KABUPATEN SLEMAN</h1>
            <h4>Provinsi Yogyakarta</h4>
        </div>
        <div id="map"></div>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            // Inisialisasi peta
            var map = L.map("map").setView([-7.771894719699051, 110.29587456492033], 10);

            // Tile Layer Base Map
            var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            });

            var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
            });

            var rupabumiindonesia = L.tileLayer('https://geoservices.big.go.id/rbi/rest/services/BASEMAP/Rupabumi_Indonesia/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Badan Informasi Geospasial'
            });

            // Menambahkan layer ke peta
            osm.addTo(map);

            // Control Layer
            var baseMaps = {
                "OpenStreetMap": osm,
                "Esri World Imagery": Esri_WorldImagery,
                "Rupa Bumi Indonesia": rupabumiindonesia,
            };

            var controllayer = L.control.layers(baseMaps, null, { collapsed: false });
            controllayer.addTo(map); // Tambahkan kontrol layer setelah layer ditambahkan ke peta
        </script>

        <?php
        // Mengoneksikan ke database sesuai dengan setting MySQL
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "pgweb-acara8";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM acara8";
        $result = $conn->query($sql);

        // Menambahkan marker ke peta
        if ($result->num_rows > 0) {
            echo "<script>"; // Memulai block JavaScript
            while ($row = $result->fetch_assoc()) {
                $long = $row["longitude"];
                $lat = $row["latitude"];
                $info = htmlspecialchars($row["kecamatan"]); // Menghindari XSS
                echo "L.marker([$lat, $long]).addTo(map).bindPopup('$info');\n"; // Menambahkan marker ke peta
            }
            echo "</script>"; // Menutup block JavaScript
        } else {
            echo "<script>console.log('0 results');</script>";
        }

        // Menampilkan tabel data
        echo "<table><tr>
            <th>Kecamatan</th>
            <th>Longitude</th>
            <th>Latitude</th>
            <th>Luas</th>
            <th>Jumlah Penduduk</th>
            <th>Aksi</th>
        </tr>";

        // Output data dari setiap baris
        if ($result->num_rows > 0) {
            $result->data_seek(0); // Mengembalikan pointer hasil ke baris pertama untuk digunakan kembali
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>" . ($row["kecamatan"]) . "</td>
                    <td>" . ($row["longitude"]) . "</td>
                    <td>" . ($row["latitude"]) . "</td>
                    <td>" . ($row["luas"]) . "</td>
                    <td align='right'>" . ($row["jumlah_penduduk"]) . "</td>
                    <td>
                        <a href='edit.php?id=" . intval($row["ID"]) . "'>Edit</a> |     
                        <a href='delete.php?id=" . intval($row["ID"]) . "' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?');\">Delete</a>
                    </td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Tidak ada data</p>";
        }

        $conn->close();
        ?>

</body>

</html>
