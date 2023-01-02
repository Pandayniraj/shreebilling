<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\AccountingPrefix;
use Flash;
use Illuminate\Http\Request;

class AccountingPrefixController extends Controller
{

    /**
     * @var Permission
     */
    private $permission;

    public function __construct(AccountingPrefix $accountingprefix)
    {
        $this->accountingprefix = $accountingprefix;
        parent::__construct();
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title = 'Admin | Accountingprefix |Index';
        $page_description = 'Accountingprefix Index';
        $accountingprefix = $this->accountingprefix->where('org_id',\Auth::user()->org_id)
                                ->orderBy('id', 'desc')
                                ->paginate(30);

        return view('admin.accountingprefix.index', compact('accountingprefix', 'page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Admin | accountingprefix |Create';
        $page_description = 'Create a accountingprefix';

        return view('admin.accountingprefix.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $attributes = $request->all();
        
        $attributes['org_id'] = \Auth::user()->org_id;

        Flash::success('accountingprefix SucessFully Created');
        
        $this->accountingprefix->create($attributes);

        return redirect('/admin/accountingPrefix/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $page_title = 'Admin | accountingprefix | Edit';
        $page_description = 'Edit a accountingprefix';
        $accountingprefix = $this->accountingprefix->find($id);

        return view('admin.accountingprefix.edit', compact('accountingprefix', 'page_title', 'page_description'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $accountingprefix = $this->accountingprefix->find($id);
        $attributes = $request->all();

        if (! $accountingprefix->isEditable()) {
            abort(404);
        }
        $accountingprefix->update($attributes);
        Flash::success('accountingprefix Updated');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $accountingprefix = $this->accountingprefix->find($id);
        if (! $accountingprefix->isDeletable()) {
            abort(404);
        }
        $accountingprefix->delete();
        Flash::success('accountingprefix deleted');

        return redirect('/admin/accountingPrefix/');
    }

    public function getModalDelete($id)
    {
        $error = null;

        $accountingprefix = $this->accountingprefix->find($id);
        $modal_title = 'Delete accountingprefixs';
        $modal_body = 'Are you sure you want to delte accountingprefix with name '.$accountingprefix->name.' and Id #'.$id;
        $modal_route = route('admin.accountingPrefix.delete', $id);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }
}
