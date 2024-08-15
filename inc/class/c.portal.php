<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control del portal/mi
 *
 * @name    c.portal.php
 * @author  Miguel92 & PHPost.es
 */
class tsPortal {

   /** getNews()
    * @access public
    * @param 
    * @return array
   */
   public function getNews() {
      require TS_CLASS . "c.muro.php";
      $tsMuro = new tsMuro;
      return $tsMuro->getNews(0);
   }

   /** setPostsConfig()
    * @access public
    * @param 
    * @return string
   */
   public function savePostsConfig(){
      global $tsUser;
      //
      $cat_ids = serialize(explode(',', $_POST['cids']));
      return db_exec([__FILE__, __LINE__], 'query', "UPDATE @portal SET `last_posts_cats` = '$cat_ids' WHERE `user_id` = {$tsUser->uid}") ? '1: Tus cambios fueron aplicados.' : '0: Int&eacute;ntalo mas tarde.';
   }

   /** composeCategories()
    * @access public
    * @param array
    * @return array
   */
   public function composeCategories(){
      global $tsCore, $tsUser;
      //
      $data = db_exec('fetch_assoc',db_exec([__FILE__, __LINE__], 'query', 'SELECT `last_posts_cats` FROM @portal WHERE `user_id` = ' . $tsUser->uid));
      $categorias = $tsCore->getCategorias();
      $data = safe_unserialize($data['last_posts_cats']);
      if($data == NULL) return $categorias;
      foreach($categorias as $key => $cat) {
         if(in_array($cat['cid'], $data)) $cat['check'] = 1;
         else $cat['check'] = 0;
         $categories[] = $cat;
      }
      return $categories;
   }

   /** getMyPosts()
    * @access public
    * @param
    * @return array
   */
   public function getMyPosts(){
      global $tsCore, $tsUser;
      //
      $data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT `last_posts_cats` FROM @portal WHERE `user_id` = {$tsUser->uid}"));
      $cat_ids = safe_unserialize($data['last_posts_cats']);
   
      if(is_array($cat_ids) AND safe_count($cat_ids) != 0) {
         $cat_ids = implode(',', $cat_ids);
         $where = "p.post_category IN ({$cat_ids})";
         //
         $total = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(p.post_id) AS total FROM @posts AS p WHERE p.post_status = 0 AND $where"));
         //
         if($total['total'] > 0) $pages = $tsCore->getPagination($total['total'], 20);
         else return false;
         //
         $posts['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_id, p.post_category, p.post_title, p.post_date, p.post_puntos, p.post_private, u.user_name, c.c_nombre, c.c_seo, c.c_img FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category WHERE p.post_status = 0 AND $where ORDER BY p.post_id DESC LIMIT {$pages['limit']}"));
         foreach($posts['data'] as $pid => $post) {
            $posts['data'][$pid]['c_img'] = $tsCore->imageCat($post['c_img']);
         }
         $posts['pages'] = $pages;
         //
         return $posts;
      } else return true;
   }

   /** getLastPosts()
    * @access public
    * @param string
    * @return array
   */
	public function getLastPosts($type = 'visited'){
		global $tsCore, $tsUser, $tsImages;
      //
      $dato = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT last_posts_$type FROM @portal WHERE user_id = {$tsUser->uid} LIMIT 1"));
     
      $visited = safe_unserialize($dato['last_posts_'.$type]);
      if($visited[0] === null) return;
      krsort($visited);
		// LO HAGO ASI PARA ORDENAR SIN NECESITAR OTRA VARIABLE
      foreach($visited as $key => $id) {
         $req = db_exec('fetch_assoc',db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_id, p.post_user, p.post_category, p.post_title, p.post_portada, p.post_body, p.post_date, p.post_puntos, p.post_private, u.user_id, u.user_name, c.c_nombre, c.c_seo, c.c_img FROM @posts AS p LEFT JOIN @miembros AS u ON p.post_user = u.user_id LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category WHERE p.post_status = 0 AND p.post_id = $id LIMIT 1"));

         $req['post_portada'] = $tsImages->setImageCover($req['post_id'], $req['post_portada'], $req['post_body']);
         $req['post_title'] = stripslashes($req['post_title']);
         $req["post_url"] = $tsCore->createLink('post', [
            'c_seo' => $req['c_seo'],
            'post_id' => $req['post_id'],
            'post_title' => $req['post_title']
         ]);
         $req['c_img'] = $tsCore->imageCat($req['c_img']);
         $data[] = $req;
          
      }
		//
		return $data;
	}

   /** getFavorites()
    * @access public
    * @param
    * @return array
   */
   public function getFavorites(){
      global $tsCore, $tsUser;
        //
      $total = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(fav_id) AS total FROM @posts_favoritos WHERE `fav_user` = {$tsUser->uid}"));
        
      if($total['total'] > 0) {
         $pages = $tsCore->getPagination($total['total'], 20);
      } else return false;
        //
		$data['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT f.fav_id, f.fav_date, p.post_id, p.post_title, p.post_date, p.post_puntos, p.post_category, p.post_private, COUNT(p_c.c_post_id) as post_comments,  c.c_nombre, c.c_seo, c.c_img FROM @posts_favoritos AS f LEFT JOIN @posts AS p ON p.post_id = f.fav_post_id LEFT JOIN @posts_categorias AS c ON c.cid = p.post_category LEFT JOIN @posts_comentarios AS p_c ON p.post_id = p_c.c_post_id && p_c.c_status = 0 WHERE f.fav_user = {$tsUser->uid} && p.post_status = 0 GROUP BY c_post_id ORDER BY f.fav_date DESC LIMIT {$pages['limit']}"));
		$data['pages'] = $pages;
      return $data;
   }

   /** getFotos()
    * @access public
    * @param
    * @return array
   */
   public function getFotos() {
      // FOTOS
      include TS_CLASS . "c.fotos.php";
   	$tsFotos = new tsFotos();
      return $tsFotos->getLastFotos();
   }

   /** getStats()
    * @access public
    * @param
    * @return array
   */
   public function getStats() {
    	// CLASE TOPS
    	include TS_CLASS . "c.tops.php";
    	$tsTops = new tsTops();
      return $tsTops->getStats();
   }
}