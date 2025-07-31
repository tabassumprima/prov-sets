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
                        <h2 class="content-header-title float-left mb-0">Level 2 - Chart of accounts</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Chart of accounts</a>
                                </li>
                                <li class="breadcrumb-item active">View level 2
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
                            <div class="row">
                                                <div class="col-12">

                                                    <hr>
                                                </div>

                                            </div>
                            <div class="row pb-2">
                                                <div class="col-12 col-md-3">
                                                    <label class="form-label" for="period">Period &nbsp;</label>
                                                    <select class="form-control" id="period">
                                                        <option value="1">For the month</option>
                                                        <option value="3">For the quarter</option>
                                                        <option value="Q">For six months</option>
                                                        <option value="M">For the year</option>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-3">
                                                    <label class="form-label" for="portfolio">Portfolio &nbsp;</label>
                                                    <select class="form-control" id="portfolio">
                                                        <option value="ALL">All lines</option>
                                                        <option value="FR">Fire</option>
                                                        <option value="MR">Motor</option>
                                                        <option value="ME">Marine</option>
                                                        <option value="MS">Miscellaneous</option>
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-3">
                                                    <label class="form-label" for="filter">Filter &nbsp;</label>
                                                    <button type="button" class="btn btn-primary form-control" id="filter">Update</button>
                                                </div>
                                                <div class="col-12 col-md-3">
                                                    <label class="form-label" for="download">Download &nbsp;</label>
                                                    <button type="button" class="btn btn-success form-control" id="download">
                                                        <i data-feather='download'></i>
                                                        <span>Microsoft Excel</span>
                                                    </button>
                                                </div>
                                            </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>S.no</th>
                                                <th>GL Code</th>
                                                <th>Level 1</th>
                                                <th>GL Name</th>
                                                <th>GL Desc</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr><td>1</td><td>001</td><td>Other assets</td><td>Investment In Equity Securities</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>2</td><td>002</td><td>Other assets</td><td>Investment Term Deposits</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>3</td><td>003</td><td>Other assets</td><td>Investment In Debt Securities</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>4</td><td>004</td><td>Other assets</td><td>Property And Equipment </td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>5</td><td>005</td><td>Insurance contract liabilities</td><td>Liability for remaining coverage</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>6</td><td>006</td><td>Other assets</td><td>Deferred Wakala Expense</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>7</td><td>007</td><td>Other assets</td><td>Cash And Bank</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>8</td><td>008</td><td>Other assets</td><td>Cash And Bank - Ptf</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>9</td><td>009</td><td>Other assets</td><td>Cash And Bank - Opf</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>10</td><td>010</td><td>Other assets</td><td>Insurance / Reinsurance Receivables</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>11</td><td>011</td><td>Other assets</td><td>Loan And Other Receivables</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>12</td><td>012</td><td>Other assets</td><td>Accrued Investment Income - Ptf</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>13</td><td>013</td><td>Other assets</td><td>Prepayments </td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>14</td><td>014</td><td>Other assets</td><td>Salvage Recoveries Accrued </td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>15</td><td>015</td><td>Insurance service expense</td><td>Gross claim expense</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>16</td><td>016</td><td>Other assets</td><td>Prepayments - Ptf</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>17</td><td>017</td><td>Other assets</td><td>Taxation - Provision Less Payment</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>18</td><td>018</td><td>Other assets</td><td>Other Creditors And Accruals</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>19</td><td>019</td><td>Other assets</td><td>Receivable From Opf / Ptf</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>20</td><td>020</td><td>Other liabilities</td><td>Ordinary Share Capital</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>21</td><td>021</td><td>Other liabilities</td><td>Reserves</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>22</td><td>022</td><td>Insurance contract liabilities</td><td>Liability for incurred claims</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>23</td><td>023</td><td>Reinsurance contract asset</td><td>Liability for incurred claims</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>24</td><td>024</td><td>Other liabilities</td><td>Other Creditors And Accruals - Ptf</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>25</td><td>025</td><td>Other liabilities</td><td>Other Creditors And Accruals</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>26</td><td>026</td><td>Other liabilities</td><td>Property And Equipment </td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>27</td><td>027</td><td>Other liabilities</td><td>Investment In Equity Securities</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>28</td><td>028</td><td>Other liabilities</td><td>Insurance / Reinsurance Receivables</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>29</td><td>029</td><td>Other liabilities</td><td>Retirement Benefit Obligations</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>30</td><td>030</td><td>Other liabilities</td><td>Management Expenses</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>31</td><td>031</td><td>Other liabilities</td><td>Loan And Other Receivables</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>32</td><td>032</td><td>Other liabilities</td><td>Prepayments </td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>33</td><td>033</td><td>Other liabilities</td><td>Other Creditors And Accruals - Shf</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>34</td><td>034</td><td>Other liabilities</td><td>Taxation - Provision Less Payment</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>35</td><td>035</td><td>Other liabilities</td><td>Provision For Taxtation</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>36</td><td>036</td><td>Other liabilities</td><td>Deferred Taxation</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>37</td><td>037</td><td>Other liabilities</td><td>Unearned Wakala Fee</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>38</td><td>038</td><td>Other liabilities</td><td>  Unearned Reinsurance Commission </td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>39</td><td>039</td><td>Other liabilities</td><td>Payable To Opf / Ptf</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>40</td><td>040</td><td>Other liabilities</td><td>Borrowings</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>41</td><td>041</td><td>Other income</td><td>Investment Income</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>42</td><td>042</td><td>Other income</td><td>Other Income</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>43</td><td>043</td><td>Other income</td><td>Management Expenses</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>44</td><td>044</td><td>Other income</td><td>Wakala Fee</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>45</td><td>045</td><td>Other expenses</td><td>Management Expenses</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>46</td><td>046</td><td>Other expenses</td><td>Other Creditors And Accruals</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>47</td><td>047</td><td>Other expenses</td><td>Finance Costs</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>48</td><td>048</td><td>Other expenses</td><td>Other Expenses</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>49</td><td>049</td><td>Other expenses</td><td>Zakat</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>50</td><td>050</td><td>Other expenses</td><td>Investment Income</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>51</td><td>051</td><td>Other expenses</td><td>Direct Expenses</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>52</td><td>052</td><td>Insurance service expense</td><td>Acquisition expense</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>53</td><td>053</td><td>Insurance revenue</td><td>Earned premium</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>54</td><td>054</td><td>Reinsurance expense</td><td>Earned ceded premium</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>55</td><td>055</td><td>Other expenses</td><td>Gross Wakala Fee / Expense</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>56</td><td>056</td><td>Other revenue</td><td>Deferred Wakala Expense</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>57</td><td>057</td><td>Reinsurance income</td><td>Claim recovery income</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>58</td><td>058</td><td>Other revenue</td><td>Management Expenses</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>59</td><td>059</td><td>Other revenue</td><td>Investment Income</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>60</td><td>060</td><td>Other revenue</td><td>Change in OCI</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>
                                        <tr><td>61</td><td>061</td><td>Other revenue</td><td>Earned premium</td><td>To record insurance liabilities</td><td><a href='#'><i data-feather='edit'></i></a>&nbsp;<a href='#'><i data-feather='x-circle'></i></a> </td></tr>




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
                                        name="gl-code" aria-label="gl-code" maxlength="2" />
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
