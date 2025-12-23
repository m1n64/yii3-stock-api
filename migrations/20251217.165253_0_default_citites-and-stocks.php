<?php

declare(strict_types=1);

namespace App\Migration;

use Cycle\Migrations\Migration;

class OrmDefaultAcb1f2c370c44803f6dba9e2156a0780 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $cities = $this->table('cities');
        $cities->addColumn('city_id', 'uuid', ['nullable' => false]);
        $cities->addColumn('name', 'string', ['length' => 255, 'nullable' => false]);
        $cities->addColumn('created_at', 'datetime', ['nullable' => false]);
        $cities->setPrimaryKeys(['city_id']);
        $cities->create();

        $stocks = $this->table('stocks');
        $stocks->addColumn('stock_id', 'uuid', ['nullable' => false]);
        $stocks->addColumn('city_id', 'uuid', ['nullable' => false]);
        $stocks->addColumn('address', 'string', ['length' => 255, 'nullable' => false]);
        $stocks->addColumn('lat', 'float8', ['nullable' => false]);
        $stocks->addColumn('lng', 'float8', ['nullable' => false]);
        $stocks->addColumn('created_at', 'datetime', ['nullable' => false]);
        $stocks->addColumn('updated_at', 'datetime', ['nullable' => true]);

        $stocks->setPrimaryKeys(['stock_id']);

        $stocks->addForeignKey(['city_id'], 'cities', ['city_id'], [
            'delete' => 'CASCADE',
            'update' => 'CASCADE'
        ]);

        $stocks->create();

        $this->database()->execute('ALTER TABLE cities ALTER COLUMN city_id SET DEFAULT gen_random_uuid()');
        $this->database()->execute('ALTER TABLE stocks ALTER COLUMN stock_id SET DEFAULT gen_random_uuid()');
        $this->database()->execute(
            'CREATE INDEX stocks_coords_gist_idx ON stocks USING GIST (CAST(ST_SetSRID(ST_MakePoint(lng, lat), 4326) AS geography))'
        );
    }

    public function down(): void
    {
        $this->table('stocks')->drop();
        $this->table('cities')->drop();
    }
}
