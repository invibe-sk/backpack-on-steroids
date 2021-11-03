<?php

namespace Invibe\BackpackOnSteroids\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class TempImage
 * @author Adam Ondrejkovic
 * @package App\Models
 */
class TempFile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return array
     * @author Adam Ondrejkovic
     */
    public function toResponseArray()
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "path" => $this->path,
            "url" => $this->getUrl(),
            "type" => "temp",
        ];
    }

    /**
     * @param $ids
     * @return array
     * @author Adam Ondrejkovic
     */
    public static function tempImagesForField($ids)
    {
        return array_values(TempFile::whereIn('temp_files.id', $ids)->get()->map(function (TempFile $image) {
            return $image->toResponseArray();
        })->toArray());
    }

    /**
     * @return string
     * @author Adam Ondrejkovic
     */
    public function getPath()
    {
        return Storage::disk(config('backpack-on-steroids.temp_files_disk'))->path($this->path);
    }

    /**
     * @return string
     * @author Adam Ondrejkovic
     */
    public function getUrl()
    {
        return route('files.getTempFile', $this->id);
    }
}
