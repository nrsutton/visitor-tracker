<?php
    class VisitorExtension extends DataExtension
    {
        private static $has_one = array (
            'Visitor' => 'Visitor'
        );

        function __destruct()
        {
            // Only way I can see of adding module javascript after the main page javascript (So after the jquery include)
            // is to put the requirement in the __destruct
            Requirements::javascript( "moduleVisitorTracker/javascript/visitor-tracker.js" );
        }

        public function contentControllerInit( $controller )
        {
            if ( ! Director::is_ajax() )
            {
                // Find or create the visitor record
                    $visitor = Visitor::initVisitor();

                // Log the arrival of this visitor to this page
                    $visitor->logPageArrival();
            }
        }
    }
