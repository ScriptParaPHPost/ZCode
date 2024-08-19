<?php

/**
 * @name OpenGraph.php
 * @copyright ZCode 2024
 * @link https://zcode.newluckies.com/ (DEMO)
 * @link https://zcode.newluckies.com/feed/ (Informacion y actualizaciones)
 * @link https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link https://sourceforge.net/projects/zcode-script/ (Repositorio Sourceforge)
 * @author Miguel92
 * @version v1.7.0
 * @description Obtener datos como titulo, descripcion e imagen 
**/

class getOpenGraph {

   private function getUrlContents($url, $maximumRedirections = null, $currentRedirection = 0) {
      $result = false;
      $contents = @file_get_contents($url);
      if (isset($contents) && is_string($contents)) {
         preg_match_all('/<[\s]*meta[\s]*http-equiv="?REFRESH"?[\s]*content="?[0-9]*;[\s]*URL[\s]*=[\s]*([^>"]*)"?[\s]*[\/]?[\s]*>/si', $contents, $match);
         if (isset($match) && is_array($match) && count($match) == 2 && count($match[1]) == 1) {
            if (!isset($maximumRedirections) || $currentRedirection < $maximumRedirections) return $this->getUrlContents($match[1][0], $maximumRedirections, ++$currentRedirection);
            $result = false;
         } else $result = $contents;
      }
      return $contents;
   }
   
   public function getUrlData($url, $raw=false) {
      $result = false;
      $contents = $this->getUrlContents($url);
      if (isset($contents) && is_string($contents)) {
         $title = null;
         $metaTags = null;
         $metaProperties = null;
         #
         preg_match('/<title>([^>]*)<\/title>/si', $contents, $match );
         #
         if (isset($match) && is_array($match) && count($match) > 0) $title = strip_tags($match[1]);
         #
         preg_match_all('/<[\s]*meta[\s]*(name|property)="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si', $contents, $match);
         #
         if (isset($match) && is_array($match) && count($match) == 4) {
            $originals = $match[0];
            $names = $match[2];
            $values = $match[3];
            if (count($originals) == count($names) && count($names) == count($values)) {
               $metaTags = array();
               $metaProperties = $metaTags;
               if ($raw) {
                  $flags = (version_compare(PHP_VERSION, '5.4.0') == -1) ? ENT_COMPAT : ENT_COMPAT | ENT_HTML401;
                  for ($i=0, $limiti=count($names); $i < $limiti; $i++) {
                     $meta_type = ($match[1][$i] == 'name') ? 'metaTags' : 'metaProperties';
                     if ($raw) {
                        $$meta_type[$names[$i]] = [
                           'html' => htmlentities($originals[$i], $flags, 'UTF-8'),
                           'value' => $values[$i]
                        ];
                     } else {
                        $$meta_type[$names[$i]] = [
                           'html' => $originals[$i],
                           'value' => $values[$i]
                        ];
                     }
                  }
                  # for
               }
               # if raw
            }
            $result = array (
               'title' => $title,
               'metaTags' => $metaTags,
               'metaProperties' => $metaProperties,
            );
         }
         return $result;
      }
   }

   private function checkImage(array $data = []) {
      if(isset($data["metaTags"]['og:image'])) {
         $image = $data["metaTags"]["og:image"]["value"];
      } elseif(isset($data["metaProperties"]['og:image'])) {
         $image = $data["metaProperties"]["og:image"]["value"];
      } else {
         $image = $tsCore->settings['url'] . "/files/portada.png";
      }
      return $image;
   }

   public function getUrlDataInfo(string $url = '', array $datos = []) {
      $obtener_datos = $this->getUrlData($url, true);
      $datos = [
         'title' => $obtener_datos["title"],
         'description' => $obtener_datos["metaTags"]["description"]["value"]
      ];
      $datos['image'] = $this->checkImage($obtener_datos);
      return $datos;
   }
}