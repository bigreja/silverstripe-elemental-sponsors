<?php

namespace bigreja\Elements\Sponsors\Model;

use Dynamic\BaseObject\Model\BaseElementObject;
use bigreja\Elements\Sponsors\Elements\ElementSponsor;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Director;
use SilverStripe\Forms\FieldList;

/**
 * Class Sponsor
 * @package Dynamic\Elements\Sponsors\Model
 *
 * @method \SilverStripe\ORM\ManyManyList SponsorsElements()
 */
class Sponsor extends BaseElementObject
{
    /**
     * @var string
     */
    private static $singular_name = 'Sponsor';

    /**
     * @var string
     */
    private static $plural_name = 'Sponsors';

    /**
     * @var string
     */
    private static $table_name = 'Sponsor';

    /**
     * @var array
     */
    private static $belongs_many_many = [
        'SponsorsElements' => ElementSponsor::class,
    ];

    private static $has_one = array(
        'LogoImage' => Image::class,
        'LogoFile' => File::class,
    );

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = new FieldList(
            $rootTab = new TabSet(
                "Root",
                new Tab(
                    "Main",
                    new TextField('Title', 'Name', null, 100),
                    $image = new UploadField('LogoImage', 'Logo Image'),
                    $file = new UploadField('LogoFile', 'Logo SVG File'),
                    new TextField('Link', 'Link', null, 255)
                )
                )
        );

        return $fields;
    }

    /**
     * @return \SilverStripe\ORM\ValidationResult
     */
    public function validate()
    {
        $result = parent::validate();

        if (!$this->ImageID) {
            $result->addError('A logo is required before you can save');
        }

        return $result;
    }
}
