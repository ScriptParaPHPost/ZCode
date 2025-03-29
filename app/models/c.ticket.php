<?php 

if ( ! defined('ZCODE2')) exit('No se permite el acceso directo al script');

/**
 * @package ZCode
 * @author Miguel92
 * @copyright 2024 - 2025
 * @version 2.1.15
 * @link https://zcodev.alwaysdata.net/ (DEMO)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
**/

class tsTicket {

   private $core;

   private $user;

   private $zcode;

   public function __construct() {
      $this->core = new tsCore;
      $this->user = new tsUser;
      $this->zcode = new tsZCode;
   }

   public function getTypeStatus(string $type = '') {
      $all = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT status_id, status_title, status_slug, status_icon FROM @tickets_status"));
      $one = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT status_title FROM @tickets_status WHERE status_slug = '$type'"));
      return empty($type) ? $all : $one['status_title'];
   }

   private function getParam(string $type = '') {
      $param['o'] = isset($_GET['o']) ? $_GET['o'] : 'id';
      $param['m'] = isset($_GET['m']) ? $_GET['m'] : 'a';
      $param['s'] = isset($_GET['s']) ? $_GET['s'] : '0';
      $param['text'] = isset($_POST['text']) ? $this->core->setSecure($_POST['text']) : '';
      return $param[$type];
   }

   public function getTickets() {

      $max = 15; // MAXIMO A MOSTRAR
      $limit = $this->core->setPageLimit($max, true);
      //
      $act = isset($_GET['action']) AND $_GET['action'] !== 'mis-tickets' ? " AND status_slug = '{$_GET['action']}'" : '';
      $text = $this->getParam('text');
      $where = !empty($text) ? "AND (user_name LIKE '$text%' OR ticket_title LIKE '%$text%')" : '';
      //
      $md = $this->getParam('m') === 'a' ? 'ASC' : 'DESC';
      
      $query = db_exec([__FILE__, __LINE__], 'query', "SELECT ticket_id, ticket_user, ticket_title, ticket_status, ticket_date, ticket_updated, type_title, type_icon, status_title, status_slug, status_icon, user_name FROM @tickets LEFT JOIN @tickets_type ON type_id = ticket_type LEFT JOIN @tickets_status ON status_id = ticket_status LEFT JOIN @miembros ON user_id = ticket_user WHERE ticket_status > 0$act $where ORDER BY ticket_{$this->getParam('o')} $md LIMIT $limit");
      $data['data'] = result_array($query);
      foreach($data['data'] as $t => $item) {
         $data['data'][$t]['ticket_slug'] = str_replace(' ', '_', strtolower($item['status_title']));
      }
      // PAGINAS
      $query = db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(*) FROM @tickets LEFT JOIN @tickets_status ON status_id = ticket_status WHERE ticket_id > 0$act");
      list($total) = db_exec('fetch_row', $query);
      $subpage = (isset($_GET['action']) AND !in_array($_GET['action'], ['mis-tickets', 'filtro'])) ? "/{$_GET['action']}" : '';
      $p = '/tickets' . $subpage;
      $data['pages'] = $this->core->pageIndex("$p/?o={$this->getParam('o')}&m={$this->getParam('m')}&s={$this->getParam('s')}", $total, $max);
      //
      return $data;
   }

   public function getTicketsOpenHome(string $type = 'home') {
      $where = ($type === 'home') ? '(ticket_status = 1 OR ticket_status = 2 OR ticket_status = 5)' : 'ticket_status > 0';
      $limit = ($type === 'home') ? 5 : 10;
      $where .= (isset($_GET["action"]) AND $_GET["action"] === 'mis-tickets') ? " AND ticket_user = {$this->user->uid}" : '';
   	$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT ticket_id, ticket_user, ticket_title, ticket_status, ticket_date, ticket_updated, type_title, status_title, user_name FROM @tickets LEFT JOIN @tickets_type ON type_id = ticket_type LEFT JOIN @tickets_status ON status_id = ticket_status LEFT JOIN @miembros ON user_id = ticket_user WHERE $where ORDER BY ticket_id DESC LIMIT $limit"));
   	foreach($data as $t => $item) {
   		$data[$t]['ticket_slug'] = str_replace(' ', '_', strtolower($item['status_title']));
   	}
   	return $data;
   }

   private function getTicketStatus(int $tid = 0, int $type = 0) {
      $leftjoin = ($tid === 0) ? '' : " LEFT JOIN @miembros ON user_id = ticket_user";
      $type .= ($tid === 0? '' : " AND ticket_id = $tid");
      return (int)db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(ticket_type) FROM @tickets LEFT JOIN @tickets_status ON status_id = ticket_type$leftjoin WHERE ticket_type = $type"))[0];
   }

   public function getFilterTickets(int $tid = 0, int|array $see = []) {
      $status = [];
      $init = 1;
      foreach($this->getTypeStatus() as $key => $type) {
         ['status_title' => $title, 'status_slug' => $slug] = $type;
         if(is_array($see) && $tid !== 0) {
            $status[$slug] = [
               'name' => $title,
               'total' => $this->getTicketStatus($tid, $init)
            ];
         } else {
            $status[$slug] = $this->getTicketStatus($tid, $init);
         }
         $init++;
      }
      return (is_array($see) && $tid !== 0) ? array_intersect_key($status, array_flip($see)) : $status;
   }

   public function getTicket() {
      $tid = (int)$_GET['tid'];
      $data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT ticket_id, ticket_user, ticket_title, ticket_body, ticket_type, ticket_status, ticket_date, ticket_updated, type_title, type_icon, status_title, user_id, user_name, r_name FROM @tickets LEFT JOIN @tickets_type ON type_id = ticket_type LEFT JOIN @tickets_status ON status_id = ticket_status LEFT JOIN @miembros ON user_id = ticket_user LEFT JOIN @rangos ON rango_id = user_rango WHERE ticket_id = $tid"));
      $data['ticket_slug'] = str_replace(' ', '_', strtolower($data['status_title']));
      $data['user_avatar'] = $this->zcode->getAvatar($data['user_id'], 'use');
      $data['ticket_body'] = $this->core->parseBBCode($data['ticket_body']);
      $data['status'] = $this->getFilterTickets($tid, ['en-espera', 'en-proceso', 'cancelado']);
      return $data;
   }

}