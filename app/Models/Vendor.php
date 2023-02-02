<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    protected $table = 'vendors';

    use Notifiable;

    protected $fillable =
    [
        'name', 'logo', 'mobile', 'address', 'password', 'email', 'active', 'category_id', 'latitude', 'longitude', 'created_at', 'updated_at',
    ];

    protected $hidden =
    [
        'category_id', 'created_at', 'updated_at',
    ];


    public function scopeActive($q)
    {
        return  $q->where('active', 1);
    }

    //Accessors 
    public function getLogoAttribute($val)
    {
        return ($val !== NUll)  ? asset('assets/' . $val) : '';
    }


    public function scopeSelection($q)
    {
        return $q->select('id', 'category_id', 'email', 'address', 'name', 'logo', 'mobile', 'active', 'latitude', 'longitude');
    }

    public function category()
    {
        return $this->belongsTo(mainCategory::class, 'category_id', 'id');
    }

    public function getActive()
    {
        return    $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }
}
