<?php
    class PageView extends DataObject
    {
        private static $db = array (
            'URL' => 'varchar(255)',
            'Referrer' => 'varchar(255)',
            'UserAgent' => 'text',
            'Cookie' => 'text',
            'ScrollDepth' => 'int',
            'Notes' => 'text',
            'TimeOnPage' => 'int'
        );

        private static $has_one = array (
            'Visitor' => 'Visitor'
        );
    }
