<?php
declare(strict_types=1);

namespace App\Transport\Http\ApiDoc\Schema;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "NearbyStockRequest",
    title: "Nearby Stock Request Model",
    description: "Data transfer object for requesting nearby stocks based on latitude and longitude",
    required: ["lat", "lng"],
    properties: [
        new OA\Property(property: "lat", type: "number", format: "float", example: -65.38491),
        new OA\Property(property: "lng", type: "number", format: "float", example: 170.51276)
    ]
)]
interface NearbyStockRequestInterface
{

}
