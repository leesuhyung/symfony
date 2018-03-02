<?php
namespace AppBundle\Security;

use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{
    private $jwtEncoder;
    private $em;

    public function __construct(JWTEncoderInterface $JWTEncoder, EntityManager $em)
    {
        $this->jwtEncoder = $JWTEncoder;
        $this->em = $em;
    }

    /*
     * ------ step 0 ------
     *
     * start() : Authorization 헤더가 있는지 판별. 있으면 getCredentials() 로 넘기고, 없으면 예외처리함.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw $authException;
//        return new Response('Auth header required', 401);
    }

    /*
     * ------ step 1 ------
     *
     * getCredentials() : Authorization 헤더를 읽고 전달되는 토큰을 반환.
     *                    이를 돕기 위해 JWT bundle 의 객체를 사용.
     */
    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
            'Bearer',
            'Authorization'
        );

        $token = $extractor->extract($request);

        if (!$token) {
            return null;
        }

        return $token;
    }

    /*
     * ------ step 2 ------
     *
     * getUser() : getCredentials() 로 부터 토큰을 인자로 받아, 해당 토큰을 사용하여 관련 사용자를 찾는다. (lexik_jwt_authentication.encoder)
     *             찾은 사용자를 DB 에서 찾는다. (EntityManager)
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $data = $this->jwtEncoder->decode($credentials);

        if ($data === false) {
            throw new CustomUserMessageAuthenticationException("Invalid Token");
        }

        $username = $data['username'];

        return $this->em
            ->getRepository('AppBundle:User')
            ->findOneBy(['email' => $username]);
    }

    /*
     * ------ step 3 ------
     *
     * checkCredentials() : 유저가 발견되지 않는 경우 - null 를 리턴하여 인증은 실패한다.
     *                      유저가 발견되는 경우     - 이 함수를 호출한다. (true 리턴)
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return null;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null; // do nothing - let the controller be called
    }

    public function supportsRememberMe()
    {
        return false;
    }
}