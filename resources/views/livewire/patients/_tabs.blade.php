@php
    $tabs = [
        [
            'name' => __('Patient Information'),
            'route' => 'patients.information',
            'params' => ['patient' => $patient->id],
            'icon' => '<path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />',
        ],
        // Add more tabs here as needed
        // [
        //     'name' => __('Appointments'),
        //     'route' => 'patients.appointments',
        //     'params' => ['patient' => $patient->id],
        //     'icon' => '<path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />',
        // ],
    ];
@endphp

<div wire:ignore>
    {{-- Mobile select --}}
    <div class="grid grid-cols-1 sm:hidden">
        <select
            aria-label="Select a tab"
            onchange="window.location.href = this.value"
            class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white dark:bg-zinc-800 py-2 pr-8 pl-3 text-base text-gray-900 dark:text-zinc-100 outline-1 -outline-offset-1 outline-gray-300 dark:outline-zinc-700 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600"
        >
            @foreach($tabs as $tab)
                <option
                    value="{{ route($tab['route'], $tab['params']) }}"
                    @if(request()->routeIs($tab['route'])) selected @endif
                >
                    {{ $tab['name'] }}
                </option>
            @endforeach
        </select>
        <svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end fill-gray-500 dark:fill-zinc-400">
            <path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
        </svg>
    </div>

    {{-- Desktop tabs --}}
    <div class="hidden sm:block">
        <div class="border-b border-gray-200 dark:border-zinc-700">
            <nav aria-label="Tabs" class="-mb-px flex space-x-8">
                @foreach($tabs as $tab)
                    @php
                        $isActive = request()->routeIs($tab['route']);
                    @endphp
                    <a
                        href="{{ route($tab['route'], $tab['params']) }}"
                        wire:navigate
                        class="group inline-flex items-center border-b-2 px-1 py-4 text-sm font-medium transition-colors
                            @if($isActive)
                                border-indigo-500 text-indigo-600 dark:text-indigo-400
                            @else
                                border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-zinc-400 dark:hover:text-zinc-300 dark:hover:border-zinc-600
                            @endif
                        "
                    >
                        <svg
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            data-slot="icon"
                            aria-hidden="true"
                            class="mr-2 -ml-0.5 size-5 transition-colors
                                @if($isActive)
                                    text-indigo-500 dark:text-indigo-400
                                @else
                                    text-gray-400 group-hover:text-gray-500 dark:text-zinc-500 dark:group-hover:text-zinc-400
                                @endif
                            "
                        >
                            {!! $tab['icon'] !!}
                        </svg>
                        <span>{{ $tab['name'] }}</span>
                    </a>
                @endforeach
            </nav>
        </div>
    </div>
</div>
