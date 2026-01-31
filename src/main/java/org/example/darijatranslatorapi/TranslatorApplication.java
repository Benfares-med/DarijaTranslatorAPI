package org.example.darijatranslatorapi;

import jakarta.ws.rs.ApplicationPath;
import jakarta.ws.rs.core.Application;

@ApplicationPath("/") // Ceci sera le préfixe de toutes vos URLs
public class RestApplication extends Application {
    // Laisser vide, c'est suffisant pour activer JAX-RS sur WildFly
}