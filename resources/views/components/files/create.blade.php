<form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <x-input-label class="mb-1" for="rsa-pri-file" :value="__('Select RSA private file (for encrypting aes key)')" />
    <div class="flex flex-row items-center space-x-3">

        <!-- Upload Excel File -->
        <div>
            <x-input-file id="rsa-pri-file" type="file" name="rsa_pri_file" class="form-control block m-0 p-0 w-72" accept=".pem" required autofocus />
        </div>
    </div>
    <x-input-error :messages="$errors->get('rsa_pri_file')" class="mt-2" />
    <x-input-label class="mb-1 mt-4" for="aeskey" :value="__('Generate New AES Key (for encrypting upload)')" />
    <span id="aeskey-link" class="hidden">{{ route('file.keygen.aes') }}</span>
    <div class="flex flex-row items-center space-x-3">

        <!-- Upload Excel File -->
        <div>
            <x-text-input id="aeskey" type="text" name="aeskey" class="form-control block m-0 p-0 w-72 h-8" required readonly />
        </div>

        <!-- Submit Button -->
        <div>
            <x-primary-button id="aes-key-gen">
                {{ __('Generate Key') }}
            </x-primary-button>
        </div>
    </div>
    <x-input-error :messages="$errors->get('aeskey')" class="mt-2" />
    <x-input-label class="mb-1 mt-4" for="file" :value="__('Select file to upload')" />
    <div class="flex flex-row items-center space-x-3">

        <!-- Upload Excel File -->
        <div>
            <x-input-file id="file" type="file" name="file" class="form-control block m-0 p-0 w-72" accept=".txt,.pdf,.csv,.xlx,.xls,.xlsx,.doc,.docx,.html,.css,.js,.jpg,.jpeg,.png,.gif,.mp4,.avi,.3gp,.webm,.wav,.ogg,.mp3" required />
        </div>

        <!-- Submit Button -->
        <div>
            <x-primary-button class="">
                {{ __('Encrypt & Upload') }}
            </x-primary-button>
        </div>
    </div>
    <x-input-error :messages="$errors->get('file')" class="mt-2" />
</form>

