function switchDisplay() {
    let blocContainer = document.getElementById('bloc-container');
    let repartitionTable = document.getElementById('repartition-table');

    //Si le tableau est visible alors on est en mode desktop
    if (repartitionTable.checkVisibility()) {
        blocContainer.classList.add('show');
        blocContainer.classList.remove('hidden');

        repartitionTable.classList.add('hidden');
        repartitionTable.classList.remove('show');
    } else {
        blocContainer.classList.add('hidden');
        blocContainer.classList.remove('show');

        repartitionTable.classList.add('show');
        repartitionTable.classList.remove('hidden');
    }
}
