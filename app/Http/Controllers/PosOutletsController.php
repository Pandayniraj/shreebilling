<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Attraction;
use Flash;

use Session;

class PosOutletsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        $costcenters = \App\Models\PosOutlets::orderBy('id', 'desc')->get();

        $page_title = 'Admin | Hotel | POS Outlets';

        return view('admin.posoutlets.index', compact('page_title', 'costcenters'));
    }

    public function posDashboard(Request $request)
    {

        $page_title = 'Admin | POS | Transaction Dashboard';
        $description = 'Select options';

        $outlets = \App\Models\PosOutlets::pluck('short_name', 'id')->all();

        if (\Auth::user()->hasRole('admins')) {
            $outlets = \App\Models\PosOutlets::pluck('short_name', 'id')->all();
        } else {
            $outletusers = \App\Models\OutletUser::where('user_id', \Auth::user()->id)->get()->pluck('outlet_id');
            $outlets = \App\Models\PosOutlets::whereIn('id', $outletusers)
                ->orderBy('id', 'DESC')
                ->where('enabled', 1)
                ->get();
        }

        $sessions = \App\Models\PosSession::pluck('name', 'id')->all();

        return view('admin.posoutlets.dashboard', compact('page_title', 'description', 'outlets', 'users', 'sessions'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $page_title = 'Admin | Hotel | Pos Outlets';

        $description = 'Create a new Outlets';

        return view('admin.posoutlets.create', compact('page_title', 'description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $outlets = $request->all();
        \App\Models\PosOutlets::create($outlets);

        Flash::success('POS Outlets added');

        return redirect('/admin/pos-outlets/index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $description = 'Edit an Pos Outlets ';
        $page_title = 'Admin | Hotel | Outlets';
        $edit =  \App\Models\PosOutlets::find($id);

        return view('admin.posoutlets.edit', compact('edit', 'description', 'page_title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $attributes = $request->all();
        $pos_outlets =  \App\Models\PosOutlets::find($id);
        $pos_outlets->update($attributes);

        Flash::success('POS Outlets updated');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $kitchen = \App\Models\PosOutlets::find($id)->delete();

        Flash::success('Pos Kitchen deleted');

        return redirect('/admin/pos-outlets/index');
    }

    public function getModalDelete($id)
    {

        $error = null;

        $outlets =  \App\Models\PosOutlets::find($id);

        $modal_title = "Delete Pos Kitchen";

        $modal_body = "Are you sure that you want to delete POS Outlets ID " . $outlets->id . ". This operation is irreversible";

        $modal_route = route('admin.pos-outlets.delete', $outlets->id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }


    public function openoutlets(Request $request)
    {

        $page_title = 'Admin | POS | Open Outlets';
        $description = 'Open  Outlets';

        $outlets = \App\Models\PosOutlets::pluck('short_name', 'id')->all();

        if (\Auth::user()->hasRole('admins')) {
            $outlets = \App\Models\PosOutlets::where('fnb_outlet', 0)->get();
        } else {
            $outletusers = \App\Models\OutletUser::where('user_id', \Auth::user()->id)->get()->pluck('outlet_id');
            $outlets = \App\Models\PosOutlets::whereIn('id', $outletusers)
                ->orderBy('id', 'DESC')
                ->where('enabled', 1)
                ->where('fnb_outlet', 0)
                ->get();
        }


        $sessions = \App\Models\PosSession::pluck('name', 'id')->all();

        return view('admin.posoutlets.openoutlets', compact('page_title', 'description', 'outlets', 'users', 'sessions'));
    }

    public function resturantoutlets(Request $request)
    {

        $page_title = 'Admin | POS | Open Resturamt | Outlets';
        $description = 'Open Resturant  Outlets';
        
        $outlets = \App\Models\PosOutlets::pluck('short_name', 'id')->all();

        if (\Auth::user()->hasRole('admins')) {
            $outlets = \App\Models\PosOutlets::where('fnb_outlet', 1)->get();
            // dd($outlets);
        } else {
            $outletusers = \App\Models\OutletUser::where('user_id', \Auth::user()->id)->get()->pluck('outlet_id');
            $outlets = \App\Models\PosOutlets::whereIn('id', $outletusers)
                ->orderBy('id', 'DESC')
                ->where('enabled', 1)
                ->where('fnb_outlet', 1)
                ->get();
        }
         if(count($outlets) == 1){

            $outletsFirst = $outlets->first();

            return redirect("/admin/showtablelists?type=invoice&outlet_id={$outletsFirst->id}");
            
        }
    
        $sessions = \App\Models\PosSession::pluck('name', 'id')->all();

        return view('admin.posoutlets.resturantoutletsorder', compact('page_title', 'description', 'outlets', 'users', 'sessions'));
    }


    public function outletsales(Request $request)
    {

        $page_title = 'Outlet Sales';
        $description = 'Sales';

        $outlets = \App\Models\PosOutlets::pluck('short_name', 'id')->all();

        if (\Auth::user()->hasRole('admins')) {
            $outlets = \App\Models\PosOutlets::where('enabled', 1)->get();
        } else {
            $outletusers = \App\Models\OutletUser::where('user_id', \Auth::user()->id)->get()->pluck('outlet_id');
            $outlets = \App\Models\PosOutlets::whereIn('id', $outletusers)
                ->orderBy('id', 'DESC')
                ->where('enabled', 1)
                ->get();
        }

        if(count($outlets) == 1){

            $outletsFirst = $outlets->first();

            return redirect("admin/orders/outlet/{$outletsFirst->id}");
            
        }


        $sessions = \App\Models\PosSession::pluck('name', 'id')->all();

        return view('admin.posoutlets.outletsales', compact('page_title', 'description', 'outlets', 'users', 'sessions'));
    }

    public function takeopenoutlets(Request $request)
    {

        $outlet_id = $request->outlet_id;
        $accounting_date = $request->accounting_date;
        $session_id = $request->session_id;

        return redirect('/admin/orders/create?type=invoice&outlet_id=' . $outlet_id . '&accounting_date=' . $accounting_date . '&session_id=' . $session_id);
    }


    public function addUser($id)
    {

        $outlets = \App\Models\PosOutlets::find($id);

        $users = \App\User::select('id', 'username')->get();


        $outlet_users = \App\Models\OutletUser::where('outlet_id', $id)->get();

        $page_title = 'Add User To Outlet';


        return view('admin.posoutlets.adduser', compact('id', 'outlets', 'users', 'outlet_users', 'page_title'));
    }

    public function postUser(Request $request, $id)
    {

        $attributes = $request->all();

        $outlets_users = \App\Models\OutletUser::create($attributes);

        Flash::success("User sucessfully added to Outlet");

        return redirect('/admin/pos-outlets/' . $id . '/adduser');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyUser($id)
    {

        $outletuser = \App\Models\OutletUser::find($id)->delete();

        Flash::success('Pos Outlet User deleted');

        return redirect()->back();
    }

    public function getModalDeleteUser($id)
    {

        $error = null;
        $outlets =  \App\Models\OutletUser::find($id);
        $modal_title = "Delete Pos Outlet User";
        $modal_body = "Are you sure that you want to delete POS Outlets User";
        $modal_route = route('admin.pos-outlets.adduser.delete', $outlets->id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function showtablelists(Request $request)
    {

        $page_title = "Table Show";
        $page_description = "pluck of Tables";
        $type = \Request::get('type');
        $outlet_id = \Request::get('outlet_id');
        
        $table_area = \App\Models\TableArea::select('table_area.*')
            ->leftjoin('pos_floors', 'pos_floors.id', '=', 'table_area.floor_id')
            ->leftjoin('pos_outlets', 'pos_outlets.id', '=', 'pos_floors.outlet_id')
            ->where('pos_outlets.id', $outlet_id)
            ->get();
        // dd($table_area);

       // $order= \App\Models\Orders::orderBy('id', 'desc')
       //      ->where('org_id', \Auth::user()->org_id)
       //      ->where('outlet_id', $outlet_id)
       //      ->first();


        return view('admin.posoutlets.tableshow', compact('page_title', 'table_area', 'page_description', 'type', 'outlet_id'));
    }
}
