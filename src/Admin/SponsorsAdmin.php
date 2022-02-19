<?php

namespace Dynamic\Elements\Sponsors\Admin;

use Dynamic\Elements\Sponsors\Model\Sponsor;
use SilverStripe\Admin\ModelAdmin;

/**
 * Class SponsorsAdmin
 * @package Dynamic\Elements\Sponsors\Admin
 */
class SponsorsAdmin extends ModelAdmin
{

    /**
     * @var array
     */
    private static $managed_models = [
        Sponsor::class => [
            'title' => 'Sponsors',
        ],
    ];

    /**
     * @var string
     */
    private static $url_segment = 'sponsors';

    /**
     * @var string
     */
    private static $menu_title = 'Sponsors';
}
