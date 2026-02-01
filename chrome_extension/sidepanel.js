document.getElementById('translateBtn').addEventListener('click', async () => {
    const text = document.getElementById('inputText').value;
    const resultDiv = document.getElementById('result');

    // 1. On vide le résultat précédent et on montre un message de chargement
    resultDiv.innerText = "Traduction en cours...";
    resultDiv.style.display = 'block';

    try {
        const response = await fetch('http://localhost:8080/DarijaTranslatorAPI-1.0-SNAPSHOT/api/translate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
                // ON A SUPPRIMÉ L'AUTHORIZATION BASIC ICI
            },
            body: JSON.stringify({ text: text })
        });

        if (response.ok) {
            const data = await response.json();
            // Assure-toi que ton API Java renvoie bien un objet avec la clé "translation"
            resultDiv.innerText = data.translation;
        } else {
            // Si on a encore une erreur, on affiche le texte de l'erreur
            const errorData = await response.text();
            resultDiv.innerText = "Erreur " + response.status + ": " + errorData;
        }
    } catch (error) {
        console.error(error);
        resultDiv.innerText = "Erreur de connexion au serveur local.";
    }
});