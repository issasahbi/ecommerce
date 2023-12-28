<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SubCategory extends Model
{
    use Notifiable;
    protected $table = 'sub_categories';
    protected $fillable=['translation_lang','parent_id','translation_of','name','slug','photo','active','created_at','updated_at'];

    public function scopeActive($q){
        return $q->where('active',1);
    }
    public function scopeSelection($q){
        return $q-> select('id','translation_lang','translation_of','name','slug','photo','active','parent_id');
    }

    public function getPhotoAttribute($val){
        return( $val!==null) ? asset('assets/'.$val) : "";
    }
    public function getActive(){
        return $this->active ==1 ?'مفعل':'غير مفعل' ;
    }

    public function maincategories(){
        return $this->belongsTo('App\Models\MainCategory','category_id','id');
    }
   /* public function categories(){
        return $this->hasMany(self::class,'translation_of');
    }

    public function vendors(){
        return $this->hasMany('App\Models\Vendor','category_id','id');
    }*/
}
