@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="{{asset('css/department.css')}}">
@endsection
@section('title')
| Departments
@endsection
@section('content')
@inject('accountDetailModel', 'Modules\HR\Entities\AccountDetail')
@inject('userModel', 'App\Models\User')
@inject('roleModel', 'Spatie\Permission\Models\Role')
@inject('departmentModel', 'Modules\HR\Entities\Department')


{{-- Board Members --}}
<div class="pg-orgchart">
    <div class="org-chart">
        <?php
            $roleMembers = $roleModel::where('name', 'Board Members')->first();

            $boardMembers = [];
            foreach ($userModel::all() as $key => $value) {
                if($value->hasRole('Board Members')){
                    $boardMembers[] = $value;
                }
            }
        ?>
        <div style="position: absolute">{{$roleMembers ? $roleMembers->name : ''}}</div>
        <ul>
            @foreach ($boardMembers as $item)
            <?php $accountDetail = $item->accountDetail()->first(); ?>
            <li>
                <div class="user">
                    <img src="{{ $accountDetail->avatar ? $accountDetail->avatar->getUrl('thumb') : asset('images/default.png') }}"
                        class="img-responsive" />
                    <a class="name text-danger d-block" href="{{route('hr.admin.account-details.show', $accountDetail->select('id')->first()->id)}}">{{$accountDetail->fullname}}</a>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
{{-- Board Members --}}



<div class="pg-orgchart">
    <div class="org-chart">
        <ul>
        @foreach ($departmentModel::all() as $department)
        @if ($department->department_name != 'Board Members')
        <div class="pg-orgchart">
            <div class="org-chart">
                <li>
                    <div class="user">
                        <div class="name">{{$department->department_name}}</div>
                    </div>
                </li>
                <?php
                    $departmentHead = $department->department_head()->first() ? $department->department_head->accountDetail()->first() : '';
                ?>
                @if ($departmentHead)
                <li>
                    <div class="user">
                        <img src="{{ $departmentHead->avatar ? $departmentHead->avatar->getUrl('thumb') : asset('images/default.png') }}"
                            class="img-responsive" />
                        <div class="name">{{$departmentHead->designation ? ($departmentHead->designation->department ? $departmentHead->designation->department()->first()->department_name : '') : ''}}</div>
                        <a class="manager" href="{{route('hr.admin.account-details.show', $departmentHead->id)}}">{{$departmentHead->fullname}}</a>
                    </div>
                    <ul>
                        <?php
                            $designations = $department->departmentDesignations()->get();

                            $groupedLeaders = $designations->groupBy('designation_leader_id')->toArray();
                        ?>
                        @foreach ($groupedLeaders as $key => $designation)

                        @if ($key)

                        <?php $leader = $accountDetailModel->where('user_id', $key)->first(); ?>

                        <li>
                            @if ($leader)
                            <div class="user" style="background-color: #ccc;">
                                <img src="{{ $leader->avatar ? $leader->avatar->getUrl('thumb') : asset('images/default.png') }}"
                                class="img-responsive" />
                                {{-- <div class="name">{{$leader->designation ? $leader->designation->department()->first()->department_name : ''}}</div> --}}
                                <div class="name">{{$leader->designation()->first()->designation_name}}</div>
                                <a class="manager" href="{{route('hr.admin.account-details.show', $leader->user()->select('id')->first()->id)}}">{{$leader->fullname}}</a>
                            </div>
                            <ul>
                                <?php
                                    $designationIds = [];
                                        foreach ($designation as $key => $des) {
                                            $designationIds[] = $des['id'] ?? '';
                                        }
                                    $usersDesignation = $accountDetailModel->whereIn('designation_id', $designationIds)->get();
                                ?>
                                @if ($usersDesignation)
                                @foreach ($usersDesignation as $item)
                                @if ($item && $item->id != $leader->id)
                                <li>
                                    <div class="user">
                                        <img src="{{ $item->avatar ? $item->avatar->getUrl('thumb') : asset('images/default.png') }}"
                                            class="img-responsive" />
                                        {{-- <div class="name">{{$item->designation->department()->first()->department_name}}</div> --}}
                                        <div class="name">{{$item->designation()->first()->designation_name}}</div>
                                        <a class="manager" href="{{route('hr.admin.account-details.show', $item->user()->select('id')->first()->id)}}">{{$item->fullname}}</a>
                                    </div>
                                </li>
                                @endif
                                @endforeach
                                @endif
                            </ul>
                            @endif
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </li>
                @else
                {{-- <ul>
                    <li>
                        <div class="user">
                            <img src="https://s3.amazonaws.com/uifaces/faces/twitter/marcosmoralez/128.jpg"
                                class="img-responsive" />
                            <div class="name">Roy Lemarie</div>
                            <div class="role">System Architect</div>
                            <a class="manager" href="#">Jahn Philson Doe</a>
                        </div>
                    </li>
                    <li>
                        <div class="user">
                            <img src="https://s3.amazonaws.com/uifaces/faces/twitter/jina/128.jpg"
                                class="img-responsive" />
                            <div class="name">Eloisa Stubbolo</div>
                            <div class="role">System Architect</div>
                            <a class="manager" href="#">Jahn Philson Doe</a>
                        </div>
                    </li>
                </ul> --}}
                @endif
            </div>
        </div>
        @endif
        @endforeach
        </ul>

    </div>
</div>


@endsection
