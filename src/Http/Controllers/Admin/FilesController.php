<?php

namespace Invibe\BackpackOnSteroids\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use Invibe\BackpackOnSteroids\Http\Controllers\Controller;
use Invibe\BackpackOnSteroids\Models\TempFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class GalleryController
 * @author Adam Ondrejkovic
 * @package App\Http\Controllers\Admin
 */
class FilesController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @author Adam Ondrejkovic
     */
    public function uploadSingleFile(Request $request)
    {
        $fileName = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs('temp', $fileName, config('backpack-on-steroids.temp_files_disk'));

        $tempImage = TempFile::create([
            'name' => $fileName,
            'path' => $path
        ]);

        return response()->json($tempImage->toResponseArray());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @author Adam Ondrejkovic
     */
    public function getTempFiles(Request $request)
    {
        return response()->json(TempFile::tempImagesForField($request->input('ids')));
    }

    /**
     * @param Request $request
     * @return mixed
     * @author Adam Ondrejkovic
     */
    public function getMedia(Request $request)
    {
        $model = app($request->input('class_name'))
            ->withoutGlobalScopes()
            ->find($request->input('class_id'));

        $customProperties = $request->input('lang') ?
            ['lang' => $request->input('lang')] :
            [];

        $media = $model->getMedia($request->input('collection', 'default'), $customProperties)
            ->map(function (Media $media) {
                return [
                    "id" => $media->id,
                    "name" => $media->file_name,
                    "url" => $media->getUrl(),
                    "type" => "media"
                ];
            })->toArray();

        return array_values($media);
    }

    /**
     * @param $tempFile
     * @return BinaryFileResponse
     * @author Adam Ondrejkovic
     */
    public function getTempFile($tempFile)
    {
        $tempFile = TempFile::findOrFail($tempFile);

        abort_unless(Storage::disk(config('backpack-on-steroids.temp_files_disk'))->exists($tempFile->path), 404);

        return response()->file(Storage::disk(config('backpack-on-steroids.temp_files_disk'))->path($tempFile->path));
    }
}
