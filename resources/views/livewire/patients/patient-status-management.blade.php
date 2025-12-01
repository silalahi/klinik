<div>
    <div class="overflow-hidden ring-1 ring-zinc-200 dark:ring-zinc-700 sm:rounded-lg bg-white dark:bg-zinc-800">
        <div class="bg-zinc-100 px-3 py-3 sm:px-6 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
            <div>
                <flux:heading size="md" class="font-medium uppercase tracking-wider text-zinc-500">
                    {{ __('Status Management') }}
                </flux:heading>
            </div>
            @if($patient->status !== \App\Enums\PatientStatus::Deceased)
                <flux:modal.trigger name="edit-patient-status">
                    <flux:button size="sm" variant="primary" color="indigo">
                        {{ __('Change Status') }}
                    </flux:button>
                </flux:modal.trigger>
            @endif
        </div>

        <div class="border-t border-zinc-100 dark:border-zinc-700">
            {{-- Current Status --}}
            <div class="px-4 py-4 sm:px-6">
                <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-2">{{ __('Current Status') }}</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center gap-2 rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset
                        @if($patient->status === \App\Enums\PatientStatus::Active) bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20
                        @elseif($patient->status === \App\Enums\PatientStatus::Inactive) bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-400/10 dark:text-amber-500 dark:ring-amber-400/20
                        @else bg-zinc-50 text-zinc-700 ring-zinc-600/20 dark:bg-zinc-400/10 dark:text-zinc-500 dark:ring-zinc-400/20
                        @endif">
                        @if($patient->status === \App\Enums\PatientStatus::Active)
                            <flux:icon.check-circle variant="micro"/>
                        @elseif($patient->status === \App\Enums\PatientStatus::Inactive)
                            <flux:icon.minus-circle variant="micro"/>
                        @else
                            <flux:icon.x-circle variant="micro"/>
                        @endif
                        {{ $patient->status->label() }}
                    </span>

                    @if($patient->status === \App\Enums\PatientStatus::Deceased)
                        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                            {{ __('Status of deceased patients cannot be changed.') }}
                        </p>
                    @endif
                </dd>
            </div>

            {{-- Status History --}}
            @if($this->statusHistory->isNotEmpty())
                <div class="border-t border-zinc-100 dark:border-zinc-700 px-4 py-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-3">{{ __('Status History') }}</dt>
                    <dd>
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach($this->statusHistory as $index => $change)
                                    <li>
                                        <div class="relative pb-8">
                                            @if($index < $this->statusHistory->count() - 1)
                                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-zinc-200 dark:bg-zinc-700" aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-400 dark:bg-zinc-600 ring-8 ring-white dark:ring-zinc-800">
                                                        <flux:icon.arrow-path variant="micro" class="h-5 w-5 text-white"/>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $change['user'] }}</span>
                                                            {{ __('changed status from') }}
                                                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $change['old_status'] }}</span>
                                                            {{ __('to') }}
                                                            <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $change['new_status'] }}</span>
                                                        </p>
                                                        @if($change['reason'])
                                                            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                                                                {{ $change['reason'] }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-zinc-500 dark:text-zinc-400">
                                                        <time datetime="{{ $change['created_at']->toIso8601String() }}">
                                                            {{ $change['created_at']->diffForHumans() }}
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </dd>
                </div>
            @endif
        </div>
    </div>

    {{-- Status Change Modal --}}
    <flux:modal name="edit-patient-status" variant="flyout" class="w-full md:w-1/3">
        <form wire:submit="save" class="space-y-6">
            <div class="border-b border-zinc-200 pb-5">
                <flux:heading size="xl">{{ __('Change Patient Status') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Update the patient status and optionally provide a reason.') }}</flux:text>
            </div>

            @if($patient->status === \App\Enums\PatientStatus::Deceased)
                <flux:callout variant="warning">
                    {{ __('Status of deceased patients cannot be changed.') }}
                </flux:callout>
            @else
                <div class="grid grid-cols-1 gap-6">
                    <flux:field>
                        <flux:label>{{ __('New Status') }}</flux:label>
                        <flux:select wire:model="new_status" variant="listbox">
                            @foreach($this->availableStatuses as $status)
                                <flux:select.option value="{{ $status['value'] }}">{{ $status['label'] }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="new_status" />
                    </flux:field>

                    <flux:field>
                        <flux:label>{{ __('Reason (Optional)') }}</flux:label>
                        <flux:textarea
                            wire:model="reason"
                            placeholder="{{ __('Optional: Provide reason for status change...') }}"
                            rows="4"
                        />
                        <flux:description>
                            {{ __('Optional: Document why this status change is being made.') }}
                        </flux:description>
                        <flux:error name="reason" />
                    </flux:field>
                </div>

                <div class="flex justify-end gap-3 border-t border-zinc-200 pt-5">
                    <flux:modal.close>
                        <flux:button variant="ghost">
                            {{ __('Cancel') }}
                        </flux:button>
                    </flux:modal.close>
                    <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">{{ __('Save changes') }}</span>
                        <span wire:loading wire:target="save">{{ __('Saving...') }}</span>
                    </flux:button>
                </div>
            @endif
        </form>
    </flux:modal>
</div>