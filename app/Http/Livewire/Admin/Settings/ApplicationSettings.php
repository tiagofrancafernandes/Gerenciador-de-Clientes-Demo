<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Settings;

use App\Models\Setting;
use App\Http\Livewire\Base;
use Livewire\WithFileUploads;
use App\Helpers\ConfigHandler;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

use Illuminate\Validation\ValidationException;

use function view;
use function flash;
use function add_user_log;

class ApplicationSettings extends Base
{
    use WithFileUploads;

    public $siteName    = '';
    public $isForced2Fa = '';

    public function mount(): void
    {
        $this->siteName    = ConfigHandler::get('app.name');
        $this->isForced2Fa = ConfigHandler::get('is_forced_2fa');
    }

    public function render(): View
    {
        return view('livewire.admin.settings.application-settings');
    }

    protected function rules(): array
    {
        return [
            'siteName' => 'required|string'
        ];
    }

    protected array $messages = [
        'siteName.required' => 'Site name is required',
    ];

    /**
     * @throws ValidationException
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function update(): void
    {
        $this->validate();

        Cache::flush();
        Setting::updateOrCreate(['key' => 'app.name'], ['value' => $this->siteName]);
        Setting::updateOrCreate(['key' => 'is_forced_2fa'], ['value' => $this->isForced2Fa]);

        add_user_log([
            'title'        => 'updated application settings',
            'link'         => route('admin.settings'),
            'reference_id' => auth()->id(),
            'section'      => 'Settings',
            'type'         => 'Update'
        ]);

        flash('Application Settings Updated!')->success();
    }
}
