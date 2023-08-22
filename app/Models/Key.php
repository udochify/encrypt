<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Crypto\Rsa\KeyPair;

class Key extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'rsa_pri_key',
        'rsa_pub_key',
        'rsa_pri_key_path',
        'rsa_pub_key_path',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public static function generateRSAKeys($pathToPrivateKey, $pathToPublicKey)
    {
        try {
            return (new KeyPair())->generate($pathToPrivateKey, $pathToPublicKey);
        }
        catch(Exception $e) {
            throw $e;
        }
        return false;
    }
}
