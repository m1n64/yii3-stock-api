<?php

declare(strict_types=1);

namespace App\Migration;

use Cycle\Migrations\Migration;

class OrmDefaultFf900a18fdddcba20d8be2600019edb3 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('cities')
            ->addColumn('updated_at', 'datetime', ['nullable' => true])
            ->update();
    }

    public function down(): void
    {
        $this->table('cities')
            ->dropColumn('updated_at')
            ->update();
    }
}
