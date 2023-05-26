$(document).ready(function() {
    $("#date").val(new Date().toISOString().slice(0,new Date().toISOString().lastIndexOf(":")));
    let dateInput = document.getElementById("date");
    dateInput.min = new Date().toISOString().slice(0,new Date().toISOString().lastIndexOf(":"));
    
    const datetimeInput = document.querySelector('#date');

    // Définit la fonction pour arrondir une date à un multiple de 15 minutes
    function roundToNearest20Minutes(date) {
        const coeff = 1000 * 60 * 20; // nombre de millisecondes dans 15 minutes
        return new Date(Math.round(date.getTime() / coeff) * coeff);
    }
    $("#medecin").html(sessionStorage.getItem('medecin'));
    $(document).on('input', '#date', function() {
        const inputDate = new Date(datetimeInput.value);
        const roundedDate = roundToNearest20Minutes(inputDate);
        const roundedDateString = roundedDate.toISOString().slice(0, 16);
        datetimeInput.value = roundedDateString;
    })

    $(document).on('click', '#reserver', function() {
        var dateRdv = $("#date").val().replace("T", " ");
        var med = sessionStorage.getItem('medecin');
        var idpat = sessionStorage.getItem('idPatient');
        $.ajax({
            method: "POST",
            url: URIRDV,
            data: JSON.stringify({dateHeureRdv: dateRdv, idPatient: idpat, idMedecin: med}),
          })
          .done(function(data, textStatus, jqXHR) {
            alert("Votre rendez-vous a bien été enregistré !")
          })
          .fail(function() {
            if (jqXHR.status === 409) {
                // Handle 404 error
                alert("L'heure de ce rendez-vous est déjà prise");
            } else if (jqXHR.status === 500) {
                // Handle 500 error
                alert("Une erreur interne est survenue");
            } else {
                // Handle other errors
                alert("Une erreur est survenue.");
            }
          });
    })
});