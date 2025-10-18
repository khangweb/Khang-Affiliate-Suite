<?php
return [
    [
        'key'        => 'scraper',
        'name'       => 'Scraper', // 'scraper::app.acl.scraper'
        'route'      => 'admin.scraper.scraping-templates.index',
        'sort'       => 1,
    ],[
        'key'        => 'scraper.scraping-templates',
        'name'       => 'Template', // 'scraper::app.acl.scraped-products'
        'route'      => 'admin.scraper.scraping-templates.index',
        'sort'       => 1,
    ],[
        'key'        => 'scraper.scraped_products',
        'name'       => 'Data', // 'scraper::app.acl.scraped-products'
        'route'      => 'scraper.admin.scraped_products.index',
        'sort'       => 2,

    ],[
        'key'        => 'scraper.settings',
        'name'       => 'Settings', // 'scraper::app.acl.import-settings'
        'route'      => 'admin.scraper.import.index',
        'sort'       => 3,

    ],
    
];