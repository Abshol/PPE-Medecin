$(document).ready(function() {
    $("#date").val(new Date().toISOString().slice(0,new Date().toISOString().lastIndexOf(":")));
    
    const datetimeInput = document.querySelector('#date');

    // Définit la fonction pour arrondir une date à un multiple de 15 minutes
    function roundToNearest20Minutes(date) {
        const coeff = 1000 * 60 * 20; // nombre de millisecondes dans 15 minutes
        return new Date(Math.round(date.getTime() / coeff) * coeff);
    }

    $(document).on('input', '#date', function() {
        const inputDate = new Date(datetimeInput.value);
        const roundedDate = roundToNearest20Minutes(inputDate);
        const roundedDateString = roundedDate.toISOString().slice(0, 16);
        datetimeInput.value = roundedDateString;
    })
});