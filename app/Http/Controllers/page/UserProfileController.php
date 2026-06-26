<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\PublicInfo;
use App\Models\DistrictAwards;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class UserProfileController extends Controller
{
    public function index($slug)
    {

        $view = match ($slug) {
            default => $this->renderDefault($slug),
        };
        return $view;
    }

    function renderDefault($slug) {
        $data = PublicInfo::where('slug', $slug)->first();

        if (!$data) {
            abort(404);
        }

        return view('page.profile', compact('slug', 'data'));
    }



}
