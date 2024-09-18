<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Helpers\EncryptionHelper;
class profileModel extends Model
{
    use HasFactory;

    protected $table = 'profile_user';

    protected $primaryKey="profile_id";
    public $incrementing=false;
    protected $keyType = "string";
    // fillable
   protected $fillable = [
        'profile_id',
        'user_id',
        'second_phone_number',
    ];

    // relationship

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }

    // create boot method

    protected static function boot() {
        parent::boot();

        //generate UUID for profile_id

        static::creating(function($model){
            $model->profile_id=(string) str::uuid();
        });
        
        
        // Add saving event to encrypt data
        static::saving(function ($model) {
            
            $encryptionHelper = new EncryptionHelper();
            $model->second_phone_number = $encryptionHelper->encryptData($model->second_phone_number);
        });

        // Add retrieved event to decrypt data
        static::retrieved(function ($model) {
            $encryptionHelper = new EncryptionHelper();
            $model->second_phone_number = $encryptionHelper->decryptData($model->second_phone_number);
        });
    }
}