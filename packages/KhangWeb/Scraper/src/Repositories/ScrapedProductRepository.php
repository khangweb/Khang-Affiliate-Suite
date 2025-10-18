<?php

namespace KhangWeb\Scraper\Repositories;

use Webkul\Core\Eloquent\Repository;

class ScrapedProductRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return \KhangWeb\Scraper\Models\ScrapedProduct::class;
    }
}

