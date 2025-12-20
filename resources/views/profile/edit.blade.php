<x-app>
    <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">Mi Perfil</h1>

        <div class="bg-white dark:bg-gray-800 shadow-lg overflow-hidden sm:rounded-lg p-8 border-t-4 border-blue-600 dark:border-blue-500 transition-colors">
            
            {{-- Formulario Único --}}
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- FOTO DE PERFIL --}}
                <div class="mb-8 text-center">
                    <div class="relative inline-block group">
                        <div class="mt-2">
                            <img id="preview-image" 
                                 class="h-32 w-32 rounded-full object-cover mx-auto shadow-lg border-4 border-white dark:border-gray-700" 
                                 src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                 alt="{{ $user->name }}">
                        </div>
                        
                        <label class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full shadow-md cursor-pointer transition transform hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                            </svg>
                            <input type="file" name="photo" class="hidden" onchange="previewFile(this)" accept="image/*" />
                        </label>
                    </div>
                    @error('photo') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- DATOS PERSONALES --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Nombre Completo</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition">
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr class="my-8 border-gray-200 dark:border-gray-700">

                {{-- SEGURIDAD --}}
                <h3 class="font-bold text-lg mb-2 text-gray-800 dark:text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2.166 10.3c.769-2.985 3.836-5.549 8.179-5.549 4.344 0 7.41 2.564 8.179 5.549.082.318-.16.651-.488.651H2.654c-.328 0-.57-.333-.488-.651zM10.335 12a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" /><path d="M4 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z" /></svg>
                    Cambiar Contraseña
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Deja estos campos vacíos si no quieres cambiar tu contraseña actual.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Contraseña Actual</label>
                        <input type="password" name="current_password" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="••••••••">
                        @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Nueva Contraseña</label>
                        <input type="password" name="new_password" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="••••••••">
                        @error('new_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-2">Confirmar Nueva</label>
                        <input type="password" name="new_password_confirmation" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="••••••••">
                    </div>
                </div>

                {{-- BOTONES DE ACCIÓN --}}
                <div class="flex justify-end items-center gap-4 border-t dark:border-gray-700 pt-6">
                    
                    {{-- 1. BOTÓN CANCELAR (Gris, lleva al Dashboard) --}}
                    <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white font-bold py-3 px-6 rounded-lg hover:bg-gray-600 shadow-md transform transition hover:-translate-y-1 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        Cancelar
                    </a>

                    {{-- 2. BOTÓN GUARDAR (Azul, envía el formulario) --}}
                    <button type="submit" class="bg-blue-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-blue-700 shadow-lg transform transition hover:-translate-y-1 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script para previsualizar --}}
    <script>
        function previewFile(input) {
            var file = input.files[0];
            if(file){
                var reader = new FileReader();
                reader.onload = function(){
                    var output = document.getElementById('preview-image');
                    output.src = reader.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app>