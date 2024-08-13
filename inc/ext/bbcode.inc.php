<?php

require_once 'JBBCode/Parser.php';
require_once 'JBBCode/definitions/Video.php';
require_once 'JBBCode/validators/ColorValidator.php';
require_once 'JBBCode/validators/UrlValidator.php';
require_once 'JBBCode/validators/AlignValidator.php';
require_once 'JBBCode/validators/SizeValidator.php';
require_once 'JBBCode/validators/SwfValidator.php';
require_once 'JBBCode/validators/ImgValidator.php';
require_once 'JBBCode/validators/FontValidator.php';

/**
 * Clase responsable de la conversión de texto en formato
 * de marcado BBCode a XHTML para creración de contenido
 * usado en posts, fotos, comentarios, etc.
 *
 * Extiende de la clase jBBCode para facilitar el uso de
 * todas las herramientas disponibles para la conversión
 * además de su excelente seguridad para el script.
 *
 * @author Kmario19 y PHPost.
 */
class BBCode {

	/**
	 * String texto BBcode
	 */
	private $text;

	/**
	 * BBCodes permitidos
	 */
	private $restriction;

	/**
	 * jBBCode
	 */
	private $parser;


	public function __construct() {
		$this->restriction = array();
		$this->parser = new JBBCode\Parser();
	}

	/**
	 * Prepara el texto con el que se trabajará
	 *
	 * @param string $text  texto a parsear
	 */
	public function setText($text) {
		$this->text = $text;
		$this->unclosedTags();
	}

	/**
	 * Modificar restricciones de BBCode
	 *
	 * @param Array string $restriccion  lista de tags permitidos
	 */
	public function setRestriction($array) {
		$this->restriction = $array;
		$this->addBBcodes();
	}

	/**
	 * Elimina etiquetas BBcode y deja el texto plano
	 *
	 * @return string
	 */
	public function getAsText() {
		$this->parser->parse($this->text);
		$this->text = $this->parser->getAsText();
		$this->delExtraTags();
		return htmlspecialchars_decode(strip_tags($this->text));
	}

	/**
	 * Obtiene el texto en HTML
	 *
	 * @return string
	 */
	public function getAsHtml() {
		$this->parser->parse($this->text);
		$this->text = $this->parser->getAsHtml();
		$this->setExtraTags();
		return nl2br($this->text);
	}

	/**
	 * Fix para tags que no tienen etiqueta de cierre
	 * y para tags de YouTube de la versión anterior
	 */
	private function unclosedTags() {
		if (!isset($this->text)) return; 
		$this->text = preg_replace("/[\.com]+\/v\//i", ".com/watch?v=", $this->text);
		$this->text = preg_replace("/\[swf=(http|https)?(\:\/\/)?www\.youtube\.com\/watch\?v([A-z0-9=\-]+?)\]/i", "[video]$1$2www.youtube.com/watch?v$3[/video]", $this->text);
		
		$this->text = preg_replace("/\[img\=(.+?)\]/i", "[img]$1[/img]", $this->text);
		$this->text = preg_replace("/\[swf\=(.+?)\]/i", "[swf]$1[/swf]", $this->text);

		$this->text = str_replace('&#039;', '\'', $this->text);
	}

	/**
	 * Parsea tag de línea de división
	 * saltos de línea
	 */
	private function setExtraTags() {
		if (in_array('hr', $this->restriction)) {
			$this->text = str_replace('[hr]', '<hr />', $this->text);
		}
		$this->text = str_replace(['\n', '\r'], ['<br />', ''], $this->text);
	}

	/**
	 * Elimina tag de línea de división
	 * saltos de línea
	 * espacios vacíos
	 */
	private function delExtraTags() {
		$this->text = str_replace(array('[hr]', '\n', '\r'), ' ', $this->text);
		$this->text = preg_replace('!\s+!', ' ', $this->text);
		$this->text = preg_replace('/((http|https|www)[^\s]+)/', '', $this->text);
	}

