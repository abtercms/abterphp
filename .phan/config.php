<?php

declare(strict_types=1);

return [
    'target_php_version'                          => '8.0',
    'directory_list'                              => ['src/', 'vendor/'],
    'exclude_analysis_directory_list'             => [
        'vendor/',
        'src/Framework/resources',
        'src/Admin/resources',
        'src/Website/resources',
        'src/Contact/resources',
        'src/Files/resources',
        'src/PropellerAdmin/resources',
        'src/Bootstrapper4Website/resources',
        'src/WebsiteCreative/resources',
    ],
    // exclude test files, test cases, abterphp config files, and resources files
    'exclude_file_regex'                          => '@(Test|TestCase|Mock[a-zA-Z]*|Stub[a-zA-Z]*|abter)\.php$@',
    'quick_mode'                                  => true,
    'analyze_signature_compatibility'             => true,
    'minimum_severity'                            => 0,
    'allow_missing_properties'                    => false,
    'null_casts_as_any_type'                      => false,
    'null_casts_as_array'                         => false,
    'array_casts_as_null'                         => false,
    'scalar_implicit_cast'                        => true, // TODO: Consider removing
    'scalar_implicit_partial'                     => [],
    'ignore_undeclared_variables_in_global_scope' => true, // TODO: No globals!
    'suppress_issue_types'                        => [
        'PhanTypeInvalidThrowsIsInterface',
        'PhanParamSignatureRealMismatchReturnType',
    ],
];
