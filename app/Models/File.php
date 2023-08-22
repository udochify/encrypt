<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class File extends Model
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
        'file_path',
        'aes_key',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    
    public function get_name($len = 100)
    {
        if ( strlen($sub = (substr($this->name, strpos($this->name, '_')+1))) <= $len)
            return $sub;
        return substr_replace($sub, '...', $len);
    }

    public function get_fullname() 
    {
        return substr($this->name, strpos($this->name, '_')+1);
    }

    public function getFileSize() 
    {
        $size = Storage::size($this->file_path);

        switch (true) 
        {
            case ($size/1024 < 1):
                return $size.'B';
            case ($size/pow(1024, 2) < 1):
                return round($size/1024, 2).'KB';
            case ($size/pow(1024, 3) < 1):
                return round($size/pow(1024, 2), 2).'MB';
            case ($size/pow(1024, 4) < 1):
                return round($size/pow(1024, 3), 2).'GB';
            default:
                return round($size/pow(1024, 4), 2).'TB';
        }
    }

    public function encrypt() 
    {
        $key = Crypt::generateKey('AES-256-CBC');
        $encrypter = New Encrypter(base64_decode('uYxn5+Fl+jK6s4gY+0Bqm2BCItRHzMgVm6gDdbfmdwU='), 'AES-256-CBC');
        if($file_str = file_get_contents('storage/'.$this->file_path))
            return $encrypter->encrypt($file_str);
        return false;
    }

    public static function generateAESKey()
    {
        return base64_encode(Crypt::generateKey('AES-256-CBC'));
    }

    
    public static function aes_encrypt($str, $base64key)  {
        try {
            $encrypter = New Encrypter(base64_decode($base64key), 'AES-256-CBC');
            return $encrypter->encrypt($str);
        }
        catch(Exception $e) {
            throw $e;
        }
        return false;
    }

    
    public static function aes_decrypt($str, $base64key)  {
        try {
            $encrypter = New Encrypter(base64_decode($base64key), 'AES-256-CBC');
            return $encrypter->decrypt($str);
        }
        catch(Exception $e) {
            throw $e;
        }
        return false;
    }
}
