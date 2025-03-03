@extends('layout.master')

@section('title' , 'Smarven Contact')

@section('content')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4" style="overflow: hidden; height: auto;">
                    <div class="wrapper">
                        <div class="card">
                            <div class="d-flex justify-content-between align-items-center card-header pb-0">
                                <h6>About Smarven</h6>
                                <div class="mb-1">
                                    <a class="btn btn-success" href="{{route('about.edit' , $siteSetting->id)}}"><i class="fas fa-pen"></i>&nbsp;&nbsp;Edit Information</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-striped table-bordered">
                                        <thead>
                                            <th scope="col" width="10%">#</th>
                                            <th scope="col" width="20%">Information</th>
                                        </thead>
                                        <tr>
                                            <td>site name</td>
                                            <td>{{$siteSetting->title}}</td>
                                        </tr>
                                        <tr>
                                            <td>tagline</td>
                                            <td>{{$siteSetting->tagline}}</td>
                                        </tr>
                                        <tr>
                                            <td>Owner</td>
                                            <td>{{"Mr." . $siteSetting->user->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>meta_title</td>
                                            <td>{{$siteSetting->meta_title}}</td>
                                        </tr>
                                        <tr>
                                            <td>Markets</td>
                                            <td>
                                                {{ implode(' - ', $siteSetting->markets->pluck('market')->toArray()) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Phones</td>
                                            <td>
                                                {{ implode(' - ', $siteSetting->phones->pluck('phone')->toArray()) }}
                                            </td>
                                        </tr>
                                            <tr>
                                                <td>Website Link</td>
                                                <td>
                                                    {{config('app.url')}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Admin Link</td>
                                                <td>
                                                    {{config('app.url')}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Redirecting</td>
                                                <td>
                                                    @if($siteSetting->enable_redirecting == 1)
                                                        <span class='badge bg-warning'>Enabled</span>
                                                    @else
                                                        <span class='badge bg-primary'>Disabled</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tracking</td>
                                                <td>
                                                    @if($siteSetting->enable_tracking == 1)
                                                        <span class='badge bg-warning'>Enabled</span>
                                                    @else
                                                        <span class='badge bg-primary'>Disabled</span>
                                                    @endif
                                                </td>
                                            </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
