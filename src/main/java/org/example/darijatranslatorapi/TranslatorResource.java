package org.example.darijatranslatorapi;

import jakarta.ws.rs.*;
import jakarta.ws.rs.core.MediaType;
import jakarta.ws.rs.core.Response;
import jakarta.json.*;

import java.io.StringReader;
import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;

@Path("/translate")
public class TranslatorResource {

    // On utilise ta clé Groq
    String GROQ_API_KEY = System.getenv("GROQ_API_KEY");;
    private static final String GROQ_URL = "https://api.groq.com/openai/v1/chat/completions";

    @POST
    @Consumes(MediaType.APPLICATION_JSON)
    @Produces(MediaType.APPLICATION_JSON)
    public Response translate(TranslationRequest request) {
        // Vérification du texte
        if (request == null || request.getText() == null || request.getText().trim().isEmpty()) {
            return Response.status(Response.Status.BAD_REQUEST)
                    .entity("{\"error\":\"Text is required\"}")
                    .build();
        }

        try {
            // --- MODIFICATION ICI ---
            // On demande explicitement : SEULEMENT l'alphabet Latin, PAS d'arabe.
            String prompt = "Translate this English text to Moroccan Darija using ONLY the Latin alphabet (French/Chat style). " +
                    "Do NOT use Arabic letters. " +
                    "Return ONLY the translation, no explanations, no notes. " +
                    "Text to translate: " + request.getText();

            String translatedText = callGroqAPI(prompt);

            JsonObject responseJson = Json.createObjectBuilder()
                    .add("translation", translatedText)
                    .build();

            // AJOUT DES HEADERS CORS ICI
            return Response.ok(responseJson.toString())
                    .header("Access-Control-Allow-Origin", "*")
                    .header("Access-Control-Allow-Methods", "POST, GET, OPTIONS")
                    .header("Access-Control-Allow-Headers", "Content-Type")
                    .build();

        } catch (Exception e) {
            return Response.serverError()
                    .entity("{\"error\":\"" + e.getMessage() + "\"}")
                    .header("Access-Control-Allow-Origin", "*")
                    .build();
        }
    }

    private String callGroqAPI(String promptText) throws Exception {
        // Construction du JSON au format OpenAI/Groq
        String jsonBody = "{"
                + "\"model\": \"llama-3.1-8b-instant\","
                + "\"messages\": [{\"role\": \"user\", \"content\": \"" + escapeJson(promptText) + "\"}]"
                + "}";

        HttpClient client = HttpClient.newHttpClient();
        HttpRequest request = HttpRequest.newBuilder()
                .uri(URI.create(GROQ_URL))
                .header("Content-Type", "application/json")
                .header("Authorization", "Bearer " + GROQ_API_KEY)
                .POST(HttpRequest.BodyPublishers.ofString(jsonBody))
                .build();

        HttpResponse<String> response = client.send(request, HttpResponse.BodyHandlers.ofString());

        if (response.statusCode() == 200) {
            return extractTextFromGroqResponse(response.body());
        } else {
            throw new RuntimeException("Erreur Groq (Code " + response.statusCode() + "): " + response.body());
        }
    }

    private String extractTextFromGroqResponse(String jsonResponse) {
        try (JsonReader reader = Json.createReader(new StringReader(jsonResponse))) {
            JsonObject root = reader.readObject();
            return root.getJsonArray("choices")
                    .getJsonObject(0)
                    .getJsonObject("message")
                    .getString("content")
                    .trim();
        } catch (Exception e) {
            throw new RuntimeException("Erreur parsing Groq JSON: " + e.getMessage());
        }
    }

    private String escapeJson(String text) {
        if (text == null) return "";
        return text.replace("\\", "\\\\").replace("\"", "\\\"").replace("\n", " ").replace("\r", "");
    }
}