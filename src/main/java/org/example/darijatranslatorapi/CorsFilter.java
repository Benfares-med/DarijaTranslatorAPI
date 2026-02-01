package org.example.darijatranslatorapi;

import jakarta.ws.rs.container.ContainerRequestContext;
import jakarta.ws.rs.container.ContainerResponseContext;
import jakarta.ws.rs.container.ContainerResponseFilter;
import jakarta.ws.rs.ext.Provider;
import java.io.IOException;

@Provider
public class CorsFilter implements ContainerResponseFilter {

    @Override
    public void filter(ContainerRequestContext requestContext,
                       ContainerResponseContext responseContext) throws IOException {

        // Autorise toutes les origines (nécessaire pour les extensions Chrome)
        responseContext.getHeaders().add("Access-Control-Allow-Origin", "*");

        // Autorise l'envoi des identifiants (Basic Auth)
        responseContext.getHeaders().add("Access-Control-Allow-Credentials", "true");

        // Autorise les headers spécifiques que nous utilisons
        responseContext.getHeaders().add("Access-Control-Allow-Headers",
                "origin, content-type, accept, authorization");

        // Autorise les méthodes HTTP
        responseContext.getHeaders().add("Access-Control-Allow-Methods",
                "GET, POST, PUT, DELETE, OPTIONS, HEAD");
    }
}