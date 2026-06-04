    <div class="max-w-7xl mx-auto mt-4 mb-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="text-green-600 hover:text-green-800 font-bold ml-4">
                    &times;
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded flex justify-between items-center">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="text-red-600 hover:text-red-800 font-bold ml-4">
                    &times;
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-4 py-3 rounded flex justify-between items-center">
                <span>{{ session('warning') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" 
                        class="text-yellow-600 hover:text-yellow-800 font-bold ml-4">
                    &times;
                </button>
            </div>
        @endif
    </div>