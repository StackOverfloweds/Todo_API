<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
class todoModel extends Model
{
    use HasFactory;

    protected $table="todo";

    protected $primaryKey="todo_id";
    public $incrementing=false;
    protected $keyType = "string";

    protected $fillable=["user_id","name","status_task"];

    protected static function boot()
    {
        parent::boot();
        //generate uuid for user_id
        static::creating(function ($todo) {
            $todo->todo_id=(string) Str::uuid();
        });    
    } 

     /**
     * Define the relationship between Todo and User.
     * 
     * @return BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}