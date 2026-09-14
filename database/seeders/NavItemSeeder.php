<?php

namespace Database\Seeders;

use App\Models\NavItem;
use Illuminate\Database\Seeder;

class NavItemSeeder extends Seeder
{
    /**
     * Imports the menu that used to be hard-coded in the layout. Safe to re-run:
     * rows are matched on label, so an admin's edits are only overwritten for
     * items they have not renamed.
     */
    public function run(): void
    {
        foreach (NavItem::defaultRows() as $i => $row) {
            NavItem::firstOrNew(['label' => $row['label']])
                ->fill($row + ['sort_order' => $i, 'is_active' => true])
                ->save();
        }
    }
}
