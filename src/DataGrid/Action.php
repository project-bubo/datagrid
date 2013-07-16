<?php

namespace DataGrid;
use Nette;

/**
 * Representation of data grid action.
 *
 * @author     Roman Sklenář
 * @copyright  Copyright (c) 2009 Roman Sklenář (http://romansklenar.cz)
 * @license    New BSD License
 * @example    http://addons.nette.org/datagrid
 * @package    Nette\Extras\DataGrid
 */
class Action extends Nette\ComponentModel\Component implements IAction
{
	/**#@+ special action key */
	const WITH_KEY		= TRUE;
	const WITHOUT_KEY	= FALSE;
	/**#@-*/

	/** @var Nette\Utils\Html  action element template */
	protected $html;

	/** @var string */
	static public $ajaxClass = 'datagrid-ajax';

	/** @var string */
	public $destination;

	/** @var bool|string */
	public $key;

	/** @var Nette\Callback|Closure */
	public $ifDisableCallback;


	/**
	 * Data grid action constructor.
	 * @note   for full ajax support, destination should not change module,
	 * @note   presenter or action and must be ended with exclamation mark (!)
	 *
	 * @param  string  textual title
	 * @param  string  textual link destination
	 * @param  Nette\Utils\Html    element which is added to a generated link
	 * @param  bool    use ajax? (add class self::$ajaxClass into generated link)
	 * @param  mixed   generate link with argument? (if yes you can specify name of parameter
	 * 				   otherwise variable DataGrid\DataGrid::$keyName will be used and must be defined)
	 * @return void
	 */
	public function __construct($title, $destination, Nette\Utils\Html $icon = NULL, $useAjax = FALSE, $key = self::WITH_KEY)
	{
		parent::__construct();
		$this->destination = $destination;
		$this->key = $key;

		$a = Nette\Utils\Html::el('a')->title($title);
		if ($useAjax) $a->addClass(self::$ajaxClass);

		if ($icon !== NULL && $icon instanceof Nette\Utils\Html) {
			$a->add($icon);
		} else {
			$a->setText($title);
		}
		$this->html = $a;
	}


	/**
	 * Generates action's link. (use before data grid is going to be rendered)
	 * @return void
	 */
	public function generateLink(array $args = NULL)
	{
		$dataGrid = $this->lookup('DataGrid\DataGrid', TRUE);
		$control = $dataGrid->lookup('Nette\Application\UI\Control', TRUE);
        $presenter = $dataGrid->lookup('Nette\Application\UI\Presenter', TRUE);
        
        
		switch ($this->key) {
		case self::WITHOUT_KEY:
			$link = $control->link($this->destination); break;
		case self::WITH_KEY:
		default:
			$key = $this->key == NULL || is_bool($this->key) ? $dataGrid->keyName : $this->key;          
            
            // modified by mara
            // in request contains plugin param
            // change destination from signal to request
            $params = $presenter->getParam();           
            
            $linkParams = array(
                $key => $args[$dataGrid->keyName]
            );
            
            $mode = 'normal';
            $pluginDestination = '';
            
            if (isset($params['plugin']) && isset($params['view'])) {
                // contains plugin params!!
                $pluginDestination = $params['plugin'] . ':' . $this->destination;
                                
                if (strpos($this->destination, '!') === FALSE) {
                    $mode = 'plugin';
                }
            }
           
            
            $link = $control->link($this->destination, $linkParams); 
            if ($mode == 'plugin') 
                $link = $presenter->pluglink($pluginDestination, $linkParams); 
            
            break;
		}

		$this->html->href($link);
	}



	/********************* interface DataGrid\IAction *********************/



	/**
	 * Gets action element template.
	 * @return Nette\Utils\Html
	 */
	public function getHtml()
	{
		return $this->html;
	}

}