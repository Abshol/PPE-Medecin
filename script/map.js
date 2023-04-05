sessionStorage.setItem("connecte", false)


var map = L.map('map').setView([48.822915, 2.271123], 14);
$(document).ready(function(){
    $.ajax({
        method: "GET",
        url: "https://data.issy.com/api/records/1.0/search/?dataset=medecins-generalistes-et-infirmiers&q=&rows=10000",
        dataType: "json",
        success: function(data) {
            $.each(data.records, function(i, doc) {
                if (doc.fields.ville == "ISSY LES MOULINEAUX" && doc.fields.specialite == "MEDECIN GENERALISTE") {
                    if (sessionStorage.getItem("connecte") == true) {
                        var select = "<button>Réserver un rendez-vous</button>";
                    } else {
                        var select = "<br>Vous n'êtes pas connectés, <button id='connexion'>Se connecter</button>";
                    }
                    console.log(doc.fields.geolocalisation);
                    var marker = L.marker(doc.fields.geolocalisation).addTo(map);
                    marker.bindPopup("<b>"+doc.fields.specialite+"</b><br>"+doc.fields.civilite+" "+doc.fields.nom+"."+select).openPopup();
                }
            });
        }
    });

    $(document).on('click', '#connexion', function(event) {
        window.location.href = "connexion/";
    });
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    // var circle = L.circle([51.508, -0.11], {
    //     color: 'red',
    //     fillColor: '#f03', 
    //     fillOpacity: 0.5,
    //     radius: 500
    // }).addTo(map);

    // var polygon = L.polygon([
    //     [51.509, -0.08],
    //     [51.503, -0.06],
    //     [51.51, -0.047]
    // ]).addTo(map);

    
    // circle.bindPopup("I am a circle.");
    // polygon.bindPopup("I am a polygon.");

    // var popup = L.popup()
    //     .setLatLng([51.513, -0.09])
    //     .setContent("I am a standalone popup.")
    //     .openOn(map);

    function onMapClick(e) {
        alert("You clicked the map at " + e.latlng);
    }

    map.on('click', onMapClick);

    var popup = L.popup();

    function onMapClick(e) {
        popup
            .setLatLng(e.latlng)
            .setContent("You clicked the map at " + e.latlng.toString())
            .openOn(map);
    }

    map.on('click', onMapClick);
});