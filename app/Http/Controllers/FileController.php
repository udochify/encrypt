<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Rules\CustomFileRule;
use Exception;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = File::all();
        if (count($files) < 1) session()->flash('status', 'No files uploaded yet!');
        return view('files', ['files'=>$files]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('files');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request)
    {
        $file_name = time().'_'.$request->file->getClientOriginalName();
        $file_path = 'uploads/'.auth()->user()->name.'_'.auth()->user()->id.'/'.$file_name;
        try {
            // load rsa private key from uploaded rsa file
            $privateKey = PrivateKey::fromFile($_FILES['rsa_pri_file']['tmp_name']);

            // encrypt aes key with rsa private key
            $aesEncKey = base64_encode($privateKey->encrypt($request->aeskey));

            // create custom folder if it does't exit
            Storage::makeDirectory('uploads/'.auth()->user()->name.'_'.auth()->user()->id);

            // encrypt upload with aes key and store encrypted data to disk
            Storage::put($file_path, File::aes_encrypt(file_get_contents($_FILES['file']['tmp_name']), $request->aeskey));

            // create new file model instance and save
            $file = New File([
                'user_id'=>auth()->user()->id, 
                'name'=>$file_name, 
                'file_path'=>$file_path,
                'aes_key'=>$aesEncKey,
            ]);
            $file->save();
            return redirect()->route('files.index')->with('status', 'Encrypted Upload was successful');
        } catch(Exception $e) {
            return redirect()->route('files.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        Storage::delete($file->file_path);
        $fileName = $file->get_fullname();
        $file->delete();
        return redirect()->route('files.index')->with('status', $fileName.' has been successfully deleted.');
    }

    public function generate_aes_key()
    {
        session()->flash('error', 'Important: copy and save generated key in a safe place!');
        return response()->json([
            'view'=> view('ajax.notification')->render(),
            'aesKey'=>File::generateAESKey()
        ]);
    }

    public function decrypt_file(Request $request, File $file)
    {
        // custom validation
        $validator = Validator::make($request->all(), 
            ['rsa_pub_file' => ['required', new CustomFileRule(['pem']), 'max:1024'],],
            [
                'rsa_pub_file.required' => 'RSA file is required',
                'rsa_pub_file.required' => 'RSA file size must be less than 1MB',
            ]
        );

        // redirect if validation fails
        if($validator->fails()) {
            return redirect()->back()->with('file_id', $file->id)->withErrors($validator)->withInput();
        }
        
        try {
            // load rsa private key from uploaded rsa file
            $publicKey = PublicKey::fromFile($_FILES['rsa_pub_file']['tmp_name']);
    
            // decrypt aes key with rsa private key
            $aesEncKey = $publicKey->decrypt(base64_decode($file->aes_key));
            
            // create custom folder for storing decrypted files if it does't exit
            Storage::makeDirectory('decrypts/'.auth()->user()->name.'_'.auth()->user()->id);
    
            // created decryted file path
            $decrypt_file_path = substr_replace($file->file_path, 'decrypts', strpos($file->file_path, 'uploads'), strlen('uploads'));
    
            // encode aes key, decrypt file and store in decryted file path
            Storage::put($decrypt_file_path, File::aes_decrypt(file_get_contents('storage/'.$file->file_path), $aesEncKey));

            // redirect back with decrypted file link
            return redirect()->back()->with(['status'=>'File Decrypted successfully.', 'decrypt_file_path'=>$decrypt_file_path, 'file_id'=>$file->id]);
        } catch(Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function delete_decrypted(File $file)
    {
        $decrypt_file_path = substr_replace($file->file_path, 'decrypts', strpos($file->file_path, 'uploads'), strlen('uploads'));
        Storage::delete($decrypt_file_path);
        return redirect()->route('files.index')->with('status', 'The decrypted file has been successfully deleted from our servers.');
    }
}
