<?php
declare(strict_types=1);

namespace App\Transport\Http\ApiDoc\Schema;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CityRequest",
    title: "City Request Model",
    description: "Payload for creating or updating a city",
    required: ["name"],
    properties: [
        new OA\Property(
            property: "name",
            description: "The name of the city",
            type: "string",
            maxLength: 255,
            minLength: 2,
            example: "Berlin"
        )
    ]
)]
interface CityRequestInterface
{

}
