<?php

return [
    // aws & rds
    'aws_bucket'    => env('AWS_BUCKET', 'algoryne-dev-delta-data-manual'),
    'rds_host'      => env('DB_HOST', null),
    'rds_db_name'   => env('DB_DATABASE', null),
    'rds_user'      => env('DB_USERNAME', null),
    'rds_password'  => env('DB_PASSWORD', null),
    'rds_port'      => env('DB_PORT', null),

    // Parent Level
    'chart_of_account_level' => 'DL00001',
    'unallocated_level' => 'DL00002',

    // admin user
    'email' => 'admin@admin.com',
    'password' => env('ADMIN_PASSWORD'),
    'google_2fa' => env('GOOGLE_2fa'),

    'default_roles' => 'standard|manager',
    'datetime_format' => 'd M, y - h:i A',
    'default_users' => [
        array('User|user@user.com|user123'), //name|email|password
        array('User|user1@user.com|user123'),
    ],
    'default_countries' => [
        array('United States|US'), //name|code
        array('United Kingdom|GB'),
        array('Pakistan|PK'),
        array('Australia|AU'),
        array('Saudi Arabia|KSA'),
        array('Kuwait|KW'),
        array('United Arab Emirates|AE'),
    ],
    'default_currencies' => [
        array('US Dollar|USD'), //name|symbol
        array('Euro|EUR'),
        array('Pakistani Rupee|PKR'),
        array('British Pound|GBP'),
        array('Australian Dollar|AUD'),
        array('Saudi Riyal|SAR'),
        array('Kuwaiti Dinar|KWD'),
        array('Dirham|AED'),
    ],
    'default_departments' => [
        array('13|001001|MOTOR|A'), //name|description|description_abb
        array('14|001001|MISCELLANEOUS|S'),
        array('11|001001|FIRE|F'),
        array('12|001001|ACCIDENT|A'),
        array('15|001001|THEFT|A'),
        array('16|001001|PROPERTY|A'),
    ],
    'default_organizations' => [
        array('1001|My Insurance Organization|General insurance Organization')
    ],
    'default_branch_type' => [
        array('C|Conventional'),
        array('T|Takaful')
    ],
    'default_insurance_type' => [
        array('O|Our Lead|'),
        array('D|Direct (100%)|'),
        array('I|Other Lead|'),
        array('C|Re-Insurance Ceded|'),
        array('A|Re-Insurance Accepted|'),
        array('S|Cession|'),
    ],
    'default_transaction_type' => [
        array('A|Automatic'),
        array('M|Manual'),
    ],
    'default_entry_type' => [
        array('Org|Original System'),
        array('Org-R|Original system - reversed'),
        array('M|Manual system'),
    ],
    'default_cohorts' => [
        array('A|Annual|12'), //name|months
        array('S|Semi-Annual|6'),
        array('Q|Quarterly|3'),
        array('M|Monthly|1'),
    ],
    'default_measurement_models' => [
        array('PAA|Premium Allocation Approach'), //name|symbol
        array('VFA|Variable Fee Approach'),
        array('GMM|General Measurement Model'),
        array('PAAE|PAA Eligibility Mode')
    ],
    'default_prodcut_grouping' => [
        '01',
        '02',
        '03',
        '04',
    ],
    'applicable_to' => [   //slug => title
        'insurance'     => 'Insurance',
        're-insurance' => 'Re-Insurance',
    ],
    'date_format' => [
        'set' => 'Y-m-d',
        'get' => 'M d, Y'
    ],
    'default_severities' => [
        'high'       => 'High',
        'medium'     => 'Medium',
        'low'        => 'Low',
    ],
    'default_business_types' => [
        'c' => 'Conventional',
        't'     => 'Takaful',
    ],
    'default_statuses' => [
        array('default|Started|started|success'),
        array('default|Not Started|not-started|danger'),
        array('default|Expired|expired|secondary'),
        array('default|Approved|approved|success'),
        array('default|Pending|pending|primary'),
        array('default|Revoked|revoked|danger'),
        array('default|Running|running|primary'),
        array('default|Importing|importing|primary'),
        array('default|Import Failed|importing-failed|danger'),
        array('default|Import Invalid|invalid|danger'),
        array('default|Processing|processing|primary'),
        array('default|Processing Failed|processing-failed|danger'),
        array('default|Completed|completed|success'),
        array('default|Locked|locked|danger'),
        array('default|Uploading|uploading|success'),
        array('default|Pending Import|pending_import|success'),
        array('default|Pending Summary|pending-summary|success'),
        array('default|Rollback in progress|rollback-inprogress|danger'),
        array('default|Failed|failed|danger'),
        array('default|Error|error|danger'),
        array('criteria|Started|started|success'),
        array('criteria|Not Started|not-started|danger'),
        array('criteria|Expired|expired|secondary'),
        array('group|Started|started|success'),
        array('group|Not Started|not-started|danger'),
        array('group|Expired|expired|secondary'),
        array('provision|Started|started|success'),
        array('provision|Approved|approved|success'),
        array('provision|Pending|pending|success'),
        array('provision|Expired|expired|secondary'),
        array('provision|Not Started|not-started|danger'),
        array('provision|Revoked|revoked|danger'),
        array('provision|Processing|processing|primary'),
        array('provision|Processing Failed|processing-failed|danger'),
        array('import|Started|started|success'),
        array('import|Approved|approved|success'),
        array('import|Pending|pending|success'),
        array('import|Expired|expired|secondary'),
        array('import|Not Started|not-started|danger'),
        array('import|Importing|importing|primary'),
        array('import|Import Failed|importing-failed|danger'),
        array('import|Import Invalid|invalid|danger'),
        array('import|Completed|completed|success'),
        array('posting|Started|started|success'),
        array('posting|Approved|Approved|success'),
        array('posting|Pending|pending|success'),
        array('posting|Expired|expired|secondary'),
        array('posting|Not Started|not-started|danger'),
        array('posting|Posting|posting|primary'),
        array('posting|Post Failed|post-failed|danger'),
    ],

    'default_financial_year' => [
        "January-December" => 'January - December',
        "February-January" => 'February - January',
        "March-February" => 'March - February',
        "April-March"  => 'April - March',
        "May-April" => 'May - April',
        "June-May" => 'June - May',
        "July-June" => 'July - June',
        "August-July" => 'August - July',
        "September-August" => 'September - August',
        "October-September"  => 'October - September',
        "November-October" => 'November - October',
        "December-January"  => 'December - January',
    ],
    's3_paths' => [
        "organization_path"     => '/',

        "logo"                  => 'logo/tenant_id=?',
        "chart_of_accounts"     => 'configs/tenant_id=?/rules/chart_of_accounts',
        "import_config"         => 'configs/',
        "provision_rules"       => [
            "ibnr_assumptions"  => 'configs/tenant_id=?/rules/provisions/ibnr_assumptions/',
            "risk_adjustments"  => 'configs/tenant_id=?/rules/provisions/risk_adjustments/',
            "discount_rates"    => 'configs/tenant_id=?/rules/provisions/discount_rates/',
            "claim_patterns"    => 'configs/tenant_id=?/rules/provisions/claim_patterns/',
            "lambda_commands"    => 'configs/tenant_id=?/rules/provisions/lambda_commands/',
        ],
        "provision_files"       => [
            "output"            => 'files/tenant_id=?/provisions/',
            "ibnr_assumptions"  => 'files/tenant_id=?/provisions/ibnr_assumptions/',
            "risk_adjustments"  => 'files/tenant_id=?/provisions/risk_adjustments/',
            "discount_rates"    => 'files/tenant_id=?/provisions/discount_rates/',
            "claim_patterns"    => 'files/tenant_id=?/provisions/claim_patterns/',
        ],
        "report_type"           => [
            "pnl"               => 'files/tenant_id=?/report/PNL',
            "bs"                => 'files/tenant_id=?/report/BS',
            "sop"               => 'files/tenant_id=?/report/SOP',
            "soc"               => 'files/tenant_id=?/report/SOC',
            "soe"               => 'files/tenant_id=?/report/SOE',
            "lic"               => 'files/tenant_id=?/report/LIC',
            "breakup"           => 'files/tenant_id=?/report/BREAKUP',
            "lrc"               => 'files/tenant_id=?/report/LRC',
        ],
        "chart_of_account_files"  => 'files/tenant_id=?/chart_of_accounts/',
        "data_path"               => 'raw_extracted_data/tenant_id=?/import_id=?/unprocessed/',
        'dashboard'               => 'configs/tenant_id=?/dashboard/files/',
        'disclosure'              => 'configs/tenant_id=?/disclosure/files/',
        "error_path"              => 'raw_extracted_data/tenant_id=?/import_id=?/unprocessed/summaries/?/',
        'dependency'               => 'configs/tenant_id=?/',
        'manual_upload_path'      => 'raw_zipped_data/tenant_id=?/import_id=?/unprocessed/',
        'manual_uploaded_path'      => 'raw_extracted_data/tenant_id=?/import_id=?/processed/',
        "data_import_files" =>
        [
            'import_data_path'  => 'raw_zipped_data//tenant_id=?/',
            'start_end_date'    => 'date=?/processed/',
            "output"            => 'raw_extracted_data/tenant_id=?/',
            "import_id"            => 'import_id=?/processed/',
            "import_id_2"            => 'import_id=?/unprocessed/',
            'start_end_date_2'    => 'date=?/processed/',
        ]
    ],
    'default_organization_types'=> [
        "Life Insurance",
        "Non-Life Insurance",
        "Composite"
    ],
    'system_posting_type'       => 'delta',
    'organization_pagination'   => 6,

    'categories' => [
        'insurance' => 'Insurance' ,
        'fac'     => 'Facultative',
        'headoffice' => 'Head Office',
        'treaty' => 'Treaty',
    ],

    'expense_types' => [
        'dat' =>  "Directly Attributable",
        'acq' =>  "Insurance Acquisition",
        'pat' =>  "Partially Attributable",
        'nat' =>  "Not Attributable"
    ],
    'allocation_basis' => [
        'gwp' =>  "Gross Written Premium",
        'nwp' =>  "Net Written Premium",
        'gep' =>  "Gross Earned Premium",
        'nep' =>  "Net Earned Premium",
        'gcp' =>  "Gross Claim Paid",
        'ncp' =>  "Net Claim Paid",
        'gce' =>  "Gross Claim Expense",
        'nce' =>  "Net Claim Expense"
    ],

    'log_retention_days' => env('LOG_RETENTION_DAYS', 30),
    'OPENING_FUNCTION_NAME'    => env('OPENING_FUNCTION_NAME', 'algoryne-dev-delta-python-execution-manual'),

    'import_table_types'          =>
    [
        'endorsement_types'       =>
        [
            "view_mapping"        => 'Endorsement Types',
            "table_mapping"       => 'endorsement_types'
        ],
        'insurance_types'         =>
        [
            "view_mapping"        => 'Insurance Types',
            "table_mapping"       => 'insurance_types'
        ],
        'document_types'          =>
        [
            "view_mapping"        => 'Document Types',
            "table_mapping"       => 'document_types'
        ],
        'business_types'          =>
        [
            "view_mapping"        => 'Business Types',
            "table_mapping"       => 'business_types'
        ],
        'branches'                =>
        [
            "view_mapping"        => 'Branches',
            "table_mapping"       =>  'branches'
        ],
        'gl_codes'                =>
        [
            "view_mapping"        => 'Gl Codes',
            "table_mapping"       => 'gl_codes'
        ],
        'system_departments'      =>
        [
            "view_mapping"        => 'System Departments',
            "table_mapping"       => 'system_departments'
        ],
        'voucher_types'           =>
        [
            "view_mapping"        => 'Voucher Types',
            "table_mapping"       => 'voucher_types'
        ],
        'accounting_years'        =>
        [
            "view_mapping"        => 'Accounting Years',
            "table_mapping"       => 'accounting_years'
        ],
        'product_codes'           =>
        [
            "view_mapping"        => 'Product Codes',
            "table_mapping"       => 'product_codes'
        ],
        're_products_treaties'    =>
        [
            "view_mapping"        => 'Re Products Treaties',
            "table_mapping"       => 're_products_treaties'
        ],
        'treaty_pools'            =>
        [
                "view_mapping"    => 'Treaty Pools',
                "table_mapping"   => 'treaty_pools'
        ],
        'opening_balances'        =>
        [
            "view_mapping"        => 'Opening Balances',
            "table_mapping"       => 'opening_balances'
        ],
        'premium_registers'       =>
        [
            "view_mapping"        => 'Premium Registers',
            "table_mapping"       => 'premium_registers'
        ],

        'claim_paid_registers'    =>
        [
            "view_mapping"        => 'Claim Paid Registers',
            "table_mapping"       => 'claim_paid_registers'
        ],
        'fac_claim_paid'          =>
        [
            "view_mapping"        => 'Fac Claim Paid',
            "table_mapping"       => 'fac_claim_paids'
        ],
        'fac_registers'           =>
        [
            "view_mapping"        => 'Fac Registers',
            "table_mapping"       => 'fac_registers'
        ],
        'intimation_registers'    =>
        [
            "view_mapping"        => 'Intimation Registers',
            "table_mapping"       => 'intimation_registers'
        ],
        'fac_intimation_register' =>
        [
            "view_mapping"        => 'Fac Intimation Register',
            "table_mapping"       => 'fac_intimation_registers'
        ],
        're_intimation_registers' =>
        [
            "view_mapping"        => 'Re Intimation Registers',
            "table_mapping"       => 're_intimation_registers'
        ],
        'treaty_claim_recoveries' =>
        [
                "view_mapping"    => 'Treaty Claim Recoveries',
                "table_mapping"   => 'treaty_claim_recoveries'
        ],
        'treaty_registers'        =>
        [
            "view_mapping"        => 'Treaty Registers',
            "table_mapping"       => 'treaty_registers'
        ],
        'journal_entries'         =>
        [
            "view_mapping"        => 'Journal Entries',
            "table_mapping"       => 'journal_entries'
        ],
    ]
];
