<?php
declare(strict_types=1);

namespace App\Application\Command\Stock\UpdateStock;

use Yiisoft\Hydrator\Attribute\Parameter\Data;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Type\FloatType;
use Yiisoft\Validator\Rule\Uuid;

final class UpdateStockCommand
{
    /**
     * @param string $id
     * @param string|null $cityId
     * @param string|null $address
     * @param float|null $lat
     * @param float|null $lng
     */
    public function __construct(
        #[Required]
        #[Uuid]
        public string $id,

        #[Data('city_id')]
        #[Uuid(skipOnEmpty: true)]
        public string|null $cityId = null,
        public string|null $address = null,

        #[FloatType(skipOnEmpty: true)]
        public float|null $lat = null,

        #[FloatType(skipOnEmpty: true)]
        public float|null $lng = null,
    )
    {
    }

    /**
     * @return Result
     */
    #[Callback]
    public function validateCoords(): Result
    {
        $result = new Result();
        if (($this->lat === null) !== ($this->lng === null)) {
            $result->addError('Both lat and lng must be provided together or both omitted.');
        }
        return $result;
    }
}