	/**
	 * Agrega y valida los BBcodes a parsear.
	 *
	 * Si el bbcode se encuentra en el array de la restricción, será permitido.
	 * Si no es válido lo que se pasa por parametro o contenido se verá el bbcode
	 * sin ser parseado. Ejemplo: [a]no es link[/a] => [a]no es link[/a]
	 *
	 * Cada bbcode tiene su configuración de:
	 *
	 * TagName: Nombre del tag de bbcode.
	 * Replace: En qué formrato HTML se reemplazará.
	 *          Usar como variables de referencia {option} y {param}.
	 * UseOption: Si el tag usa parámetro ({option}).
	 * ParseContent: Si el contenido del tag también será parseado.
	 * NestLimit: Límite de cuantas veces se repite este tag en su contenido (incluyendose).
	 * OptionValidator: Clase con la cual se valida lo que se pasa por parámetro.
	 * BodyValidator: Clase con la cual se valida lo que se pasa como contenido del tag.
	*/
	public function addBBcodes() {
		$urlValidator = new \JBBCode\validators\UrlValidator();
		$colorValidator = new \JBBCode\validators\ColorValidator();
		$sizeValidator = new \JBBCode\validators\SizeValidator();
		$alignValidator = new \JBBCode\validators\AlignValidator();
		$swfValidator = new \JBBCode\validators\SwfValidator();
		$imgValidator = new \JBBCode\validators\ImgValidator();
		$fontValidator = new \JBBCode\validators\FontValidator();

		$tagCodes = [
			['tag' => 'b', 'replace' => '<strong>{param}</strong>'],
			['tag' => 'i', 'replace' => '<em>{param}</em>'],
			['tag' => 'u', 'replace' => '<u>{param}</u>'],
			['tag' => 's', 'replace' => '<strike>{param}</strike>'],
			['tag' => 'sub', 'replace' => '<sub>{param}</sub>'],
			['tag' => 'sup', 'replace' => '<sup>{param}</sup>'],
			['tag' => 'table', 'replace' => '<table class="bbctab"><tbody>{param}</tbody></table>'],
			['tag' => 'tr', 'replace' => '<tr>{param}</tr>'],
			['tag' => 'td', 'replace' => '<td>{param}</td>'],
			['tag' => 'ul', 'replace' => '<ul>{param}</ul>'],
			['tag' => 'li', 'replace' => '<li>{param}</li>'],
			['tag' => 'ol', 'replace' => '<ol>{param}</ol>'],
			['tag' => 'url', 'replace' => '<a href="{param}" target="_blank">{param}</a>', 'parse' => false, 'validParam' => $urlValidator],
			['tag' => 'url', 'replace' => '<a href="{option}" target="_blank">{param}</a>', 'option' => true, 'validOption' => $urlValidator],
			['tag' => 'img', 'replace' => '<img src="{param}" class="wysibb--image w-100 object-fit-cover rounded border"/>', 'parse' => false, 'validParam' => $imgValidator],
			['tag' => 'color', 'replace' => '<span style="color: {option}">{param}</span>', 'option' => true, 'validOption' => $colorValidator],
			['tag' => 'size', 'replace' => '<span style="font-size: {option}pt; line-height: {option}pt">{param}</span>', 'option' => true, 'validOption' => $sizeValidator],
			['tag' => 'align', 'replace' => '<div style="text-align: {option}">{param}</div>', 'option' => true, 'validOption' => $alignValidator],
			['tag' => 'font', 'replace' => '<span style="font-family: {option}">{param}</span>', 'option' => true, 'validOption' =>$fontValidator],
			['tag' => 'code', 'replace' => '<pre><code>{param}</code></pre>', 'parse' => true, 'limit' => 1],
			['tag' => 'spoiler', 'replace' => '<div class="spoiler"><div class="title"><a href="#" onclick="spoiler($(this)); return false;">Spoiler:</a></div><div class="body">{param}</div></div>'],
			// Cita
			['tag' => 'quote', 'replace' => '<blockquote><div class="cita"><strong>Cita:</strong></div><div class="citacuerpo"><p>{param}</p></div></blockquote>'],
			['tag' => 'quote', 'replace' => '<blockquote><div class="cita"><strong>{option} dijo:</strong></div><div class="citacuerpo"><p>{param}</p></div></blockquote>', 'option' => true],
			// Mensajes
			['tag' => 'notice', 'replace' => '<div class="bbcmsg notice">{param}</div>'],
			['tag' => 'info', 'replace' => '<div class="bbcmsg info">{param}</div>'],
			['tag' => 'warning', 'replace' => '<div class="bbcmsg warning">{param}</div>'],
			['tag' => 'error', 'replace' => '<div class="bbcmsg error">{param}</div>'],
			['tag' => 'success', 'replace' => '<div class="bbcmsg success">{param}</div>']
		];

		foreach ($tagCodes as $bbcode) {
			if (in_array($bbcode['tag'], $this->restriction) || !$this->restriction) {
				$tag = $bbcode['tag'];
				$replace = $bbcode['replace'];
				$option = isset($bbcode['option']) ? $bbcode['option'] : false;
				$parse = isset($bbcode['parse']) ? $bbcode['parse'] : true;
				$limit = isset($bbcode['limit']) ? $bbcode['limit'] : -1;
				$validOption = isset($bbcode['validOption']) ? $bbcode['validOption'] : null;
				$validParam = isset($bbcode['validParam']) ? $bbcode['validParam'] : null;

				$this->parser->addBBCode($tag, $replace, $option, $parse, $limit, $validOption, $validParam);
			}
		}
		// Tag de video independiente
		if (in_array('video', $this->restriction) || !$this->restriction) {
			$this->parser->addCodeDefinition(new Video());
		}
	 }
	 
