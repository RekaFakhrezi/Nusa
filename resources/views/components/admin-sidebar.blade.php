<!-- Admin Sidebar Component -->
@php
    $currentRoute = request()->route()->getName() ?? '';
@endphp

<div class="flex min-h-[calc(100vh-4rem)]">
    <!-- Sidebar -->
    <aside class="w-64 glass-card-strong border-r border-border-light flex-shrink-0 hidden lg:block">
        <div class="sticky top-16 p-6 space-y-2">
            <h3 class="text-xs font-black text-ink-muted uppercase tracking-widest mb-4">Panel Admin</h3>

            <a href="{{ route('admin.dashboard') }}" 
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ $currentRoute === 'admin.dashboard' ? 'bg-ink text-surface' : 'text-ink-light hover:text-ink hover:bg-surface' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Overview
            </a>

            <a href="{{ route('admin.verifikasi') }}" 
            <a href="{{ route('admin.verifikasi') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ $currentRoute === 'admin.verifikasi' ? 'bg-ink text-surface' : 'text-ink-light hover:text-ink hover:bg-surface' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Verifikasi
                @php $pendingCount = \App\Models\Article::where('status', 'pending')->count(); @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.published') }}" 
            <a href="{{ route('admin.published') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ $currentRoute === 'admin.published' ? 'bg-ink text-surface' : 'text-ink-light hover:text-ink hover:bg-surface' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                Berita Tayang
            </a>

            <a href="{{ route('admin.unpublished') }}" 
            <a href="{{ route('admin.unpublished') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ $currentRoute === 'admin.unpublished' ? 'bg-ink text-surface' : 'text-ink-light hover:text-ink hover:bg-surface' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                Diturunkan
            </a>

            <a href="{{ route('admin.trash') }}" 
            <a href="{{ route('admin.trash') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ $currentRoute === 'admin.trash' ? 'bg-ink text-surface' : 'text-ink-light hover:text-ink hover:bg-surface' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Trash
            </a>

            <div class="h-px bg-border-light my-2"></div>

            <a href="{{ route('admin.categories') }}" 
            <a href="{{ route('admin.categories') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition-all {{ $currentRoute === 'admin.categories' ? 'bg-ink text-surface' : 'text-ink-light hover:text-ink hover:bg-surface' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Kategori
            </a>

            <div class="border-t border-border-light pt-4 mt-4">
                <a href="{{ route('home') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-ink-muted hover:text-ink hover:bg-surface transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 p-6 lg:p-8 max-w-full overflow-x-hidden">
        {{ $slot }}
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        var scrollpos = sessionStorage.getItem('scrollpos_' + window.location.pathname);
        if (scrollpos) {
            window.scrollTo(0, scrollpos);
            sessionStorage.removeItem('scrollpos_' + window.location.pathname);
        }

        // Clear scroll position for sidebar links to allow fresh navigation
        document.querySelectorAll('aside a').forEach(link => {
            link.addEventListener('click', () => {
                const url = new URL(link.href, window.location.origin);
                sessionStorage.removeItem('scrollpos_' + url.pathname);
            });
        });
    });

    window.addEventListener('beforeunload', function(e) {
        sessionStorage.setItem('scrollpos_' + window.location.pathname, window.scrollY);
    });
</script>
