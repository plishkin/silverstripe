<?php

namespace App\Swoole\Utils;

use App\extensions\MemberExtension;
use SilverStripe\Security\Member;

class TokenUtils
{

    /** @var string */
    private static string $localToken = '';

    /**
     * @return string
     */
    public static function getLocalToken(): string
    {
        if (!self::$localToken) {
            $dir = BASE_PATH . '/storage/tokens';
            if (!is_dir($dir)) mkdir($dir, 0777, true);
            $path = $dir . '/local_token.txt';
            if (!is_file($path)) touch($path);
            $token = file_get_contents($path);
            if (!$token) {
                $s = 'XmMfzcDyYWdSnsuJrKheLUjoAGkQFZvaRCIxNlibPBOgpTEqHwtV';
                $l = strlen($s);
                for ($i = 0; $i < 64; $i++) $token .= $s[rand(0, $l - 1)];
                file_put_contents($path, $token);
            }
            self::$localToken = $token;
        }
        return self::$localToken;
    }

    public static function authorize(string $token): Member
    {
        /** @var Member|MemberExtension $member */
        $member = null;
        if ($token) {
            $hash = TokenUtils::getLocalToken();
            if ($token === $hash) {
                /** @var Member|MemberExtension $member */
                $member = Member::create();
                $member->buildLocalMember();
                $member->AuthorizeToken = $hash;
            } else {
                $member = Member::get()->filter('AuthorizeToken', $token)->first();
            }
        }
        return $member ?: Member::create();
    }

}
