<?php
    class VisitorExtension extends Extension
    {
        private static $has_one = array (
            'Visitor' => 'Visitor'
        );

        private static $allowed_actions = array (
            'handleVisitor'
        );

        public function contentControllerInit( $controller )
        {
        }

        public function preRequest( SS_HTTPRequest $request, Session $session, DataModel $model )
        {
        }

        public function postRequest( SS_HTTPRequest $request, SS_HTTPResponse $response, DataModel $model )
        {
        }

        public function handleVisitor()
        {
            $exceptions = array( '95.154.224.214' );
            if  ( ! in_array( $_SERVER[ 'REMOTE_ADDR' ], $exceptions ) )
            {
                // Find or create the visitor record
                    $visitor = Visitor::initVisitor();

                // Log the arrival of this visitor to this page
                    $visitor->logPageArrival();
            }
        }

        public function onAfterInit()
        {
            Requirements::customScript( "
                if (document.visibilityState !== 'prerender')
                {
                    var xmlhttp;
                    var referer = document.referrer;
                    var resolution = window.screen.width + '%20x%20' + window.screen.height;
                    var platform = navigator.platform;

                    if (window.XMLHttpRequest) {
                        // code for IE7+, Firefox, Chrome, Opera, Safari
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        // code for IE6, IE5
                        xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
                    }

                    var l = window.location.pathname;
                    if( l == '/' ) l = '/home/';

                    xmlhttp.open('POST', l + 'handleVisitor/', true);
                    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xmlhttp.send('ref=' + referer + '&res=' + resolution + '&plat=' + platform);
                }
            ");
        }
    }
