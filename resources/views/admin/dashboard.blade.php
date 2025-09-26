@extends('admin.maindesign') {{-- Extend your admin layout --}}

@section('content')
    <section class="no-padding-top no-padding-bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('add.user') }}" class="statistic-link">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title">
                                    <div class="icon"><i class="fa-solid fa-user-plus""></i></div><strong>Create
                                        User</strong>
                                </div>
                                <div class="number dashtext-3">{{ $userCount }}</div>
                            </div>
                            <div class="progress progress-template">
                                <div role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0"
                                    aria-valuemax="100" class="progress-bar progress-bar-template dashbg-3"></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('users') }}" class="statistic-link">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title">
                                    <div class="icon">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                    <strong>All User</strong>
                                </div>
                                <div class="number dashtext-1">{{ $userCount }}</div>
                            </div>
                            <div class="progress progress-template">
                                <div role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0"
                                    aria-valuemax="100" class="progress-bar progress-bar-template dashbg-1"></div>
                            </div>
                        </div>
                    </a>
                </div>
                @php
                    $mappedPercentage = $userCount > 0 ? ($mappedUsersCount / $userCount) * 100 : 0;
                @endphp
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('view.user.shops') }}" class="statistic-link">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title">
                                    <div class="icon">
                                        <i class="fa-solid fa-user-check"></i>
                                    </div>
                                    <strong>User Maping</strong>
                                </div>
                                <div class="number dashtext-1">{{ $mappedUsersCount }}</div>
                            </div>
                            <div class="progress progress-template">
                                <div role="progressbar" style="width: {{ $mappedPercentage }}%"
                                    aria-valuenow="{{ $mappedPercentage }}" aria-valuemin="0" aria-valuemax="100"
                                    class="progress-bar progress-bar-template dashbg-1"></div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('user.collectionreport') }}" class="statistic-link">
                        <div class="statistic-block block">
                            <div class="progress-details d-flex align-items-end justify-content-between">
                                <div class="title">
                                    <div class="icon">
                                        <i class="fa-solid fa-indian-rupee-sign"></i>
                                    </div>
                                    <strong>Collection Report</strong>
                                </div>
                                <div class="number dashtext-1">{{ $totalShops }}</div>
                            </div>
                            <div class="progress progress-template">
                                <div role="progressbar" style="width: {{ $shopEntryPercent }}%"
                                    aria-valuenow="{{ $shopEntryPercent }}" aria-valuemin="0" aria-valuemax="100"
                                    class="progress-bar progress-bar-template dashbg-3"></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
