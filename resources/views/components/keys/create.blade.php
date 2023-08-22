<form action="{{ route('keys.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <x-input-label class="mb-1" for="name" :value="__('Enter a name for your Key')" />
    <div class="flex flex-row items-center space-x-3">

        <!-- Upload Excel File -->
        <div>
            <x-text-input id="rsa-name" type="text" name="name" class="form-control block m-0 p-0 pl-2 w-72 h-8" required autofocus />
        </div>

        <!-- Submit Button -->
        <div>
            <x-primary-button id="generate-keys-btn" class="">
                {{ __('Generate keys') }}
            </x-primary-button>
        </div>
    </div>
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</form>

    <x-input-label class="mb-1 mt-4" for="rsapubkey" :value="__('Download RSA public key')" />
    <span id="aeskey-link" class="hidden">{{ route('file.keygen.aes') }}</span>
    <div class="flex flex-row items-center space-x-3">

        <!-- Upload Excel File -->
        <div>
            <x-text-input id="rsapubkey" type="text" name="rsapubkey" class="form-control block m-0 p-0 w-72 h-8" required readonly />
        </div>

        <!-- Submit Button -->
        <div>
            <a id="publink">
                <x-primary-button id="pub-key-download">
                    {{ __('Download') }}
                </x-primary-button>
            </a>
        </div>
    </div>
    <x-input-error :messages="$errors->get('rsapubkey')" class="mt-2" />

    <x-input-label class="mb-1 mt-4" for="rsaprikey" :value="__('Download RSA private key')" />
    <span id="aeskey-link" class="hidden">{{ route('file.keygen.aes') }}</span>
    <div class="flex flex-row items-center space-x-3">

        <!-- Upload Excel File -->
        <div>
            <x-text-input id="rsaprikey" type="text" name="rsaprikey" class="form-control block m-0 p-0 w-72 h-8" required readonly />
        </div>

        <!-- Submit Button -->
        <div>
            <a id="prilink">
                <x-primary-button id="pub-key-download">
                    {{ __('Download') }}
                </x-primary-button>
            </a>
        </div>
    </div>
    <x-input-error :messages="$errors->get('rsaprikey')" class="mt-2" />

