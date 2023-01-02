<!DOCTYPE html>
<html>
<head>
    <title>{{ env('APP_COMPANY')}} | Event Booking PDF</title>

    <style type="text/css">
        @font-face {
            font-family: Arial;
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #AAAAAA;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 5px;
        }

        table th,
        table td {
            padding: 3px;
            background: ;
            text-align: left;
            border-bottom: 1px solid #FFFFFF;
        }

        tr td {
            padding-top: 5px;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: left;
        }

        table td h3 {
            color: #57B223;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .no {
            color: #FFFFFF;
            font-size: 1em;
            background: #57B223;
        }

        table .desc {
            text-align: left;
        }

        table .unit {
            background: #DDDDDD;
        }

        table .qty {}

        table .total {
            background: #57B223;
            color: #FFFFFF;
        }

        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            padding: 5px 10px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1em;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr:last-child td {
            color: #57B223;
            font-size: 1em;
            border-top: 1px solid #57B223;
            font-weight: bold;
        }

        table tfoot tr td:first-child {
            border: none;
        }

        #thanks {
            font-size: 2em;
            margin-bottom: 50px;
        }

        #notices {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
        }

        #notices .notice {
            font-size: 1.2em;
        }

        footer {
            color: #777777;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            border-top: 1px solid #AAAAAA;
            padding: 8px 0;
            text-align: center;
        }

    </style>

