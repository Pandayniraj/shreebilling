<?php

namespace App;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Error;
use App\Models\Organization;
use App\Models\Role;
use App\Models\Setting as SettingModel;
use App\Traits\UserHasPermissionsTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Shanmuga\LaravelEntrust\Traits\LaravelEntrustUserTrait as EntrustUserTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;
use YoHang88\LetterAvatar\LetterAvatar;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, JWTSubject
{
    use HasFactory;
    use Notifiable;
    use Authenticatable {
        Authenticatable::getRememberToken as authenticatableGetRememberToken;
        Authenticatable::setRememberToken as authenticatableSetRememberToken;
    }
    use CanResetPassword;
    use EntrustUserTrait, UserHasPermissionsTrait {
        EntrustUserTrait::hasRole as entrustUserTraitHasRole;
        UserHasPermissionsTrait::can insteadof EntrustUserTrait;
        UserHasPermissionsTrait::boot insteadof EntrustUserTrait;
        UserHasPermissionsTrait::hasPermission insteadof EntrustUserTrait;
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'username', 'email', 'password', 'auth_type', 'enabled', 'departments_id', 'designations_id', 'imap_email', 'imap_password', 'image', 'department_head', 'org_id', 'super_manager', 'phone', 'dob', 'ledger_id', 'project_id', 'payroll_method','work_station','division','position','first_line_manager','second_line_manager','int_designation'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The accessor to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name'];

    /**
     * Handle on the users settings class.
     *
     * @var Setting
     */
    protected $settings = null;

    /**
     * Eloquent hook to HasMany relationship between User and Audit.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    

    public function audits()
    {
        return $this->hasMany(\App\Models\Audit::class);
    }

    public function userDetail()
    {
        return $this->belongsTo(\App\Models\UserDetail::class, 'id', 'user_id');
    }

    /**
     * Eloquent hook to HasMany relationship between User and Error.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function errors()
    {
        return $this->hasMany(Error::class);
    }

    /**
     * Alias to eloquent many-to-many relation's sync() method.
     *
     * @param array $attributes
     */
    private function assignMembership(array $attributes = [])
    {
        if (array_key_exists('role', $attributes) && ($attributes['role'])) {
            $this->roles()->sync($attributes['role']);
        } else {
            $this->roles()->sync([]);
        }
    }

    public function clocking_status()
    {
        $attendance_log = \App\Models\Attendance::where('user_id', $this->id)->where('clocking_status', '1')->first();
        if (! $attendance_log) {
            $status = 'in';
        } else {
            $status = 'out';
        }

        return $status;
    }

    /**
     * Alias to eloquent many-to-many relation's sync() method.
     *
     * @param array $attributes
     */
    private function assignPermission(array $attributes = [])
    {
        if (array_key_exists('perms', $attributes) && ($attributes['perms'])) {
            $this->permissions()->sync($attributes['perms']);
        } else {
            $this->permissions()->sync([]);
        }
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }

    /**
     * @return string
     */
    public function getFullNameAndUsernameAttribute()
    {
        return "$this->first_name $this->last_name ($this->username)";
    }

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * @return bool
     */
    public function isRoot()
    {
        // Protect the root user from edits.
        if ('root' == $this->username) {
            return true;
        }
        // Otherwise
        return false;
    }

     /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the root user from deletion.
        if ('root' == $this->username) {
            return false;
        }
        // Prevent user from deleting his own account.
        if (Auth::check() && (Auth::user()->id == $this->id)) {
            return false;
        }
        // Otherwise
        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the root user from deletion.
        if ('root' == $this->username) {
            return false;
        }
        // Prevent user from deleting his own account.
        if (Auth::check() && (Auth::user()->id == $this->id)) {
            return false;
        }
        // Otherwise
        return true;
    }

    /**
     * @return bool
     */
    public function canBeDisabled()
    {
        // Protect the root user from being disabled.
        if ('root' == $this->username) {
            return false;
        }
        // Prevent user from disabling his own account.
        if (Auth::check() && (Auth::user()->id == $this->id)) {
            return false;
        }
        // Otherwise
        return true;
    }

    /**
     * Force the user to have the given role.
     *
     * @param $roleName
     */
    public function forceRole($roleName)
    {
        // If the user is not a member to the given role,
        if (null == $this->roles()->where('name', $roleName)->first()) {
            // Load the given role and attach it to the user.
            $roleToForce = Role::where('name', $roleName)->first();
            $this->roles()->attach($roleToForce->id ?? null);
        }
    }

    /**
     * Code copy of EntrustUserTrait::hasRole(...) with the one addition to,
     * optionally, check if a role is enabled before returning true.
     *
     * @param $name
     * @param bool $requireAll
     * @return bool
     */
    public function hasRole($name, $requireAll = false, $mustBeEnabled = true)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && ! $requireAll) {
                    return true;
                } elseif (! $hasRole && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the roles were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the roles were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->roles as $role) {
                if ($role->name == $name) {
                    if ($mustBeEnabled) {
                        if ($role->enabled) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Overwrite Model::create(...) to save group membership if included,
     * or clear it if not. Also force membership to group 'users'.
     *
     * @param array $attributes
     * @return User
     */
    // public static function create(array $attributes = [])
    // {
    //     // If the auth_type is not explicitly set by the call function or module,
    //     // set it to the internal value.
    //     if (! array_key_exists('auth_type', $attributes) || ('' == ($attributes['auth_type']))) {
    //         $attributes['auth_type'] = \Config::get('settings.eloquent-ldap_label_internal');
    //     }
    //     // Call original create method from parent
    //     $user = parent::create($attributes);
    //     // Assign membership(s)
    //     $user->assignMembership($attributes);
    //     // Assign permission(s)
    //     $user->assignPermission($attributes);
    //     // Force membership to group 'users'
    //     $user->forceRole('users');

    //     return $user;
    // }

    /**
     * Overwrite Model::update(...) to save group membership if included,
     * or clear it if not. Also force membership to group 'users'.
     *
     * @param array $attributes
     * @return void
     */

    public function safeUpdate(array $attributes){

        foreach ($attributes as $key => $value) {
            $this[$key] = $value;
        }

        $this->save();
    }
    public function update(array $attributes = [],array $options = [])
    {
        if (array_key_exists('first_name', $attributes)) {
            $this->first_name = $attributes['first_name'];
        }
        if (array_key_exists('last_name', $attributes)) {
            $this->last_name = $attributes['last_name'];
        }
        if (array_key_exists('username', $attributes)) {
            if ($attributes['username'] != $this->username) {
                // Forget settings asociated to previous username. New settings will be saved bellow.
                // Setting::forget($this->settings()->prefix());
            }
            $this->username = $attributes['username'];
        }
        if (array_key_exists('email', $attributes)) {
            $this->email = $attributes['email'];
        }
        if (array_key_exists('phone', $attributes)) {
            $this->phone = $attributes['phone'];
        }
        if (array_key_exists('dob', $attributes)) {
            $this->dob = $attributes['dob'];
        }
        if (array_key_exists('image', $attributes)) {
            $this->image = $attributes['image'];
        }
        if (array_key_exists('password', $attributes)) {
            $this->password = $attributes['password'];
        }
        if (array_key_exists('departments_id', $attributes)) {
            $this->departments_id = $attributes['departments_id'];
        }
        if (array_key_exists('org_id', $attributes)) {
            $this->org_id = $attributes['org_id'];
        }
        if (array_key_exists('designations_id', $attributes)) {
            $this->designations_id = $attributes['designations_id'];
        }
        if (array_key_exists('enabled', $attributes)) {
            $this->enabled = $attributes['enabled'];
        }
        if (array_key_exists('department_head', $attributes)) {
            $this->department_head = $attributes['department_head'];
        }
        if (array_key_exists('super_manager', $attributes)) {
            $this->super_manager = $attributes['super_manager'];
        }
        if (array_key_exists('ledger_id', $attributes)) {
            $this->ledger_id = $attributes['ledger_id'];
        }
        if (array_key_exists('project_id', $attributes)) {
            $this->project_id = $attributes['project_id'];
        }
        if (array_key_exists('payroll_method', $attributes)) {
            $this->payroll_method = $attributes['payroll_method'];
        }

        if (array_key_exists('work_station', $attributes)) {
            $this->work_station = $attributes['work_station'];
        }
        if (array_key_exists('division', $attributes)) {
            $this->division = $attributes['division'];
        }
        if (array_key_exists('position', $attributes)) {
            $this->position = $attributes['position'];
        }
        if (array_key_exists('first_line_manager', $attributes)) {
            $this->first_line_manager = $attributes['first_line_manager'];
        }
        if (array_key_exists('second_line_manager', $attributes)) {
            $this->second_line_manager = $attributes['second_line_manager'];
        }
        if (array_key_exists('int_designation', $attributes)) {
            $this->int_designation = $attributes['int_designation'];
        }



        $this->save();

        // Assign membership(s)
        $this->assignMembership($attributes);
        // Assign permission(s)
        $this->assignPermission($attributes);
        // Force membership to group 'users'
        $this->forceRole('users');
        $this->forceRole('profile-managers');
        $this->forceRole('clockin-clockout');
        $this->forceRole('chat-manager');
        $this->forceRole('leave-manager');
        $this->forceRole('attendance');
        $this->forceRole('calendar');

        // Process user settings
        $this->processUserSetting("theme.".\Auth::user()->org_id,$attributes);
        // $this->processUserSetting('theme', $attributes);
        $tzIdentifiers = \DateTimeZone::listIdentifiers();
        $this->processUserSetting('time_zone', $attributes, $tzIdentifiers);
        $this->processUserSetting('time_format', $attributes);
        $this->processUserSetting('locale', $attributes);

    }

    public function updateOrg(array $attributes = [])
    {
        if (array_key_exists('org_id', $attributes)) {
            $this->org_id = $attributes['org_id'];
        }
        $this->save();
    }

    /**
     * Overwrite Model::delete() to clear/delete user settings first,
     * then invoke original delete method.
     *
     * @throws \Exception
     */
    // public function delete()
    // {
    //     $this->settings()->forget();

    //     parent::delete();
    // }

    /**
     * Implements the 'isMemberOf(...)' as required by Eloquent-LDAP by using
     * the hasRole method and ignoring the enable state of the role.
     *
     * @param $name
     * @return bool
     */
    public function isMemberOf($name)
    {
        return $this->hasRole($name, false, false);
    }

    /**
     * Implements the 'membershipList()' method as required by Eloquent-LDAP.
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function membershipList()
    {
        return $this->roles();
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designations_id', 'designations_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'departments_id', 'departments_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Projects::class, 'project_id', 'id');
    }

    public function timekeeping()
    {
        return $this->belongsTo(\App\Models\TimeKeeping::class, 'id', 'user_id');
    }

    /**
     * Returns the validation rules required to create a User.
     *
     * @return array
     */
    public static function getCreateValidationRules()
    {
        return ['username'          => 'required|unique:users',
                      'email' => 'required|email|max:255|unique:users|email:rfc,dns',
                      'first_name'        => 'required',
                      'last_name'         => 'required',
                    ];
    }

    /**
     * Returns the validation rules required to update a User.
     *
     * @return array
     */
    public static function getUpdateValidationRules($id)
    {
        return ['username'          => 'required|unique:users,username,'.$id,
                      'email'             => 'required|unique:users,email,'.$id,
                      'first_name'        => 'required',
                      'last_name'         => 'required',
                    ];
    }

    /**
     * Return the existing instance of the users settings or create a new one.
     *
     * @return Setting
     */
    public function settings()
    {
        if (null != $this->settings) {
            return $this->settings;
        } else {
            return new SettingModel('User.'.$this->username);
        }
    }

    /**
     * Save or forget a user setting with the value from the attribute list.
     * If an array of value is provided, the setting value in the attribute
     * list is looked up in the array of values for the actual value to
     * use.
     *
     * @param $settingKey
     * @param array $attributes
     * @param array $valuesArr
     */
    private function processUserSetting($settingKey, array $attributes, array $valuesArr = null)
    {
        try {
          
            // Get the value from the HTTP atributes
            $setting_selected = $attributes[$settingKey];

            if(substr( $settingKey, 0, 5 ) === "theme"){
                $setting_selected = $attributes['theme'];
            }
           
            // If not null set it otherwise forget it.
            if ('' != trim($setting_selected) || strpos($settingKey,'theme') == 0) {
    
                // If a array of values was provided, look up the real value by using the index.
                if (! is_null($valuesArr)) {
                    $setting_value = $valuesArr[$setting_selected];
                } else {
                    $setting_value = $setting_selected;
                }
                // Set the value.
                $this->settings()->set($settingKey, $setting_value);
            } else {
               
                $this->settings()->forget($settingKey);
            }
        } catch (\Exception $ex) {
            
            // Setting [$settingKey] not found in list [$attributes]?!
        }
    }

    /**
     * Scope a query to only include users of a given username.
     *
     * @param $query
     * @param $string
     * @return mixed
     */
    public function scopeOfUsername($query, $string)
    {
        return $query->where('username', $string);
    }

    /**
     * Scope a query to only include users with a given confirmation_code.
     *
     * @param $query
     * @param $string
     * @return mixed
     */
    public function scopeWhereConfirmationCode($query, $string)
    {
        return $query->where('confirmation_code', $string);
    }

    /**
     * If option enabled, send an email to the user with email validation link.
     */
    public function emailValidation()
    {
        if (\Config::get('settings.auth_email_validation')) {
            // Set or reset validation code.
            $confirmation_code = Str::random(30);
            $this->confirmation_code = $confirmation_code;
            $this->save();
            // Send email.
            Mail::send(['html' => 'emails.html.email_validation', 'text' => 'emails.text.email_validation'], ['user' => $this], function ($message) {
                $message->from(\Config::get('settings.mail_system_sender_address'), \Config::get('settings.mail_system_sender_label'));
                $message->to($this->email, $this->full_name)->subject(trans('emails.email_validation.subject', ['first_name' => $this->first_name]));
            });
        }
    }

    /**
     * If option enabled, send an email to the user to notify him of the password change.
     */
    public function emailPasswordChange()
    {
        if (\Config::get('settings.app_email_notifications')) {
            // Send an email to the user to notify him of the password change.
            Mail::send(['html' => 'emails.html.password_changed', 'text' => 'emails.text.password_changed'], ['user' => $this], function ($message) {
                $message->from(\Config::get('settings.mail_system_sender_address'), \Config::get('settings.mail_system_sender_label'));
                $message->to($this->email, $this->full_name)->subject(trans('emails.password_changed.subject'));
            });
        }
    }

    /**
     * Set of method to prevent setting the remember auth token at the user model.
     * Based on code picked up from: https://laravel.io/index.php/forum/05-21-2014-how-to-disable-remember-token.
     */
    public function getRememberToken()
    {
        if (\Config::get('settings.auth_enable_remember_token')) {
            return $this->authenticatableGetRememberToken();
        } else {
            return null; // not supported
        }
    }

    public function setRememberToken($value)
    {
        if (\Config::get('settings.auth_enable_remember_token')) {
            $this->authenticatableSetRememberToken($value);
        } else {
            // not supported
        }
    }

    /**
     * Overrides the method to ignore the remember token.
     */
    public function setAttribute($key, $value)
    {
        if (\Config::get('settings.auth_enable_remember_token')) {
            parent::setAttribute($key, $value);
        } else {
            // Filter out remember token.
            $isRememberTokenAttribute = $key == $this->getRememberTokenName();
            if (! $isRememberTokenAttribute) {
                parent::setAttribute($key, $value);
            }
        }
    }

    public function getAvatarAttribute()
    {
        return new LetterAvatar($this->first_name.' '.$this->last_name);
    }

        public function assign_default_role(){

        $this->forceRole('users');
        $this->forceRole('profile-managers');
        $this->forceRole('clockin-clockout');
        $this->forceRole('chat-manager');
        $this->forceRole('leave-manager');
        $this->forceRole('attendance');
        $this->forceRole('calendar');
        $this->forceRole('normal-user');

        return 1;
    }
}
