<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogoResource;
use App\Http\Requests\LogoRequest;
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:setting-general');
    }

    /**
     * Get current logo information
     */
    public function index()
    {
        $logoConfig = Config::where('key', 'setting.general.app_logo')->first();
        $logoValue = $logoConfig ? $logoConfig->value : 'logo.png';
        
        return response()->json([
            'status' => 'success',
            'data' => new LogoResource([
                'filename' => $logoValue,
                'url' => $this->getLogoUrl($logoValue),
                'is_default' => $logoValue === 'logo.png'
            ])
        ]);
    }

    /**
     * Upload new logo
     */
    public function store(LogoRequest $request)
    {
        try {
            $imageLogo = $request->file('logo');

            // Generate unique filename
            $imagenameLogo = $imageLogo->getClientOriginalName();
            $aLogo = explode(".", $imagenameLogo);
            $fileExtLogo = strtolower(end($aLogo));
            $namaFileLogo = substr(md5(date("YmdHis")), 0, 10) . "." . $fileExtLogo;

            // Storage path
            $destination_pathLogo = public_path() . '/media/logo/';

            // Move file to storage
            $imageLogo->move($destination_pathLogo, $namaFileLogo);

            // Update config
            Config::where('key', 'setting.general.app_logo')->update(['value' => $namaFileLogo]);

            return response()->json([
                'status' => 'success',
                'message' => 'Logo uploaded successfully',
                'data' => new LogoResource([
                    'filename' => $namaFileLogo,
                    'url' => $this->getLogoUrl($namaFileLogo),
                    'is_default' => false
                ])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload logo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update logo (same as store for this implementation)
     */
    public function update(LogoRequest $request)
    {
        return $this->store($request);
    }

    /**
     * Delete logo (reset to default)
     */
    public function destroy()
    {
        try {
            // Get current logo
            $logoConfig = Config::where('key', 'setting.general.app_logo')->first();
            $currentLogo = $logoConfig ? $logoConfig->value : 'logo.png';

            // Delete file if it's not default
            if ($currentLogo !== 'logo.png') {
                $logoPath = public_path() . '/media/logo/' . $currentLogo;
                if (file_exists($logoPath)) {
                    unlink($logoPath);
                }
            }

            // Reset to default
            Config::where('key', 'setting.general.app_logo')->update(['value' => 'logo.png']);

            return response()->json([
                'status' => 'success',
                'message' => 'Logo deleted successfully, reset to default',
                'data' => new LogoResource([
                    'filename' => 'logo.png',
                    'url' => $this->getLogoUrl('logo.png'),
                    'is_default' => true
                ])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete logo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get logo URL
     */
    private function getLogoUrl($filename)
    {
        if ($filename === 'logo.png') {
            return asset('assets/media/logos/logo.png');
        }
        
        return asset('media/logo/' . $filename);
    }
}
