<?php

namespace DataGrid\Columns;

use \Nette\Utils\Html;
/**
 * Representation of image data grid column.
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář (http://romansklenar.cz)
 * @license    New BSD License
 * @example    http://addons.nette.org/datagrid
 * @package    Nette\Extras\DataGrid
 */
class ImageColumn extends TextColumn {

    private $path;
    protected $caption;

    /**
     * Checkbox column constructor.
     * @param  string  column's textual caption
     * @return void
     */
    public function __construct($path = NULL, $caption = NULL) {
        //throw new NotImplementedException("Class was not implemented yet.");
        parent::__construct($caption);
        $this->path = $path;
       
        $this->caption = $caption;
        $this->getCellPrototype()->style('text-align: center');
    }

    /**
     * Formats cell's content.
     * @param  mixed
     * @param  DibiRow|array
     * @return string
     */
    public function formatContent($value, $data = NULL) {
        
        $path = $value;
        
        $imageSrc = $this->parent->getParent()->link(':Admin:File:getImageByPath', $path);
        $image = Html::el('img')->src($imageSrc)->alt($this->caption)->style('border: 1px solid #c8c8c8;');
        
        if (isset($data['image'])) {
            $imageLink = $this->parent->getParent()->link(':Admin:File:getImageByPath', $data['image']);
        
            //$image = \Nette\Utils\Html::el('img')->src($imageLink)->alt($this->caption);
            $a = Html::el('a')->href($imageLink);

            $image = $a->add(Html::el('img')->src($imageSrc)->alt($this->caption)->style('border: 1px solid #7F7F7F;'));
        }
        return (string) $image;
    }

}