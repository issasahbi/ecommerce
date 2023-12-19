<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use Notifiable;
    protected $table = 'vendors';
    protected $fillable=['mobile','address','password','name','email','category_id','logo','active','created_at','updated_at'];

    public function scopeActive($q){
        return $q->where('active',1);
    }
    public function scopeSelection($q){
        return $q-> select('id','name','address','category_id','email','logo','mobile','active');
    }
    // Accessor to Logo
    public function getLogoAttribute($val){
        return ($val !== null)? asset('assets/'.$val) : "";
    }

    public function getActiveAttribute($val){
        return  ($val == 1)? 'مفعل' : ' غير مفعل';
    }

    public function category(){
        return $this->belongsTo('App\Models\MainCategory','category_id','id');
    }

    public function setPasswordAttribute($val)
    {
        if (!empty($val)) {
             $this->attributes['password'] = bcrypt($val);
        }
    }
}
