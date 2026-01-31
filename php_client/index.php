<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>English to Darija Translator</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; line-height: 1.6; }
        textarea { width: 100%; height: 100px; margin-bottom: 10px; }
        .result { background: #f4f4f4; padding: 15px; border-radius: 5px; border-left: 5px solid #2ecc71; margin-top: 20px; }
        button { background: #3498db; color: white; border: none; padding: 10px 20px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Traducteur Anglais -> Darija</h1>

    <form method="POST">
        <label>Texte en anglais :</label>
        <textarea name="text_to_translate" required placeholder="Type something in English..."><?php echo isset($_POST['text_to_translate']) ? htmlspecialchars($_POST['text_to_translate']) : ''; ?></textarea>
        <button type="submit">Traduire</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['text_to_translate'])) {
        $englishText = $_POST['text_to_translate'];

        // 1. L'URL de ton API Java sur WildFly
        $apiUrl = "http://localhost:8080/DarijaTranslatorAPI-1.0-SNAPSHOT/translate";

        // 2. Préparation des données JSON (comme dans Postman)
        $data = json_encode(["text" => $englishText]);

        // 3. Configuration de la requête cURL
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);

        // 4. Exécution et récupération de la réponse
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // 5. Affichage du résultat
        if ($httpCode === 200) {
            $result = json_decode($response, true);
            echo "<div class='result'>";
            echo "<strong>Traduction :</strong><br>" . nl2br(htmlspecialchars($result['translation']));
            echo "</div>";
        } else {
            echo "<div class='result' style='border-color: red;'>";
            echo "Erreur : Impossible de contacter l'API (Code HTTP : $httpCode)";
            echo "</div>";
        }
    }
    ?>
</body>
</html>