<div class="overflow-x-auto border-r border-gray-200 dark:border-gray-800 sm:col-span-1">
    <div class="text-xs text-gray-500 dark:text-gray-400">
        <div class="mb-2 sm:col-span-1">
            <button
                @click="includeVendorFrames = !includeVendorFrames"
                class="inline-flex items-center rounded-md bg-red-100 px-3 py-1.5 text-xs font-medium leading-4 text-red-800 dark:bg-red-900 dark:text-red-100"
            >
                <span x-show="includeVendorFrames">Collapse</span>
                <span x-cloak x-show="!includeVendorFrames">Expand</span>
                <span class="ml-1">vendor frames</span>
                <!-- icons -->
                <svg
                    x-show="includeVendorFrames"
                    x-cloak
                    xmlns="http://www.w3.org/2000/svg"
                    class="ml-1 h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                <svg
                    x-cloak
                    x-show="!includeVendorFrames"
                    xmlns="http://www.w3.org/2000/svg"
                    class="ml-1 h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
            </button>
        </div>

        <div class="space-y-2">
            @foreach ($exception->frames() as $frame)
                @php
                    /** @var \Illuminate\Foundation\Exceptions\Renderer\Frame $frame */
                @endphp

                @if (! $frame->isFromVendor())
                    @php
                        $vendorFramesCollapsed = $exception->frames()->take($loop->index)->reverse()->takeUntil(fn ($frame) => ! $frame->isFromVendor());
                    @endphp

                    <div x-show="! includeVendorFrames">
                        @if ($vendorFramesCollapsed->isNotEmpty())
                            <div>
                                {{ $vendorFramesCollapsed->count() }} vendor frame{{ $vendorFramesCollapsed->count() > 1 ? 's' : '' }} collapsed
                            </div>
                        @endif
                    </div>
                @endif

                <button
                    class="w-full border-b border-gray-200 text-left dark:border-gray-900"
                    x-show="{{ $frame->isFromVendor() ? 'includeVendorFrames' : 'true' }}"
                    @click="index = {{ $loop->index }}"
                >
                    <div
                        x-bind:class="
                            index === {{ $loop->index }}
                                ? 'bg-gray-100 dark:bg-gray-800 border-l-2 border-red-500 dark:border-red-300'
                                : ''
                        "
                    >
                        <div class="border-l-2 border-transparent pl-2">
                            <div class="align-middle text-gray-900 dark:text-gray-300">
                                <span>{{ $frame->source() }}</span>
                                <span class="italic text-gray-500 dark:text-gray-400">:{{ $frame->line() }}</span>
                            </div>
                            <div class="text-gray-500 dark:text-gray-400">
                                {{ $exception->frames()->get($loop->index + 1)?->callable() }}
                            </div>
                        </div>
                    </div>
                </button>

                @if (! $frame->isFromVendor() && $exception->frames()->slice($loop->index + 1)->filter(fn ($frame) => ! $frame->isFromVendor())->isEmpty())
                    <div x-show="! includeVendorFrames">
                        <div>
                            {{ $exception->frames()->slice($loop->index + 1)->count() }} vendor
                            frame{{ $exception->frames()->slice($loop->index + 1)->count() > 1 ? 's' : '' }} collapsed
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>