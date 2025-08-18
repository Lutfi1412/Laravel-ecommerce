<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="relative flex-1 py-5 px-6 max-w-md">
        <input type="text" id="search-box"
            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                   focus:ring-blue-500 focus:border-blue-500 pl-10 p-2.5"
            placeholder="Cari..." required>
        <div class="absolute inset-y-0 left-10 flex items-center pointer-events-none">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35M9.5 17a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z"></path>
            </svg>
        </div>
    </div>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            {{ $head }}
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>

</div>
