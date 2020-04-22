pipeline
{
    agent any

    options
    {
        buildDiscarder(logRotator(numToKeepStr: '1', artifactDaysToKeepStr: '30'))
    }

    stages
    {
        stage("Init")
        {
            environment
            {
                VERSION = getGitVersion()
            }

            steps
            {
                script
                {
                    currentBuild.displayName = VERSION
                }

                sh(label: 'Create reports/ folder', script: 'mkdir -vp reports')
            }
        }

        stage("Composer")
        {
            stages
            {
                stage("Validate composer.json")
                {
                    steps
                    {
                        sh label: 'Validate composer.json', script: 'composer validate --strict'
                    }
                }

                stage("Composer")
                {
                    steps
                    {
                        sh label: 'Install composer', script: 'composer install --no-progress --verbose'
                    }
                }
            }
        }

        stage("Validation")
        {
            parallel
            {
                stage("PHPUnit")
                {
                    stages
                    {
                        stage("PHPUnit")
                        {
                            steps
                            {
                                sh (
                                    script: "vendor/bin/phpunit --log-junit=reports/logfile.xml --coverage-clover=reports/clover.xml",
                                    returnStatus: true
                                )

                                junit 'reports/logfile.xml'

                                step([
                                    $class: 'CloverPublisher',
                                    cloverReportDir: 'reports',
                                    cloverReportFileName: 'clover.xml',
                                    healthyTarget: [
                                        methodCoverage: 100, conditionalCoverage: 100, statementCoverage: 100
                                    ],
                                    unhealthyTarget: [
                                        methodCoverage: 70, conditionalCoverage: 70, statementCoverage: 70
                                    ],
                                    failingTarget: [
                                        methodCoverage: 0, conditionalCoverage: 0, statementCoverage: 0
                                    ]
                                ])
                            }
                        }

                        stage("Coverage Check")
                        {
                            when
                            {
                                changeset "src/**/*.php"
                            }

                            steps
                            {
                                sh (
                                    label: "Check Unit Test Coverage",
                                    script: "git diff --name-only origin/master..HEAD src/ | ./check-coverage.php"
                                )
                            }
                        }
                    }
                }

                stage("PHPCS")
                {
                    steps
                    {
                        sh(
                            script: "vendor/bin/phpcs -q --report=checkstyle --report-file=reports/code-sniff.xml",
                            returnStatus: true
                        )

                        recordIssues(
                            tools: [phpCodeSniffer(id: 'phpcs', name: 'Code Style', pattern: 'reports/code-sniff.xml')],
                            qualityGates: [[threshold: 1, type: 'TOTAL', unstable: true]],
                            enabledForFailure: true, failOnError: true
                        )
                    }
                }

                stage("PHPMD")
                {
                    when
                    {
                        equals expected: 1, actual: 0 // Never -- phpmd does not currently support php7.4 type hints.
                    }

                    environment
                    {
                        INSPECTIONS="codesize,unusedcode,naming,cleancode,design,controversial"
                    }

                    steps
                    {
                        sh(
                            script: "vendor/bin/phpmd src/ xml " + INSPECTIONS
                                + " >reports/phpmd.xml",
                            returnStatus: true
                        )

                        recordIssues(
                            tools: [pmdParser(id: 'phpmd', name: 'Mess Detector', pattern: 'reports/phpmd.xml')],
                            qualityGates: [[threshold: 1, type: 'TOTAL', unstable: true]],
                            enabledForFailure: true, failOnError: true
                        )
                    }
                }

                stage("PHPStan")
                {
                    steps
                    {
                        sh(
                            script: "vendor/bin/phpstan analyse --no-interaction --no-progress " +
                                "--no-ansi -c phpstan.neon --error-format=checkstyle >reports/phpstan.xml",
                            returnStatus: true
                        )

                        recordIssues(
                            tools: [phpStan(id: 'phpstan', name: 'Static Analysis', pattern: 'reports/phpstan.xml')],
                            qualityGates: [[threshold: 1, type: 'TOTAL', unstable: true]],
                            enabledForFailure: true, failOnError: true
                        )
                    }
                }
            }
        }
    }

    post
    {
        unsuccessful
        {
            archiveArtifacts('reports/*')
        }
    }
}
