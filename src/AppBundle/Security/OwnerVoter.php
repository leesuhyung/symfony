<?php
namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OwnerVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';


    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string         $attribute
     * @param mixed          $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to supports
        /**
         * @var User $owner
         */
        $owner = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($owner, $user);
            case self::EDIT:
                return $this->canEdit($owner, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(User $owner, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($owner, $user)) {
            return true;
        }

        // the Post object could have, for example, a method isPrivate()
        // that checks a boolean $private property
        return false;
    }

    private function canEdit(User $owner, User $user)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $user === $owner;
    }

}