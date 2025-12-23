<?php
declare(strict_types=1);

namespace App\Transport\Http\ApiDoc\Schema;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CityDto",
    title: "City Model",
    description: "Data transfer object representing a city entity",
    required: ["id", "name"],
    properties: [
        new OA\Property(
            property: "id",
            type: "string",
            format: "uuid",
            example: "3fa85f64-5717-4562-b3fc-2c963f66afa6"
        ),
        new OA\Property(
            property: "name",
            type: "string",
            example: "London"
        ),
        new OA\Property(
            property: "created_at",
            type: "string",
            format: "date-time",
            example: "2025-12-21T15:00:00Z"
        ),
        new OA\Property(
            property: "updated_at",
            type: "string",
            format: "date-time",
            example: "2025-12-21T15:00:00Z",
            nullable: true
        )
    ]
)]
interface CityResponseInterface
{

}
