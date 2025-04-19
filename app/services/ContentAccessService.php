<?php

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package		ZCode
 * @author 		Miguel92
 * @copyright 	2024 - 2025
 * @version 	3.1.18
 * @link 		https://zcodev.alwaysdata.net/ (DEMO)
 * @link 		https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link 		https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

namespace app\services;

use app\models\{Core,User};
use app\utils\{Images,Zcode};

class ContentAccessService {

	protected $core;
	protected $user;
	protected $images;
	protected $zcode;

	public function __construct() {
		$this->core = new Core;
      $this->user = new User;
		$this->images = new Images;
      $this->zcode = new Zcode;
	}

	public function general(&$postData, int $pid = 0, string $title = '') {
		$postData['post_url'] = $this->zcode->createLink('post', $pid);
		$postData['post_title'] = stripslashes($title);
		$postData['post_portada'] = $this->images->setImageCover($pid);
	}
	
	public function isAdmodSeeMod(): bool {
		return ($this->user->is_admod AND (int)$this->core->settings['c_see_mod'] === 1);
	}

	public function isAdmod(string $prefix = 'u.', string $addSql = ''): ?string {
		return $this->isAdmodSeeMod() ? '' : " {$prefix}user_activo = 1 AND {$prefix}user_baneado = 0{$addSql}";
	}

	public function isAdmodPost(string $prefix = 'u.', string $prefixSecondary = 'p.', string $append = '') {
	   return $this->isAdmodSeeMod() ? "{$prefixSecondary}post_id > 0" : $this->isAdmod($prefix, " AND {$prefixSecondary}post_status = 0 $append");
	}

	public function sanitizeContent(string $string, bool $badwords = false): string {
		$string = $this->core->setSecure($string);
		$string = $this->core->parseBadWords($string, $badwords);
		return $string;
	}

	public function applyFiltersToInput(string $string): string {
		$string = $this->sanitizeContent($string, true);
		$string = $this->core->parseBBCode($string);
		return $string;
	}

	public function redirectLinkPost(int $pid = 0) {
		$tsDir = $this->zcode->createLink('post', $pid);
		header("Location: $tsDir");
	}

	public function getCategoryId(string $category = ''): int {
		$result = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT cid FROM @posts_categorias WHERE c_seo = '$category' LIMIT 1"));
	   $cid = isset($result['cid']) ? (int)$result['cid'] : 0;
	}

}