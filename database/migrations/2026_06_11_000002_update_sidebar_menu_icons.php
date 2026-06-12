<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateSidebarMenuIcons extends Migration
{
    private array $icons = [
        1 => 'fa fa-cogs',
        2 => 'fa fa-sliders-h',
        3 => 'fa fa-building',
        5 => 'fa fa-briefcase',
        6 => 'fa fa-calendar-check',
    ];

    private array $legacyIcons = [
        1 => 'far fa-cogs',
        2 => 'far fa-cog',
        3 => 'far fa-cog',
        5 => 'far fa-cog',
        6 => 'far fa-cog',
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

        foreach ($this->legacyIcons as $id => $icon) {
            DB::table('menus')->where('id', $id)->update(['icon' => $icon]);
        }
    }
}
