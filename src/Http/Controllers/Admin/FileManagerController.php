<?php

namespace Invibe\BackpackOnSteroids\Http\Controllers\Admin;

use Invibe\BackpackOnSteroids\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Class FileManagerController
 * @author Adam Ondrejkovic
 * @package App\Http\Controllers\Admin
 */
class FileManagerController extends Controller
{
    /**
     * @return Application|Factory|View
     * @author Adam Ondrejkovic
     */
    public function view()
    {
        return view('backpack-on-steroids::admin.filemanager');
    }

    /**
     * @return Application|Factory|View
     * @author Adam Ondrejkovic
     */
    public function tinymce5()
    {
        return view('backpack-on-steroids::admin.filemanager-tinymce5');
    }
}
