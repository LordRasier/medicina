<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixSidebarMenuIconsToAvailableSet extends Migration
{
    private array $icons = [
        1 => 'fa fa-cogs',
        2 => 'fa fa-sliders-h',
        3 => 'fa fa-building',
        5 => 'fa fa-briefcase',
        6 => 'fa fa-calendar-check',
    ];

    private array $previousIcons = [
        1 => 'fal fa-tools',
        2 => 'fal fa-sliders-h',
        3 => 'fal fa-door-open',
        5 => 'fal fa-file-invoice-dollar',
        6 => 'fal fa-calendar-check',
    ];

    public function up()
    {
        if (! Schema::hasTable('menus')) {
            return;
        }

        foreach ($this->icons as $id => $icon) {
            DB::table('menus')->where('id', $id)->update(['icon' => $icon]);
        }
    }

    public function down()
    {
        if (! Schema::hasTable('menus')) {
            return;
        }

        foreach ($this->previousIcons as $id => $icon) {
            DB::table('menus')->where('id', $id)->update(['icon' => $icon]);
        }
    }
}
