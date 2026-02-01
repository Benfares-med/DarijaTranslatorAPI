
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darija Translator Pro</title>
    <style>
        :root {
            --primary: #2563eb;
            --bg: #f8fafc;
            --card: #ffffff;
            --text: #1e293b;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: var(--card);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
            color: var(--primary);
        }

        label { display: block; margin-bottom: 0.5rem; font-weight: 600; }

        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            resize: none;
            font-size: 1rem;
            box-sizing: border-box;
            margin-bottom: 1rem;
        }

        button {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        button:hover { opacity: 0.9; }

        .result-box {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #f1f5f9;
            border-radius: 0.5rem;
            border-left: 4px solid var(--primary);
        }

        .error {
            color: #dc2626;
            background: #fee2e2;
            border-left-color: #dc2626;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Darija Translator</h1>

    <form method="POST">
        <label>English Text</label>
        <textarea name="text" rows="4" placeholder="Enter text to translate..." required></textarea>
        <button type="submit">Translate Now</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 1. L'URL doit correspondre exactement à celle qui demande le mot de passe
        $apiUrl = "http://localhost:8080/DarijaTranslatorAPI-1.0-SNAPSHOT/api/translate";

        $data = json_encode(["text" => $_POST['text']]);

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        // --- AJOUT DES LIGNES DE SÉCURITÉ ICI ---
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "admin:Password123!");
        // ----------------------------------------

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $result = json_decode($response, true);
            // Vérifie si le décodage a réussi avant d'afficher
            $translation = isset($result['translation']) ? $result['translation'] : $response;
            echo '<div class="result-box"><strong>Darija:</strong><br>' . htmlspecialchars($translation) . '</div>';
        } else {
            // Affichera 401 si le mot de passe est faux, ou 404 si l'URL est mauvaise
            echo '<div class="result-box error"><strong>Error ' . $httpCode . ':</strong> Access denied or Server error.</div>';
        }
    }
    ?>
</div>

</body>

</html>

