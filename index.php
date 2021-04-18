<?php
require_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('kdjfs/kirbyupdate', [
    'options' => [
        'ghtoken' => '',
    ],
    'translations' => [
        'de' => [
            'kirbyupdate.linktxt' => 'Es steht ein Kirby Update bereit!',
            'kirbyupdate.instver' => 'Aktuell installierte Version:',
            'kirbyupdate.repover' => 'Version auf Github: '
        ],
        'en' => [
            'kirbyupdate.linktxt' => 'There is a Kirby Update available!',
            'kirbyupdate.instver' => 'Installed Version:',
            'kirbyupdate.repover' => 'Version on Github:'
        ]
    ],
    'hooks' => [
        'user.login:after' => function () {
            try {
                $GHclient          = new \Github\Client();
                $token             = option('kdjfs.kirbyupdate.ghtoken');
                $method            = Github\Client::AUTH_ACCESS_TOKEN;

                if(option('kdjfs.kirbyupdate.ghtoken') !== ''){
                    $authenticate  = $GHclient->authenticate($token, $method);
                }

                $rateLimitCore     = $GHclient->api('rate_limit')->getResource('core')->getLimit();
                $rateRemainingCore = $GHclient->api('rate_limit')->getResource('core')->getRemaining();

                if($rateRemainingCore < $rateLimitCore){
                    $repositories  = $GHclient->api('repo')->releases()->latest('getkirby', 'kirby');
                    $repoVersion   = $repositories['name'];
                    $repoZipURL    = $repositories['tarball_url'];
                    $kirbyVersion  = Kirby::version();

                    if($kirbyVersion < $repoVersion){
                        return [
                            'kirbyVersion' => $kirbyVersion,
                            'repoVersion' => $repoVersion,
                            'repoZipURL' => $repoZipURL
                        ];
                    }
                }
                else {
                    if(option('debug') == true){
                        throw new Exception($rateRemainingCore .'<strong>Kirbyupdate: </strong>You have reached GitHub hourly limit! Actual limit is: ' . $rateLimitCore );
                    }
                    else {
                        return ['githubRate' => false];
                    }
                }
            } catch (Exception $e) {
                return ['githubRate' => $e->getMessage()];
            }
        }
    ],
    'fields' => [
        'kirbyupdate' => [
            'computed' => [
                'kirbyupdate' => function(){

                }
            ]
        ]
    ]
]);
