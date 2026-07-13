<div id="{{ $id }}" class="fixed inset-0 z-50 hidden" role="dialog">
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="document.getElementById('{{ $id }}').classList.add('hidden')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full {{ $maxWidth ?? 'max-w-lg' }} max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                <button type="button" onclick="document.getElementById('{{ $id }}').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{ $slot }}
        </div>
    </div>
</div>