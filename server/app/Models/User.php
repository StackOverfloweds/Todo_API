<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Str;
use App\Helpers\EncryptionHelper;
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $primaryKey="user_id";
    public $incrementing=false;
    protected $keyType = "string";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone_number',
    ];

    /**
     * create boot method
     */

     protected static function boot() {
        parent::boot();

        //generate UUID for user_id
        static::creating(function($model){
            $model->user_id=(string) str::uuid();
        });

        //add saving event to create profile
        static::saving(function($model) {
            $encryptionHelper = new EncryptionHelper();
            $model->name = $encryptionHelper->encryptData($model->name);
            $model->phone_number = $encryptionHelper->encryptData($model->phone_number);
        });

         // Add retrieved event to decrypt data
         static::retrieved(function ($model) {
            $encryptionHelper = new EncryptionHelper();
            $model->name = $encryptionHelper->decryptData($model->name);
            $model->phone_number = $encryptionHelper->decryptData($model->phone_number);
        });

     }

     /**
     * Get the identifier that will be stored in the JWT subject claim.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
    * Return a key value array, containing any custom claims to be added to the JWT.
    *
    * @return array
    */
    public function getJWTCustomClaims()
    {
        return [];
    }
}