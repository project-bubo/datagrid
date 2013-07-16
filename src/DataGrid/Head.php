<?php

/**
 *
 * This file is part of MultiFileUpload component designed for Nette (http://nette.org)
 *
 * Copyright (c) 2011 Nikolas Tsiongas
 *
 * This is distributed by New BSD License.
 *
 * */

namespace DataGrid;

use Nette;

class Head extends \Nette\Application\UI\Control {

    private $tempUri;
    private $tempPath;
    private $js = array();
    private $css = array();

    /*     * ****************** Render Head ******************* */

    public function renderFront() {
        $this->js = array(
            '/datagrid.js'
        );

//        
//        $this->css = array(
//            '/datagrid.css'
//        );


        $template = $this->createTemplate();
        $template->registerFilter(new Nette\Latte\Engine);

        $template->setFile(__DIR__ . '/latte/head.latte');
        $template->render();
    }
    
    public function render() {
        $this->js = array(
            '/datagrid.js'
        );

        
        $this->css = array(
            '/datagrid.css'
        );


        $template = $this->createTemplate();
        $template->registerFilter(new Nette\Latte\Engine);

        $template->setFile(__DIR__ . '/latte/head.latte');
        $template->render();
    }

    public function setTempUri($tempUri) {
        $tempUri = str_replace('//', '/', $tempUri);
        $this->tempUri = $tempUri;
    }

    public function setTempPath($tempPath) {


        if (!is_dir($tempPath)) {
            if (!mkdir($tempPath)) {
                throw new Exception('Cannot create temp directory.');
            }
        }
        $this->tempPath = $tempPath;
    }

    protected function createComponentJs() {
        $jsLoader = new \WebLoader\JavaScriptLoader;

        $jsLoader->sourcePath = __DIR__ . "/js";
        $jsLoader->tempUri = $this->tempUri;
        $jsLoader->tempPath = $this->tempPath;

        foreach ($this->js as $js) {
            $jsLoader->addFile($js);
        }

        return $jsLoader;
    }

    protected function createComponentCss() {
        $cssLoader = new \WebLoader\CssLoader(null, null, true);

        $cssLoader->sourcePath = __DIR__ . "/css";
        $cssLoader->tempUri = $this->tempUri;
        $cssLoader->tempPath = $this->tempPath;
        
        foreach ($this->css as $css) {
            $cssLoader->addFile($css);
        }
        

        $cssLoader->filter();

        $cssLoader->filters[] = function ($code) {
                    return \CssMin::minify($code);
                };

        return $cssLoader;
    }

}
