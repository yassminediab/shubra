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
    protected $defaultIncludes = ['id', 'name' , 'avatar', 'email', 'phone','last_name' , 'date_of_birth','title'];

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

    public function includeAvatar(User $user)
    {
        return $this->primitive(asset('storage/'.$user->avatar));
    }

    public function includeName(User $user)
    {
        return $this->primitive($user->name);
    }

    public function includeLastName(User $user)
    {
        return $this->primitive($user->last_name);
    }

    public function includeDateOfBirth(User $user)
    {
        return $this->primitive($user->date_of_birth);
    }

    public function includeTitle(User $user)
    {
        return $this->primitive($user->title);
    }

}
