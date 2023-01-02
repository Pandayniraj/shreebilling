<?php namespace App\Http\Controllers;

use App\Models\Role as Permission;
use App\Models\Audit as Audit;
use App\User;
use Flash;
use DB;
use Auth;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Projectscat as ProjectscatModel;
use App\Models\MasterComments;
/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER
 */

class TaskSubCatController extends Controller
{
    /**
     * @var Client
     */
    private $projects;

    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Client $knowledgecat
     * @param Permission $permission
     * @param User $user
     */
    public function __construct(ProjectscatModel $projectscat, Permission $permission)
    {
		parent::__construct();
        $this->projectscat = $projectscat;
        $this->permission = $permission;
    }
	
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {        
        $tasksubcat = \App\Models\TaskSubCat::all();

        //dd($tasksubcat);

        $page_title = 'Task Sub Category';
        $page_description = 'Task Category';

		
        return view('admin.tasksubcat.index', compact('tasksubcat', 'page_title', 'page_description'));
    }
	
	
    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $projects = $this->projectscat->find($id);
     //   dd($projects);
		
        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-show', ['name' => $projectscat->name]));

        $page_title = 'Admin | Projects Category| Show'; // "Admin | Client | Show";
        $page_description ='Admin | Projects Category| Show' ; // "Displaying client: :name";

        return view('admin.tasksubcat.show', compact('projects', 'page_title', 'page_description'));

    }

    /**
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Projects Task Sub Category Create'; // "Admin | Client | Create";
        $page_description = "Creating a new Projects Task Sub category"; // "Creating a new client";
		
		$task_cat = \App\Models\Taskscat::pluck('name','id')->all(); 

        //dd($task_cat);

        return view('admin.tasksubcat.create', compact('projects', 'perms', 'page_title', 'page_description','users','task_cat'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

        $this->validate($request, array(    
            'name'      => 'required'

        ));

        $attributes = $request->all();
       // $attributes['user_id'] = \Auth::user()->id;
        $attributes['org_id'] = \Auth::user()->org_id;

      //
        if(!isset($attributes['enabled']))
            $attributes['enabled'] = 0;
        
        $tasksubcat = \App\Models\TaskSubCat::create($attributes);
      
        
        Flash::success( 'Project Task Sub Category created '); // 'Client successfully created');

        return redirect('/admin/tasksubcat');
    }

    /**
     * @param $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $task_sub_cat = \App\Models\TaskSubCat::find($id);
		
        $page_title = "Admin | Project Task Sub Cat | Edit"; // "Admin | Client | Edit";
        $page_description = "Editing Project Task Sub Cat";

        $task_cat = \App\Models\Taskscat::pluck('name','id')->all();


        return view('admin.tasksubcat.edit', compact('task_sub_cat','task_cat', 'page_title', 'page_description'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array(    
            'name'      => 'required',

        ));

        $attributes = $request->all();


        if(!isset($attributes['enabled']))
            $attributes['enabled'] = 0;

        $tasksubcat = \App\Models\TaskSubCat::find($id);
        
        $tasksubcat->update($attributes);

		
        Flash::success( 'Projects Task Sub Categories successfully Updated'); // 'Client successfully updated');

        return redirect('/admin/tasksubcat');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $projectscat = \App\Models\TaskSubCat::find($id);

        if (!$projectscat->isdeletable())
        {
            abort(403);
        }

        \App\Models\TaskSubCat::find($id)->delete();

        Flash::success( 'Project Task Sub Category successfully deleted' ); // 'Client successfully deleted');

        return redirect('/admin/tasksubcat');
    }

    /**
     * Delete Confirm
     *
     * @param   int   $id
     * @return  View
     */
    public function getModalDelete($id)
    {
        $error = null;

        $projectscat = \App\Models\TaskSubCat::find($id);

        if (!$projectscat->isdeletable())
        {
            abort(403);
        }

        $modal_title = ' Do you surely want to to delete this';

        $projectscat = \App\Models\TaskSubCat::find($id);
        $modal_route = route('admin.tasksubcat.delete', array('id' => $projectscat->id));

        $modal_body = trans('admin/knowledge/dialog.delete-confirm.body', ['id' => $projects->id, 'name' => $projectscat->name]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function enable($id)
    {
        $projects = $this->projectscat->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-enable', ['name' => $projects->name]));

        $projects->enabled = true;
        $projects->save();

        Flash::success(' Project category Enabled');

        return redirect('/admin/projects');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function disable($id)
    {
        //TODO: Should we protect 'admins', 'users'??

        $projects = $this->projectscat->find($id);

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-disabled', ['name' => $projects->name]));

        $projects->enabled = false;
        $projects->save();

        Flash::success('Project category disabled');

        return redirect('/admin/knowledge');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function enableSelected(Request $request)
    {
        $chkknowledge = $request->input('chkClient');

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-enabled-selected'), $chkknowledge);

        if (isset($chkknowledge))
        {
            foreach ($chkknowledge as $knowledge_id)
            {
                $knowledge = $this->knowledge->find($knowledge_id);
                $knowledge->enabled = true;
                $knowledge->save();
            }
            Flash::success(trans('admin/knowledge/general.status.global-enabled'));
        }
        else
        {
            Flash::warning(trans('admin/knowledge/general.status.no-client-selected'));
        }
        return redirect('/admin/knowledge');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function disableSelected(Request $request)
    {
        //TODO: Should we protect 'admins', 'users'??

        $chkknowledge = $request->input('chkClient');

        Audit::log(Auth::user()->id, trans('admin/knowledge/general.audit-log.category'), trans('admin/knowledge/general.audit-log.msg-disabled-selected'), $chkknowledge);

        if (isset($chkknowledge))
        {
            foreach ($chkknowledge as $knowledge_id)
            {
                $knowledge = $this->knowledge->find($knowledge_id);
                $knowledge->enabled = false;
                $knowledge->save();
            }
            Flash::success(trans('admin/knowledge/general.status.global-disabled'));
        }
        else
        {
            Flash::warning(trans('admin/knowledge/general.status.no-client-selected'));
        }
        return redirect('/admin/knowledge');
    }

    /**
     * @param Request $request
     * @return array|static[]
     */


    /**
     * @param Request $request
     * @return mixed
     */
    public function getInfo(Request $request)
    {
        $id = $request->input('id');
        $knowledge = $this->knowledgecat->find($id);

        return $knowledge;
    }

}
