<?php
if (!defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * Modelo para instanciar Smarty
 *
 * @name    c.smarty.php
 * @author  ZCode | PHPost
 */

require_once TS_SMARTY . 'bootstrap.php';

class tsSmarty extends Smarty {

	public $addTemplate;

	public $template_error = 't.error.tpl';

	/**
	 * Constructor de la clase tsSmarty.
	 * Configura las opciones predeterminadas de Smarty y establece directorios.
	*/
	public function __construct() {
		// Trae el constructor directamente de Smarty
		parent::__construct();

		// Habilita la comprobación de compilación para un rendimiento óptimo
		$this->setCompileCheck(TRUE);

		// Establece el directorio de compilación de plantillas
		$this->setCompileDir(TS_CACHE . TS_TEMA);

		// Agrega directorio de plugins Smarty
		$this->addPluginsDir(TS_PLUGINS);

		// Suprime advertencias de variables indefinidas o nulas
		$this->muteUndefinedOrNullWarnings();
	}

	/**
	 * Modifica el comportamiento de salida de la plantilla, opcionalmente aplica filtro de eliminación de espacios en blanco.
	 *
	 * @param bool $loadFilter Determina si aplicar el filtro de eliminación de espacios en blanco
	*/
	public function output($loadFilter = false) {
		if ($loadFilter) $this->loadFilter('output', 'trimwhitespace');
	}

	private function getPage($page) {
		$page = match ($page) {
			'admin', 'moderacion' => 'main.tpl',
			'saliendo' => 'assets/views/saliendo.html',
			default => "t.$page.tpl"
		};
		$temp = $this->templateExists($page) ? $page : $this->template_error;
		return $temp;
	}

	private function listDirectories() {
		return [
			'root' => TS_ROOT,
			'assets' => TS_ASSETS,
			'views' => TS_ASSETS . 'views' . TS_PATH,
			'dashboard' => TS_ADMIN,
			'admin_mods' => TS_ADMIN . 'admin_mods' . TS_PATH,
			'access' => TS_AUTH
		];
	}

	/**
	 * Carga todos los directorios de plantillas y módulos.
	 *
	 * @param string $tema   Nombre del tema a cargar
	 * @param string $tsPage Nombre de la página actual
	 */
	public function loadAllTemplates($tema, $tsPage = '') {
		$templates = TS_THEMES . $tema . TS_PATH . 'templates' . TS_PATH;
		$sections = $templates . 'sections' . TS_PATH;
		$modules = $templates . 'modules' . TS_PATH;
		$directorios = array_merge([
			'tema' => TS_THEMES . $tema,
			'templates' => $templates,
			'sections' => $sections,
			'modules' => $modules,
			'pagina' => $modules . $tsPage . TS_PATH,
			'global' => $modules . 'global' . TS_PATH,
			'php_files' => $templates . 't.php_files' . TS_PATH
		], $this->listDirectories());
		$this->addTemplateDir($directorios);
	}

	/**
	 * Carga una plantilla específica.
	 *
	 * @param string $page Nombre de la plantilla a cargar
	 */
	public function loadTemplate($page) {
		try {
			$this->display($this->getPage($page));
		} catch (Exception $e) {
			// Muestra un mensaje de error si no se puede cargar la plantilla
			$message = $e->getMessage();
			$patron = "/'([^']+)'/";
			$message_2 = preg_replace_callback($patron, function($matches) {
    			return "'<strong>{$matches[1]}</strong>'";
			}, $message);
			$show = "Lo sentimos, se produjo un error al cargar la plantilla <strong>$page</strong>.
			<br>Debido al error:<br> <code style=\"font-size:1rem;line-height: 1.3rem;color: #d971ad;word-wrap: break-word;background: rgba(217, 113, 173, .12);display:block;padding:.5em;\">$message_2</code>";
			show_error($show, 'plantilla');
		}
	}

	/**
	 * Borra la versión compilada del recurso de plantilla especificado.
	 *
	 * @param string $template Nombre de la plantilla compilada a borrar
	*/
	public function clearCompiled($template) {
		$this->clearCompiledTemplate($template);
	}

}