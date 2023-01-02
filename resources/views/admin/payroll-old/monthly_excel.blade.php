 <table id="" class="table table-bordered std_table">
                        <thead>
                         <tr><th>Month</th><th>{{$payment_month}}</th></tr>
                        <tr>
                            <th class="text-bold">Name</th>
                            <th class="text-bold">Adjustment</th>
                            <th class="text-bold" title="Gross salary form salary template">Gross</th>
                            {{--<th class="text-bold" title="Deductions  form salary template">Deduction</th>--}}
                            <th class="text-bold" title="SSF deductions  form salary template">SSF Deduction</th>
                            <!-- <th class="text-bold" title="net salary form salary template">Net</th> -->
                            <!-- <th class="text-bold" title="Insurance Premium">IP</th> -->
                            <!-- <th class="text-bold" title="Remote Tax Deductions">Remote</th> -->
                            <th class="text-bold" title="Tax Ammount">TAX</th>
                            <th class="text-bold" title="Payment Status">Status</th>
                            <th class="text-bold">Net</th>
                        </tr>
                        </thead>
                             <tbody>
                                <?php $flag = 0; ?>
                                @foreach($users as $user)
                                <tr>
                                  <?php 
                                  $salary_payment = \App\Models\PaySalary::where('payment_month',$payment_month)->where('payment_year',$payment_year)->where('user_id',$user->id)->first(); 
                                  $salary_payment_status = $salary_payment->status;
                                  $template = \App\Helpers\PayrollHelper::getEmployeePayroll($user->id)->template;
                                  $emp_payroll = \App\Models\EmployeePayroll::where('user_id',$user->id)->first();
                                
                                  $remote_tax_deduction_amount = \App\Helpers\PayrollHelper::remote_tax_deduction_amount($emp_payroll->district_name);

                                  $salary_adjustment = \App\Models\PaySalary::where('payment_month',$payment_month)->where('payment_year',$payment_year)->where('user_id',$user->id)->first()->adjustment;

                                  $insurance_premium = \App\Helpers\PayrollHelper::insurance_premium($emp_payroll->insurance_premium);



                                  $ssf_contribution_percentage = env('SSF_CONTRIBUTION');


                                  $ssf_deduction_limit = env('SSF_DEDUCTIION_LIMIT');

                                      
                                       $allowances = \PayrollHelper::getSalaryAllowance($template->salary_template_id);
                                       $allowances1 = 0;
                                        foreach ($allowances as $ak => $av) {
                                            $allowances1 += $av->allowance_value;
                                        }

                                        $ssf_contribution = ($template->basic_salary * $ssf_contribution_percentage/100);

                                        $gross_salary = $template->basic_salary +  $allowances1 + $ssf_contribution;
                                        $total_adjustment = \App\Models\PaySalary::where('user_id',$user_id)->where('payment_month','<=',$payment_month)->sum('adjustment');

                                        $option_1 = (($template->basic_salary *12 * 31) / 100)+ (($emp_payroll->other_ssf)*12) ; 


                                        $option_2 = (($gross_salary * 12)+$adjustment)*0.33;

                                        $option_3 = $ssf_deduction_limit;
                                        $ssf_deduction_total = min($option_1,$option_2,$option_3);  


                                        $tax_amount1 = \App\Helpers\PayrollHelper::get_tax_amount($user->id,$payment_month,$gross_salary,$insurance_premium,$remote_tax_deduction_amount,$emp_payroll->is_disabled,$ssf_deduction_total,$emp_payroll->marrital_option);

                                         $gender = \App\Models\UserDetail::where('user_id',$user->id)->first()->gender;

                                         if($gender != 'Male' && $emp_payroll->marrital_option == 0)
                                         {
                                           $tax_amount = $tax_amount1*0.9/12;
                                         }else{
                                            $tax_amount = $tax_amount1/12;
                                         }
                                   ?>
                                   <input type="hidden" name="salary_template_id[]" value="{{$template->salary_template_id}}">
                                   <input type="hidden" name="basic_salary[]" value="{{$template->basic_salary}}">
                                   <input type="hidden" name="user_id[]" value="{{$user->id}}">
                                    <td title="{{$user->id}} &nbsp;  {{ $user->designation->designations }}">{{ $user->first_name.' '.$user->last_name }} </td>
                                    <td title="Adjustment">
                                 
                                    {{$salary_adjustment}}                    
                                                   
                                    </td>

                                    <td title="Gross salary from Salary Template"><input type="hidden" name="gross_salary[]" value="{{ $gross_salary }}">{{  round($gross_salary) }}</td>
                                  
                                    <td title="SSF Deductions"><input type="hidden" name="ssf[]" value="{{ $ssf_deduction_total }}">{{  round($option_1/12) }}
                                    </td>
                                
                                    <td><input type="hidden" name="tax_amount[]" value="{{$tax_amount}}">{{   round($tax_amount) }}</td>
                                   
                                    <td> @if($salary_payment_status == 0) Unpaid @else Paid @endif </td>

                                     <?php $total_amount = $adjustment + $gross_salary - (($option_1/12) +  $tax_amount); ?>
                                    <td><input type="hidden" name="total_amount[]" value="{{round($taxable_amount -   $tax_amount)}}">{{  round($total_amount) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                    </table>