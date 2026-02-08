// Funció per canviar el mes i l'any del calendari
function canviarMes() {
    // Obtenim el valor del selector de mes i l'input d'any
    const mes = document.getElementById('month-select').value;
    const any = document.getElementById('year-input').value;
    // Redirigim a la pàgina del calendari amb els nous paràmetres de mes i any
    window.location.href = `calendario.php?mes=${mes}&any=${any}`;
}

// Quan el document està carregat
document.addEventListener('DOMContentLoaded', function() {
    // Obtenim els paràmetres de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const mes = urlParams.get('mes') || new Date().getMonth() + 1;
    const any = urlParams.get('any') || new Date().getFullYear();

    // Inicialitzem el calendari amb els valors de mes i any
    inicialitzarCalendari(mes, any);

    // Afegim un event listener per amagar les opcions del dia quan es fa clic fora
    document.addEventListener('click', function(event) {
        const dayOptions = document.getElementById('day-options');
        if (!event.target.closest('.day') && !event.target.closest('#day-options')) {
            dayOptions.style.display = 'none';
        }
    });

    // Afegim event listeners per canviar el mes i l'any
    document.getElementById('month-select').addEventListener('change', canviarMes);
    document.getElementById('year-input').addEventListener('change', canviarMes);
});

// Funció per inicialitzar el calendari
function inicialitzarCalendari(mes, any) {
    // Obtenim els elements del DOM per seleccionar el mes i l'any
    const seleccionarMes = document.getElementById('month-select');
    const inputAny = document.getElementById('year-input');

    // Establim el valor del selector de mes i l'input d'any als valors passats
    seleccionarMes.value = mes;
    inputAny.value = any;

    // Generem el calendari
    generarCalendari();
}

// Funció per generar el calendari
function generarCalendari() {
    // Obtenim els elements del DOM per seleccionar el mes, l'any i els dies del calendari
    const seleccionarMes = document.getElementById('month-select');
    const inputAny = document.getElementById('year-input');
    const diesCalendari = document.getElementById('calendar-days');

    // Convertim els valors del selector de mes i l'input d'any a enters
    const mes = parseInt(seleccionarMes.value);
    const any = parseInt(inputAny.value);

    // Esborrem els dies del calendari anterior
    diesCalendari.innerHTML = '';

    // Obtenim el primer dia del mes
    const primerDia = new Date(any, mes - 1, 1).getDay();
    // Obtenim el nombre de dies del mes
    const diesMes = new Date(any, mes, 0).getDate();

    // Omplim el calendari amb dies buits fins al primer dia del mes
    for (let i = 0; i < (primerDia === 0 ? 6 : primerDia - 1); i++) {
        const diaBuit = document.createElement('div');
        diaBuit.classList.add('day');
        diesCalendari.appendChild(diaBuit);
    }

    // Omplim el calendari amb els dies reals del mes
    for (let dia = 1; dia <= diesMes; dia++) {
        const elementDia = document.createElement('div');
        elementDia.classList.add('day');
        elementDia.setAttribute('data-day', dia);
        diesCalendari.appendChild(elementDia);
    }

    // Afegim events de clic als dies després de generar-los
    const days = document.querySelectorAll('.day');

    days.forEach(day => {
        day.addEventListener('click', function(event) {
            event.stopPropagation(); // Evitar que l'event es propagui i tanqui el menú
            const rect = day.getBoundingClientRect();
            const x = rect.right + window.scrollX; // Posicionar a la dreta del dia
            const y = rect.top + window.scrollY; // Mantenir la mateixa alçada
            const dayOptions = document.getElementById('day-options');
            dayOptions.style.top = `${y}px`;
            dayOptions.style.left = `${x}px`;
            dayOptions.style.display = 'block';
        });
    });

    // Afegim events al calendari
    esdeveniments.forEach(esdeveniment => {
        const dataEsdeveniment = new Date(esdeveniment.data_inici);
        const diaEsdeveniment = dataEsdeveniment.getDate();
        const mesEsdeveniment = dataEsdeveniment.getMonth() + 1;
        const anyEsdeveniment = dataEsdeveniment.getFullYear();
        console.log(esdeveniment);
    
        if (mesEsdeveniment === mes && anyEsdeveniment === any) {
            const diaElement = document.querySelector(`.day[data-day='${diaEsdeveniment}']`);
            if (diaElement) {
                const hora = dataEsdeveniment.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                const esdevenimentDiv = document.createElement('div');
                esdevenimentDiv.classList.add('event');
                esdevenimentDiv.style.backgroundColor = esdeveniment.color; // Establir color de l'esdeveniment
                esdevenimentDiv.textContent = `${hora} - ${esdeveniment.nom_events}`;
                
                // Afegir un event de clic que redirigeix l'usuari
                esdevenimentDiv.addEventListener('click', function() {
                    // Redirigir a la pàgina de ressenya amb l'ID de l'esdeveniment
                    window.location.href = `modificarEsdeveniment.php?id_esdeveniment=${esdeveniment.id_event}`;
                });
    
                // Afegir l'esdeveniment al dia
                diaElement.appendChild(esdevenimentDiv);
            }
        }
    });
}

// Popup eventReviews.php 

        // Obtenim el popup
        var popup = document.getElementById("reviewPopup");

        // Obtenim el botó que obre el popup
        var btn = document.getElementById("addReviewBtn");

        // Obtenim l'element <span> que tanca el popup
        var span = document.getElementsByClassName("close")[0];

        // Quan l'usuari fa clic al botó, obrim el popup 
        btn.onclick = function() {
            popup.style.display = "block";
        }

        // Quan l'usuari fa clic a <span> (x), tanquem el popup
        span.onclick = function() {
            popup.style.display = "none";
        }

        // Quan l'usuari fa clic fora del popup, tanquem el popup
        window.onclick = function(event) {
            if (event.target == popup) {
                popup.style.display = "none";
            }
        }

        // Funcionalitat de valoració amb estrelles
        const starRating = document.querySelector('.star-rating');
        const stars = starRating.querySelectorAll('span');
        const ratingInput = document.getElementById('ratingInput');

        stars.forEach((star) => {
            star.addEventListener('click', () => {
                const rating = star.getAttribute('data-rating');
                ratingInput.value = rating;
                stars.forEach((s) => {
                    if (s.getAttribute('data-rating') <= rating) {
                        s.innerHTML = '★';
                    } else {
                        s.innerHTML = '☆';
                    }
                });
            });
        });