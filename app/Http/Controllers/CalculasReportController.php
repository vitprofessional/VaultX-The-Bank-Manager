<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankCapital;
use App\Models\BankEmployee;
use App\Models\DebitCredit;
use Session;

class CalculasReportController extends Controller
{
    public function generateReport(){
        return view('adminPanel.reportGenerate');
    }
    
    public function getData(Request $requ){
        $requestedEmployeeId = $requ->employeeId;
        $currentAdminId = $this->currentAdminId();
        $currentAdmin = BankEmployee::find($currentAdminId);
        
        // Super admin can view all records; others can only view their own
        if($currentAdmin && $currentAdmin->profileType == 1) {
            // Super admin - allow access to requested employee's records
            $employee_id = $requestedEmployeeId;
            if (empty($employee_id)) {
                return back()->with('error', 'Please select a cashier to generate report.');
            }

            $isCashier = BankEmployee::where('id', $employee_id)->where('profileType', 4)->exists();
            if (!$isCashier) {
                return back()->with('error', 'Invalid cashier selected for report generation.');
            }
        } else {
            // Other roles - restrict to their own records
            $employee_id = $currentAdminId;
        }
        
        $cDate          = date_create($requ->reportDate);
        $dateToday      = date_format($cDate,'Y-m-d');
        
        $data   = BankCapital::whereDate('created_at',$dateToday)->where(['employee_id'=>$employee_id])->first();

        $debit  = DebitCredit::whereDate('created_at',$dateToday)
            ->where(['employee_id'=>$employee_id])
            ->where(['txnType'=>'Debit'])
            ->orderBy('id','DESC')
            ->get();

        $credit = DebitCredit::whereDate('created_at',$dateToday)
            ->where(['txnType'=>'Credit','employee_id'=>$employee_id])
            ->orderBy('id','DESC')
            ->get();

        $creditTotal    = $credit->sum('amount');
        $debitTotal     = $debit->sum('amount');

        return view('adminPanel.reportGenerate',['data'=>$data,'debit'=>$debit,'credit'=>$credit,'creditTotal'=>$creditTotal,'debitTotal'=>$debitTotal,'reportDate'=>$requ->reportDate,'reportDate'=>$dateToday]);
    }
    
    private function currentAdminId(): ?int
    {
        if (Session::has('superAdmin')) {
            return BankEmployee::find(Session::get('superAdmin'))?->id;
        }

        if (Session::has('generalAdmin')) {
            return BankEmployee::find(Session::get('generalAdmin'))?->id;
        }

        if (Session::has('manager')) {
            return BankEmployee::find(Session::get('manager'))?->id;
        }

        if (Session::has('cashier')) {
            return BankEmployee::find(Session::get('cashier'))?->id;
        }

        return null;
    }
}
