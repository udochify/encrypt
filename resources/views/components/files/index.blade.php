@aware(['files'])

<p class="text-[1em] mb-3">All Uploaded Files</p>
<div class="p-4 grid gap-x-5 gap-y-5 items-center grid-cols-[repeat(5,max-content)]">
    <p class="font-bold text-[1em]">s/no</p>
    <p class="font-bold text-[1em]">Name</p>
    <p class="font-bold text-[1em]">Size</p>
    <p class="font-bold text-[1em]"></p>
    <p class="font-bold text-[1em]"></p>
    @foreach ($files as $file)
    <div class="col-span-5 w-full h-[1px] bg-gray-400"></div>
    <p class="text-[1em]">{{ $file->id }}</p>
    <p title="{{ $file->get_fullname() }}" class="text-[1em]">{{ $file->get_name(20) }}</p>
    <p class="text-[1em]">{{ $file->getFileSize() }}</p>
    <div>
        <form action="{{ route('files.delete', $file->id) }}" method="POST">
            @csrf
            @method('delete')
            <x-primary-button>
                {{ __('Delete') }}
            </x-primary-button>
        </form>
    </div>
    <div>
        <a href="{{ asset('storage/'.$file->file_path) }}" download>
            <x-primary-button>
                {{ __('Download') }}
            </x-primary-button>
        </a>
    </div>
    <p class="text-[1em]"></p>
    @if((session('decrypt_file_path') ?? false) && session('file_id', '') == $file->id)
        <div class="col-span-2">
            <form action="{{ route('files.delete.decrypted', $file->id) }}" method="POST">
                @csrf
                @method('delete')
                <x-primary-button>
                    {{ __('Delete Decrypted File') }}
                </x-primary-button>
            </form>
        </div>
        <div class="col-span-2">
            <a href="{{ asset('storage/'.session('decrypt_file_path')) }}" download>
                <x-primary-button>
                    {{ __('Download Decrypted File') }}
                </x-primary-button>
            </a>
        </div>
    @else
        <div class="col-span-3 relative">
            @if(session('file_id', '') == $file->id) 
                <x-input-error :messages="$errors->get('rsa_pub_file')" class="m-0 mt-[-20px] mr-2 p-0 absolute bg-white" />
            @else
                <x-input-label class="m-0 mt-[-20px] mr-2 p-0 absolute bg-white" for="aeskey" :value="__('select rsa public key')" />
            @endif
            <x-input-file form="decrypt-form-{{ $file->id }}" id="rsa-pub-file" type="file" name="rsa_pub_file" class="form-control block m-0 p-0 w-72" accept=".pem" required autofocus />
        </div>
        <form id="decrypt-form-{{ $file->id }}" action="{{ route('files.decrypt', $file->id) }}" enctype="multipart/form-data" method="POST">
            @csrf
            <x-primary-button>
                {{ __('Decrypt') }}
            </x-primary-button>
        </form>
    @endif
    @endforeach
</div>