<?php
/**
 * Type:     modifier
 * Name:     human
 * Date:     Jun 04, 2024
 * Purpose:  Convert 10000 => 1K, 1000000 => 1M
 * Example:  {$number|human}
 * @author   Miguel92
 * @version 1.0
 * @param int
 * @return string
 * @return decimal
*/
function smarty_modifier_human($number = 0) {
   if($number <= 0) return 0;
   $abbrevs = ['', 'K', 'M', 'B', 'T'];
   $factor = floor((strlen($number) - 1) / 3);
   if ($factor == 0) return $number;
   return sprintf("%.1f", $number / pow(1000, $factor)) . $abbrevs[$factor];
}