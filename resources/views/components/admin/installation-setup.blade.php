<div>
    <x-ui.page title="Installation Setup" subtitle="Configure your organization?s branding, welcome page, package, and available features.">
        @if($savedMessage)
            <div role="status" class="rounded-md border border-med-primary bg-white p-4 text-med-primary">{{ $savedMessage }}</div>
        @endif
        <form wire:submit="saveBranding" class="space-y-6">
            <x-ui.card title="System branding" subtitle="Branding changes are saved separately from package access.">
                @if($brandingMessage)<p role="status" class="mb-4 text-sm text-med-primary">{{ $brandingMessage }}</p>@endif
                <div class="grid gap-4 md:grid-cols-2">
                    <x-ui.input label="Brand name" name="brandName" wire:model.live.debounce.400ms="brandName" maxlength="120" required />
                    <x-ui.input label="Organization address" name="brandAddress" wire:model="brandAddress" maxlength="255" />
                    <div>
                        <x-ui.input type="file" label="Logo" name="logo" wire:model="logo" accept="image/png,image/jpeg,image/webp" />
                        <p class="mt-2 text-xs text-med-muted">PNG, JPG or WebP. Up to 2 MB and 2048 ? 2048 pixels.</p>
                        <p wire:loading wire:target="logo" class="text-sm text-med-muted">Uploading logo...</p>
                        <label class="mt-3 flex items-center gap-2 text-sm text-med-muted"><input type="checkbox" wire:model.live="removeLogo">Use default logo</label>
                    </div>
                    <x-ui.select label="Welcome page template" name="welcomeTemplate" wire:model.live="welcomeTemplate">
                        <option value="auto">Automatic ? match installation package</option>
                        @foreach($welcomeTemplates as $key => $option)<option value="{{ $key }}">{{ $option['label'] }}</option>@endforeach
                    </x-ui.select>
                    <x-ui.input label="Welcome heading (optional)" name="welcomeHeading" wire:model.live.debounce.400ms="welcomeHeading" maxlength="180" placeholder="Use the template heading" />
                    <x-ui.textarea label="Welcome statement (optional)" name="welcomeStatement" wire:model.live.debounce.400ms="welcomeStatement" rows="3" maxlength="1500" placeholder="Use the template welcome statement" />
                </div>
                <div class="mt-6 rounded-md border border-med-line p-6" style="background: {{ $welcomePreview['background'] }}; color: {{ $welcomePreview['accent'] }}">
                    <p class="mb-3 text-xs font-semibold uppercase">Welcome preview ? {{ $welcomePreview['label'] }}</p>
                    <img src="{{ $removeLogo ? asset('images/logo.png') : (($logo && !$errors->has('logo')) ? $logo->temporaryUrl() : $currentLogoUrl) }}" alt="Logo preview" style="width:64px;height:64px;object-fit:contain" class="mb-4">
                    <p class="text-sm font-semibold">{{ $brandName }}</p>
                    <h2 class="mt-2 text-2xl font-semibold">{{ $welcomeHeading ?: $welcomePreview['heading'] }}</h2>
                    <p class="mt-3 max-w-2xl whitespace-pre-line text-sm leading-6">{{ $welcomeStatement ?: $welcomePreview['statement'] }}</p>
                </div>
                <div class="mt-5 flex flex-wrap items-center gap-4">
                    <x-ui.button type="submit" wire:loading.attr="disabled" wire:target="saveBranding,logo">Save system branding</x-ui.button>
                    <a href="{{ url('/') }}" target="_blank" rel="noopener" class="text-sm font-semibold text-med-primary">View saved welcome page <i class="bi bi-box-arrow-up-right"></i></a>
                </div>
            </x-ui.card>
        </form>
        <form wire:submit="save" class="space-y-6">
            <x-ui.card title="Organization and package" subtitle="Selecting a package loads its recommended modules. Customize the selection below before saving.">
                <div class="grid gap-4 md:grid-cols-2">
                    <x-ui.input label="Organization name" name="clientName" wire:model="clientName" required />
                    <x-ui.select label="Installation package" name="plan" wire:model.live="plan">
                        @foreach($plans as $key => $package)
                            <option value="{{ $key }}">{{ $package['label'] }}</option>
                        @endforeach
                    </x-ui.select>
                    <x-ui.input type="date" label="Access expiry (optional)" name="expiresAt" wire:model="expiresAt" />
                    <label class="flex items-center gap-3 text-sm text-med-ink">
                        <input type="checkbox" wire:model="active" class="rounded border-med-line text-med-primary">
                        Installation active
                    </label>
                </div>
                <p class="mt-4 text-sm text-med-muted">{{ $plans[$plan]['description'] ?? '' }}</p>
            </x-ui.card>
            <x-ui.card title="Available modules" subtitle="Core System and Access Control remain available. Staff roles and module assignments still control what each user can do.">
                <p class="mb-4 text-sm font-semibold text-med-primary">{{ count(array_unique(array_merge($required, $modules))) }} modules selected</p>
                @error('modules')<p role="alert" class="mb-4 text-sm text-med-danger">{{ $message }}</p>@enderror
                @error('modules.*')<p role="alert" class="mb-4 text-sm text-med-danger">{{ $message }}</p>@enderror
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($features as $key => $label)
                        <label wire:key="module-{{ $key }}" class="flex items-start gap-3 rounded-md border border-med-line bg-white p-4">
                            @if(in_array($key, $required, true))
                                <input type="checkbox" checked disabled class="mt-1 rounded border-med-line text-med-primary">
                            @else
                                <input type="checkbox" value="{{ $key }}" wire:model.live="modules" class="mt-1 rounded border-med-line text-med-primary">
                            @endif
                            <span>
                                <span class="block text-sm font-semibold text-med-ink">{{ $label }}</span>
                                @if(in_array($key, $required, true))
                                    <span class="text-xs text-med-muted">Required for every installation</span>
                                @elseif($key === 'branch_management')
                                    <span class="text-xs text-med-muted">Enterprise package only</span>
                                @elseif(!empty($dependencies[$key]))
                                    <span class="text-xs text-med-muted">Requires {{ implode(', ', array_map(fn ($dependency) => $features[$dependency], $dependencies[$key])) }}</span>
                                @else
                                    <span class="text-xs text-med-muted">Optional module</span>
                                @endif
                            </span>
                        </label>
                    @endforeach
                </div>
            </x-ui.card>
            <div class="flex flex-wrap items-center justify-between gap-4 rounded-md border border-med-line bg-white p-4">
                <p class="text-sm text-med-muted">Changes apply immediately. Disabling a module preserves its existing records.</p>
                <x-ui.button type="submit" wire:loading.attr="disabled" wire:target="save">
                    <span wire:loading.remove wire:target="save">Save installation</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </x-ui.button>
            </div>
        </form>
    </x-ui.page>
</div>
