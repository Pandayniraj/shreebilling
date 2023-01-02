  <table class="table table-triped DatsaTables  dataTable no-footer dtr-inline" id="DataTables">
                              <thead>
                                 <tr>
                                    <th>Payroll Department: {{$payroll->department->deptname}}</th>
                                    <th>Payroll Month: {{$payroll->date}}</th>
                                 </tr>
                                 <tr>
                                    <td style="background-color: skyblue;" class="text-bold col-sm-1">EMP ID</td>
                                    <td style="background-color: skyblue;" class="text-bold">Name</td>
                                    <td style="background-color: skyblue;" class="text-bold">Attendance</td>
                                    <td style="background-color: skyblue;" class="text-bold">Annual Leave</td>
                                    <td style="background-color: skyblue;" class="text-bold col-sm-1">Sick Leave</td>
                                    <td style="background-color: skyblue;" class="text-bold">PHL</td>
                                    <td style="background-color: skyblue;" class="text-bold">MOL/ML/PL</td>
                                    <td style="background-color: skyblue;" class="text-bold">LWP</td>
                                    <td style="background-color: skyblue;" class="text-bold">Absent</td>
                                    <td style="background-color: skyblue;" class="text-bold">1/2 Day Late</td>
                                    <td style="background-color: skyblue;" class="text-bold"> Total </td>
                                    <td style="background-color: skyblue;" class="text-bold"> Payble Attendance </td>
                                    <td style="background-color: skyblue;" class="text-bold"> OT Hours </td>
                                    <td style="background-color: skyblue;" class="text-bold"> T.Basic </td>
                                    <td style="background-color: skyblue;" class="text-bold"> Others Allowance </td>
                                    <td style="background-color: skyblue;" class="text-bold"> D.A </td>
                                    <td style="background-color: skyblue;" class="text-bold">Total</td>
                                    <td style="background-color: skyblue;" class="text-bold">2 hrs additional</td>
                                    <td style="background-color: skyblue;" class="text-bold">Total Salary</td>
                                    <td style="background-color: skyblue;" class="text-bold">Salary for the month</td>
                                    <td style="background-color: skyblue;" class="text-bold">Working Days Basic</td>
                                    <td style="background-color: skyblue;" class="text-bold">OT Amount</td>
                                    <td style="background-color: skyblue;" class="text-bold">Errors Adjust</td>
                                    <td style="background-color: skyblue;" class="text-bold">Gratuity</td>
                                    <td style="background-color: skyblue;" class="text-bold">PF</td>
                                    <td style="background-color: skyblue;" class="text-bold">CTC</td>
                                    <td style="background-color: skyblue;" class="text-bold">Adv.</td>
                                    <td style="background-color: skyblue;" class="text-bold">P.F</td>
                                    <td style="background-color: skyblue;" class="text-bold">CIT</td>
                                    <td style="background-color: skyblue;" class="text-bold">Uniform Deduction</td>
                                    <td style="background-color: skyblue;" class="text-bold">Monthly Payable Amount</td>
                                    <td style="background-color: skyblue;" class="text-bold">SST</td>
                                    <td style="background-color: skyblue;" class="text-bold">Net Salary</td>
                                    <td style="background-color: skyblue;" class="text-bold">Remarks</td>
                                 </tr>
                              </thead>
                              <tbody>
                                  <?php
                                    $total_basic_sal = 0;
                                    $total_net_sal = 0;
                                    $total_overtime = 0;  
                                    $total_fine = 0;
                                    $total_sal = 0;
                                ?>
                         
                                @foreach($payrolldetails as $sk => $sv)
                                 @if($sv->user_id == $user_id)
                                       <tr>
                                          <td class="col-sm-1">{{ $sv->user_id }}
                                          </td>
                                          <td>{{ $sv->user->first_name.' '.$sv->user->last_name }}</td>
                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  Attendance">{{$sv->attendance}}</td>
                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | Annual leave">{{$sv->anual_leave}}</td> 
                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | sick leave">{{$sv->sick_leave}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | phl">{{$sv->phl}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  mol ml pl">{{$sv->mol_ml_pl}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | lwp">{{$sv->lwp}}</td>
                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  absent">{{$sv->absent}}</td>
                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | late half days">{{$sv->late_half_days}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} total attendance">{{$sv->total_days}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | payable attendance">{{$sv->payable_attendance}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  ot hours">{{$sv->ot_hours}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  t basic">{{$sv->t_basic}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | other allowance">{{$sv->other_allowance}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} da">{{$sv->da}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | total after allowannce">{{$sv->total_after_allowance}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | additional attendance two hours">{{$sv->additional_attendance_two_hours}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  total salary">{{$sv->total_salary}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  salary for month">{{$sv->salary_for_the_month}}</td>
                                        
                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | Working days basic">{{$sv->working_days_basic}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | ot amount">{{$sv->ot_amount}}</td>
                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  error adjustment">{{$sv->error_adjust}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | Gratuity">{{$sv->gratuity}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  PF">{{$sv->pf}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | ctc">{{$sv->ctc}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | adv">{{$sv->adv}}</td>
                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}| pf after ctc">{{$sv->pf_after_ctc}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  cit">{{$sv->cit}}</td>
                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  Uniform deduction">{{$sv->uniform_deduction}}</td>
                                          
                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }}|  Monthly payable amount">{{$sv->monthly_payable_amount}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} sst">{{$sv->sst}}</td>

                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | net salary">{{$sv->net_salary}}</td>
                                          <td title = "{{ $sv->first_name.' '.$sv->last_name }} | remarks">{{$sv->remarks}}</td>
                                       </tr>
                                     

                                      <?php
                                          $total_basic_sal = $total_basic_sal + $template->basic_salary;
                                          $total_net_sal = $total_net_sal + $net_salary;
                                          $total_overtime =  $total_overtime+$overtime_money;  

                                      ?>
                                 @endif
                                 @endforeach

                              </tbody>
                                 {{--}} <tr>
                                    <td colspan="2"></td>
                                    <td style="float: right">Total</td>
                                    <td>{{ $total_basic_sal }}</td>
                                    <td>{{ $total_net_sal }}</td>
                                    <td>{{ $total_overtime }}</td>
                                    <td>{{ $total_fine }}</td>
                                    <td>{{ $total_sal }}</td>
                                 </tr> --}}
                           </table>