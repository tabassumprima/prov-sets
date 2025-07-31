<?php

namespace Database\Seeders;

use App\Models\BranchInfo;
use App\Models\Criteria;
use App\Models\DocumentReference;
use App\Models\GlCode;
use App\Models\InsuranceType;
use App\Models\JournalEntry;
use App\Models\Portfolio;
use App\Models\VoucherType;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Faker\Generator;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DatabaseConfigSeeder::class,
            CountrySeeder::class,
            CurrencySeeder::class,
            // OrganizationSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            EntrySeeder::class,
            // ChartOfAccountSeeder::class,
            // GlCodeSeeder::class,
            UserSeeder::class,
            UserHasRoleSeeder::class,
            // SystemDepartmentSeeder::class,
            // BusinessTypeSeeder::class,
            // BranchSeeder::class,
            StatusSeeder::class,
            // CriteriaSeeder::class,
            // PortfolioSeeder::class,
            // InsuranceTypeSeeder::class,
            // DocumentTypeSeeder::class,
            // EndorsementTypeSeeder::class,
            // TransactionTypeSeeder::class,
            // EntryTypeSeeder::class,
            // ProductCodeSeeder::class,
            // VoucherTypeSeeder::class,
            // ProfitCenterSeeder::class,
            // AccountingYearSeeder::class,
            CohortSeeder::class,
            MeasurementModelSeeder::class,
            // JournalEntrySeeder::class,
            // JournalEntryGroupingSeeder::class
            PlanSeeder::class,
            FeatureSeeder::class,
            SubscriptionSeeder::class,
            // EventSeeder::class,
        ]);
    }
}