</head>
<body>
    <header class="clearfix">
        <table>
            <tr>
                <td width="50%" style="float:left">
                    <div id="logo">
                        <h4 class="name">{{ env('APP_COMPANY') }} </h4>
                        <div>{{ env('APP_ADDRESS1') }}</div>
                        <div>{{ env('APP_ADDRESS2') }}</div>
                        <div>Seller's PAN: {{ \Auth::user()->organization->vat_id }}</div>
                        <div><a href="mailto:{{ env('APP_EMAIL') }}">{{ env('APP_EMAIL') }}</a></div>
                    </div>
                </td>
                <td width="50%" style="text-align: right">
                    <div id="company">
                        <img style="max-width: 150px" src="{{env('APP_URL')}}/{{ 'org/'.$organization->logo }}">
                    </div>
                </td>
            </tr>
        </table>
    </header>

    <?php 
                   $skills = explode(',', $user_detail->skills);     
                ?>
    <main>
        <div id="details" class="clearfix">
            <h3>Personal Details of: {{$user_name}}</h3>
            <table>
                <tr>

                    <td>Father's Name: <u>@if($user_detail->father_name) <strong>{{ucwords($user_detail->father_name)}} </strong>@else _________________ @endif</u></td>
                    <td>Mother's Name: <u>@if($user_detail->mother_name) <strong>{{ucwords($user_detail->mother_name)}} </strong>@else _________________ @endif</u></td>
                    <td>Blood Group: <u>@if($user_detail->blood_group) <strong>{{ucwords($user_detail->blood_group)}} </strong>@else _________________ @endif</u></td>
                </tr>
                <tr>

                    <td>Present Address: <u>@if($user_detail->present_address) <strong>{{ucwords($user_detail->present_address)}} </strong>@else _________________ @endif</u></td>
                    <td>Gender: <u>@if($user_detail->gender) <strong>{{ucwords($user_detail->gender)}} </strong>@else _________________ @endif</u></td>
                    <td>Food: <u>@if($user_detail->food) <strong>{{ucwords($user_detail->food)}} </strong>@else _________________ @endif</u></td>
                </tr>
                <tr>
                    <td>Marital Status: <u>@if($user_detail->marital_status) <strong>{{ucwords($user_detail->marital_status)}} </strong>@else _________________ @endif</u></td>
                    <td>Education: <u>@if($user_detail->education) <strong>{{ucwords($user_detail->education)}} </strong>@else _________________ @endif</u></td>
                    <td>Skills: <u>@if($user_detail->skills) <strong>
                                @foreach($skills as $key => $value)
                                {{$value}} ,
                                @endforeach
                            </strong>@else _________________ @endif</u></td>
                </tr>
                <tr>
                    <td>Date Of Birth: <u>@if($user_detail->date_of_birth) <strong>{{ucwords($user_detail->date_of_birth)}} </strong>@else _________________ @endif</u></td>
                    <td>Nationality: <u>@if($user_detail->nationality) <strong>{{ucwords($user_detail->nationality)}} </strong>@else _________________ @endif</u></td>
                    <td>License Number: <u>@if($user_detail->license_number) <strong>{{ucwords($user_detail->license_number)}} </strong>@else _________________ @endif</u></td>
                </tr>
                <tr>
                    <td>Job Title: <u>@if($user_detail->job_title) <strong>{{ucwords($user_detail->job_title)}} </strong>@else _________________ @endif</u></td>
                    <td>Employment Type: <u>@if($user_detail->employemnt_type) <strong>{{ucwords($user_detail->employemnt_type)}} </strong>@else _________________ @endif</u></td>
                </tr>

            </table>
            <h3>Emergency Contact</h3>
            <table>
                <tr>
                    <td>Emergency Name: <u>@if($user_detail->emergency_contact_name) <strong>{{ucwords($user_detail->emergency_contact_name)}} </strong>@else _________________ @endif</u></td>
                    <td>Relationship: <u>@if($user_detail->relationship) <strong>{{ucwords($user_detail->relationship)}} </strong>@else _________________ @endif</u></td>
                    <td>Mobile: <u>@if($user_detail->mobile) <strong>{{ucwords($user_detail->mobile)}} </strong>@else _________________ @endif</u></td>
                </tr>

                <tr>
                    <td>Work Phone: <u>@if($user_detail->work_phone) <strong>{{ucwords($user_detail->work_phone)}} </strong>@else _________________ @endif</u></td>

                </tr>
            </table>

            <h3>Dependents</h3>
            <table>
                @foreach($user_dependents as $ud)

                <tr>
                    <td> Name: <u>@if($ud->name) <strong>{{ucwords($ud->name)}} </strong>@else _________________ @endif</u></td>
                    <td>Relationship: <u>@if($ud->relationship) <strong>{{ucwords($ud->relationship)}} </strong>@else _________________ @endif</u></td>
                    <td>DOB: <u>@if($ud->dob) <strong>{{ucwords($ud->dob)}} </strong>@else _________________ @endif</u></td>
                </tr>

                @endforeach
            </table>
            <h3>Education</h3>
            <table>
                @foreach($user_education as $ud)

                <tr>
                    <td> level: <u>@if($ud->level) <strong>{{ucwords($ud->level)}} </strong>@else _________________ @endif</u></td>
                    <td>Institute: <u>@if($ud->institute) <strong>{{ucwords($ud->institute)}} </strong>@else _________________ @endif</u></td>
                    <td>Major: <u>@if($ud->major) <strong>{{ucwords($ud->major)}} </strong>@else _________________ @endif</u></td>
                    <td>Year: <u>@if($ud->year) <strong>{{ucwords($ud->year)}} </strong>@else _________________ @endif</u></td>
                    <td>Score: <u>@if($ud->score) <strong>{{ucwords($ud->score)}} </strong>@else _________________ @endif</u></td>
                    <td>Start Date: <u>@if($ud->start_date) <strong>{{ucwords($ud->start_date)}} </strong>@else _________________ @endif</u></td>
                    <td>End Date: <u>@if($ud->end_date) <strong>{{ucwords($ud->end_date)}} </strong>@else _________________ @endif</u></td>
                </tr>

                @endforeach

            </table>

            <h3>Work Experience</h3>
            <table>
                @foreach($user_work_experience as $uwd)
                <tr>
                    <td> Company: <u>@if($uwd->company) <strong>{{ucwords($uwd->company)}} </strong>@else _________________ @endif</u></td>
                    <td>Job Title: <u>@if($uwd->job_title) <strong>{{ucwords($uwd->job_title)}} </strong>@else _________________ @endif</u></td>
                    <td>From: <u>@if($uwd->date_from) <strong>{{ucwords($uwd->date_from)}} </strong>@else _________________ @endif</u></td>
                    <td>To: <u>@if($uwd->date_to) <strong>{{ucwords($uwd->date_to)}} </strong>@else _________________ @endif</u></td>
                    <td>Comment: <u>@if($uwd->comment) <strong>{{ucwords($uwd->comment)}} </strong>@else _________________ @endif</u></td>
                </tr>
                @endforeach

            </table>


            <h3>Direct Deposit</h3>
            <table>
                <tr>
                    <td> Amount: <u>@if($user_detail->amount) <strong>{{ucwords($user_detail->amount)}} </strong>@else _________________ @endif</u></td>
                    <td>Bank Account No: <u>@if($user_detail->bank_account_no) <strong>{{ucwords($user_detail->bank_account_no)}} </strong>@else _________________ @endif</u></td>
                    <td>Bank Amount Branch: <u>@if($user_detail->bank_account_branch) <strong>{{ucwords($user_detail->bank_account_branch)}} </strong>@else _________________ @endif</u></td>
                </tr>
                <tr>
                    <td> Bank Name: <u>@if($user_detail->bank_name) <strong>{{ucwords($user_detail->bank_name)}} </strong>@else _________________ @endif</u></td>
                    <td>Bank Account Name: <u>@if($user_detail->bank_account_name) <strong>{{ucwords($user_detail->bank_account_name)}} </strong>@else _________________ @endif</u></td>
                    <td>Routing No: <u>@if($user_detail->routing_num) <strong>{{ucwords($user_detail->routing_num)}} </strong>@else _________________ @endif</u></td>
                </tr>
            </table>

            <h3>Service Information</h3>

            <table>
                <tr>
                    <td>Contract Start Date: <u>@if($user_detail->contract_start_date) <strong>{{ucwords($user_detail->contract_start_date)}} </strong>@else _________________ @endif</u></td>
                    <td>Contract End Date: <u>@if($user_detail->contract_end_date) <strong>{{ucwords($user_detail->contract_end_date)}} </strong>@else _________________ @endif</u></td>
                    <td>Join Date: <u>@if($user_detail->join_date) <strong>{{ucwords($user_detail->join_date)}} </strong>@else _________________ @endif</u></td>
                </tr>
                <tr>
                    <td>Date Of Probation: <u>@if($user_detail->date_of_probation) <strong>{{ucwords($user_detail->date_of_probation)}} </strong>@else _________________ @endif</u></td>
                    <td>Date of Permanent: <u>@if($user_detail->date_of_permanent) <strong>{{ucwords($user_detail->date_of_permanent)}} </strong>@else _________________ @endif</u></td>
                    <td>Last Promotion Date: <u>@if($user_detail->last_promotion_date) <strong>{{ucwords($user_detail->last_promotion_date)}} </strong>@else _________________ @endif</u></td>
                </tr>
                <tr>
                    <td>Last Transfer Date: <u>@if($user_detail->last_transfer_date) <strong>{{ucwords($user_detail->last_transfer_date)}} </strong>@else _________________ @endif</u></td>
                    <td>Date Of Retirement: <u>@if($user_detail->date_of_retirement) <strong>{{ucwords($user_detail->date_of_retirement)}} </strong>@else _________________ @endif</u></td>
                    <td>Working Status: <u>@if($user_detail->working_status) <strong>{{ucwords($user_detail->working_status)}} </strong>@else _________________ @endif</u></td>
                </tr>
            </table>

            <h3>Disabled Information</h3>

            <table>
                <tr>
                    <td>Team: <u>@if($team_name) <strong>{{ucwords($team_name)}} </strong>@else _________________ @endif</u></td>
                    <td>Organization: <u>@if($user->organization->organization_name) <strong>{{ucwords($user->organization->organization_name)}} </strong>@else _________________ @endif</u></td>
                    <td>Department: <u>@if($user->department) <strong>{{ucwords($user->department->deptname)}} </strong>@else _________________ @endif</u></td>
                </tr>
                <tr>
                    <td>designation: <u>@if($user->designation) <strong>{{ucwords($user->designation->designations)}} </strong>@else _________________ @endif</u></td>
                </tr>
            </table>


        </div>
    </main>
    <footer>
        PIS Card was created on MeroCrm Software.
    </footer>


</body>
</html>
