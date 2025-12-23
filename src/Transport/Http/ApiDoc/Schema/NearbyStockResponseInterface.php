<?php
declare(strict_types=1);

namespace App\Transport\Http\ApiDoc\Schema;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "NearbyStockDto",
    title: "Nearby Stock Response Model",
    description: "Data transfer object representing a nearby stock with its distance",
    properties: [
        new OA\Property(
            property: "stock",
            allOf: [
                new OA\Schema(ref: "#/components/schemas/StockDto"),
                new OA\Schema(properties: [
                    new OA\Property(property: "city", ref: "#/components/schemas/CityDto")
                ])
            ]
        ),
        new OA\Property(
            property: "distance_meters",
            type: "number",
            format: "float",
            example: 6007911.53
        )
    ]
)]
interface NearbyStockResponseInterface
{

}
