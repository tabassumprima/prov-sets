<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

require __DIR__ . '/auth.php';
#Disable registration
Route::any('/register', function () {
    abort(403);
});

Route::post('/2fa', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect(route('tenant.index'));
    } else {
        return redirect(route('user.dashboard'));
    }
})->name('2fa')->middleware('2fa');

// Otp Verification
Route::group(['middleware' => 'auth'], function(){
    Route::get('confirm-email','App\Http\Controllers\Auth\OtpVerificationEmailController@index')->name('otpVerify');
    Route::post('code/store','App\Http\Controllers\Auth\OtpVerificationEmailController@store')->name('otp.store');
    Route::get('resend/email','App\Http\Controllers\Auth\OtpVerificationEmailController@resendEmail')->name('resendEmail');
});
// Otp Verification End

Route::group([
    'namespace' => 'App\Http\Controllers\User',
    'middleware' => ['auth', 'notAdmin', '2fa', 'provision', 'active.plan'],
], function () {

    Route::resource('user', UserController::class)->only(['edit', 'update']);
    //Dashboard And Impersonating Route
    Route::get('dashboard-v1', 'DashboardController@index');
    Route::get('dashboard',    'DashboardController@dashboardV2')->name('user.dashboard');
    Route::get('filters',      'DashboardController@filters')->name('user.filters');

    Route::get('calendar', 'CalendarController@index')->name('calendar.index');
    Route::post('calendar/data', 'CalendarController@show')->name('calendar.show');

    Route::get('fetch-provision', 'DashboardController@fetchProvision')->name('user.provision');

    Route::post('run-provision', 'DashboardController@invokeProvision')->name('user.run-provision');

    Route::get('leave-impersonate', 'DashboardController@leaveImpersonate')->name('user.leave-impersonate')->withoutMiddleware('active.plan');

    Route::resource('criterias', 'CriteriaController')->except(['index', 'show', 'create']);
    Route::get('criteria/{type}', 'CriteriaController@index')->name('criteria.index');

    Route::resource('portfolios', 'PortfolioController')->except(['index', 'show', 'create']);
    Route::get('portfolio/{type}', 'PortfolioController@index')->name('portfolio.index');

    // Route::resource('criterias.portfolios', 'PortfolioController')->except(['show'])->scoped();
    Route::get('criteria/{criteria}/portfolios', 'PortfolioController@showMapping')->name('portfolio.showMapping');
    Route::post('criteria/{criteria}/portfolios', 'PortfolioController@saveMapping')->name('portfolio.saveMapping');

    Route::resource('import-detail', 'ImportDetailController')->only(['index', 'destroy']);
    Route::post('import-approve/{import}', 'ImportDetailController@approveEntries')->name('import.approve');
    Route::get('provision-approve', 'ImportDetailController@importDetailIndex')->name('importDetail.index');
    Route::get('approve-entries', 'ImportDetailController@approveEntryIndex')->name('approveEntry.index');

    Route::resource('summaries', 'SummaryController')->only(['index', 'destroy']);
    Route::post('summary-approve/{summary}', 'SummaryController@invokeImport')->name('invoke.import');
    Route::post('lock-summary-and-folder/{summary}', 'SummaryController@lockSummariesAndImport')->name('summary.approve');


    Route::resource('groups.products', 'GroupProductController')->only(['create', 'store']);
    Route::resource('groups.re-insurance', 'GroupProductReinsuranceController')->only(['index']);

    //this for group facultative
    Route::get('groups/{group}/re-insurance/facultative', 'GroupProductReinsuranceController@createFacultative')->name('facultative.create');
    Route::post('groups/{group}/re-insurance/facultative', 'GroupProductReinsuranceController@storeFacultative')->name('facultative.store');

    //this for group treaty
    Route::get('groups/{group}/re-insurance/treaty', 'GroupProductReinsuranceController@createTreaty')->name('treaty.create');
    Route::post('groups/{group}/re-insurance/treaty', 'GroupProductReinsuranceController@storeTreaty')->name('treaty.store');

    Route::resource('groups', 'GroupController')->except(['show', 'index']);
    Route::get('group/{type}', 'GroupController@index')->name('group.index');
    Route::post('groups-code-mapping', 'GroupController@generateGroupCode')->name('group.code');

    Route::resource('tickets', 'UserTicketController')->except(['show']);
    Route::resource('journal-entries', 'JournalEntryController')->except(['show']);
    Route::get('journal-entries/fetchPortfolio', 'JournalEntryController@getPortfolio')->name('journal.fetch.portfolio');
    Route::get('journal-entries/fetchGroup', 'JournalEntryController@getGroup')->name('journal.fetch.group');
    Route::get('journal-entries/fetchDepartments', 'JournalEntryController@getDepartments')->name('journal.fetch.departments');
    Route::resource('general-ledger', 'GeneralLedgerController')->except(['show']);
    Route::post('general-ledger', 'GeneralLedgerController@index')->name('general-ledger.index1');
    Route::get('general-ledger/filter', 'GeneralLedgerController@filter')->name('general-ledger.filter');
    Route::get('general-ledger/download', 'GeneralLedgerController@download')->name('general-ledger.download');
    Route::post('journal-entries/{journal}/approve', 'JournalEntryController@approve')->name('journal-entries.approve');
    Route::resource('trial-balance', 'TrialBalanceController')->only(['index']);
    Route::get('trial-balance/filter', 'TrialBalanceController@filter')->name('trial.filter');
    Route::get('trial-balance/download', 'TrialBalanceController@download')->name('trial.download');
    Route::resource('disclosure', 'DisclosureController')->only(['index']);
    Route::get('disclosure/download', 'DisclosureController@download')->name('disclosure.download');
    Route::get('reports/{slug}', 'JournalEntryGroupingController@index')->name('report.index');
    Route::get('report/show', 'JournalEntryGroupingController@report')->name('reports');
    Route::get('profit-and-loss/download', 'JournalEntryGroupingController@exportCSV')->name('report.download');
    Route::get('report/filter', 'JournalEntryGroupingController@filter')->name('report.filter');
    Route::get('accounting-year/fetch/{accounting_year}', 'JournalEntryGroupingController@fetchAccountingYear')->name('year.fetch');

    // Download Error File of Summary Route
    Route::get('download/error/{summary_id}', 'SummaryController@downloadErrorFile')->name('download-error-file');

    //Chart Of Account Route
    Route::resource('chart-of-accounts', 'ChartOfAccountController')->only(['index']);
    Route::post('chart-of-accounts/delete', 'ChartOfAccountController@deleteNode')->name('coa.delete');
    Route::post('chart-of-accounts/rebuild', 'ChartOfAccountController@rebuild')->name('coa.rebuild');

    // Chart of account levles Route
    Route::get('chart-of-accounts/{parentId}/create-level','ChartOfAccountController@create')->name('create-level');
    Route::post('/chart-of-accounts/{parentId}/levels/store', 'ChartOfAccountController@storeLevel')->name('store-level');

    Route::get('chart-of-accounts/{parentId}/edit-level/','ChartOfAccountController@editLevel')->name('edit-level');
    Route::post('chart-of-accounts/{chartOfAccountId}/update-level/{levelId}', 'ChartOfAccountController@updateLevel')->name('update-level');



    Route::resource('provision-setting', 'ProvisionSettingController')->only(['index', 'store', 'destroy']);
    Route::post('provision-setting/{provision}/status-update', 'ProvisionSettingController@statusUpdate')->name('provision.status');

    Route::resource('provision-setting.mappings', 'ProvisionMappingController')->only(['index'])->scoped();
    //this for provision of insurance
    Route::get('provision-mapping/{provision}/insurance', 'ProvisionMappingController@createInsurance')->name('provision.insurance.create');
    Route::post('provision-mapping/{provision}/insurance', 'ProvisionMappingController@storeInsurance')->name('provision.insurance.store');
    //this for provision of facultative
    Route::get('provision-mapping/{provision}/facultative', 'ProvisionMappingController@createFacultative')->name('provision.facultative.create');
    Route::post('provision-mapping/{provision}/facultative', 'ProvisionMappingController@storeFacultative')->name('provision.facultative.store');
    //this for provision of treaty
    Route::get('provision-mapping/{provision}/treaty', 'ProvisionMappingController@createTreaty')->name('provision.treaty.create');
    Route::post('provision-mapping/{provision}/treaty', 'ProvisionMappingController@storeTreaty')->name('provision.treaty.store');

    //this for provision of insurance-gmm
    Route::get('provision-mapping/{provision}/insurance-gmm', 'ProvisionMappingController@createInsuranceGmm')->name('provision.insurance_gmm.create');
    Route::post('provision-mapping/{provision}/insurance-gmm', 'ProvisionMappingController@storeInsuranceGmm')->name('provision.insurance_gmm.store');

    //this for provision of facultative-gmm
    Route::get('provision-mapping/{provision}/facultative-gmm', 'ProvisionMappingController@createFacultativeGmm')->name('provision.facultative_gmm.create');
    Route::post('provision-mapping/{provision}/facultative-gmm', 'ProvisionMappingController@storeFacultativeGmm')->name('provision.facultative_gmm.store');
    //this for provision of treaty-gmm
    Route::get('provision-mapping/{provision}/treaty-gmm', 'ProvisionMappingController@createTreatyGmm')->name('provision.treaty_gmm.create');
    Route::post('provision-mapping/{provision}/treaty-gmm', 'ProvisionMappingController@storeTreatyGmm')->name('provision.treaty_gmm.store');

    Route::resource('provision-setting.expense-allocation', 'ExpenseAllocationController')->only(['create','store']);

    Route::resource('discount-rates', 'DiscountRateController');

    Route::post('discount-rates/{discount_rates}/status-update', 'DiscountRateController@statusUpdate')->name('discount.file.status');
    Route::resource('discount-rates.files', 'DiscountRateFileController');

    //DISCOUNT RATES for GMM 
    Route::resource('discount-rates', 'DiscountRateController');

    Route::post('discount-rates/{discount_rates}/status-update', 'DiscountRateController@statusUpdate')->name('discount.file.status');
    Route::resource('discount-rates.files', 'DiscountRateFileController');


    Route::resource('ibnr-assumptions', 'IbnrAssumptionController');
    Route::post('ibnr-assumptions/{ibnr_assumptions}/status-update', 'IbnrAssumptionController@statusUpdate')->name('ibnr.file.status');
    Route::resource('ibnr-assumptions.files', 'IbnrAssumptionFileController');

    Route::resource('risk-adjustments', 'RiskAdjustmentController');
    Route::post('risk-adjustments/{risk_adjustments}/status-update', 'RiskAdjustmentController@statusUpdate')->name('risk.file.status');
    Route::resource('risk-adjustments.files', 'RiskAdjustmentFileController');

    Route::resource('claim-patterns', 'ClaimPatternController');
    Route::post('claim-patterns/{claim_patterns}/status-update', 'ClaimPatternController@statusUpdate')->name('claim.file.status');
    Route::resource('claim-patterns.files', 'ClaimPatternFileController');

    // Error File Download Route
    Route::get('download/discount/error', 'DiscountRateFileController@getErrorFile')->name('discount_rate.error.file');
    Route::get('download/ibnr/error', 'IbnrAssumptionFileController@getErrorFile')->name('ibnr_files.error.file');
    Route::get('download/risk/error', 'RiskAdjustmentFileController@getErrorFile')->name('risk_adjustment.error.file');
    Route::get('claim/pattern/error', 'ClaimPatternFileController@getErrorFile')->name('claim_pattern.error.file');
    //File Download Route
    Route::get('download/discount/{file}', 'DiscountRateFileController@getFile')->name('discount_rate.file');
    Route::get('download/ibnr/{file}', 'IbnrAssumptionFileController@getFile')->name('ibnr_files.file');
    Route::get('download/risk/{file}', 'RiskAdjustmentFileController@getFile')->name('risk_adjustment.file');
    Route::get('claim/pattern/{file}', 'ClaimPatternFileController@getFile')->name('claim_pattern.file');
    Route::get('download/coa', 'ChartOfAccountController@getFile')->name('coa.file');
    // Fetch File Route
    Route::get('fetch/ibnr/{file}','IbnrAssumptionFileController@fetchFile')->name('ibnr_files.fetch');
    Route::get('fetch/discount/{file}','DiscountRateFileController@fetchFile')->name('discount_files.fetch');
    Route::get('fetch/risk/{file}','RiskAdjustmentFileController@fetchFile')->name('risk_files.fetch');
    Route::get('fetch/claim/pattern/{file}','ClaimPatternFileController@fetchFile')->name('claim_pattern_files.fetch');

    Route::resource('provisions', 'ProvisionController')->only(['index', 'show']);
    Route::get('provisions-file/download/{import_detail}/{file_name}', 'ProvisionController@downloadFile')->name('provision_file.download');

    Route::post('fetch-opening', 'DashboardController@invokeOpening')->name('user.opening');

    // Routes for Data Import view
    Route::resource('data-import','ImportDetailController');
    Route::resource('data-import.sub_imports','SubImportController')->only(['index', 'store', 'destroy']);
    Route::post('data-import-file-store','SubImportController@storeImportFile')->name('data-import-file-store');
    Route::get('download-data-import/{sub_import_id}', 'SubImportController@getFile')->name('download-data-import');
    // Route::post('check-file-in-extract/{import_id}', 'SubImportController@checkFileInExtract')->name('check-file-in-extract');
    Route::get('check-import-status/{data_import}', 'SubImportController@getImportStatus')->name('check-import-status');
    Route::delete('delete-data-import/{sub_import_id}/', 'SubImportController@deleteFile')->name('delete-data-import');

    Route::post('check-folder-status/{folder_id}', 'SubImportController@getFolderStatus')->name('check-folder-status');


});

