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
use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * Default Includes
     * @var array
     */
    protected $defaultIncludes = ['id', 'name' , 'image', 'email', 'phone'];

    /**
     * @param UserEntity $user
     * @return array
     */
    public function transform(User $user)
    {
        return [

        ];
    }

    /**
     * @param UserEntity $user
     * @return \League\Fractal\Resource\Primitive
     */
    public function includeId(User $user)
    {
        return $this->primitive($user->id);
    }

    /**
     * @param UserEntity $user
     * @return \League\Fractal\Resource\Primitive
     */
    public function includeEmail(User $user)
    {
        return $this->primitive($user->email);
    }

    public function includePhone(User $user)
    {
        return $this->primitive($user->phone);
    }

    public function includeImage(User $user)
    {
        return $this->primitive($user->image);
    }

    public function includeName(User $user)
    {
        return $this->primitive($user->name);
    }

}
