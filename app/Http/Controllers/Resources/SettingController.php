<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:setting-general', ['only' => ['general', 'update_general']]);
        $this->middleware('permission:setting-smtp', ['only' => ['smtp', 'update_smtp']]);
        $this->middleware('permission:setting-smtp', ['only' => ['drive', 'update_drive']]);
    }

    public function general()
    {
        return view('pages.resources.settings.general');
    }

    public function update_general(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'url' => 'required',
            'description' => 'required'
        ]);

        // dd($request->file('logo'));
        // validation logo
        if ($request->file('logo')) {
            $imageLogo = $request->file('logo');

            //name file
            $imagenameLogo = $imageLogo->getClientOriginalName();
            $aLogo = explode(".", $imagenameLogo);
            $fileExtLogo = strtolower(end($aLogo));
            $namaFileLogo = substr(md5(date("YmdHis")), 0, 10) . "." . $fileExtLogo;

            //penyimpanan
            $destination_pathLogo = public_path() . '/media/logo/';

            // simpan ke folder
            $request->file('logo')->move($destination_pathLogo, $namaFileLogo);
            Config::where('key', 'setting.general.app_logo')->update(['value' => $namaFileLogo]);
        }

        // validation favicon
        if ($request->file('favicon')) {
            $imageFavicon = $request->file('favicon');

            $imagenameFavicon = $imageFavicon->getClientOriginalName();
            $aFavicon = explode(".", $imagenameFavicon);
            $fileExtFavicon = strtolower(end($aFavicon));
            $namaFileFavicon = substr(md5(date("YmdHis")), 0, 10) . "." . $fileExtFavicon;

            $destination_pathFavicon = public_path() . '/media/favicon/';

            $request->file('favicon')->move($destination_pathFavicon, $namaFileFavicon);
            Config::where('key', 'setting.general.app_favicon')->update(['value' => $namaFileFavicon]);
        }

        $config = Config::where('key', 'app.name')->update(['value' => $request->name]);
        $config = Config::where('key', 'app.url')->update(['value' => $request->url]);
        $config = Config::where('key', 'setting.general.app_description')->update(['value' => $request->description]);

        $message = [
            "status" => $config ? "success" : "failed",
            "message" => $config ? "Data updated successfully" : "Data failed to update!"
        ];

        return redirect()->route('resources.setting.general.index')->with("message", $message);
    }

    public function smtp()
    {
        return view('pages.resources.settings.smtp');
    }

    public function update_smtp(Request $request)
    {
        $request->validate([
            'mail_name' => 'required',
            'mail_address' => 'required',
            'mail_driver' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_encryption' => 'required'
        ]);

        $config = Config::where('key', 'mail.from.name')->update(['value' => $request->mail_name]);
        $config = Config::where('key', 'mail.from.address')->update(['value' => $request->mail_address]);
        $config = Config::where('key', 'mail.default')->update(['value' => $request->mail_driver]);
        $config = Config::where('key', 'mail.mailers.smtp.host')->update(['value' => $request->mail_host]);
        $config = Config::where('key', 'mail.mailers.smtp.port')->update(['value' => $request->mail_port]);
        $config = Config::where('key', 'mail.mailers.smtp.username')->update(['value' => $request->mail_username]);
        $config = Config::where('key', 'mail.mailers.smtp.password')->update(['value' => $request->mail_password]);
        $config = Config::where('key', 'mail.mailers.smtp.encryption')->update(['value' => $request->mail_encryption]);

        $message = [
            "status" => $config ? "success" : "failed",
            "message" => $config ? "Data updated successfully" : "Data failed to update!"
        ];

        return redirect()->route('resources.setting.smtp.index')->with("message", $message);
    }

    public function drive()
    {
        return view('pages.resources.settings.drive');
    }

    public function update_drive(Request $request)
    {
        $request->validate([
            'credentials_file' => 'file|mimes:json|max:2048',
            'folder_id' => 'required',
        ]);

        if ($request->hasFile('credentials_file')) {
            $file = $request->file('credentials_file');
            $filename = 'credentials.json';
            $file->storeAs('credentials', $filename);
        }

        Config::where('key', 'google.drive.folder_id')->update(['value' => $request->folder_id]);

        $message = [
            "status" => "success",
            "message" => "Google Drive settings updated successfully",
        ];

        return redirect()->route('resources.setting.drive.index')->with("message", $message);
    }


    public function register()
    {
        return view('pages.resources.settings.register');
    }

    public function update_register(Request $request)
    {
        $request->validate([
            'registration_code' => 'required',
        ]);

        $config = Config::updateOrCreate(
            ['key' => 'app.registration_code'], // conditions to search
            ['value' => $request->registration_code] // values to update or create
        );

        $message = [
            "status" => $config ? "success" : "failed",
            "message" => $config ? "Data updated successfully" : "Data failed to update!"
        ];

        return redirect()->route('resources.setting.register.index')->with("message", $message);
    }
}
