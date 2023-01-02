
    <div class="panel panel-custom">
        <!-- Default panel contents -->
        <div class="panel-heading bg-purple">
            <div class="panel-title">
                <strong>{{ $userIdStatus->first_name }} {{ $userIdStatus->last_name }} Leave Balance Stats</strong>
            </div >
        </div>
        <table class="table">
            <tbody>
                

            <?php $leaves = 0; $totalLeaves = 0; ?>
            @foreach($categories->where('leave_flow','static') as $cv)
            <tr>
                <td>{{ $cv->leave_category }}</td>
                <?php
                    $leavesTaken = \TaskHelper::userLeave($userIdStatus->id, $cv->leave_category_id, date('Y'));
                    $leaves += $leavesTaken;
                    $totalLeaves += ($cv->leave_quota - $leavesTaken) 
                ?>
                <td>{{  $cv->leave_quota - $leavesTaken  }}</td>
            </tr>
            @endforeach

            @foreach($categories->where('leave_flow','dynamic') as $cv)
            <tr>
                <td>{{ $cv->leave_category }}</td>
                 <td>{{ \TaskHelper::userLeave($userIdStatus->id, $cv->leave_category_id, date('Y')) }}</td>
            </tr>
            @endforeach

        <tr class="bg-info">
                <td>Carry forward</td>
                <td> {{ TaskHelper::getCarryForwardLeave($userIdStatus->id) }} </td>
            </tr>
            <?php  $timeoff = \TaskHelper::userLeave($userIdStatus->id,env('TIME_OFF_ID') , date('Y')); ?>
            <tr>
                <td style="font-size: 14px; font-weight: bold;">
                    <strong>Time Off</strong>:
                </td>
                <td style="font-size: 14px; font-weight: bold;"> {{ 120 - $timeoff }} Min. </td>
            </tr>



            
            </tbody>
        </table>
    </div>
