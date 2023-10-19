@php
    $icon = 'heroicon-o-x-mark';
    $true = 'heroicon-o-check-circle';
    $false = 'heroicon-o-x-circle';
    $iconEdit = 'heroicon-o-pencil-square';
@endphp
<div
    class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10"
>
    <div
        class="fi-ta-header flex flex-col justify-start gap-3 p-4 sm:px-6,
        sm:flex-row sm:items-center sm:justify-between"
    >
        <div class="grid gap-y-1">
            <h3
                class="fi-ta-header-heading text-base font-semibold leading-6"
            >
                {{ $label ?? null }}
            </h3>
        </div>
    </div>
    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
        <thead class="bg-gray-50 dark:bg-white/5 text-left">
            <tr
                class="fi-ta-row transition duration-75, hover:bg-gray-50 dark:hover:bg-white/5"
            >
                <th class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                    <span class="text-sm font-semibold text-gray-950 dark:text-white">Id</span>
                </th>
                <th class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                    <span class="text-sm font-semibold text-gray-950 dark:text-white">Title origin</span>
                </th>
                <th class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                    <span class="text-sm font-semibold text-gray-950 dark:text-white">Image</span>
                </th>
                <th class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                    <span class="text-sm font-semibold text-gray-950 dark:text-white">Title extra</span>
                </th>
                <th class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                    <span class="text-sm font-semibold text-gray-950 dark:text-white">Explain</span>
                </th>
                <th class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                    <span class="text-sm font-semibold text-gray-950 dark:text-white">Is active</span>
                </th>
                <th class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                    <span class="text-sm font-semibold text-gray-950 dark:text-white"></span>
                </th>
            </tr>
        </thead>
        <tbody class="text-left divide-y divide-gray-200 whitespace-normal dark:divide-white/5">
        @isset($questions)
            @if(count($questions) > 0)
                @foreach($questions as $question)
                    <tr
                        class="fi-ta-row transition duration-75, hover:bg-gray-50 dark:hover:bg-white/5"
                    >
                        <td
                            class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                        >
                            <span
                                class="text-xs fi-ta-text-item inline-flex items-center gap-1.5 text-sm text-gray-950 dark:text-white"
                            >
                                {{ $question->id }}
                            </span>
                        </td>
                        <td
                            class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                        >
                            <span
                                class="text-xs fi-ta-text-item inline-flex items-center gap-1.5 text-sm text-gray-950 dark:text-white"
                            >
                                {{ $question->title_origin }}
                            </span>
                        </td>

                        <td
                            class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                        >
                        <span
                            class="text-xs fi-ta-text-item inline-flex items-center gap-1.5 text-sm text-gray-950 dark:text-white"
                        >
                            <div
                                class="fi-ta-image, px-3 py-4"
                            >
                                <div class="flex items-center gap-x-2.5">
                                    <div class="flex-space-x-8 gap-x-1.5">
                                        <img
                                            class="max-w-none object-cover object-center"
                                            src="{{ $question->image ? asset('storage/'.$question->image) : asset('image/no-image-icon.png') }}" width="50" height="50"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                        </span>
                        </td>

                        <td
                            class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                        >
                            <span
                                class="text-xs fi-ta-text-item inline-flex items-center gap-1.5 text-sm text-gray-950 dark:text-white"
                            >
                                {{ $question->title_extra ?? '' }}
                            </span>
                        </td>

                        <td
                            class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                        >
                            <span
                                class="text-xs fi-ta-text-item inline-flex items-center gap-1.5 text-sm text-gray-950 dark:text-white"
                            >
                                @if($question->is_active == 0)
                                    <x-filament::icon
                                        :icon="$false"
                                        class="text-danger-400 dark:text-danger-500
                                        text-custom-500 dark:text-custom-400
                                        fi-ta-icon-item fi-ta-icon-item-size-lg h-6 w-6"
                                        style="--c-400:var(--danger-400);--c-500:var(--danger-500);"
                                    />
                                @else
                                    <x-filament::icon
                                        :icon="$true"
                                        class="text-success-500 dark:text-success-400
                                        text-custom-500 dark:text-custom-400
                                        fi-ta-icon-item fi-ta-icon-item-size-lg h-6 w-6"
                                        style="--c-400:var(--success-400);--c-500:var(--success-500);"
                                    />
                                @endif
                            </span>
                        </td>

                        <td
                            class="fi-ta-header-cell px-0.5 py-0.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6"
                        >
                        <span
                            class="text-sm font-bold fi-ta-text-item inline-flex items-center gap-1.5 text-sm text-gray-950 dark:text-white"
                        >
                            <x-filament::icon
                                :icon="$iconEdit"
                                class="text-success-500 dark:text-success-400
                                        text-custom-500 dark:text-custom-400
                                        fi-ta-icon-item fi-ta-icon-item-size-md h-5 w-5"
                                style="--c-300:var(--primary-300);--c-400:var(--primary-400);--c-500:var(--primary-500);
                                --c-600:var(--primary-600);"
                            />
                            <a href="exam/questions/{{ $question->id }}/edit"
                               class="custom-edit"
                               style="font-size: 13px; color: #fbbf24; text-underline: #fbbf24;"
                            >
                                Sửa
                            </a>
                        </span>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6">
                        <div
                            class="fi-ta-empty-state px-6 py-12"
                        >
                            <div
                                class="fi-ta-empty-state-content mx-auto grid max-w-lg justify-items-center text-center"
                            >
                                <div
                                    class="fi-ta-empty-state-icon-ctn mb-4 rounded-full bg-gray-100 p-3 dark:bg-gray-500/20"
                                >
                                    <x-filament::icon
                                        :icon="$icon"
                                        class="fi-ta-empty-state-icon h-6 w-6 text-gray-500 dark:text-gray-400"
                                    />
                                </div>

                                <h4
                                    class="fi-ta-empty-state-heading text-base font-semibold leading-6 text-gray-950 dark:text-white"
                                >
                                    Không có dữ liệu nào
                                </h4>
                            </div>
                        </div>
                    </td>
                </tr>
            @endif
        @endisset
        </tbody>
    </table>
</div>
