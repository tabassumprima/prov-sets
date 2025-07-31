@extends('user.layouts.app')

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">Level 1 - Chart of accounts</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Chart of accounts</a>
                                </li>
                                <li class="breadcrumb-item active">View level 1
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">


            <!-- Basic tabs start -->
            <section id="basic-tabs-components">
                <div class="row match-height">
                    <!-- Basic Tabs starts -->
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row w-100">

                                    <div class="col-12 text-right">

                                        <button class="btn add-new btn-primary" tabindex="0" type="button"
                                            data-toggle="modal" data-target="#modals-slide-in"><i
                                                data-feather='plus'></i><span> Add new</span></button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>S.no</th>
                                                <th>GL Code</th>
                                                <th>GL Name</th>
                                                <th>GL Desc</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>11</td>
                                                <td>Insurance contract liabilities</td>
                                                <td>To record insurance liabilities</td>
                                                <td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i
                                                            data-feather='x-circle'></i></a> </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>31</td>
                                                <td>Insurance revenue</td>
                                                <td>To record insurance liabilities</td>
                                                <td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i
                                                            data-feather='x-circle'></i></a> </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>41</td>
                                                <td>Insurance service expense</td>
                                                <td>To record insurance liabilities</td>
                                                <td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i
                                                            data-feather='x-circle'></i></a> </td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>22</td>
                                                <td>Other assets</td>
                                                <td>To record insurance liabilities</td>
                                                <td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i
                                                            data-feather='x-circle'></i></a> </td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>42</td>
                                                <td>Other expenses</td>
                                                <td>To record insurance liabilities</td>
                                                <td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i
                                                            data-feather='x-circle'></i></a> </td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>32</td>
                                                <td>Other income</td>
                                                <td>To record insurance liabilities</td>
                                                <td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i
                                                            data-feather='x-circle'></i></a> </td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>12</td>
                                                <td>Other liabilities</td>
                                                <td>To record insurance liabilities</td>
                                                <td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i
                                                            data-feather='x-circle'></i></a> </td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>33</td>
                                                <td>Other revenue</td>
                                                <td>To record insurance liabilities</td>
                                                <td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i
                                                            data-feather='x-circle'></i></a> </td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>21</td>
                                                <td>Reinsurance contract asset</td>
                                                <td>To record insurance liabilities</td>
                                                <td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i
                                                            data-feather='x-circle'></i></a> </td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>42</td>
                                                <td>Reinsurance expense</td>
                                                <td>To record insurance liabilities</td>
                                                <td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i
                                                            data-feather='x-circle'></i></a> </td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>52</td>
                                                <td>Reinsurance income</td>
                                                <td>To record insurance liabilities</td>
                                                <td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i
                                                            data-feather='x-circle'></i></a> </td>
                                            </tr>



                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Basic Tabs ends -->
                </div>
                <!-- Modal to add new user starts-->
                <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                    <div class="modal-dialog">
                        <form class="add-new-user modal-content pt-0" action="/chart-of-accounts/level-1">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                            <div class="modal-header mb-1">
                                <h5 class="modal-title" id="exampleModalLabel">New level 1 item</h5>
                            </div>
                            <div class="modal-body flex-grow-1">
                                <div class="form-group">
                                    <label class="form-label" for="gl-code">Gl code</label>
                                    <input type="number" class="form-control" id="gl-code" placeholder="11"
                                        name="gl-code" aria-label="gl-code" max="2"/>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="gl-name">Gl name</label>
                                    <input type="text" class="form-control" id="gl-name" placeholder="Insurance contract assets"
                                        name="gl-name" aria-label="gl-name" />
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="gl-desc">Gl description</label>
                                    <input type="text" class="form-control" id="gl-desc" placeholder="To record insurance contract assets"
                                        name="gl-desc" aria-label="gl-desc" />
                                </div>

                                <button type="submit" class="btn btn-primary mr-1 data-submit">Create</button>
                                <button type="reset" class="btn btn-outline-secondary"
                                    data-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal to add new user Ends-->
            </section>

        </div>
    </div>
</div>
<!-- END: Content-->
@endSection

@section('page-css')

@endSection

@section('scripts')

@endSection
