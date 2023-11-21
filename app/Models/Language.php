<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Language extends Model
{
    use Notifiable;
    protected $table = 'languages';
    protected $fillable=['abbr','local','name','direction','active','created_at','updated_at'];


    public function scopeActive($q){
        return $q->where('active',1);
    }
    public function scopeSelection($q){
        return $q->select('id','abbr','name','direction','active');
    }
    public function getActive(){
        return $this->active ==1 ?'مفعل':'غير مفعل' ;
    }
}
