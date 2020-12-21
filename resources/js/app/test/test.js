$(() => {
    if ($('#map')) {
        let littleton = L.marker([39.61, -105.02]).bindPopup('This is Littleton, CO.'),
            denver    = L.marker([39.74, -104.99]).bindPopup('This is Denver, CO.'),
            aurora    = L.marker([39.73, -104.8]).bindPopup('This is Aurora, CO.'),
            golden    = L.marker([39.77, -105.23]).bindPopup('This is Golden, CO.');

        let cities = L.layerGroup([littleton, denver, aurora, golden]);

        let streets = L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}", {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 22,
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoiYmV3YXRlci1kZXYiLCJhIjoiY2tpeWU4dGIwNDJleDJ5cWptcjJqamt1aSJ9.jo7MJzmi_-zqWUX3KJACYg'
        });

        let grayscale = L.tileLayer("https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}", {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 22,
            id: 'mapbox/light-v10',
            tileSize: 512,
            zoomOffset: -1,
            accessToken: 'pk.eyJ1IjoiYmV3YXRlci1kZXYiLCJhIjoiY2tpeWU4dGIwNDJleDJ5cWptcjJqamt1aSJ9.jo7MJzmi_-zqWUX3KJACYg'
        });

        let map = L.map('map', {
            center: [39.73, -104.99],
            zoom: 10,
            layers: [grayscale, cities]
        });

        let baseMaps = {
            "Grayscale": grayscale,
            "Streets": streets
        };

        let overlayMaps = {
            "Cities": cities
        };

        L.control.layers(baseMaps, overlayMaps).addTo(map);

        let marker = L.marker([51.5, -0.10]).addTo(map);
        marker.bindPopup("<b>Hello world!</b><br>I am a popup.");

        let geojsonFeature = {
            "type": "Feature",
            "properties": {},
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [
                                -0.105743408203125,
                                51.51515248101072
                        ],
                        [
                                -0.08909225463867188,
                                51.50489601254001
                        ],
                        [
                                -0.06505966186523438,
                                51.51322956905176
                        ],
                        [
                            -0.07055282592773438,
                            51.524765823244685
                        ],
                        [
                            -0.105743408203125,
                            51.51515248101072
                        ]
                    ]
                ]
            }
        };

        var geoJsonLayer = L.geoJSON().addTo(map);

        geoJsonLayer.addData(geojsonFeature);

        var popup = L.popup();

        function onMapClick(e) {
            popup.setLatLng(e.latlng)
                .setContent("You clicked the map at " + e.latlng.toString())
                .openOn(map);
        }

        map.on('click', onMapClick);
    }
});
