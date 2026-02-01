package org.example.darijatranslatorapi;

public class TranslationRequest {
    private String text;

    // Constructeur vide obligatoire pour JAX-RS
    public TranslationRequest() {}

    public String getText() { return text; }
    public void setText(String text) { this.text = text; }
}