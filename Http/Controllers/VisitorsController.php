<?php

namespace App\Http\Controllers;

use App\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class VisitorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $carbon = new Carbon();
        $visitors = Auth::user()->visitors()->orderBy('created_at', 'desc')
        ->take(100)->get()->unique('visitor_id');
        
        $visitors = $this->custom_paginate($visitors,20);

        Auth::user()->notifications()
        ->where('type','App\Notifications\Visitors')
        ->delete();

        return view('sign-in.visitors',compact('visitors','carbon'));
    }

    public function destroy()
    {
        Auth::user()->visitors()->delete();
        return 'success';
    }


    private function custom_paginate($items, $perPage){

    $pageStart           = request('page', 1);
    $offSet              = ($pageStart * $perPage) - $perPage;
    $itemsForCurrentPage = $items->slice($offSet, $perPage);

    return new LengthAwarePaginator(
        $itemsForCurrentPage, $items->count(), $perPage,
        Paginator::resolveCurrentPage(),
        ['path' => Paginator::resolveCurrentPath()]
    );

    }
}
