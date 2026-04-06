<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ServerConfig;
use App\Models\BankEmployee;
use Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('*',function($view){
            if(Session::has('superAdmin')):
                $employee = BankEmployee::find(Session::get('superAdmin'));
            elseif(Session::has('generalAdmin')):
                $employee = BankEmployee::find(Session::get('generalAdmin'));
            elseif(Session::has('manager')):
                $employee = BankEmployee::find(Session::get('manager'));
            elseif(Session::has('cashier')):
                $employee = BankEmployee::find(Session::get('cashier'));
            else:
                $employee = null;
            endif;
            
            if(!empty($employee)):
                $employee_id    = $employee->id;
                $creator        = $employee->creator;
            else:
                $employee_id    = null;
                $creator        = "";
            endif;

            $server = null;
            if(!empty($employee_id)):
                $server = ServerConfig::where('employee_id', $employee_id)
                    ->orWhere('employee_id', $creator)
                    ->first();
            endif;
            
            $view->with(['serverData'=>$server,'employee'=> $employee, 'employee_id'=>$employee_id]);
        });
    }
}
