var map = L.map('map').setView([48.822915, 2.271123], 14);
$(document).ready(function(){
    $.ajax({
        method: "GET",
        url: "https://data.issy.com/api/records/1.0/search/?dataset=medecins-generalistes-et-infirmiers&q=&rows=10000",
        dataType: "json",
        success: function(data) {
            $.each(data.records, function(i, doc) {
                if (doc.fields.ville == "ISSY LES MOULINEAUX" && doc.fields.specialite == "MEDECIN GENERALISTE") {
                    if (getCookie("connecte") == 'true') {
                        var select = "<button class='reserv' id='"+doc.fields.civilite+" "+doc.fields.nom+"'>Réserver un rendez-vous</button>";
                    } else {
                        var select = "<br>Vous n'êtes pas connectés, <button id='connexion'>Se connecter</button>";
                    }
                    console.log(doc.fields.geolocalisation);
                    var marker = L.marker(doc.fields.geolocalisation).addTo(map);
                    marker.bindPopup("<h2>"+doc.fields.civilite+" "+doc.fields.nom+"</h2><br>"+doc.fields.specialite+"<br>"+doc.fields.adresse+".<br>"+select).openPopup();
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
    
    $(document).on('click', '.reserv', function(event) {
        var id = $(this).attr('id'); // Retrieve the id of the clicked button
        sessionStorage.setItem("medecin", id); // Store the id in session storage
        window.location.href = "reserver/"; // Redirect to the reservation page
    })
});