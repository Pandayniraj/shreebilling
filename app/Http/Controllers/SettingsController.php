<?php

namespace App\Http\Controllers;

use App\Exceptions\FileNotFoundException;
use App\Http\Helpers\TaskHelper;
use App\Libraries\FlashLevel;
use App\Libraries\Utils;
use App\Models\Audit as Audit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Laracasts\Flash\Flash;
use Setting;

class SettingsController extends Controller
{
    public function __construct(Application $app, Audit $audit)
    {
        parent::__construct($app, $audit);
        // Set default crumbtrail for controller.
        session(['crumbtrail.leaf' => 'setting']);
    }

    public function index()
    {
        Audit::log(Auth::user()->id, trans('admin/settings/general.audit-log.category'), trans('admin/settings/general.audit-log.msg-index'));

        $page_title = trans('admin/settings/general.page.index.title'); // "Admin | Settings";
        $page_description = trans('admin/settings/general.page.index.description'); // "List of Settings";

//        $settings = (new SettingModel())->all();
        $settings = Setting::all();
        $settings = Arr::dot($settings);
        $settingsFiltered = Utils::FilterOutUserSettings($settings);
        $settingsFiltered = Arr::sortRecursive($settingsFiltered);

        return view('admin.settings.index', compact('settingsFiltered', 'page_title', 'page_description'));
    }

    public function show($key)
    {
        $value = Setting::get($key);

        if (is_bool($value)) {
            $value = ($value) ? 'true' : 'false';
        }

        Audit::log(Auth::user()->id, trans('admin/settings/general.audit-log.category'), trans('admin/settings/general.audit-log.msg-show', ['key' => $key]));

        $page_title = trans('admin/settings/general.page.show.title');
        $page_description = trans('admin/settings/general.page.show.description', ['key' => $key]);

        return view('admin.settings.show', compact('key', 'value', 'page_title', 'page_description'));
    }

    public function create()
    {
        $page_title = trans('admin/settings/general.page.create.title');
        $page_description = trans('admin/settings/general.page.create.description');

        $key = '';
        $value = '';

        return view('admin.settings.create', compact('key', 'value', 'page_title', 'page_description'));
    }

    public function store(Request $request)
    {
        $this->validate($request, ['key'       => 'required',
                                        'value'     => 'required',
                                        'encrypted' => 'required', ]);

        $attributes = $request->all();

        Audit::log(Auth::user()->id, trans('admin/settings/general.audit-log.category'), trans('admin/settings/general.audit-log.msg-store', ['key' => $attributes['key']]));

        Setting::set($attributes['key'], $attributes['value'], $attributes['encrypted']);

        Flash::success(trans('admin/settings/general.status.created'));

        return redirect('/admin/settings');
    }

    public function edit($key)
    {
        $value = Setting::get($key);

        if (is_bool($value)) {
            $value = ($value) ? 'true' : 'false';
        }

        $page_title = trans('admin/settings/general.page.edit.title');
        $page_description = trans('admin/settings/general.page.edit.description', ['key' => $key]);

        Audit::log(Auth::user()->id, trans('admin/settings/general.audit-log.category'), trans('admin/settings/general.audit-log.msg-edit', ['key' => $key]));

        return view('admin.settings.edit', compact('key', 'value', 'page_title', 'page_description'));
    }

    public function update(Request $request)
    {
        $this->validate($request, ['orgKey'    => 'required',
                                    'key'       => 'required',
                                    'value'     => 'required',
                                    'encrypted' => 'required', ]);

        $attributes = $request->all();
        $orgKey = $attributes['orgKey'];
        $key = $attributes['key'];
        $value = $attributes['value'];
        $encrypted = $attributes['encrypted'];

        Audit::log(Auth::user()->id, trans('admin/settings/general.audit-log.category'), trans('admin/settings/general.audit-log.msg-update', ['key' => $key]));

        Setting::set($key, $value, $encrypted);
        if ($orgKey != $key) {
            Setting::forget($orgKey);
        }

        Flash::success(trans('admin/settings/general.status.updated'));

        return redirect('/admin/settings');
    }

    public function destroy($key)
    {
        Setting::forget($key);

        Audit::log(Auth::user()->id, trans('admin/settings/general.audit-log.category'), trans('admin/settings/general.audit-log.msg-destroy', ['key' => $key]));

        Flash::success(trans('admin/settings/general.status.deleted'));

        return redirect('/admin/settings');
    }

    public function getModalDelete($key)
    {
        $error = null;

        $modal_title = trans('admin/settings/dialog.delete-confirm.title');

        $modal_route = route('admin.settings.delete', ['key' => $key]);

        $modal_body = trans('admin/settings/dialog.delete-confirm.body', ['key' => $key]);

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function load()
    {
        $envName = env('APP_ENV', 'production');

        try {
            $cnt = Setting::load($envName);
            if (0 == $cnt) {
                Utils::flashAndAudit(trans('admin/settings/general.audit-log.category'), trans('admin/settings/general.status.no-settings-loaded', ['env' => $envName]), FlashLevel::WARNING);
            } else {
                Utils::flashAndAudit(trans('admin/settings/general.audit-log.category'), trans('admin/settings/general.status.settings-loaded', ['number' => $cnt, 'env' => $envName]), FlashLevel::SUCCESS);
            }
        } catch (FileNotFoundException $fnfx) {
            Utils::flashAndAudit(trans('admin/settings/general.audit-log.category'), trans('admin/settings/general.status.settings-file-not-found', ['env' => $envName]), FlashLevel::ERROR);
        }

        return redirect('/admin/settings');
    }

    public function backupDB()
    {
        $backup_name = \TaskHelper::backup_tables(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'), env('DB_PORT'));

        //dd($backup_name);
        if ($backup_name != 0) {
            DB::table('backup')->insert(['name' => $backup_name, 'created_at' => date('Y-m-d H:i:s')]);

            Audit::log(Auth::user()->id, 'Database Backup', 'Backup Created. Name: '.$backup_name.'');
            \Flash::success('Successfully BackedUp');
        }

        return redirect()->intended('/admin/backup/list');
    }

    public function backupList()
    {
        $data['menu'] = 'setting';
        $data['sub_menu'] = 'general';
        $data['list_menu'] = 'backup';
        $data['path'] = storage_path();
        $data['backupData'] = DB::table('backup')->orderBy('id', 'desc')->get();

        Audit::log(Auth::user()->id, 'Database Backup', 'Accessed datbase  Backup Page');

        return view('admin.settings.backupList', $data);
    }

    public function destroyBackup($id)
    {
        if (isset($id)) {
            $record = \DB::table('backup')->where('id', $id)->first();
            if ($record) {
                DB::table('backup')->where('id', '=', $id)->delete();

                unlink(storage_path('/laravel-backups/'.$record->name));

                Audit::log(Auth::user()->id, 'Database Backup', 'Destroyed  Backup Name: '.$record->name.'');

                \Flash::success('Deleted Successfully');

                return redirect()->intended('/admin/backup/list');
            }
        }
    }

    public function downloadbackup($file_name)
    {

        //dd(storage_path());

        $path = storage_path().'/laravel-backups/'.$file_name;

        if (file_exists($path)) {
            Audit::log(Auth::user()->id, 'Database Backup', 'Downloaded Databse  Backup. Name: '.$file_name.'');

            return \Response::download($path);
        }
    }
}
