fetch('http://127.0.0.1:5000/route/v1/biking/51.0094,3.7846;51.0354,3.7686;51.0387,3.8153?steps=true&geometries=geojson&overview=full')
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        L.geoJSON(data).addTo(map);

    });