<?php

namespace App\Http\Controllers;

use App\Models\Product as Product;
use App\Repositories\RoleRepository as Permission;
use Flash;
use Illuminate\Support\Facades\Auth;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER.
 */
class ApiController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    public function getProduct($id)
    {
        $product = Product::all();

        return ['service'=>$product];
    }

    public function submitLead($id, $data)
    {
        $dat = json_decode($data);
        $enq = new Customer;
        $enq->Service = $dat->Service;
        $enq->name = $dat->fname;
        $enq->mobile = $dat->mobile;
        $enq->email = $dat->email;
        $enq->Residental = $dat->Rno;
        $enq->desc = $dat->desc;
        if ($enq->save()) {
            return ['data'=>1];
        } else {
            return ['data'=>0];
        }
    }

    public function postMobileEnquiry($data)
    {
        $data = json_encode($data);
        $attributes['course_id'] = $data->Service;
        $attributes['name'] = $data->fname;
        $attributes['mob_phone'] = $data->mobile;
        $attributes['email'] = $data->email;
        $attributes['home_phone'] = $data->Rno;
        $attributes['description'] = $data->desc;
        $attributes['communication_id'] = '8';
        // TODO: This lead_type_id can be dynamic later when this CRM grows like post_type in wordpress
        $attributes['lead_type_id'] = '1';
        //$attributes['communication_id'] = '8';    //Since the communication is from Website, 8 = Crmenquiry from databaseenqiury mode from database
        $attributes['status_id'] = '17';    // 17 = Pending status from database
        $attributes['user_id'] = '1';   // 1 = Root, user is setup as online to post this entry
        $attributes['viewed'] = '0';
        $attributes['org_id'] = Auth::user()->org_id;

        $lead = $this->lead->create($attributes);
    }
}
