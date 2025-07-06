<?php 

/**
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
 * @package     ZCode
 * @author      Miguel92
 * @copyright   2024 - 2025
 * @version     3.1.18
 * @link        https://zcodev.alwaysdata.net/ (DEMO)
 * @link        https://github.com/ScriptParaPHPost/zcode (Repositorio Github)
 * @link        https://sourceforge.net/projects/zcodephp/ (Repositorio Sourceforge)
 * #==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#==#
**/

namespace app\interfaces;

if ( ! defined('ZCODEV3')) exit('No se permite el acceso directo al script');

interface CuentaInterface {
    public function loadPerfil(int $user_id = 0);
    public function setSocialData(&$data, bool $n = false);
    public function loadHeadInfo(int $user_id = 0);
    public function getAvatarSocials();
    public function activeAvatarSocial();
    public function saveColorCustomizer();
    public function loadGeneral(int $user_id = 0);
    public function iyfollow(int $user_id = 0, string $type = 'iFollow');
    public function loadPosts(int $user_id = 0);
    public function loadMedallas(int $user_id = 0);
    public function saveAvatarGif();
    public function regenerateToken();
    public function activeTwoFactor();
    public function removeTwoFactor();
    public function saveCuenta();
    public function saveSeguridad();
    public function savePrivacidad();
    public function saveNick();
    public function savePerfil();
    public function saveAppearence();
    public function saveThemeOption(string $type = '');
    public function saveSettings(string $save = '');
    public function getAvatarImages(?string $folder = null);
    public function changeAvatar();
    public function isEmail(string $email = '');
    public function desCuenta();
    public function bloqueosCambiar();
    public function loadBloqueos();
}
