{{-- =========================================================
     Footer — resources/views/components/footer.blade.php
     ========================================================= --}}

<footer class="flex-shrink-0 bg-white border-t border-gray-200 px-6 py-3">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-1">

        {{-- Kiri: Copyright --}}
        <p class="text-sm text-gray-500">
            &copy; {{ date('Y') }}
            <span class="font-medium text-gray-600">Sistem Hafalan Qur'an</span>.
            All rights reserved.
        </p>

        {{-- Kanan: Versi & Framework --}}
        <div class="flex items-center gap-3 text-xs text-gray-400">
            <span class="inline-flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                v1.0.0
            </span>
            <span>|</span>
            <span>Laravel {{ app()->version() }}</span>
            <span>|</span>
            <span>PHP {{ PHP_MAJOR_VERSION }}.{{ PHP_MINOR_VERSION }}</span>
        </div>

    </div>
</footer>
