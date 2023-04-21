if (sessionStorage.getItem("connecte") != 'true') {
    window.location.href = "../";
}
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
        var idPatient = getCookie('user');
        var med = sessionStorage.getItem('medecin');
        console.log(dateRdv);
        console.log(idPatient);
        console.log(med);
        $.ajax({
            method: "POST",
            url: URIRDV,
            data: JSON.stringify({dateHeureRdv: dateRdv, idPatient: idPatient, idMedecin: med}),
          })
          .done(function(data, textStatus, jqXHR) {
              $("#nav").prepend("<span>Vous êtes connecté en tant que: <strong>"+data.nomPatient+"</strong></span> <a id='deco' class='button'>Se déconnecter</a>");
              console.log(data); // Réponse HTTP (body)
              console.log(textStatus); // Statut de la requête AJAX
              console.log(jqXHR); // Objet jqXHR (infos de la requête AJAX)
          });
    })
});