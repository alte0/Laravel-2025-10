<?php

namespace App\Documentation;

use OpenApi\Attributes as OA;


#[OA\Info(
    version: "1.0.0",
    description: "API documentation for Task API",
    title: "My Task API",
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Local development server"
)]
#[OA\Server(
    url: "{protocol}://{environment}.laravel-task-api.com",
    description: "Production server",
    variables: [
        new OA\ServerVariable(
            serverVariable: "protocol",
            default: "https",
            enum: ["http", "https"]
        ),
        new OA\ServerVariable(
            serverVariable: "environment",
            default: "prod",
            enum: ["staging", "prod"]
        )
    ]
)]
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    description: "Enter token in format (Bearer <token>)",
    name: "Authorization",
    in: "header",
    bearerFormat: "JWT",
    scheme: "bearer"
)]
class OpenApiConfig
{
}