//Report Routes
Route::group([
    'prefix' => 'report',
    'namespace' => 'App\Http\Controllers\User\Report',
    'middleware' => ['auth', '2fa']
], function () {
    Route::get('balance-trials', 'BalanceTrialController@index')->name('balance.trial');
    Route::get('balance-trials/filter', 'BalanceTrialController@filter')->name('balance.filter');
});

//Admin Routes
Route::group([
    'prefix' => 'admin',
    'namespace' => 'App\Http\Controllers\Admin',
    'middleware' => ['auth', 'role:admin', '2fa']
], function () {
    Route::resource('tenant', 'TenantController')->only(['index']);
    Route::resource('currencies', 'CurrencyController')->except(['show']);
    Route::resource('countries', 'CountryController')->except(['show']);
    Route::resource('db_config', 'DatabaseConfigController')->except(['show']);
    Route::resource('admin-users', 'AdminUserController')->except(['show', 'create']);
    Route::prefix('admin-users')->name('users.')->group(function () {
        Route::post('{user}/edit-password', 'AdminUserController@editPassword');
        Route::post('{user}/status-update', 'AdminUserController@statusUpdate');
        Route::post('{user}/generate2fa', 'AdminUserController@generate2fa')->name('admin.generate2fa');
        Route::post('/users/welcome-mail/{id}', 'AdminUserController@welcomeMail')->name('admin.welcome-mail');
        Route::post('/users/reset-mail/{id}', 'AdminUserController@resetMail')->name('admin.reset-mail');
    });

    //Ticket Route
    Route::resource('user_ticket', 'UserTicketController');
    Route::get('tickets/{ticket}/status-update', 'UserTicketController@statusUpdate')->name('tickets.status-update');

    Route::resource('roles', 'RoleController')->except(['show']);
    Route::resource('organizations', 'OrganizationController')->only(['create', 'store']);
    Route::get('organization/{organization}/logo', 'OrganizationController@getLogo')->name('organization.logo');

    //Tenant Routes
    Route::group(['suffix' => '{org?}', 'middleware' => 'tenancy'], function () {
        Route::resource('report-format', 'FormatJsonController')->only(['index', 'create', 'store', 'destroy']);
        Route::get('download/report-format/{file}', 'FormatJsonController@getReportFormatFile')->name('report-format.file');
        Route::resource('dashboard', 'DashboardController')->only(['index']);
        Route::post('dashboard/boarding', 'DashboardController@toggleBoarding')->name('organization.boarding');
        Route::post('import-data', 'DashboardController@importDataJob')->name('importData.lambda');
        Route::get('module/status', 'DashboardController@moduleStatus')->name('module.status');
        Route::get('download/files/{file}', 'DashboardController@downloadDashboardFiles')->name('dashboard.file');
        Route::get('fetch/provision/{folder}/{file}', 'DashboardController@FetchProvisionFiles')->name('fetch-provision-file');
        Route::get('download/config/{file}', 'DashboardController@downloadConfigFile')->name('download-config-file');

        Route::resource('lambda', 'LambdaFunctionController');
        Route::resource('settings', 'SettingController')->only(['create', 'store']);
        // Route::resource('cloud-settings', 'CloudSettingController')->only(['create']);
        Route::get('cloud-settings', 'SettingController@cloudSetting')->name('cloud.setting');
        Route::post('cloud-settings/update', 'SettingController@updateCloudSetting')->name('cloud.update');
        Route::get('generate-access-key', 'SettingController@generateAccessKey')->name('generate-access-key');
        Route::delete('revoke-access-key',   'SettingController@revokeAccessKey')->name('revoke-access-key');

        Route::resource('lambda-sub-functions', 'LambdaSubFunctionController');
        Route::resource('chart-of-accounts', 'ChartOfAccountController')->except(['index', 'update']);
        Route::resource('organizations', 'OrganizationController')->except(['show', 'create', 'store', 'index']);
        Route::resource('provision-rules', 'ProvisionRuleController')->only(['index', 'store']);
        Route::resource('subscriptions', 'SubscriptionController')->only(['index', 'store', 'destroy']);
        Route::get('download/provision-rule/{module}/{file?}', 'ProvisionRuleController@getRuleFile')->name('provison_rule.file');
        Route::post('graph-json', 'ProvisionRuleController@storeGraphJson')->name('graph_json.file');
        Route::resource('import-detail-configs', 'ImportDetailConfigController');
        Route::post('import-detail-configs/rollback/', 'ImportDetailConfigController@rollBack')->name('import_detail.rollBack');
        Route::post('import-detail-configs/run', 'ImportDetailConfigController@createAndImport')->name("import.detail.create");
        Route::resource('roles', 'RoleController')->except(['show']);

        //Download Chart of Account File Route
        Route::get('download/chart-of-account', 'ChartOfAccountController@downloadFile')->name('chart-of-account.file');

        //User Routes
        Route::resource('users', 'UserController')->except(['show', 'create']);
        Route::prefix('users')->name('users.')->group(function () {
            Route::post('{user}/edit-password', 'UserController@editPassword');
            Route::post('{user}/status-update', 'UserController@statusUpdate');
            Route::post('{user}/generate2fa', 'UserController@generate2fa')->name('generate2fa');
            Route::get('{user}/impersonate', 'UserController@impersonate')->name('impersonate');
            Route::post('/users/welcome-mail/{id}', 'UserController@welcomeMail')->name('welcome-mail');
            Route::post('/users/reset-mail/{id}', 'UserController@resetMail')->name('reset-mail');
        });
    });
});