	/**
	 * @name parseMentions
	 * @access public
	 * @param string
	 * @return string
	 * @info PONE LOS LINKS A LOS MENCIONADOS
	 */
	public function parseMentions() {
		global $tsUser;
		$founds = array();
		$this->text .= ' ';
		preg_match_all('/\B@([a-zA-Z0-9_-]{4,16}+)\b/', $this->text, $users);
		foreach ($users[1] as $user) {
			if (!in_array($user, $founds)) {
				$uid = $tsUser->getUserID($user);
				if (!empty($uid)) {
					$find = '@' . $user . ' ';
					$replace = '@<a href="' . $this->settings['url'] . '/perfil/' . $user . '">' . $user . '</a> ';
					$this->text = str_replace($find, $replace, $this->text);
				}
				$founds[] = $user;
			}
		}
		$this->text = substr($this->text, 0, -1);
	}
	 
	 /**
	  * @name parseSmiles()
	  * @access public
	  * @description Convierte los Smiles
	  */
	public function parseSmiles() {
		global $tsCore;
		// SMILEYS
		$bbcode = [];
		$html = [];
		$useImage = true;
		//
		$file = json_decode(file_get_contents(TS_ASSETS . 'icons/emojis/emoji.json'), true);
		foreach($file as $name => $emoji) {
			if($emoji['category'] == 'people' AND (int)$emoji['emoji_order'] <= 95) {
				$image = $tsCore->settings['assets'] . "/icons/emojis/small/$name.png";
				$bbcode[] = $emoji['shortname'];
				if($useImage) {
					$html[] = "<img title=\"{$emoji['name']}\" class=\"emoji emoji-people\" style=\"vertical-align:middle;\" src=\"$image\" />";
				} else {
					$html[] = "<span title=\"{$emoji['name']}\" class=\"emoji emoji-people\">{$emoji['code_points']['decimal']}</span>";
				}
			}
		}
		// REEMPLAZAMOS SMILEYS
      if (!isset($this->text)) return; 
		$this->text = str_replace($bbcode, $html, $this->text);
	}    
}
