@extends('admin.maindesign') {{-- Extend your admin layout --}}

@section('content')
        <section class="no-padding-top no-padding-bottom">
          <div class="container-fluid">
            <div class="row">
<div class="col-md-3 col-sm-6">
    <a href="{{ route('users') }}" class="statistic-link">
        <div class="statistic-block block">
            <div class="progress-details d-flex align-items-end justify-content-between">
                <div class="title">
                    <div class="icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <strong>User</strong>
                </div>
                <div class="number dashtext-1">{{ $userCount }}</div>
            </div>
            <div class="progress progress-template">
                <div role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"
                    class="progress-bar progress-bar-template dashbg-1"></div>
            </div>
        </div>
    </a>
</div>
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
                <div class="number dashtext-1">{{ $mappedUsersCount}}</div>
            </div>
            <div class="progress progress-template">
                <div role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"
                    class="progress-bar progress-bar-template dashbg-1"></div>
            </div>
        </div>
    </a>
</div>

              <div class="col-md-3 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-paper-and-pencil"></i></div><strong>New Invoices</strong>
                    </div>
                    <div class="number dashtext-3">140</div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-3"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6">
                <div class="statistic-block block">
                  <div class="progress-details d-flex align-items-end justify-content-between">
                    <div class="title">
                      <div class="icon"><i class="icon-writing-whiteboard"></i></div><strong>All Projects</strong>
                    </div>
                    <div class="number dashtext-4">41</div>
                  </div>
                  <div class="progress progress-template">
                    <div role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar-template dashbg-4"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        @endsection
