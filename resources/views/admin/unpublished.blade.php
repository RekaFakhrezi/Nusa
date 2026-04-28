<x-app-layout>
    <x-admin-sidebar>

        <h2 class="text-2xl font-black text-ink mb-6">Berita Diturunkan</h2>

        <form action="{{ route('admin.bulkTrash') }}" method="POST" id="bulkTrashForm">
            @csrf
        </form>

        <div class="mb-6 flex items-center justify-between bg-surface-2 p-3 rounded-xl border border-border-light">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" id="selectAll" class="w-5 h-5 rounded border-border-light text-ink focus:ring-ink transition-all">
                <span class="text-sm font-black text-ink">Pilih Semua</span>
            </label>
            <button type="submit" form="bulkTrashForm" id="bulkActionBtn" class="hidden items-center gap-2 bg-red-50 hover:bg-red-500 text-red-600 hover:text-white px-4 py-2 rounded-xl text-xs uppercase tracking-wider font-black transition-colors border border-red-200 hover:border-red-500" onclick="return confirm('Pindahkan berita yang dipilih ke Trash?')">
                <svg class="w-4 h-4 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Trash <span id="selectedCount">0</span>
            </button>
        </div>

        @forelse($articles as $article)
            <div class="glass-card rounded-2xl p-5 mb-4 hover:shadow-card-hover transition-all relative">
                <div class="absolute top-5 right-5 z-10">
                    <input type="checkbox" name="ids[]" value="{{ $article->id }}" form="bulkTrashForm" class="article-checkbox w-5 h-5 rounded border-border-light bg-white text-ink focus:ring-ink transition-all shadow-sm">
                </div>
                <h3 class="text-lg font-bold text-ink mb-1 pr-8">{{ $article->title }}</h3>
                <p class="text-ink-muted text-xs mb-2">
                    {{ $article->user->name ?? 'Unknown' }} • Diturunkan: {{ $article->updated_at->format('d M Y H:i') }}
                </p>
                <p class="text-ink-light text-sm line-clamp-2 mb-3">{{ \Illuminate\Support\Str::limit(strip_tags($article->content), 150) }}</p>
                <div class="flex flex-wrap gap-2">
                    <form action="{{ route('admin.republish', $article->id) }}" method="POST" class="inline">
                        @csrf
                        <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-xl text-xs font-bold transition-colors" onclick="return confirm('Publish ulang?')">Publish Ulang</button>
                    </form>
                    <a href="{{ route('admin.edit', $article->id) }}" class="bg-surface hover:bg-surface-2 text-ink px-3 py-1.5 rounded-xl text-xs font-bold transition-colors border border-border-light">Edit</a>
                    <form action="{{ route('admin.destroy', $article->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-xl text-xs font-bold transition-colors" onclick="return confirm('Hapus?')">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="glass-card rounded-2xl p-12 text-center">
                <p class="text-ink-muted">Tidak ada berita yang diturunkan.</p>
            </div>
        @endforelse

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const selectAll = document.getElementById('selectAll');
                const checkboxes = document.querySelectorAll('.article-checkbox');
                const bulkActionBtn = document.getElementById('bulkActionBtn');
                const selectedCount = document.getElementById('selectedCount');

                function updateBulkButton() {
                    const checkedList = document.querySelectorAll('.article-checkbox:checked');
                    if (checkedList.length > 0) {
                        bulkActionBtn.classList.remove('hidden');
                        bulkActionBtn.classList.add('flex');
                        selectedCount.textContent = checkedList.length;
                    } else {
                        bulkActionBtn.classList.add('hidden');
                        bulkActionBtn.classList.remove('flex');
                    }
                }

                if(selectAll) {
                    selectAll.addEventListener('change', function() {
                        checkboxes.forEach(cb => cb.checked = this.checked);
                        updateBulkButton();
                    });
                }

                checkboxes.forEach(cb => {
                    cb.addEventListener('change', function() {
                        if (!this.checked) selectAll.checked = false;
                        if (document.querySelectorAll('.article-checkbox:checked').length === checkboxes.length) selectAll.checked = true;
                        updateBulkButton();
                    });
                });
            });
        </script>

    </x-admin-sidebar>
</x-app-layout>
