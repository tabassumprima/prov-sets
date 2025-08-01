<?php

return [

    'models' => [

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */

        'permission' => Spatie\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */

        'role' => Spatie\Permission\Models\Role::class,

    ],

    'table_names' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'roles' => 'roles',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'permissions' => 'permissions',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your models permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_permissions' => 'model_has_permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your models roles. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_roles' => 'model_has_roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [
        /*
         * Change this if you want to name the related pivots other than defaults
         */
        'role_pivot_key' => null, //default 'role_id',
        'permission_pivot_key' => null, //default 'permission_id',

        /*
         * Change this if you want to name the related model primary key other than
         * `model_id`.
         *
         * For example, this would be nice if your primary keys are all UUIDs. In
         * that case, name this `model_uuid`.
         */

        'model_morph_key' => 'model_id',

        /*
         * Change this if you want to use the teams feature and your related model's
         * foreign key is other than `team_id`.
         */

        'team_foreign_key' => 'team_id',
    ],

    /*
     * When set to true the package implements teams using the 'team_foreign_key'. If you want
     * the migrations to register the 'team_foreign_key', you must set this to true
     * before doing the migration. If you already did the migration then you must make a new
     * migration to also add 'team_foreign_key' to 'roles', 'model_has_roles', and
     * 'model_has_permissions'(view the latest version of package's migration file)
     */

    'teams' => false,

    /*
     * When set to true, the required permission names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */

    'display_permission_in_exception' => false,

    /*
     * When set to true, the required role names are added to the exception
     * message. This could be considered an information leak in some contexts, so
     * the default setting is false here for optimum safety.
     */

    'display_role_in_exception' => false,

    /*
     * By default wildcard permission lookups are disabled.
     */

    'enable_wildcard_permission' => false,

    'cache' => [

        /*
         * By default all permissions are cached for 24 hours to speed up performance.
         * When permissions or roles are updated the cache is flushed automatically.
         */

        'expiration_time' => \DateInterval::createFromDateString('24 hours'),

        /*
         * The cache key used to store all permissions.
         */

        'key' => 'spatie.permission.cache',

        /*
         * You may optionally indicate a specific cache driver to use for permission and
         * role caching using any of the `store` drivers listed in the cache.php config
         * file. Using 'default' here means to use the `default` set in cache.php.
         */

        'store' => 'default',
    ],

    //Custom Permissions
    'permissions' => [
        'Finance' => [
            //provision setting
            'Provision' => [
                // 'view-provision',
                // 'approve-provision',
                // 'delete-provision',
                'execute-provision',
            ],

            //provision output
            'Provision Output' => [
                'view-provision-output',
            ],

            //summary setting
            'Summaries' => [
                'view-summary',
                'approve-summary',
                'delete-summary',
            ],

            //summary setting
            'Approve Entries' => [
                'view-approve-entry',
                'approve-approve-entry',
                'delete-approve-entry',
            ],

            //provision setting
            'Provision Settings' => [
                'view-provision-setting',
                'create-provision-setting',
                'delete-provision-setting',
                'status-update-provision-setting',
            ],

            //jorunal entries
            'Journal Entries' => [
                'view-journal-entry',
                'create-journal-entry',
                'delete-journal-entry',
                'approve-journal-entry',
            ],

            // chart of accounts
            'Chart Of Accounts' => [
                'view-chart-of-account',
                'update-coa',
                'update-coa-level',
            ],

            // General Ledger
            'General Ledger' => [
                'view-general-ledger',
            ],

            // Trial Balance
            'Trial Balance' => [
                'view-trial-balance',
            ],

            'disclosure' => [
                'view-disclosure',
            ]
        ],
        'Actuarial' => [
            //discount rate
            'Discount Rates' => [
                'view-discount-rate',
                'create-discount-rate',
                'update-status-discount-rate',
            ],
             //gmm discount rate
            'Discount Rates Gmm' => [
                'view-discount-rate-gmm',
            ],
               //gmm discount rate file
            'Discount Rates File Gmm' => [
                'view-discount-rate-file-gmm',
                'download-discount-rate-file',
            ],
            //Ibnr assumption
            'Ibnr Assumptions' => [
                'view-ibnr-assumption',
                'create-ibnr-assumption',
                'update-status-ibnr-assumption',
            ],

            //claim pattern
            'Claim Patterns' => [
                'view-claim-pattern',
                'create-claim-pattern',
                'update-status-claim-pattern',
            ],

            //risk adjustment
            'Risk Adjustments' => [
                'view-risk-adjustment',
                'create-risk-adjustment',
                'update-status-risk-adjustment',
            ],

            //discount rate files
            'Discount Rate Files' => [
                'view-discount-rate-file',
                'create-discount-rate-file',
                'update-discount-rate-file',
                'delete-discount-rate-file',
                'download-discount-rate-file',
            ],

            //Ibnr assumption files
            'Ibnr Assumption Files' => [
                'view-ibnr-assumption-file',
                'create-ibnr-assumption-file',
                'update-ibnr-assumption-file',
                'delete-ibnr-assumption-file',
                'download-ibnr-assumption-file',
            ],

            //claim pattern files
            'Claim Pattern Files' => [
                'view-claim-pattern-file',
                'create-claim-pattern-file',
                'update-claim-pattern-file',
                'delete-claim-pattern-file',
                'download-claim-pattern-file',
            ],

            //risk adjustment files
            'Risk Adjustment Files' => [
                'view-risk-adjustment-file',
                'create-risk-adjustment-file',
                'update-risk-adjustment-file',
                'delete-risk-adjustment-file',
                'download-risk-adjustment-file',
            ],
        ],
        'Insurance Grouping' => [
            /**  Insurance **/
            //Pofilio
            'Insurance Portfolios' => [
                'view-insurance-portfolio',
                'create-insurance-portfolio',
                'update-insurance-portfolio',
                'delete-insurance-portfolio',
            ],

            //porfolio criteria
            'Insurance Portfolio Criteria' => [
                'view-insurance-portfolio-criteria',
                'create-insurance-portfolio-criteria',
                'delete-insurance-portfolio-criteria',
            ],
            //group
            'Insurance Groups' => [
                'view-insurance-group',
                'create-insurance-group',
                'delete-insurance-group',
            ],
        ],
        'Reinsurance Grouping' => [
            /**  Re-Insurance **/
            //Pofilio
            'Re-insurance Portfolios' => [
                'view-re-insurance-portfolio',
                'create-re-insurance-portfolio',
                'update-re-insurance-portfolio',
                'delete-re-insurance-portfolio',
            ],

            //porfolio criteria
            'Re-insurance Portfolio Criteria' => [
                'view-re-insurance-portfolio-criteria',
                'create-re-insurance-portfolio-criteria',
                'delete-re-insurance-portfolio-criteria',
            ],

            //group
            'Re-insurance Groups' => [
                'view-re-insurance-group',
                'create-re-insurance-group',
                'delete-re-insurance-group',
            ],
        ],
        'Miscellaneous' => [
            //Mix
            'Mix' => [
                "regenerate-group-codes",
            ],

            // Reports
            'Reports' => [
                "view-reports"
            ],

            // Calenders
            'Calender' => [
                'view-calender'
            ],

            // Report Issues
            'Report Issues' => [
                'view-report-issue',
                'create-report-issue',
                'update-report-issue',
                'delete-report-issue',
            ]

        ]
        //not in use then remove can or replace can with authorize
        // "update sync data timer",
        // "sync data",
        // "revert data import",
        // "change report format",
        //Manage user
    ],
];
