<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';

    protected $fillable = [
        'parent_id', 'translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active', 'created_at', 'updated_at',
    ];


    protected $hidden = [
        'created_at', 'updated_at',
    ];


    // public function scopeDefualtCategory($query)
    // {
    //     return $query->where('translation_of', 0);
    // }


    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeSelection($query)
    {
        return $query->select('id', 'parent_id', 'category_id', 'slug', 'translation_lang', 'name', 'photo', 'translation_of', 'active');
    }

    public function getActive()
    {
        return    $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }

    //Accessors 
    public function getPhotoAttribute($val)
    {
        return ($val !== NUll)  ? asset('assets/' . $val) : '';
    }


    //relation

    //get main_category of sub category
    public function mainCategory()
    {
        return $this->belongsTo(mainCategory::class, 'category_id', 'id');
    }
}
