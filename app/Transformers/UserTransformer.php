<?php

/**
 * @package  saad/fractal
 *
 * @author Ahmed Saad <a7mad.sa3d.2014@gmail.com>
 * @license MIT MIT
 * @date    Sun, Jan 13, 2019 8:35 PM
 */

namespace App\Transformers;


use App\Entities\UserEntity;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * Default Includes
     * @var array
     */
    protected $defaultIncludes = ['id', 'email', 'status', 'currency', 'amount', 'created_at'];

    /**
     * @param UserEntity $user
     * @return array
     */
    public function transform(UserEntity $user)
    {
        return [

        ];
    }

    /**
     * @param UserEntity $user
     * @return \League\Fractal\Resource\Primitive
     */
    public function includeId(UserEntity $user)
    {
        return $this->primitive($user->getIdentification());
    }

    /**
     * @param UserEntity $user
     * @return \League\Fractal\Resource\Primitive
     */
    public function includeEmail(UserEntity $user)
    {
        return $this->primitive($user->getEmail());
    }

    /**
     * @param UserEntity $user
     * @return \League\Fractal\Resource\Primitive
     */
    public function includeCurrency(UserEntity $user)
    {
        return $this->primitive($user->getCurrency());
    }

    /**
     * @param UserEntity $user
     * @return \League\Fractal\Resource\Primitive
     */
    public function includeStatus(UserEntity $user)
    {
        return $this->primitive($user->getStatusCode());
    }

    /**
     * @param UserEntity $user
     * @return \League\Fractal\Resource\Primitive
     */
    public function includeCreatedAt(UserEntity $user)
    {
        return $this->primitive($user->getRegistrationDate());
    }

    /**
     * @param UserEntity $user
     * @return \League\Fractal\Resource\Primitive
     */
    public function includeAmount(UserEntity $user)
    {
        return $this->primitive($user->getAmount());
    }

}
