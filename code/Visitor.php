<?php
    class Visitor extends DataObject {
        private static $db = array (
            'IPAddress' => 'varchar',
            'referer' => 'text',
            'searchTerm' => 'varchar',
            'securityID' => 'varchar',
            'resolution' => 'varchar',
            'platform' => 'varchar'
        );

        private static $has_many = array (
            'PageViews' => 'PageView'
        );

        public function logPageArrival()
        {
            $referer = isset( $_POST[ 'ref' ] ) ? $_POST[ 'ref' ] : "";
            $resolution = isset( $_POST[ 'res' ] ) ? $_POST[ 'res' ] : "";

            $lastPageViews = PageView::get( "PageView", "VisitorID = {$this->ID}" );
            if ( $lastPageViews->count() > 0 )
            {
                $scrollDepth = 0;
                $vidLength = 0;
                $lastPageView = $lastPageViews->last();

                // Calculate the time on the previous page
                    $startTime = strtotime( $lastPageView->Created );
                    $endTime = time();
                    $timeOnPage = $endTime - $startTime;

                // Check if a scroll depth for the previous page was sent through
                    if ( isset( $_COOKIE[ "vt_sd" ] ) )
                    {
                        // Get the scroll depth
                            $scrollDepth = (int)$_COOKIE[ "vt_sd" ];
                        // Reset the depth
                            setcookie("vt_sd", 0, time() - 3600);
                    }

                    if ( isset( $_COOKIE['vid-start'] ) && isset( $_COOKIE['vid-end'] ) )
                    {
                        $vidLength = (int)$_COOKIE['vid-end'] - (int)$_COOKIE['vid-start'];
                        setcookie("vid-start", "", time()-3600);
                        setcookie("vid-end", "", time()-3600);
                    }

                $lastPageView->ScrollDepth = $scrollDepth;
                $lastPageView->TimeOnPage = $timeOnPage;
                if ( $vidLength > 0 ) $lastPageView->Notes = "The video on this page was viewed for " . $vidLength . " seconds";

                $lastPageView->write();
            }

            // Save this page view (Scroll depth will be updated on the next page's view)
                $PageView = PageView::create( array (
                    'VisitorID'   => $this->ID,
                    'URL'         => isset( $_SERVER[ 'REQUEST_URI' ] ) ? str_replace( "/handleVisitor", "", $_SERVER[ 'REQUEST_URI' ] ) : "",
                    'Referrer'     => $referer,
                    'UserAgent'   => isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) ? $_SERVER[ 'HTTP_USER_AGENT' ] : "",
                    'Cookie'      => isset( $_SERVER[ 'HTTP_COOKIE' ] ) ? $_SERVER[ 'HTTP_COOKIE' ] : "",
                    'ScrollDepth' => 0,
                    'Notes'       => ''
                ) );
                print_r( $PageView );
                $PageView->write();
        }

        public static function initVisitor()
        {
            $secID =  SecurityToken::inst()->getSecurityID();
            if ( ! $visitor = self::get()->find( 'securityID', $secID ) )
            {
                $referer = isset( $_POST[ 'ref' ] ) ? $_POST[ 'ref' ] : "";
                $resolution = isset( $_POST[ 'res' ] ) ? $_POST[ 'res' ] : "";
                $platform = isset( $_POST[ 'plat' ] ) ? $_POST[ 'plat' ] : "";
                $searchTerm = "";

                // This is a new visitor so lets see if we can find out where they came from
                $visitor = self::saveVisitor( $secID, $_SERVER[ 'REMOTE_ADDR' ], $referer, $searchTerm, $resolution, $platform );


            }

            return $visitor;
        }

        private static function getDomain( $url )
        {
            return parse_url( $url, PHP_URL_HOST );
        }

        private static function saveVisitor( $secID, $ipAddress, $referer = "", $searchTerm = "", $resolution = "", $platform = "")
        {
            $visitor = self::create( array (
                "IPAddress" => $ipAddress,
                "securityID" => $secID,
                "referer" => $referer,
                "searchTerm" => $searchTerm,
                "resolution" => $resolution,
                "platform" => $platform
            ));
            $visitor->write();
            return $visitor;
        }
    }