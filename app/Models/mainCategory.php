<?php

namespace App\Models;

use App\Observers\MainCategoryObserver;
use Illuminate\Database\Eloquent\Model;

class mainCategory extends Model
{
    protected $table = 'main_categories';

    protected $fillable = [
        'translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active', 'created_at', 'updated_at'
    ];


    protected $hidden = [
        'created_at', 'updated_at',
    ];

    //observer link
    protected static function boot()
    {
        parent::boot();
        mainCategory::observe(MainCategoryObserver::class);
    }


    //relations 

    //get all translation
    public function categories()
    {
        return  $this->hasMany(self::class, 'translation_of', 'id');
    }

    public function subCategories()
    {
        return  $this->hasMany(SubCategory::class, 'category_id', 'id');
    }


    //end of relations

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeDefualtCategory($query)
    {
        return $query->where('translation_of', 0);
    }


    public function scopeSelection($query)
    {
        return $query->select('id', 'slug', 'translation_lang', 'name', 'photo', 'translation_of', 'active');
    }

    //model method 

    public function getActive()
    {
        return    $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }

    //Accessors 
    public function getPhotoAttribute($val)
    {
        return ($val !== NUll)  ? asset('assets/' . $val) : '';
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'category_id', 'id');
    }
}
