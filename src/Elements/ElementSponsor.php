<?php

namespace bigreja\Elements\Sponsors\Elements;

use DNADesign\Elemental\Models\BaseElement;
use bigreja\Elements\Sponsors\Model\Sponsor;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridFieldAddExistingAutocompleter;
use SilverStripe\ORM\FieldType\DBField;
use Symbiote\GridFieldExtensions\GridFieldAddExistingSearchButton;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

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
 * Class ElementSponsor
 * @package bigreja\Elements\Sponsors\Elements
 *
 * @property int $Limit
 * @property string $Content
 * @method \SilverStripe\ORM\ManyManyList Sponsors()
 */
class ElementSponsor extends BaseElement
{
    /**
     * @var string
     */
    private static $icon = 'font-icon-external-link';

    /**
     * @var string
     */
    private static $singular_name = 'Sponsors Element';

    /**
     * @var string
     */
    private static $plural_name = 'Sponsors Elements';

    /**
     * @var string
     */
    private static $table_name = 'ElementSponsor';

    /**
     * @var array
     */
    private static $db = array(
        'Limit' => 'Int',
        'Content' => 'HTMLText',
    );

    /**
     * @var array
     */
    private static $many_many = [
        'Sponsors' => Sponsor::class,
    ];

    /**
     * @var array
     */
    private static $many_many_extraFields = [
        'Sponsors' => [
            // would be sort but issues arise when parent and child both have the same sort field names.
            'SponsorSort' => 'Int',
        ],
    ];

    /**
     * @var array
     */
    private static $defaults = array(
        'Limit' => 0,
    );

    /**
     * Set to false to prevent an in-line edit form from showing in an elemental area. Instead the element will be
     * clickable and a GridFieldDetailForm will be used.
     *
     * @config
     * @var bool
     */
    private static $inline_editable = false;

    /**
     * @return string
     */
    public function getSummary()
    {
        if ($this->Sponsors()->count() == 1) {
            $label = ' sponsor';
        } else {
            $label = ' sponsors';
        }
        return DBField::create_field('HTMLText', $this->Sponsors()->count() . $label)->Summary(20);
    }

    /**
     * @return array
     */
    protected function provideBlockSchema()
    {
        $blockSchema = parent::provideBlockSchema();
        $blockSchema['content'] = $this->getSummary();
        return $blockSchema;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__.'.BlockType', 'Sponsors');
    }

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(
            function (FieldList $fields) {
                $fields->dataFieldByName('Content')
                    ->setRows(8);

                $fields->dataFieldByName('Limit')
                    ->setTitle('Number of sponsors to show')
                    ->setDescription('0 will show all sponsors');

                if ($this->exists()) {
                    $config = $fields->dataFieldByName('Sponsors')->getConfig();
                    $config->removeComponentsByType(GridFieldAddExistingAutocompleter::class);
                    $config->addComponent(new GridFieldAddExistingSearchButton());
                    $config->addComponent(new GridFieldOrderableRows('SponsorSort'));
                }
            }
        );

        return parent::getCMSFields();
    }

    /**
     * @return mixed
     */
    public function getSponsorsList()
    {
        $list = $this->Sponsors()->sort('SponsorSort');

        if ($this->Limit > 0) {
            $list = $list->limit($this->Limit);
        }

        return $list;
    }
}
