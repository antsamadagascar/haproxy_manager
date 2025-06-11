// Constants pour les IDs
const ADD_LOG_FORM_ID = 'addLogForm';
const DELETE_LOG_FORM_ID = 'deleteLogForm';
const LOG_LEVEL_SELECT_ID = 'logLevel';
const LOG_TO_DELETE_SELECT_ID = 'logToDelete';
const NAME_INPUT_ID = 'nameInput';

// Event Listeners pour les boutons
document.getElementById('addLogButton').addEventListener('click', function() {
    toggleForm(ADD_LOG_FORM_ID, DELETE_LOG_FORM_ID);
});

document.getElementById('deleteLogButton').addEventListener('click', function() {
    toggleForm(DELETE_LOG_FORM_ID, ADD_LOG_FORM_ID);
});

/**
 * Bascule l'affichage du formulaire ciblé et cache l'autre
 * @param {string} formToToggleId - ID du formulaire à basculer
 * @param {string} formToHideId - ID du formulaire à cacher
 */
function toggleForm(formToToggleId, formToHideId) {
    const formToToggle = document.getElementById(formToToggleId);
    const formToHide = document.getElementById(formToHideId);
    
    // Cache l'autre formulaire
    formToHide.style.display = 'none';
    
    // Bascule l'affichage du formulaire ciblé
    if (formToToggle.style.display === 'none' || formToToggle.style.display === '') {
        formToToggle.style.display = 'flex';
        
        // Si c'est le formulaire d'ajout, initialise les champs
        if (formToToggleId === ADD_LOG_FORM_ID) {
            initializeAddForm();
        }
        // Si c'est le formulaire de suppression, charge les options
        else if (formToToggleId === DELETE_LOG_FORM_ID) {
            initializeDeleteForm();
        }
    } else {
        formToToggle.style.display = 'none';
    }
}

async function initializeAddForm() {
    const select = document.getElementById(LOG_LEVEL_SELECT_ID);
    try {
        const response = await fetch(`${BASE_URL}Log_Controller/get_logs`);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const responseText = await response.text();
        
        // Extraire uniquement la partie JSON
        const jsonStartIndex = responseText.indexOf('[');
        const jsonEndIndex = responseText.lastIndexOf(']') + 1;
        
        if (jsonStartIndex !== -1 && jsonEndIndex !== -1) {
            const jsonString = responseText.substring(jsonStartIndex, jsonEndIndex);
            const logs = JSON.parse(jsonString);
            
            // Vider et remplir le select avec les niveaux de log
            select.innerHTML = '';
            logs.forEach(log => {
                const option = document.createElement('option');
                option.value = log.nom;
                option.textContent = `${log.nom} (${log.code_numerique})`;
                select.appendChild(option);
            });

            const localCount = GLOBAL_CONFIG.filter(line => line.includes("/dev/log")).length;
            nameInput.value = `local${localCount}`;
        } else {
            console.error('Aucun JSON valide trouvé dans la réponse');
        }
    } catch (error) {
        console.error('Erreur lors de l\'initialisation du formulaire:', error);
    }
}
/**
 * Initialise le formulaire de suppression avec les options disponibles
 */
function initializeDeleteForm() {
    const select = document.getElementById(LOG_TO_DELETE_SELECT_ID);
    
    // Vide et remplit le select avec les logs existants
    select.innerHTML = '';
    const logs = GLOBAL_CONFIG.filter(line => line.includes("/dev/log"));

    logs.forEach(log => {
        const match = log.match(/local\d+/);
        if (match) {
            const option = document.createElement('option');
            option.value = log;
            option.textContent = match[0];
            select.appendChild(option);
        }
    });
}