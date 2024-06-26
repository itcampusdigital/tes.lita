<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Selection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Default
        $selection = false;
        $images = [
        'lightning-bolts.svg',
        'arrows.svg',
        'thoughts.svg',
        'gears.svg',
        'keys.svg',
        'lightning-bolts.svg',
        'arrows.svg',
        'thoughts.svg',
        'gears.svg',
        'keys.svg',
        'lightning-bolts.svg',
        'arrows.svg',
        'thoughts.svg',
        'keys.svg',
        'gears.svg',
        'keys.svg',
        'keys.svg',
        'thoughts.svg',
        'thoughts.svg',
        'keys.svg',
        'gears.svg',
        'keys.svg',
        'keys.svg',
        'thoughts.svg',
        'keys.svg',
        'gears.svg',
        'keys.svg',
        'keys.svg',
        'lightning-bolts.svg',
        'lightning-bolts.svg',
        'lightning-bolts.svg',
        'lightning-bolts.svg',
        'arrows.svg',
        'gears.svg',
        'keys.svg',
        'keys.svg',
        'thoughts.svg',
        'keys.svg',
        'gears.svg',
        'keys.svg',
        'keys.svg',
        'lightning-bolts.svg',
        'lightning-bolts.svg',
        'lightning-bolts.svg',
        'lightning-bolts.svg',
        'arrows.svg',
        'thoughts.svg',
        'gears.svg',
        'keys.svg',
        'lightning-bolts.svg',
        'arrows.svg',
        'thoughts.svg',
        'gears.svg',
        'keys.svg',
        'lightning-bolts.svg',
        'lightning-bolts.svg',
        'thoughts.svg',
        'gears.svg',
        'keys.svg',
        'lightning-bolts.svg',
        'lightning-bolts.svg',
        'keys.svg',
        'lightning-bolts.svg',
        'keys.svg',
        'lightning-bolts.svg',
        'keys.svg',
        'lightning-bolts.svg',
        'keys.svg',
        'lightning-bolts.svg',
        'keys.svg',
        'lightning-bolts.svg',
        'lightning-bolts.svg',
        'lightning-bolts.svg',
        'lightning-bolts.svg',
        'keys.svg',
        'gears.svg',
        'keys.svg',
        'gears.svg',
        'arrows.svg',
        'thoughts.svg',
        'keys.svg',
        'gears.svg',
        'keys.svg',
        'gears.svg',
        'arrows.svg',
        'thoughts.svg',
        'thoughts.svg',
        'keys.svg',
        'gears.svg',
        'keys.svg',
        'keys.svg',
        'thoughts.svg',
        'keys.svg',
        'gears.svg',
        'lightning-bolts.svg',
        'keys.svg',
        'thoughts.svg',
        'gears.svg',
        'gears.svg',
        'lightning-bolts.svg',

    ];

        // Get tests
        if(Auth::user()->role->is_global === 1) {
            $tests = Test::all();
        }
        elseif(Auth::user()->role->is_global === 0 && Auth::user()->role->id === 2){
            $tests = Auth::user()->attribute->company->tests;

        }
        elseif(Auth::user()->role->is_global === 0) {
            $test = Auth::user()->attribute->position->tests;
            // $tests = Auth::user()->attribute->company->tests;
            $cekk = $test->pluck('id')->toArray();
            $cekk = array_values(array_unique($cekk));
            $tests = DB::table('tests')->whereIn('id', $cekk)->get();

        }

        // Get the selection
        if(Auth::user()->role_id == role('applicant')) {
            $selection = Selection::where('user_id','=',Auth::user()->id)->first();
        }

        // View
        return view('dashboard/index', [
            'images' => $images,
            'selection' => $selection,
            'tests' => $tests,
        ]);
    }
}
