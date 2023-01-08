<?php

declare(strict_types=1);

namespace App\Http\Livewire\Admin\Settings;

use Exception;
use App\Models\Setting;
use App\Http\Livewire\Base;
use Livewire\WithFileUploads;
use App\Helpers\ConfigHandler;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

use Illuminate\Validation\ValidationException;

use function view;
use function flash;
use function add_user_log;

class ApplicationLogo extends Base
{
    use WithFileUploads;

    public $applicationLogo             = '';
    public $existingApplicationLogo     = '';
    public $applicationLogoDark         = '';
    public $existingApplicationLogoDark = '';

    public function mount(): void
    {
        parent::mount();

        $this->existingApplicationLogo     = ConfigHandler::get('applicationLogo');
        $this->existingApplicationLogoDark = ConfigHandler::get('applicationLogoDark');
    }

    public function render(): View
    {
        return view('livewire.admin.settings.application-logo');
    }

    protected function rules(): array
    {
        return [
            'applicationLogo'     => 'image|mimes:png,jpg,gif|max:5120',
            'applicationLogoDark' => 'image|mimes:png,jpg,gif|max:5120'
        ];
    }

    /**
     * @throws ValidationException
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /**
     * @throws Exception
     */
    public function update(): void
    {
        $this->validate();

        Cache::flush();

        if ($this->applicationLogo !== '') {
            $applicationLogo = ConfigHandler::get('applicationLogo');

            if ($applicationLogo !== null) {
                Storage::disk('public')->delete($applicationLogo);
            }

            $token = md5(random_int(1, 10) . microtime());
            $name  = $token . '.png';
            $img   = Image::make($this->applicationLogo)->encode('png')->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->stream();

            Storage::disk('public')->put('logo/' . $name, $img);

            //TODO: colocar no evento updated/created a limpeza do cache ConfigHandler::clearCache
            Setting::updateOrCreate(['key' => 'applicationLogo'], ['value' => 'logo/' . $name]);
        }

        if ($this->applicationLogoDark !== '') {
            $applicationLogoDark = ConfigHandler::get('applicationLogoDark');

            if ($applicationLogoDark !== null) {
                Storage::disk('public')->delete($applicationLogoDark);
            }

            $token = md5(random_int(1, 10) . microtime());
            $name  = $token . '.png';
            $img   = Image::make($this->applicationLogoDark)->encode('png')->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->stream();

            Storage::disk('public')->put('logo/' . $name, $img);
            Setting::updateOrCreate(['key' => 'applicationLogoDark'], ['value' => 'logo/' . $name]);
        }

        add_user_log([
            'title'        => 'updated Application logo',
            'link'         => route('admin.settings'),
            'reference_id' => auth()->id(),
            'section'      => 'Settings',
            'type'         => 'Update'
        ]);

        flash('Application Logo Updated!')->success();
    }
}
