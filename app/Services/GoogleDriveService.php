<?php

namespace App\Services;

use Google\Service\Drive;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;

class GoogleDriveService
{
    private $driveService;
    private $parentFolderId;

    public function __construct()
    {
        $this->initializeGoogleDriveService();
    }

    private function initializeGoogleDriveService()
    {
        $client = new GoogleClient();
        $client->setAuthConfig(storage_path('app/credentials/credentials.json'));
        $client->addScope(GoogleDrive::DRIVE_FILE);

        $this->driveService = new GoogleDrive($client);
        $this->parentFolderId = config(key: 'google.drive.folder_id');
    }

    public function uploadFile($filePath, $fileName, $folderId = null)
    {
        $fileMetadata = [
            'name' => $fileName,
            'parents' => $folderId ? [$folderId] : [$this->parentFolderId],
        ];

        $file = new \Google\Service\Drive\DriveFile($fileMetadata);
        $content = file_get_contents($filePath);

        $uploadedFile = $this->driveService->files->create(
            $file,
            [
                'data' => $content,
                'mimeType' => mime_content_type($filePath),
                'uploadType' => 'multipart',
                'fields' => 'id, webViewLink, webContentLink',
            ]
        );

        return [
            'viewLink' => $uploadedFile->getWebViewLink(),
            'downloadLink' => $uploadedFile->getWebContentLink(),
        ];
    }

    public function createFolder($folderName)
    {
        $fileMetadata = [
            'name' => $folderName,
            'parents' => [$this->parentFolderId],
            'mimeType' => 'application/vnd.google-apps.folder',
        ];

        $folder = $this->driveService->files->create(
            new \Google\Service\Drive\DriveFile($fileMetadata),
            ['fields' => 'id, name']
        );

        return [
            'id' => $folder->id,
            'name' => $folder->name,
        ];
    }

    public function firstOrCreateFolder($folderName)
    {
        $query = sprintf(
            "name = '%s' and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
            addslashes($folderName)
        );

        $folders = $this->driveService->files->listFiles([
            'q' => $query,
            'spaces' => 'drive',
            'fields' => 'files(id, name)',
        ]);

        if (count($folders->getFiles()) > 0) {
            $folder = $folders->getFiles()[0]; 
            return [
                'id' => $folder->getId(),
                'name' => $folder->getName(),
            ];
        }

        return $this->createFolder($folderName);
    }

    public function synchronizeFolders()
    {
        // Ambil semua folder dari Google Drive
        $folders = $this->driveService->files->listFiles([
            'q' => "mimeType = 'application/vnd.google-apps.folder' and trashed = false",
            'spaces' => 'drive',
            'fields' => 'files(id, name)'
        ]);

        // Ambil semua folder_name dari database yang ada di tabel Anda
        $databaseFolders = YourModel::all(['folder_name']); // Sesuaikan dengan model dan kolom yang sesuai

        // Membandingkan folder_name di database dengan folder di Google Drive
        foreach ($databaseFolders as $databaseFolder) {
            $folderInDrive = null;
            
            // Mencocokkan folder berdasarkan nama (bukan ID)
            foreach ($folders->getFiles() as $driveFolder) {
                if ($driveFolder->getName() == $databaseFolder->folder_name) {
                    // Folder ada di Google Drive
                    $folderInDrive = $driveFolder;
                    break;
                }
            }

            // Jika folder tidak ditemukan di Google Drive, buat folder baru
            if (!$folderInDrive) {
                $this->createFolder($databaseFolder->folder_name);
            }
        }
    }

    public function getAllFoldersInParent()
    {
        // Membuat query untuk mengambil folder yang berada di dalam folder induk
        $query = sprintf(
            "'%s' in parents and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
            $this->parentFolderId
        );

        // Mendapatkan daftar folder di Google Drive berdasarkan query
        $folders = $this->driveService->files->listFiles([
            'q' => $query,
            'spaces' => 'drive',
            'fields' => 'files(id, name)', // Ambil ID dan nama folder
        ]);

        // Kembalikan daftar folder yang ditemukan
        return $folders->getFiles();
    }


    public function folderExists($folderId)
    {
        try {
            // Memeriksa folder di Google Drive
            $folder = $this->driveService->files->get($folderId, ['fields' => 'id, trashed']);
            
            // Jika folder ditemukan dan tidak dalam keadaan di-trash
            if ($folder && !$folder->getTrashed()) {
                return true; // Folder ditemukan dan aktif
            }
            
            // Jika folder tidak ditemukan atau di-trash, kembalikan false
            return false;
        } catch (\Google\Service\Exception $e) {
            if ($e->getCode() === 404) {
                // Folder tidak ditemukan
                return false;
            }
            // Menangani error lainnya
            throw new \Exception('Gagal memeriksa folder: ' . $e->getMessage());
        }
    }

    public function updateFolderName($folderId, $newName)
    {
        try {
            $fileMetadata = [
                'name' => $newName,
            ];

            $this->driveService->files->update(
                $folderId,
                new \Google_Service_Drive_DriveFile($fileMetadata)
            );
        } catch (\Exception $e) {
            throw new \Exception('Failed to update folder: ' . $e->getMessage());
        }
    }

    public function deleteFolder($folderId)
    {
        try {
            $this->driveService->files->delete($folderId);
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete folder: ' . $e->getMessage());
        }
    }

    public function deleteFile($fileId)
    {
        try {
            $this->driveService->files->delete($fileId);
        } catch (\Exception $e) {
            throw new \Exception('Failed to delete file: ' . $e->getMessage());
        }
    }
}
