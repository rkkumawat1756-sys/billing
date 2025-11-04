<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasFactory, SoftDeletes;

    // Table name (optional if it follows Laravel convention)
    protected $table = 'settings';

    // Fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'name',
        'title',
        'mobile',
        'email',
        'address',
        'logo',
        'background_image',
        'status',
    ];

    // Dates for soft deletes
    protected $dates = ['deleted_at'];

    public function floors()
    {
        return $this->hasMany(Floor::class);
    }
}
