function switchDisplay() {
    let blocContainerAller = document.getElementById('bloc-container-aller');
    let repartitionTableAller = document.getElementById('repartition-table-aller');
    let blocContainerRetour = document.getElementById('bloc-container-retour');
    let repartitionTableRetour = document.getElementById('repartition-table-retour');

    //Si le tableau est visible alors on est en mode desktop
    if (repartitionTableAller.checkVisibility() || repartitionTableRetour.checkVisibility()) {
        blocContainerAller.classList.add('show');
        blocContainerAller.classList.remove('hidden');

        repartitionTableAller.classList.add('hidden');
        repartitionTableAller.classList.remove('show');

        blocContainerRetour.classList.add('show');
        blocContainerRetour.classList.remove('hidden');

        repartitionTableRetour.classList.add('hidden');
        repartitionTableRetour.classList.remove('show');
    } else {
        blocContainerAller.classList.add('hidden');
        blocContainerAller.classList.remove('show');

        repartitionTableAller.classList.add('show');
        repartitionTableAller.classList.remove('hidden');

        blocContainerRetour.classList.add('hidden');
        blocContainerRetour.classList.remove('show');

        repartitionTableRetour.classList.add('show');
        repartitionTableRetour.classList.remove('hidden');
    }
}

function switchDirection(direction) {
    let btnAller = document.getElementById('btn-aller');
    let btnRetour = document.getElementById('btn-retour');

    let blocAller = document.getElementById('bloc-aller');
    let blocRetour = document.getElementById('bloc-retour');

    if (direction === 'aller') {
        btnAller.classList.add('active');
        btnRetour.classList.remove('active');

        blocAller.classList.add('show');
        blocAller.classList.remove('hidden');

        blocRetour.classList.add('hidden');
        blocRetour.classList.remove('show');
    } else {
        btnAller.classList.remove('active');
        btnRetour.classList.add('active');

        blocAller.classList.add('hidden');
        blocAller.classList.remove('show');

        blocRetour.classList.add('show');
        blocRetour.classList.remove('hidden');
    }
}

