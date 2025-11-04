<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sidebar extends Model
{
    protected $table = 'sidebars';

    // Recursive relation
    public function children()
    {
        return $this->hasMany(Sidebar::class, 'sidebar_id', 'id')
                    ->where('status', 1)
                    ->orderBy('order_by')
                    ->with('children'); // recursion enabled here
    }
}
