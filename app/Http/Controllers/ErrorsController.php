<?php

namespace App\Http\Controllers;

use App\Models\Audit as Audit;
use App\Models\Error as Error;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ErrorsController extends Controller
{
    /**
     * @var Error
     */
    private $error;

    /**
     * @param Route $route
     * @param Permission $permission
     */
    public function __construct(Application $app, Audit $audit, Error $error)
    {
        parent::__construct($app, $audit);
        $this->error = $error;
        // Set default crumbtrail for controller.
        session(['crumbtrail.leaf' => 'error']);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/error/general.audit-log.category'), trans('admin/error/general.audit-log.msg-index'));

        $page_title = trans('admin/error/general.page.index.title');
        $page_description = trans('admin/error/general.page.index.description');
        $purge_retention = \Config::get('settings.errors_purge_retention');

        $lern_errors = $this->error->orderBy('created_at', 'DESC')->paginate(20);

        return view('admin.errors.index', compact('lern_errors', 'purge_retention', 'page_title', 'page_description'));
    }

    public function purge()
    {
        Audit::log(Auth::user()->id, trans('admin/error/general.audit-log.category'), trans('admin/error/general.audit-log.msg-purge'));

        $purge_retention = \Config::get('settings.errors_purge_retention');
        $purge_date = (new \DateTime())->modify("- $purge_retention day");
        $errorsToDelete = $this->error->where('created_at', '<', $purge_date->format('Y-m-d'));

        foreach ($errorsToDelete as $error) {
            // The AuditRepository located at $this->error is changed to a instance of the
            // QueryBuilder when we run a query as done above. So we had to revert to some
            // Magic to get a handle of the model...
//            $this->error->delete($error->id);
            $this->app->make($this->error->model())->destroy($error->id);
        }

        return Redirect::route('admin.errors.index');
    }

    public function show($id)
    {
        $error = $this->error->find($id);

        Audit::log(Auth::user()->id, trans('admin/error/general.audit-log.category'), trans('admin/error/general.audit-log.msg-show'));

        $errorData = urldecode(http_build_query($error->data, '', PHP_EOL));

        $page_title = trans('admin/error/general.page.show.title');
        $page_description = trans('admin/error/general.page.show.description', ['error_id' => $error->id]);

        return view('admin.errors.show', compact('error', 'errorData', 'page_title', 'page_description'));
    }
}
