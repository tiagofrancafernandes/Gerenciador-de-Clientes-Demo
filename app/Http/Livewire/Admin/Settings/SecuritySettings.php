<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Settings;

use App\Models\Setting;
use App\Http\Livewire\Base;
use App\Helpers\ConfigHandler;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

use Illuminate\Validation\ValidationException;

use function view;
use function flash;
use function add_user_log;

class SecuritySettings extends Base
{
    public $ips = [];

    protected array $rules = [
        'ips.*.ip' => 'required|ip'
    ];

    protected array $messages = [
        'ips.*.ip.required' => 'IP is required',
        'ips.*.ip.ip'       => 'Please enter a valid IP address'
    ];

    public function mount(): void
    {
        parent::mount();

        $ips = ConfigHandler::get('ips');

        if ($ips !== null) {
            $this->ips = json_decode($ips, true);
        }
    }

    public function render(): View
    {
        return view('livewire.admin.settings.security-settings');
    }

    public function add(): void
    {
        $this->ips[] = [
            'ip'      => '',
            'comment' => ''
        ];
    }

    public function remove($index): void
    {
        unset($this->ips[$index]);
    }

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

        $ips = json_encode($this->ips);

        Cache::flush();
        Setting::updateOrCreate(['key' => 'ips'], ['value' => $ips]);

        add_user_log([
            'title'        => 'updated security settings',
            'link'         => route('admin.settings'),
            'reference_id' => auth()->id(),
            'section'      => 'Settings',
            'type'         => 'Update'
        ]);

        flash('Security Settings Updated!')->success();
    }
}
