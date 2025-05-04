<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class SpbHelper
{
    public static function storeSpbDocument(UploadedFile $file): string
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        return $file->storeAs('spb-documents', $filename, 'public');
    }

    public static function deleteSpbDocument(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }

    public static function getSpbDocumentUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }
}
