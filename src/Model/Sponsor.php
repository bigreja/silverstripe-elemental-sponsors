<?php

namespace bigreja\Elements\Sponsors\Model;

use Dynamic\BaseObject\Model\BaseElementObject;
use bigreja\Elements\Sponsors\Elements\ElementSponsor;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Director;
use SilverStripe\Forms\FieldList;

use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\ReadOnlyField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Assets\File;

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
        'LogoImage' => \SilverStripe\Assets\Image::class,
        'LogoFile' => \SilverStripe\Assets\File::class,
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

        if (!$this->LogoImage && !$this->LogoFile) {
            $result->addError('A logo is required before you can save');
        }

        return $result;
    }
}
