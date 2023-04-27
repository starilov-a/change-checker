<?php


namespace App\Contracts;


interface ScanSiteContract
{
    public function scanSites(int $siteId = null);
}
