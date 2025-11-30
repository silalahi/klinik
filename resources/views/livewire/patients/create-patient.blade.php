<flux:modal name="create-patient-modal" variant="flyout" class="w-1/3">
    <form wire:submit="save" class="space-y-6">
        <div class="border-b border-zinc-200 pb-5">
            <flux:heading size="xl">{{ __('Add New Patient') }}</flux:heading>
            <flux:text class="mt-2">{{ __('Complete the patient information below.') }}</flux:text>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <flux:field>
                <flux:label>{{ __('Medical Record Number') }}</flux:label>
                <flux:input wire:model="medical_record_number" placeholder="{{ __('Leave empty to generated MR number') }}"/>
                <flux:error name="medical_record_number" />
            </flux:field>

            <flux:field>
                <flux:label class="required">{{ __('ID Number (KTP/SIM)') }}</flux:label>
                <flux:input wire:model="id_number"/>
                <flux:error name="id_number" />
            </flux:field>

            <flux:field>
                <flux:label class="required">{{ __('Name') }}</flux:label>
                <flux:input wire:model="name"/>
                <flux:error name="name" />
            </flux:field>

            <flux:field>
                <flux:label class="required">{{ __('Date of birth') }}</flux:label>
{{--                <flux:input wire:model="date_of_birth" type="date"/>--}}
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
                    @foreach ($genders as $option)
                        <flux:radio value="{{ $option->value }}" label="{{ $option->label() }}"/>
                    @endforeach
                </flux:radio.group>
                <flux:error name="gender" />
            </flux:field>


            <flux:field>
                <flux:label class="required">{{ __('Phone Number') }}</flux:label>
                <flux:input wire:model="phone" />
                <flux:error name="phone" />
            </flux:field>

            <flux:field>
                <flux:label class="required">{{ __('Full Address') }}</flux:label>
                <flux:textarea wire:model="address" rows="3" />
                <flux:error name="address" />
            </flux:field>
        </div>

        <div class="flex justify-end gap-3 border-t border-zinc-200 pt-5">
            <flux:button variant="ghost" type="button" href="{{ route('patients.index') }}" wire:navigate>
                {{ __('Cancel') }}
            </flux:button>
            <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">{{ __('Next') }} &rarr;</span>
                <span wire:loading wire:target="save">{{ __('Saving...') }}</span>
            </flux:button>
        </div>
    </form>
</flux:modal>
