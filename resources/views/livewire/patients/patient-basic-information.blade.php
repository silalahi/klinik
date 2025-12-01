<div>
    <div class="overflow-hidden ring-1 ring-zinc-200 dark:ring-zinc-700 sm:rounded-lg bg-white dark:bg-zinc-800">
        <div class="bg-zinc-100 px-3 py-3 sm:px-6 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
            <div>
                <flux:heading size="md" class="font-medium uppercase tracking-wider text-zinc-500">
                    {{ __('Basic Information') }}
                </flux:heading>
            </div>
            <flux:modal.trigger name="edit-patient-basic-information">
                <flux:button size="sm" variant="primary" color="indigo">
                    {{ __('Edit') }}
                </flux:button>
            </flux:modal.trigger>
        </div>
        <div class="border-t border-zinc-100 dark:border-zinc-700">
            <dl class="divide-y divide-zinc-100 dark:divide-zinc-700">
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Patient Name') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">{{ $patient->name }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Med. Record No.') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">{{ $patient->medical_record_number }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('ID Number (KTP/SIM)') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">{{ $patient->id_number ?? '-' }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Place, Date of Birth') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">
                        {{ $patient->place_of_birth ? $patient->place_of_birth . ', ' : '' }}{{ $patient->date_of_birth?->format('d F Y') }}
                        @if($patient->date_of_birth)
                            <span class="text-zinc-500 dark:text-zinc-400">({{ $patient->date_of_birth->age }} {{ __('years old') }})</span>
                        @endif
                    </dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Gender') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">{{ $patient->gender?->label() ?? '-' }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Marital Status') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">{{ $patient->marital_status?->label() ?? '-' }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Religion') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">{{ $patient->religion?->label() ?? '-' }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Blood Type') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">{{ $patient->blood_type ?? '-' }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Occupation') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">{{ $patient->occupation ?? '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <flux:modal name="edit-patient-basic-information" variant="flyout" class="w-full md:w-1/3">
        <form wire:submit="save" class="space-y-6">
            <div class="border-b border-zinc-200 pb-5">
                <flux:heading size="xl">{{ __('Edit Patient Basic Information') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Complete the patient information below.') }}</flux:text>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <flux:field>
                    <flux:label class="required">{{ __('Name') }}</flux:label>
                    <flux:input wire:model="name"/>
                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label class="required">{{ __('Medical Record Number') }}</flux:label>
                    <flux:input wire:model="medical_record_number" disabled/>
                    <flux:error name="medical_record_number" />
                </flux:field>

                <flux:field>
                    <flux:label class="required">{{ __('ID Number (KTP/SIM)') }}</flux:label>
                    <flux:input wire:model="id_number"/>
                    <flux:error name="id_number" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Place of Birth') }}</flux:label>
                    <flux:input wire:model="place_of_birth"/>
                    <flux:error name="place_of_birth" />
                </flux:field>

                <flux:field>
                    <flux:label class="required">{{ __('Date of birth') }}</flux:label>
                    <flux:date-picker wire:model="date_of_birth">
                        <x-slot name="trigger">
                            <flux:date-picker.input />
                        </x-slot>
                    </flux:date-picker>
                    <flux:error name="date_of_birth" />
                </flux:field>

                <flux:field>
                    <flux:label class="required">{{ __('Gender') }}</flux:label>
                    <flux:radio.group wire:model="gender" variant="segmented">
                        @foreach (App\Enums\Gender::cases() as $option)
                            <flux:radio value="{{ $option->value }}" label="{{ $option->label() }}"/>
                        @endforeach
                    </flux:radio.group>
                    <flux:error name="gender" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Marital Status') }}</flux:label>
                    <flux:select wire:model="marital_status" variant="listbox" placeholder="{{ __('Choose marital status...') }}">
                        @foreach (App\Enums\MaritalStatus::cases() as $option)
                            <flux:select.option value="{{ $option->value }}">{{ $option->label() }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="marital_status" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Religion') }}</flux:label>
                    <flux:select wire:model="religion" variant="listbox" placeholder="{{ __('Choose religion...') }}">
                        @foreach (App\Enums\Religion::cases() as $option)
                            <flux:select.option value="{{ $option->value }}">{{ $option->label() }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="marital_status" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Blood Type') }}</flux:label>
                    <flux:input wire:model="blood_type" />
                    <flux:error name="blood_type" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Occupation') }}</flux:label>
                    <flux:input wire:model="occupation" />
                    <flux:error name="occupation" />
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
        </form>
    </flux:modal>
</div>


