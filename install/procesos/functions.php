<?php 

class Functions {

	protected $sample;

	protected $environment;

	protected $chars = [
		'verify' => 'Aa1Bb$2Cc#3DSd4Ee5@Ff6Z7O8LP90!-U',
		'session' => '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
		'pin' => '0A1B2C3D4E5F6L7M8Q9'
	];

	public function __construct(string $envexample = '', string $env = '') {
		$this->sample = $envexample;
		$this->environment = $env;
	}

	private function create_file() {
		if(!file_exists($this->environment)) {
			return copy($this->sample, $this->environment);
		}
	}

	public function get_data_file($file = '') {
		$content = empty($file) ? $this->environment : $file;
		return file_get_contents($content);
	}

	private function save_data_file($data) {
		return file_put_contents($this->environment, $data);
	}

	private function sanitizer(string $type) {
		return match($type) {
			'string' => FILTER_UNSAFE_RAW,
			'url' => FILTER_SANITIZE_URL,
			'email' => FILTER_SANITIZE_EMAIL,
			'int' => FILTER_SANITIZE_NUMBER_INT,
			default => FILTER_UNSAFE_RAW,
		};
	}

	public function key_generator(string $type = 'verify', int $lenght = 12) {
		$code = '';
		$max = strlen($this->chars[$type]) - 1;
		for($i = 0; $i < $lenght; $i++) {
			$code .= $this->chars[$type][mt_rand(0, $max)];
		}
		if($type === 'session') {
			$code = "ZCODEV3_$code";
		}
		return $code;
	}

	/**
	 * Obtenemos la url
	*/
	public function secure_url(bool $withoutslashes = true) {
		$ssl = 'http';
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') $ssl .= 's';
		return ($withoutslashes ? $ssl . '://' : '');
	}

	function getUrl(string $page = '', bool $withoutslashes = true) {
		$REQUEST_URI = dirname($_SERVER["REQUEST_URI"]);
		$QUERY_STRING = ($_SERVER['QUERY_STRING'] === 'action=') ? $REQUEST_URI : dirname($REQUEST_URI);
		$HTTP_HOST = $_SERVER['HTTP_HOST'] ?? 'localhost';
		return  $this->secure_url($withoutslashes) . $HTTP_HOST . $QUERY_STRING . $page;
	}

	public function save(array $data = []) {
		$this->create_file();
		$data_content = $this->get_data_file();
		foreach($data as $find => $replace) {
			$data_content = str_replace($find, $replace, $data_content);
		}
		return $this->save_data_file($data_content);
	}

	public function setInput(string $key, string $type = 'string', $flag = INPUT_GET) {
		return filter_input($flag, $key, $this->sanitizer($type));
	}

	public function setInputPost(string $key, string $type = 'string') {
		return filter_input(INPUT_POST, $key, $this->sanitizer($type));
	}

	public function generate_key(int $bytes = 16) {
		return bin2hex(random_bytes($bytes));
	}

	public function isLocalhost() {
	   return (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1', 'localhost']));
	}

	public function status_install() {
	   return $this->isLocalhost() ? 'DEVELOPMENT' : 'PRODUCTION';
	}

	public function generate_name_version(string $who = '') {
		$dato = match($who) {
			'version_code' => strtolower(str_replace(['.', ' '], '_', $_SESSION['script'] . ' v' . $_SESSION['version'])),
			default => $_SESSION['script'] . ' v' . $_SESSION['version'],
		};
		return $dato;
	}

	public function request(string $REQUEST = 'POST') {
		return $_SERVER['REQUEST_METHOD'] === $REQUEST;
	}

	public function license() {
		return isset($_SESSION['LICENSE']) && $_SESSION['LICENSE'] === true;
	}

	public function get_value(?array $faster, string $key, string $type = 'string', ?string $by = null) {
		$byFaster = ($key === 'admin_confirm') ? 'userpassword' : explode('_', $key)[1];
		if(!empty($by)) {
			$type = $by;
		}
		return $this->setInput($key, $type, INPUT_POST) ?? $faster[$byFaster] ?? '' ?? $this->getUrl('', false);
	}

	public function isEmpty(string $key) {
		return empty($this->get_value([], $key, 'string'));
	}

	public function buildUpdateSQL(array $data): string {
	   $set = [];
	   foreach ($data as $key => $value) {
	      $set[] = "`$key` = '" . addslashes($value) . "'";
	   }
	   $setClause = implode(', ', $set);
	   return $setClause;
	}

	public function slugify(string $text): string {
	   $text = preg_replace('~[^\pL\d]+~u', '-', $text);
	   $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	   $text = preg_replace('~[^-\w]+~', '', $text);
	   $text = trim($text, '-');
	   $text = preg_replace('~-+~', '-', $text);
	   return strtolower($text);
	}

	private function sanitize_string(string $string): string {
		return trim(htmlspecialchars($string));
	}

	public function generate_password(string $name, string $pass, ?string $hash = '') {
		$options = ['cost' => 12];
		$password = $this->sanitize_string($name) . $this->sanitize_string($pass);
		return empty($hash) ? password_hash($password, PASSWORD_DEFAULT, $options) : password_verify($password, $hash);
	}

}