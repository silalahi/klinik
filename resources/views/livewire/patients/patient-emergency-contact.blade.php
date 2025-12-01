<div>
    <div class="overflow-hidden ring-1 ring-zinc-200 dark:ring-zinc-700 sm:rounded-lg bg-white dark:bg-zinc-800">
        <div class="bg-zinc-100 px-3 py-3 sm:px-6 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
            <div>
                <flux:heading size="md" class="font-medium uppercase tracking-wider text-zinc-500">
                    {{ __('Emergency Contact') }}
                </flux:heading>
            </div>
            <flux:modal.trigger name="edit-patient-emergency-contact">
                <flux:button size="sm" variant="primary" color="indigo">
                    {{ __('Edit') }}
                </flux:button>
            </flux:modal.trigger>
        </div>
        <div class="border-t border-zinc-100 dark:border-zinc-700">
            <dl class="divide-y divide-zinc-100 dark:divide-zinc-700">
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Name') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">{{ $patient->emergency_contact_name ?? '-' }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Phone Number') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">
                        @if($patient->emergency_contact_phone)
                            <a href="tel:{{ $patient->emergency_contact_phone }}" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">{{ $patient->emergency_contact_phone }}</a>
                        @else
                            -
                        @endif
                    </dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Relationship') }}</dt>
                    <dd class="mt-1 text-sm text-zinc-700 dark:text-zinc-300 sm:col-span-2 sm:mt-0">{{ $patient->emergency_contact_relationship ?? '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <flux:modal name="edit-patient-emergency-contact" variant="flyout" class="w-full md:w-1/3">
        <form wire:submit="save" class="space-y-6">
            <div class="border-b border-zinc-200 pb-5">
                <flux:heading size="xl">{{ __('Edit Patient Contact Information') }}</flux:heading>
                <flux:text class="mt-2">{{ __('Complete the patient information below.') }}</flux:text>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <flux:field>
                    <flux:label class="required">{{ __('Emergency Contact Name') }}</flux:label>
                    <flux:input wire:model="emergency_contact_name"/>
                    <flux:error name="emergency_contact_name" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Emergency Contact Phone') }}</flux:label>
                    <flux:input wire:model="emergency_contact_phone"/>
                    <flux:error name="emergency_contact_phone" />
                </flux:field>

                <flux:field>
                    <flux:label>{{ __('Emergency Contact Relationship') }}</flux:label>
                    <flux:input wire:model="emergency_contact_relationship"/>
                    <flux:error name="emergency_contact_relationship" />
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
