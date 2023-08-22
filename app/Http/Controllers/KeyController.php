<?php

namespace App\Http\Controllers;

use App\Models\Key;
use App\Http\Requests\StoreKeyRequest;
use App\Http\Requests\UpdateKeyRequest;
use Exception;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class KeyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keys = Key::all();
        if (count($keys) < 1) session()->flash('status', 'No RSA keys generated yet!');
        return view('keys', ['keys'=>$keys]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('keys');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKeyRequest $request)
    {
        $userId = auth()->user()->id;
        $userName = auth()->user()->name;
        $keys = Key::where(['user_id'=>$userId,'name'=>$request->name])->get();
        if(count($keys) > 0) {
            session()->flash('error', 'Name already used. Enter new name.');
            return response()->json(['view'=> view('ajax.notification')->render()]);
        }
        try {
            Storage::makeDirectory('keys/'.$userName.'_'.$userId);
            $pathToPrivateKey = 'storage/keys/'.$userName.'_'.$userId.'/'.$request->name.'_rsa_pri.pem';
            $pathToPublicKey = 'storage/keys/'.$userName.'_'.$userId.'/'.$request->name.'_rsa_pub.pem';
            [$privateKey, $publicKey] = Key::generateRSAKeys($pathToPrivateKey, $pathToPublicKey);
            $key = New Key(['user_id'=>$userId, 'name'=>$request->name, 'rsa_pri_key'=>hash('sha3-256', $privateKey)]);
            $key->save();
            session()->flash('status', 'New RSA key pairs generated successfully. Download and save.');
            return response()->json([
                'view'=> view('ajax.notification')->render(),
                'pri_url'=>asset($pathToPrivateKey),
                'pub_url'=>asset($pathToPublicKey),
                'pri_name'=>$request->name.'_rsa_pri.pem',
                'pub_name'=>$request->name.'_rsa_bub.pem'
            ]);
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
            return response()->json(['view'=> view('ajax.notification')->render()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Key $key)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Key $key)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKeyRequest $request, Key $key)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Key $key)
    {
        //
    }
}
