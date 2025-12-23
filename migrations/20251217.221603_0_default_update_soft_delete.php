<?php

declare(strict_types=1);

namespace App\Migration;

use Cycle\Migrations\Migration;

class OrmDefaultF469ec8604d09c22212f93e369677461 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('cities')
            ->addColumn('deleted_at', 'datetime', ['nullable' => true])
            ->update();

        $this->table('stocks')
            ->addColumn('deleted_at', 'datetime', ['nullable' => true])
            ->update();
    }

    public function down(): void
    {
        $this->table('cities')
            ->dropColumn('deleted_at')
            ->update();

        $this->table('stocks')
            ->dropColumn('deleted_at')
            ->update();
    }
}
