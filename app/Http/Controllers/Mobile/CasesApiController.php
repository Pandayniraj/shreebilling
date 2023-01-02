<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Cases;
use App\Models\Usertoken;
use App\User;
use DB;
use Illuminate\Http\Request;

class CasesApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard($token)
    {
        $user_id = Usertoken::where('token', $token)->first()->user_id;
        $user = User::find($user_id);
        if ($user->hasRole('admins')) {
            $result = DB::select("
        SELECT COUNT(cases.status) as total,
               sum(CASE WHEN cases.status = 'new' THEN 1 ELSE 0 END) as new,
               sum(CASE WHEN cases.status = 'assigned' THEN 1 ELSE 0 END) as assigned,
               sum(CASE WHEN cases.status = 'closed' THEN 1 ELSE 0 END) as closed,
               sum(CASE WHEN cases.status = 'pending' THEN 1 ELSE 0 END) as pending,
               sum(CASE WHEN cases.status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM cases"
        );
        } else {
            $result = DB::select("
        SELECT COUNT(cases.status) as total,
               sum(CASE WHEN cases.status = 'new' THEN 1 ELSE 0 END) as new,
               sum(CASE WHEN cases.status = 'assigned' THEN 1 ELSE 0 END) as assigned,
               sum(CASE WHEN cases.status = 'closed' THEN 1 ELSE 0 END) as closed,
               sum(CASE WHEN cases.status = 'pending' THEN 1 ELSE 0 END) as pending,
               sum(CASE WHEN cases.status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM cases WHERE cases.assigned_to = '$user_id' OR cases.user_id = '$user_id' "
        );
        }

        return ['data'=>$result];
    }

    public function viewjob($token, $type)
    {
        $user_id = Usertoken::where('token', $token)->first()->user_id;
        $user = User::find($user_id);
        $result = Cases::select('cases.id', 'clients.name as client_name', 'clients.email as email', 'cases.telephone as phone', 'cases.job_no', 'cases.status', 'cases.type', 'cases.preff_d_t as preff_date', 'cases.address', 'cases.cust_name', 'cases.subject', 'cases.latitude', 'cases.longitude')->leftjoin('clients', 'clients.id', '=', 'cases.client_id')
       ->where(function ($query) use ($type) {
           if ($type != 'all') {
               return $query->where('cases.status', $type);
           }
       })
       ->where(function ($query) use ($user) {
           if (! $user->hasRole('admins')) {
               return $query->where('cases.assigned_to', $user->id)->orWhere('cases.user_id', $user->id);
           }
       })
        ->orderBy('id', 'desc')
        ->paginate(20);

        return \response()->json($result);
    }

    public function searchjob($token, $type)
    {
        $user_id = Usertoken::where('token', $token)->first()->user_id;
        $user = User::find($user_id);
        $term = \Request::get('term');
        $result = Cases::select('cases.id', 'clients.name as client_name', 'clients.email as email', 'cases.telephone as phone', 'cases.job_no', 'cases.status', 'cases.type', 'cases.preff_d_t as preff_date', 'cases.address', 'cases.cust_name', 'cases.subject', 'cases.latitude', 'cases.longitude')->leftjoin('clients', 'clients.id', '=', 'cases.client_id')
      ->leftjoin('products', 'products.id', '=', 'cases.product')
      ->where(function ($query) use ($type) {
          if ($type != 'all') {
              return $query->where('cases.status', $type);
          }
      })
      ->where(function ($query) use ($user) {
          if (! $user->hasRole('admins')) {
              return $query->where('cases.assigned_to', $user->id)->orWhere('cases.user_id', $user->id);
          }
      })
      ->Where('cases.id', $term)
      ->orWhere('cases.job_no', $term)
      ->orWhere('cases.cust_name', 'LIKE', '%'.$term.'%')
      ->orWhere('clients.name', 'LIKE', '%'.$term.'%')
      ->orWhere('products.name', 'LIKE', '%'.$term.'%')
      ->orWhere('cases.telephone', 'LIKE', '%'.$term.'%')
      ->orWhere('clients.email', 'LIKE', '%'.$term.'%')
      ->orderBy('cases.id', 'asc')->get();

        return \response()->json($result);
    }

    public function createjob($token)
    {
        $clients = \App\Models\Client::select('name', 'id')->get();
        $users = \App\User::select('username', 'id')->where('enabled', '1')->get();
        $dealer = \App\Models\Client::select('name', 'id')->where('type', 'Dealer')->get();

        return ['clients'=>$clients, 'users'=>$users, 'dealer'=>$dealer];
    }

    public function storejob($token, Request $request)
    {
        $jobs = (array) json_decode($request->data);
        $jobs['job_no'] = time();
        Cases::create($jobs);

        return ['sucess'=>true];
    }

    //for products
    public function productdetails($token, $jobid)
    {
        $cases = Cases::select('cases.id', 'cases.dop', 'cases.sys_status', 'cases.product', 'cases.model_no', 'cases.serial_no', 'cases.status', 'cases.do_amc')->find($jobid);
        $product = \App\Models\Product::select('name', 'id')->get();
        $model = \App\Models\ProductModel::select('model_name as name', 'id')->where('product_id', $cases->product)->get();
        $serial_num = \App\Models\ProductSerialNumber::select('serial_num as name', 'id')->where('model_id', $cases->model_no)->get();

        return ['cases'=>$cases, 'product'=>$product, 'model'=>$model, 'serial_num'=>$serial_num];
    }

    public function productmodel($token, $product_id)
    {
        $model = \App\Models\ProductModel::select('model_name as name', 'id')->where('product_id', $product_id)->get();

        return ['model'=>$model];
    }

    public function productserialnum($token, $model_id)
    {
        $serial_num = \App\Models\ProductSerialNumber::select('serial_num as name', 'id')->where('model_id', $model_id)->get();

        return ['serial_num'=>$serial_num];
    }

    public function updateProducts(Request $request, $token, $jobid)
    {
        $update = (array) json_decode($request->data);
        Cases::find($jobid)->update($update);

        return ['sucess'=>true];
    }

    //product end
    //for spare
    public function sparedetails($token, $jobid)
    {
        $spare = \App\Models\CasePart1::where('case_id', $jobid)->get();

        return ['spare'=>$spare];
    }

    public function addspare($token, Request $request)
    {
        $spare = (array) json_decode($request->data);
        foreach ($spare as $key) {
            $data = (array) ($key);
            \App\Models\CasePart1::insert($data);
        }

        return ['sucess'=>true];
    }

    public function sparedestroy($token, $spareid)
    {
        \App\Models\CasePart1::find($spareid)->delete();

        return ['data'=>'sucess'];
    }

    public function updatestatus(Request $request, $token, $jobid)
    {
        $updatestatus = (array) json_decode($request->data);
        Cases::find($jobid)->update($updatestatus);

        return ['data'=>true];
    }

    public function viewstatus($token, $jobid)
    {
        $status = Cases::select('status', 'signature', 'signature_array')->find($jobid);

        return ['data'=>$status];
    }

    public function trackloaction(Request $request)
    {
        $location = [
            'user_id'=>$request->user_id,
            'latitude'=>$request->latitude,
            'longitude'=>$request->longitude,
            'ip_address'=>\Request::getClientIp(),
        ];
        $expiresAt = \Carbon\Carbon::now()->addMinutes(4);
        \Cache::put('user-is-online-'.$request->user_id, true, $expiresAt);
        $location = $this->getLocDetails($location);
        $this->getLocDetails($location);
        if ($request->has('type') && $request->type == 'background') {
            $location['tracked_date'] = date('Y-m-d');
            \App\Models\GeolocationHistory::create($location);
        }
        $ifexists = \App\Models\UserLocation::where('user_id', $request->user_id)->first();
        if ($ifexists) {
            $ifexists->update($location);

            return ['sucess'=>true];
        }
        \App\Models\UserLocation::create($location);

        return ['sucess'=>true];
    }

    public function monitorlocation(Request $request, $token)
    {
        $user_id = $request->user_id;
        $locations = \App\Models\UserLocation::select('user_locations.latitude', 'user_locations.longitude', 'users.first_name', 'users.last_name', 'users.username')
      ->where(function ($query) use ($user_id) {
          if (! $this->checkroles($user_id)) {
              return $query->where('user_id', $user_id);
          }
      })->join('users', 'users.id', '=', 'user_locations.user_id')
        ->get();

        return ['data'=>$locations];
    }

    private function getLocDetails($location)
    {
        $api_key = 'AIzaSyBH1VwCF9e5KWE-_C9zl-7odmf4sTgWOgw';
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$location['latitude']},{$location['longitude']}&key={$api_key}";
        $api_loc = file_get_contents($url);
        $data = json_decode($api_loc, true);
        $address = $data['results']['0'];
        $location['street_name'] = $address['address_components']['1']['long_name'];
        $location['formatted_address'] = $address['formatted_address'];

        return $location;
    }

    private function checkroles($user_id)
    {
        $users = \App\User::find($user_id);
        if ($users->id != 1 && $users->id != 5 && $users->id != 4 && $users->id != 3 && ! $users->hasRole('admins')) {
            return false;
        }

        return true;
    }

    public function addserialnum(Request $request, $token, $prodid)
    {
        $serial_num = ['product_id'=>$prodid, 'serial_num'=>$request->serial_num, 'model_id'=>$request->model_id];

        $created = \App\Models\ProductSerialNumber::create($serial_num);

        return ['serial_num'=>$created];
    }

    public function updatejobs(Request $request, $token, $jobid)
    {
        $job = Cases::find($jobid);
        $attribute = $request->all();
        $job->update($attribute);

        return ['sucess'=>true];
    }

    public function showjobs($token, $jobid)
    {
        $job = Cases::find($jobid);

        return ['case'=>$job];
    }
}
