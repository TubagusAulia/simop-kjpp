<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 mb-8 text-[32px] font-poppins font-bold text-gray-800">Edit Elemen Fisik</h1>

            <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                <form method="POST" action="#" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Nama Elemen</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Deskripsi</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="pt-4 text-right">
                        <x-primary-button>Simpan Perubahan</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
