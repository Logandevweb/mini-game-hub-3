<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('games')->insert([
            ['name' => 'Box Snake', 'slug' => 'boxsnake', 'gif'  => '/games/boxsnake/screenshot.gif'],
            ['name' => 'Dinos', 'slug' => 'dinos', 'gif'  => '/games/dinos/screenshot.gif'],
            ['name' => 'Electron Stream', 'slug' => 'electronstream', 'gif'  => '/games/electronstream/screenshot.gif'],
            ['name' => 'Juggling Chain', 'slug' => 'jugglingchain', 'gif'  => '/games/jugglingchain/screenshot.gif'],
            ['name' => 'Jump in box', 'slug' => 'jumpinbox', 'gif'  => '/games/jumpinbox/screenshot.gif'],
            ['name' => 'Pakupaku', 'slug' => 'pakupaku', 'gif'  => '/games/pakupaku/screenshot.gif'],
            ['name' => 'Pillars 3d', 'slug' => 'pillars3d', 'gif'  => '/games/pillars3d/screenshot.gif'],
            ['name' => 'Thrust Strafe', 'slug' => 'thruststrafe', 'gif'  => '/games/thruststrafe/screenshot.gif'],
            ['name' => 'Thunder', 'slug' => 'thunder', 'gif'  => '/games/thunder/screenshot.gif'],
            ['name' => 'Timber Test', 'slug' => 'timbertest', 'gif'  => '/games/timbertest/screenshot.gif'],
            ['name' => 'Up1Way', 'slug' => 'up1way', 'gif'  => '/games/up1way/screenshot.gif'],
            ['name' => 'ZigZag Pressure', 'slug' => 'zigzagpressure', 'gif'  => '/games/zigzagpressure/screenshot.gif'],
        ]);
    }

    public function down(): void
    {
        DB::table('games')->whereIn('slug', [
            'boxsnake',
            'dinos',
            'electronstream',
            'jugglingchain',
            'jumpinbox',
            'pakupaku',
            'pillars3d',
            'thruststrafe',
            'thunder',
            'timbertest',
            'up1way',
            'zigzagpressure'
        ])->delete();
    }
};
