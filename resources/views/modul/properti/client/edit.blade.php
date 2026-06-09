<x-app-layout>
    <div class="min-h-screen bg-gray-50 pb-12">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <h1 class="mt-8 mb-8 text-[32px] font-poppins font-bold text-gray-800">Edit Project</h1>

            <div class="bg-white p-8 rounded-[40px] shadow-[0_20px_40px_rgba(0,0,0,0.04)]">
                <form method="POST" action="{{ route('projects.update', $project->id) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Nama Project</label>
                        <input type="text" name="nama_project" required value="{{ old('nama_project', $project->nama_project) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Contract Date</label>
                        <input type="date" name="contract_date" required value="{{ old('contract_date', $project->contract_date) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Contact Person</label>
                        <input type="text" name="contact_person" required value="{{ old('contact_person', $project->contact_person) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Kategori Project</label>
                        <select name="kategori" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            <option value="" disabled>Pilih Kategori Project</option>
                            <option value="Contoh Kategori Project 1" {{ old('kategori', $project->kategori) == 'Contoh Kategori Project 1' ? 'selected' : '' }}>Contoh Kategori Project 1</option>
                            <option value="Contoh Kategori Project 2" {{ old('kategori', $project->kategori) == 'Contoh Kategori Project 2' ? 'selected' : '' }}>Contoh Kategori Project 2</option>
                            <option value="Contoh Kategori Project 3" {{ old('kategori', $project->kategori) == 'Contoh Kategori Project 3' ? 'selected' : '' }}>Contoh Kategori Project 3</option>
                            <option value="Lainnya" {{ old('kategori', $project->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('deskripsi', $project->deskripsi) }}</textarea>
                    </div>

                    <div class="pt-4 text-right">
                        <x-primary-button>Update Project</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
