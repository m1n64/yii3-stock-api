<?php
declare(strict_types=1);

namespace App\Transport\Http\ApiDoc;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    description: "Service to manage cities and their stock information.",
    title: "City & Stock API",
)]
#[OA\Server(url: "http://localhost/api", description: "Local Development")]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    description: "Enter your API token in the format: Bearer <token>",
    name: "Authorization",
    in: "header",
    bearerFormat: "JWT",
    scheme: "bearer"
)]
#[OA\Components(
    schemas: [
        new OA\Schema(
            schema: "PaginationMeta",
            properties: [
                new OA\Property(property: "page_size", type: "integer", example: 10),
                new OA\Property(property: "current_page", type: "integer", example: 1),
                new OA\Property(property: "total_pages", type: "integer", example: 2)
            ],
            type: "object"
        ),
        new OA\Schema(
            schema: "ApiResponse",
            properties: [
                new OA\Property(property: "status", type: "string", example: "success"),
                new OA\Property(property: "data", type: "object")
            ],
            type: "object"
        )
    ],
    parameters: [
        new OA\Parameter(
            parameter: "TraceparentHeader",
            name: "Traceparent",
            description: "W3C Trace Context for distributed tracing",
            in: "header",
            required: false,
            schema: new OA\Schema(type: "string", example: "00-0d449f32dca6d73df88ca6c9e5d8a081-5fd9dbc51c009077-01")
        ),
        new OA\Parameter(
            parameter: "ContentTypeHeader",
            name: "Content-Type",
            description: "Indicates the media type of the resource",
            in: "header",
            required: true,
            schema: new OA\Schema(type: "string", default: "application/json")
        )
    ]
)]
#[OA\Schema(
    schema: "ValidationErrorResponse",
    properties: [
        new OA\Property(property: "status", type: "string", example: "error"),
        new OA\Property(
            property: "errors",
            type: "object",
            example: ["name" => ["The name field is required.", "Too short."]],
            additionalProperties: new OA\AdditionalProperties(
                type: "array",
                items: new OA\Items(type: "string")
            )
        )
    ],
    type: "object"
)]
final readonly class OpenApi
{

}
