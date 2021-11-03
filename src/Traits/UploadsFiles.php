<?php

namespace Invibe\BackpackOnSteroids\Traits;

use Invibe\BackpackOnSteroids\Models\TempFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Trait UploadsFiles
 * @author Adam Ondrejkovic
 * @package App\Traits\Admin
 */
trait UploadsFiles
{
    /**
     * @return array|mixed
     * @author Adam Ondrejkovic
     */
    public function getMediaCollectionNames()
    {
        return data_get($this->crud->entry->getRegisteredMediaCollections(), "*.name");
    }


    /**
     * @author Adam Ondrejkovic
     */
    private function handleFileUploads()
    {
        foreach (request()->only($this->getMediaCollectionNames()) as $fieldName => $fieldValue) {
            $data = json_decode($fieldValue, true);

            Media::whereIn('media.id', data_get($data, "removedFiles.media", []))->delete();
            TempFile::whereIn('temp_files.id', data_get($data, "removedFiles.temp"))->delete();

            /** @var TempFile $tempFile */
            foreach (TempFile::whereIn('temp_files.id', data_get($data, "temp", []))->get() as $tempFile) {

                $this->crud->entry
                    ->addMedia($tempFile->getPath())
                    ->withCustomProperties($this->getCustomProperties($data))
                    ->toMediaCollection($fieldName);

                $tempFile->delete();
            }
        }
    }

    /**
     * @param $data
     * @return array
     * @author Adam Ondrejkovic
     */
    private function getCustomProperties($data)
    {
        if (data_get($data, "translatable", false)) {
            return ['lang' => data_get($data, "lang", config('app.locale'))];
        } else {
            return [];
        }
    }
}
